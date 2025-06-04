<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Midtrans\Notification;
use Midtrans\Config;
use Illuminate\Support\Str;

class PlCallbackController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $messaging;

    public function __construct(Database $database, Messaging $messaging) {
        $this->database = $database;
        $this->refTableName = 'orders';
        $this->messaging = $messaging;
    }

    public function handleCallback(Request $request)
    {
        try {
            // Setup Midtrans config
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Ambil data mentah JSON notifikasi dari Midtrans
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);

            if (!$data) {
                Log::error("Midtrans callback: Invalid JSON");
                return response()->json(['status' => 'bad_request'], 400);
            }

            // Validasi signature key untuk keamanan
            $serverKey = config('services.midtrans.server_key');
            $expectedSignature = hash('sha512', $data['order_id'] . $data['status_code'] . $data['gross_amount'] . $serverKey);

            if ($expectedSignature !== $data['signature_key']) {
                Log::warning("Midtrans callback: Invalid signature key");
                return response()->json(['status' => 'unauthorized'], 403);
            }

            // Gunakan Midtrans Notification helper agar lebih aman dan lengkap parsingnya
            try {
                $notification = new \Midtrans\Notification();
            } catch (\Exception $e) {
                Log::error("Midtrans callback error: " . $e->getMessage());
                return response()->json(['status' => 'error'], 500);
            }

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;

            $uuidBase = Str::beforeLast($orderId, '-');

            Log::info("Midtrans callback received for order_id: $orderId with status: $transactionStatus");

            if (!Str::isUuid($orderId)) {
                $targetOrderId = Str::beforeLast($orderId, '-');
            }

            $orders = $this->database
                ->getReference($this->refTableName)
                ->orderByChild('order_id')
                ->equalTo($targetOrderId)
                ->getValue();

            if (empty($orders)) {
                Log::warning("Order with order_id $orderId not found");
                return response()->json(['status' => 'not_found'], 404);
            }

            $orderKey = array_key_first($orders);
            $order = $orders[$orderKey];

            // Map status Midtrans ke status internal
            $statusMap = [
                'capture' => 'success',
                'settlement' => 'success',
                'pending' => 'pending',
                'deny' => 'failed',
                'expire' => 'expired',
                'cancel' => 'canceled'
            ];

            $status = $statusMap[$transactionStatus] ?? $transactionStatus;

            $statusPesananMap = [
                'pending' => 'menunggu pembayaran',
                'success' => 'dikemas',
                'failed' => 'gagal pembayaran',
                'expired' => 'kadaluarsa',
                'canceled' => 'dibatalkan',
            ];

            $statusPesanan = $statusPesananMap[$status] ?? $status;

            // Update status di Firebase
            $this->database->getReference($this->refTableName . '/' . $orderKey)->update([
                'status' => $status,
                'statusPesanan' => $statusPesanan,
                'updated_at' => now()->toDateTimeString(),
            ]);

            // Kirim push notification jika token ada
            if (!empty($order['user_token'])) {
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

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }
}
