<?php

namespace App\Http\Controllers\Midtrans;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Midtrans\Config;
use Illuminate\Support\Facades\Http;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $auth;

    public function __construct(Database $database, Auth $auth)
    {
        $this->database = $database;
        $this->refTableName = 'orders';
        $this->auth = $auth;
    }

    public function checkout(Request $request)
    {
        $this->configureMidtrans();

        $orderId = Str::uuid()->toString() . '-' . now()->timestamp;
        $grossAmount = $request->total; // pastikan sesuai dengan harga total dari produk

        // Simpan order lebih dulu ke Firebase (status pending)
        $orderData = [
            'order_id' => $orderId,
            'status' => 'pending',
            'statusPesanan' => 'menunggu pembayaran',
            'created_at' => now()->toDateTimeString(),
            'uid' => $request->uid,
            'namaLengkap' => $request->namaLengkap,
            'produk' => $request->produk,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'total' => $grossAmount,
            'metode_pembayaran' => $request->metode_pembayaran,
            'biayaOngkir' => $request->biayaOngkir,
            'layanan' => $request->layanan,
            'kurir' => $request->kurir,
            'tipeCheckout' => $request->tipeCheckout,
            'user_token' => $request->user_token ?? '',
        ];

        $this->database->getReference($this->refTableName . '/' . $orderId)->set($orderData);

        // Buat parameter Snap API
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $request->namaLengkap,
                'phone' => $request->no_telp,
            ],
            'enabled_payments' => ['gopay', 'shopeepay', 'bank_transfer', 'bca_va', 'bni_va', 'bri_va'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error("Error creating Snap Token: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat token pembayaran'], 500);
        }
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

}
