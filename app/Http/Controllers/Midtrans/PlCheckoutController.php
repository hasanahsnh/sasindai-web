<?php

namespace App\Http\Controllers\Midtrans;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PlCheckoutController extends Controller
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

        $produkDipesan = $request->input('produk_dipesan', []);
        $metodePembayaran = $request->input('metode_pembayaran');
        $total = $request->input('total');
        $hargaProduk = $request->input('harga_produk');
        $ongkir = $request->input('ongkir');

        $userSnapshot = $this->database->getReference('users/' . $uid)->getSnapshot();
        if (!$userSnapshot->exists()) {
            return response()->json(['error' => 'User data not found'], 404);
        }

        $userData = $userSnapshot->getValue();
        $namaLengkap = $userData['namaLengkap'] ?? 'User';
        $noTelp = $userData['noTelp'] ?? '081111111111';
        $email = $userData['email'] ?? 'Email;';

        $orderId = (string) Str::uuid();

        // Simpan pesanan awal ke Firebase
        $orderData = [
            'order_id' => $orderId,
            'uid' => $uid,
            'namaLengkap' => $namaLengkap,
            'no_telp' => $noTelp,
            'total' => $total,
            'alamat' => $request->input('alamat'),
            'kurir' => $request->input('kurir'),
            'layanan' => $request->input('layanan'),
            'produk' => $produkDipesan,
            'metode_pembayaran' => $metodePembayaran,
            'status' => 'pending',
            'created_at' => now()->toDateTimeString(),
            'uidPenjual' => $request->input('uid_penjual'),
            'statusPesanan' => $request->input('statusPesanan'),
            'biayaOngkir' => $request->input('ongkir'),
            'tipeCheckout' => $request->input('tipe_checkout'),
        ];

        $this->database->getReference($this->refTableName . '/' . $orderId)->set($orderData);

        // Kirim request ke Midtrans Payment Link API
        $serverKey = config('services.midtrans.server_key');
        $basicAuth = base64_encode($serverKey . ':');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $basicAuth,
        ])->post('https://api.sandbox.midtrans.com/v1/payment-links', [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total
            ],
            'customer_details' => [
                'first_name' => $namaLengkap,
                'phone' => $noTelp,
                'email' => $email,
            ],
            'item_details' => [
                [
                    'id' => 'produk',
                    'price' => $hargaProduk,
                    'quantity' => 1,
                    'name' => 'Harga Produk'
                ],
                [
                    'id' => 'ongkir',
                    'price' => $ongkir,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim'
                ]
            ],
            'enabled_payments' => $metodePembayaran ? [$metodePembayaran] : null,
            'expiry' => [
                'duration' => 1,
                'unit' => 'days'
            ]
        ]);

        if ($response->successful()) {
            $paymentUrl = $response->json('payment_url');

            // Simpan payment_url ke Firebase
            $this->database->getReference($this->refTableName . '/' . $orderId . '/payment_url')->set($paymentUrl);

            return response()->json([
                'payment_url' => $paymentUrl,
                'order_id' => $orderId
            ]);
        } else {
            Log::error('Gagal membuat Payment Link: ' . $response->body());
            return response()->json([
                'error' => 'Gagal membuat payment link',
                'details' => $response->json()
            ], 500);
        }
    }
}
