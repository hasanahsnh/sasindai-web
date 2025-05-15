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

class CallbackController extends Controller
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
        // Set konfigurasi Midtrans
        try {
            // Set konfigurasi Midtrans
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $serverKey = config('services.midtrans.server_key');

            // Ambil informasi notifikasi dari Midtrans
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);
            $notification = new Notification();

            $expectedSignature = hash('sha512', $data['order_id'] . $data['status_code'] . $data['gross_amount'] . $serverKey);

            if ($expectedSignature !== $data['signature_key']) {
                return response()->json(['status' => 'unauthorized'], 403);
            }
            
            // Ambil data transaksi
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;

            Log::info('Looking for order_id: ' . $orderId);

            // Temukan order berdasarkan order_id
            $orders = $this->database
                ->getReference($this->refTableName)
                ->orderByChild('order_id')
                ->equalTo($orderId)
                ->getValue();

            if (!empty($orders)) {
                $orderKey = array_key_first($orders);
                $order = $orders[$orderKey];

                // Tentukan status transaksi
                $status = '';
                switch ($transactionStatus) {
                    case 'capture':
                    case 'settlement':
                        $status = 'success';
                        break;
                    case 'pending':
                        $status = 'pending';
                        break;
                    case 'deny':
                        $status = 'failed';
                        break;
                    case 'expire':
                        $status = 'expired';
                        break;
                    case 'cancel':
                        $status = 'canceled';
                        break;
                }

                // Update status transaksi di Firebase
                $this->database->getReference($this->refTableName . '/' . $orderKey)->update([
                    'status' => $status,
                    'updated_at' => now()->toDateTimeString()
                ]);

                $userToken = $order['user_token'] ?? null;
                if ($userToken) {
                    $message = CloudMessage::new()
                        ->withNotification([
                            'title' => 'Pembayaran Anda',
                            'body' => 'Status transaksi Anda adalah: ' . ucfirst($status),
                        ])
                        ->withData([
                            'order_id' => $orderId,
                            'status' => $status,
                        ]);

                    try {
                        // Kirim pesan ke FCM
                        $this->messaging->send($message);
                    } catch (\Exception $e) {
                        // Log error if FCM fails
                        Log::error('FCM Error: ' . $e->getMessage());
                    }
                }
            }

            // Kembalikan response sukses
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }
}
