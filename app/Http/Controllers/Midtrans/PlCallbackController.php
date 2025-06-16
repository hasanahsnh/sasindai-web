<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Midtrans\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PlCallbackController extends Controller
{
    protected $database, $messaging;
    protected $refOrders = 'orders', $refMitras = 'mitras';

    public function __construct(Database $db, Messaging $msg)
    {
        $this->database = $db;
        $this->messaging = $msg;
    }

    public function handleCallback(Request $request)
    {
        Log::info('Callback Midtrans diterima (payment link)');

        $input = json_decode($request->getContent(), true);
        Log::info('Payload diterima dari Midtrans', ['payload' => $input]);

        $orderId = $input['order_id'] ?? '';
        $signature = $input['signature_key'] ?? '';
        //$transactionStatus = $input['payload']['transaction_status'] ?? '';
        $statusCode = $input['status_code'] ?? '';
        $gross = $input['gross_amount'] ?? '';
        if (!$orderId) {
            Log::warning('order_id tidak tersedia di callback');
            return response()->json(['status' => 'bad request'], 400);
        }
        if (!$signature) {
            Log::warning('signature_key tidak tersedia di callback');
            return response()->json(['status' => 'bad request'], 400);
        }
        if (!$this->isValidSignature($input)) {
            Log::error('Signature tidak valid!', ['input' => $input]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    
        // Query langsung ke Midtrans untuk dapat info lengkap
        $serverKey = config('services.midtrans.server_key'); // server_key yang digunakan
        $resp = Http::withBasicAuth($serverKey, '')
                    ->get("https://api.sandbox.midtrans.com/v2/{$orderId}/status");

        if ($resp->failed()) {
            Log::error('Gagal inquiry ke Midtrans', ['orderId' => $orderId]);
            return response()->json(['status' => 'error'], 500);
        }
        $transaction = $resp->json();

        //$transactionStatus = $transaction['transaction_status']; // settlement, pending, expire, cancel, deny
        //$paymentType = $transaction['payment_type']; // gopay, shopeepay, bank_transfer, etc
        
        // Setelah dapat info yang sah, proses sesuai kebutuhan
        $this->prosesLanjutan([
            'order_id' => $orderId,
            //'transaction_status' => $transactionStatus,
            'gross_amount' => $gross,
            //'payment_type' => $paymentType,
        ]);

        return response()->json(['status' => 'ok']);
    }

    private function prosesLanjutan(array $data)
    {
        $orderId = $this->normalizeOrderId($data['order_id'] ?? '');
        if (!$orderId) {
            Log::warning("orderId tidak tersedia.");
            return;
        }
        $orderInfo = $this->getOrderFromFirebase($orderId);
        if (!$orderInfo) {
            Log::warning("Order tidak ditemukan: $orderId.");
            return;
        }
        [$key, $order] = $orderInfo;

        // Update pesanan jadi "dikemas" saja
        $this->updateOrderStatusInFirebase($key, 'success', 'dikemas');    

        // Kurangi stok & bersihkan keranjang
        $uid = $order['uidUser'] ?? $order['uid'] ?? '';
        $produk = $order['produk'] ?? [];

        if ($uid && $produk) {
            $this->kurangiStokDanBersihkanKeranjang($uid, $produk, $order['tipe_checkout'] ?? 'beli_sekarang');
        }
    
        // Kirim notifikasi fonnte & FCM jika dibutuhkan
        $this->sendFonnteNotification($order, $data['order_id']); 
        $this->sendFonnteOrderToSeller($order, $data['order_id']); 
        $this->sendPushNotification($order, $data['order_id'], 'success'); 
    
        Log::info("Proses callback Midtrans selesai untuk Order: $orderId.");
    }

    protected function isValidSignature($input)
    {
        $serverKey = config('services.midtrans.server_key'); 
        $orderId = $input['order_id']; 
        $statusCode = $input['status_code']; 
        $gross = $input['gross_amount']; 
        $signature = hash('sha512',$orderId . $statusCode . $gross . $serverKey);
        return $signature === ($input['signature_key'] ?? '');
    }

    private function normalizeOrderId($id): string
    {
        return Str::isUuid($id) ? $id : Str::beforeLast($id, '-');
    }

    private function getOrderFromFirebase($orderId): ?array
    {
        $orders = $this->database->getReference($this->refOrders)
            ->orderByChild('order_id')->equalTo($orderId)->getValue();
        if (!$orders) return null;
        $key = array_key_first($orders);
        return [$key, $orders[$key]];
    }

    private function mapTransactionStatus($status): string
    {
        return [
            'capture' => 'success',
            'settlement' => 'success',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'canceled',
        ][$status] ?? $status;
    }

    private function mapStatusPesanan($status): string
    {
        return [
            'pending' => 'menunggu pembayaran',
            'success' => 'dikemas',
            'failed' => 'gagal pembayaran',
            'expired' => 'kadaluarsa',
            'canceled' => 'dibatalkan',
        ][$status] ?? $status;
    }

    private function sendFonnteNotification($order, $orderId)
    {
        Http::withHeaders(['Authorization' => config('services.fonnte.token_fonnte')])
            ->post('https://api.fonnte.com/send', [
                'target' => $order['no_telp'],
                'message' => "Halo {$order['namaLengkap']}, pembayaran berhasil! Order ID: $orderId",
                'countryCode' => '62'
            ]);
    }

    private function sendFonnteOrderToSeller($order, $orderId)
    {
        $uid = $order['uidPenjual'] ?? null;
        if (!$uid) return;

        $mitra = $this->database->getReference($this->refMitras)
            ->orderByChild('uid')->equalTo($uid)->getValue();
        $mitra = $mitra ? reset($mitra) : null;
        if (!$mitra || empty($mitra['noTelp'])) return;

        $msg = "Halo {$mitra['namaLengkap']}, pesanan baru berhasil dibayar. Order ID: $orderId\n" .
            "Proses segera ya. Buka dashboard: http://sasindai.my.id/produk";

        Http::withHeaders(['Authorization' => config('services.fonnte.token_fonnte')])
            ->post('https://api.fonnte.com/send', [
                'target' => $mitra['noTelp'],
                'message' => $msg,
                'countryCode' => '62'
            ]);
    }

    private function updateOrderStatusInFirebase($key, $status, $statusText)
    {
        $this->database->getReference("{$this->refOrders}/$key")->update([
            'status' => $status,
            'statusPesanan' => $statusText,
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    private function sendPushNotification($order, $orderId, $status)
    {
        try {
            $msg = CloudMessage::new()
                ->withNotification(['title' => 'Status Pembayaran', 'body' => 'Status: ' . ucfirst($status)])
                ->withData(['order_id' => $orderId, 'status' => $status]);
            $this->messaging->send($msg);
        } catch (\Exception $e) {
            Log::error('FCM error: ' . $e->getMessage());
        }
    }

    private function kurangiStokDanBersihkanKeranjang($uid, $produk, $tipe)
    {
        foreach ($produk as $item) {
            $id = $item['id_produk'] ?? null;
            $varian = $item['nama_varian'] ?? null;
            $qty = $item['qty'] ?? 0;

            if (!$id || !$varian || $qty <= 0) continue;

            $ref = $this->database->getReference("produk/$id");
            $data = $ref->getValue();
            if (!$data || !isset($data['varian'])) continue;

            foreach ($data['varian'] as $i => $v) {
                if (strcasecmp($v['nama'], $varian) === 0) {
                    $data['varian'][$i]['stok'] = max(0, $v['stok'] - $qty);
                    break;
                }
            }

            $data['sisaStok'] = max(0, ($data['sisaStok'] ?? 0) - $qty);
            $data['terjual'] = ($data['terjual'] ?? 0) + $qty;
            $ref->set($data);

            if ($tipe !== 'beli_sekarang') {
                $this->database->getReference("keranjang/$uid/$id/$varian")->remove();
            }
        }
    }
}
