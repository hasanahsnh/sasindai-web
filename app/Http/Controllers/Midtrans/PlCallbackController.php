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
use Midtrans\Notification;

class PlCallbackController extends Controller
{
    protected $database;
    protected $refTableNameOrders, $refTableNameMitras;
    protected $messaging;

    public function __construct(Database $database, Messaging $messaging) {
        $this->database = $database;
        $this->refTableNameOrders = 'orders';
        $this->refTableNameMitras = 'mitras';
        $this->messaging = $messaging;
    }

    public function handleCallback(Request $request)
    {
        try {
            $this->configureMidtrans();

            $data = $this->parseRequestData();
            if (!$this->isValidSignature($data)) {
                Log::warning("Midtrans callback: Invalid signature key");
                return response()->json(['status' => 'unauthorized'], 403);
            }

            $notification = $this->getMidtransNotification();
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;

            Log::info("Midtrans callback received for order_id: $orderId with status: $transactionStatus");

            $targetOrderId = $this->normalizeOrderId($orderId);
            $orderInfo = $this->getOrderFromFirebase($targetOrderId);

            if (!$orderInfo) {
                Log::warning("Order with order_id $orderId not found");
                return response()->json(['status' => 'not_found'], 404);
            }

            [$orderKey, $order] = $orderInfo;
            $status = $this->mapTransactionStatus($transactionStatus);
            $statusPesanan = $this->mapStatusPesanan($status);

            if ($status === 'success') {
                $this->sendFonnteNotification($order, $orderId); // Kirim notifikasi ke customer
                $this->sendFonnteOrderToSeller($order, $orderId); // Kirim notifikasi ke penjual
            }

            $this->updateOrderStatusInFirebase($orderKey, $status, $statusPesanan);

            if (!empty($order['user_token'])) {
                $this->sendPushNotification($order, $orderId, $status);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    private function parseRequestData(): array
    {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        if (!$data) {
            Log::error("Midtrans callback: Invalid JSON");
            abort(400, 'Bad request');
        }

        return $data;
    }

    private function isValidSignature(array $data): bool
    {
        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $data['order_id'] . $data['status_code'] . $data['gross_amount'] . $serverKey);
        return $expectedSignature === $data['signature_key'];
    }

    private function getMidtransNotification(): Notification
    {
        try {
            return new Notification();
        } catch (\Exception $e) {
            Log::error("Midtrans notification error: " . $e->getMessage());
            abort(500, 'Internal error');
        }
    }

    private function normalizeOrderId(string $orderId): string
    {
        return Str::isUuid($orderId) ? $orderId : Str::beforeLast($orderId, '-');
    }

    private function getOrderFromFirebase(string $orderId): ?array
    {
        $orders = $this->database
            ->getReference($this->refTableNameOrders)
            ->orderByChild('order_id')
            ->equalTo($orderId)
            ->getValue();

        if (empty($orders)) return null;

        $key = array_key_first($orders);
        return [$key, $orders[$key]];
    }

    private function mapTransactionStatus(string $status): string
    {
        return [
            'capture' => 'success',
            'settlement' => 'success',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'canceled'
        ][$status] ?? $status;
    }

    private function mapStatusPesanan(string $status): string
    {
        return [
            'pending' => 'menunggu pembayaran',
            'success' => 'dikemas',
            'failed' => 'gagal pembayaran',
            'expired' => 'kadaluarsa',
            'canceled' => 'dibatalkan'
        ][$status] ?? $status;
    }

    private function sendFonnteNotification(array $order, string $orderId): void
    {
        try {
            $message =
                "Halo {$order['namaLengkap']}, pembayaran Anda berhasil! Pesanan sedang dikemas. Order ID: {$orderId}";

            Http::withHeaders([
                'Authorization' => config('services.fonnte.token_fonnte'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $order['no_telp'],
                'message' => $message,
                'countryCode' => '62'
            ]);
        } catch (\Exception $e) {
            Log::error('Fonnte WA error: ' . $e->getMessage());
        }
    }

    private function sendFonnteOrderToSeller(array $order, string $orderId): void
    {
        try {
            // Ambil data penjual berdasarkan uidPenjual dari data order
            $uidPenjual = $order['uidPenjual'] ?? null;

            if (!$uidPenjual) {
                Log::warning("Order missing uidPenjual field");
                return;
            }

            $mitras = $this->database
                ->getReference($this->refTableNameMitras)
                ->orderByChild('uid')
                ->equalTo($uidPenjual)
                ->getValue();

            if (empty($mitras)) {
                Log::warning("No mitra found for uidPenjual: $uidPenjual");
                return;
            }

            $mitraKey = array_key_first($mitras);
            $mitra = $mitras[$mitraKey];

            $waNumber = $mitra['noTelp'] ?? null;

            if (!$waNumber) {
                Log::warning("Mitra does not have phone number for WA: $uidPenjual");
                return;
            }

            $bukaDashboardAdmin = "http://sasindai.my.id/produk";

            $message = 
            "Halo {$mitra['namaLengkap']}, ada pesanan baru yang berhasil dibayar!. Order ID: {$orderId}\n\n" .
            "Silakan segera proses pengiriman pesanan. Terima kasih 🙏\n\n" . 
            "Buka dasboard admin: {$bukaDashboardAdmin}";

            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token_fonnte'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $waNumber,
                'message' => $message,
                'countryCode' => '62'
            ]);

            Log::info("Notifikasi Fonnte dikirim ke penjual: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Fonnte WA send to seller error: ' . $e->getMessage());
        }
    }

    private function updateOrderStatusInFirebase(string $key, string $status, string $statusPesanan): void
    {
        $this->database->getReference($this->refTableNameOrders . '/' . $key)->update([
            'status' => $status,
            'statusPesanan' => $statusPesanan,
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    private function sendPushNotification(array $order, string $orderId, string $status): void
    {
        $message = CloudMessage::new()
            ->withNotification([
                'title' => 'Status Pembayaran',
                'body' => 'Status transaksi Anda: ' . ucfirst($status),
            ])
            ->withData([
                'order_id' => $orderId,
                'status' => $status,
            ]);

        try {
            $this->messaging->send($message);
        } catch (\Exception $e) {
            Log::error('Failed to send FCM notification: ' . $e->getMessage());
        }
    }
}
