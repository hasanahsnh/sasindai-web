<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PlCallbackController extends Controller
{
    protected $database;
    protected $refOrders = 'orders', $refMitras = 'mitras';

    public function __construct(Database $db)
    {
        $this->database = $db;
    }

    public function handleCallback(Request $request)
    {
        // Dari sini sampai ...
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
        // sini, jangan diubah, karena ini sudah sesuai dengan struktur yang diharapkan

        $this->prosesLanjutan([
            'order_id' => $orderId,
            'gross_amount' => $gross,
            'transaction_status' => $transaction['transaction_status'] ?? 'unknown'
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function prosesLanjutan(array $data)
    {
        $orderId = $this->normalizeOrderId($data['order_id']);
        [$key, $order] = $this->getOrderFromFirebase($orderId) ?? [null, null];

        if (!$key || !$order) {
            Log::warning("Order tidak ditemukan: $orderId");
            return;
        }

        $midtransStatus = $data['transaction_status'] ?? 'pending';
        $status = $this->mapTransactionStatus($midtransStatus);
        $statusPesanan = $this->mapStatusPesanan($status);

        Log::info("Proses callback untuk Order: $orderId, status: $status, statusPesanan: $statusPesanan");

        $currentStatus = $order['status'] ?? '';
        $stokDikurangi = $order['stok_dikurangi'] ?? false;

        if ($currentStatus === $status && in_array($status, ['success', 'expired', 'canceled', 'failed'])) {
            Log::info("Callback diabaikan karena status sudah $status untuk Order: $orderId");
            return;
        }

        $uid = $order['uidUser'] ?? $order['uid'] ?? '';
        $produk = $order['produk'] ?? [];
        $tipe = $order['tipe_checkout'] ?? 'beli_sekarang';

        Log::info("Status sebelumnya: $currentStatus, status baru: $status");

        // Kurangi stok hanya sekali jika belum pernah dikurangi
        if (!$stokDikurangi && in_array($status, ['pending', 'success'])) {
            $this->kurangiStokDanBersihkanKeranjang($uid, $produk, $tipe);
            $this->database->getReference("{$this->refOrders}/$key/stok_dikurangi")->set(true);
            Log::info("Stok berhasil dikurangi dan keranjang dibersihkan untuk Order: $orderId");
        }

        // Kirim notifikasi hanya saat success pertama kali
        if ($status === 'success' && $currentStatus !== 'success') {
            $this->sendFonnteNotification($order, $orderId);
            $this->sendFonnteOrderToSeller($order, $orderId);
        }

        // Kembalikan stok hanya jika belum expired/canceled/failed sebelumnya
        if (in_array($status, ['expired', 'canceled', 'failed']) &&
            !in_array($currentStatus, ['expired', 'canceled', 'failed'])) {

            $this->kembalikanStokDanRestoreKeranjang($uid, $produk, $tipe);
            $this->database->getReference("{$this->refOrders}/$key/produk")->set($produk);

            // Tambahkan kembali produk ke order node
            $this->database->getReference("{$this->refOrders}/$key/produk")->set($produk);

            Log::info("Stok dan keranjang dikembalikan untuk order: $orderId");
        }

        $this->updateOrderStatusInFirebase($key, $status, $statusPesanan);

        Log::info("Proses callback selesai untuk Order: $orderId, status: $status");
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

    public function normalizeOrderId($id): string
    {
        return Str::isUuid($id) ? $id : Str::beforeLast($id, '-');
    }

    public function getOrderFromFirebase($orderId): ?array
    {
        $orders = $this->database->getReference($this->refOrders)
            ->orderByChild('order_id')->equalTo($orderId)->getValue();
        if (!$orders) return null;
        $key = array_key_first($orders);
        return [$key, $orders[$key]];
    }

    public function mapTransactionStatus($status): string
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

    public function mapStatusPesanan($status): string
    {
        return [
            'pending' => 'menunggu pembayaran',
            'success' => 'dikemas',
            'failed' => 'gagal pembayaran',
            'expired' => 'kadaluarsa',
            'canceled' => 'dibatalkan',
        ][$status] ?? $status;
    }

    public function sendFonnteNotification($order, $orderId)
    {
        Http::withHeaders(['Authorization' => config('services.fonnte.token_fonnte')])
            ->post('https://api.fonnte.com/send', [
                'target' => $order['no_telp'],
                'message' => "Halo {$order['namaLengkap']}, pembayaran berhasil! Order ID: $orderId",
                'countryCode' => '62'
            ]);
    }

    public function sendFonnteOrderToSeller($order, $orderId)
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

    public function updateOrderStatusInFirebase($key, $status, $statusText)
    {
        $this->database->getReference("{$this->refOrders}/$key")->update([
            'status' => $status,
            'statusPesanan' => $statusText,
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    public function kurangiStokDanBersihkanKeranjang($uid, $produk, $tipe)
    {
        foreach ($produk as $item) {
            $id = $item['idProduk'] ?? null;
            $idVarian = $item['idVarian'] ?? null;
            $qty = $item['qty'] ?? 0;

            Log::info("Menghapus keranjang di varian: $idVarian" . ", uid: $uid");

            if (!$id || !$idVarian || $qty <= 0) continue;

            $ref = $this->database->getReference("produk/$id");
            $data = $ref->getValue();
            if (!$data || !isset($data['varian'])) continue;

            foreach ($data['varian'] as $i => $v) {
                if (strcasecmp($v['nama'], $idVarian) === 0) {
                    $data['varian'][$i]['stok'] = max(0, $v['stok'] - $qty);
                    break;
                }
            }

            $data['sisaStok'] = max(0, ($data['sisaStok'] ?? 0) - $qty);
            $data['terjual'] = ($data['terjual'] ?? 0) + $qty;
            $ref->set($data);

            if ($tipe !== 'beli_sekarang') {
                $this->database->getReference("keranjang/$uid/$id/$idVarian")->remove();
            }
        }
    }

    public function kembalikanStokDanRestoreKeranjang($uid, $produk, $tipe)
    {
        foreach ($produk as $item) {
            $id = $item['idProduk'] ?? null;
            $varian = $item['namaVarian'] ?? null;
            $qty = $item['qty'] ?? 0;

            if (!$id || !$varian || $qty <= 0) continue;

            $ref = $this->database->getReference("produk/$id");
            $data = $ref->getValue();
            if (!$data || !isset($data['varian'])) continue;

            foreach ($data['varian'] as $i => $v) {
                if (strcasecmp($v['nama'], $varian) === 0) {
                    $data['varian'][$i]['stok'] = ($v['stok'] ?? 0) + $qty;
                    break;
                }
            }

            $data['sisaStok'] = ($data['sisaStok'] ?? 0) + $qty;
            $data['terjual'] = max(0, ($data['terjual'] ?? 0) - $qty);
            $ref->set($data);
        }
    }
}
