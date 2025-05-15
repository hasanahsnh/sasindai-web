<?php

namespace App\Http\Controllers\Midtrans;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;

use Symfony\Component\HttpFoundation\Response;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $auth;
    public function __construct(Database $database, Auth $auth) {
        $this->database = $database;
        $this->refTableName = 'orders';
        $this->auth = $auth;
    }
    public function checkout(Request $request){
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $idToken = $request->bearerToken();
        Log::info('Token diterima: ' . $idToken);
        if (!$idToken) {
            return response()->json(['error' => 'Unauthorized: No token provided'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $leewayInSeconds = 8 * 60 * 60;

            $verifiedToken = $this->auth->verifyIdToken($idToken, false, $leewayInSeconds);
            $uid = $verifiedToken->claims()->get('sub');
        } catch (\Throwable $e) {
            Log::error('Error verifying Firebase token: ' . $e->getMessage());
            return response()->json([
                'error' => 'Unauthorized: Invalid Firebase token',
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }

        $uid = $verifiedToken->claims()->get('sub');
        $produkDipesan = $request->input('produk_dipesan', []);

        $existingOrder = collect($this->database->getReference($this->refTableName)->getValue())
            ->filter(fn($order) => $order['uid'] === $uid && $order['status'] === 'pending')
            ->first();

        if ($existingOrder) {
            return response()->json([
                'snap_token' => $existingOrder['snap_token'],
                'order_id' => $existingOrder['order_id']
            ]);
        }

        $orderId = (String) Str::uuid();
        $total = $request->input('total');

        $orderData = [
            'order_id' => $orderId,
            'uid' => $uid,
            'total' => $total,
            'alamat' => $request->input('alamat'),
            'kurir' => $request->input('kurir'),
            'layanan' => $request->input('layanan'),
            'produk' => $produkDipesan,
            'status' => 'pending',
            'created_at' => now()->toDateTimeString()
        ];

        $this->database->getReference($this->refTableName . '/' . $orderId)->set($orderData);

        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'uid' => 'UID_' . $uid,
            ],
            'item_details' => [
                [
                    'id' => 'produk',
                    'price' => $request->input('harga_produk'),
                    'quantity' => 1,
                    'name' => 'Harga Produk'
                ],
                [
                    'id' => 'ongkir',
                    'price' => $request->input('ongkir'),
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim'
                ]
            ]
        ]);

        $this->database->getReference($this->refTableName . '/' . $orderId . '/snap_token')->set($snapToken);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $orderId
        ]);
    }
}
