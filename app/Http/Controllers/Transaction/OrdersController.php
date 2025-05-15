<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $messaging;
    public function updateStatus(Request $request, $orderId)
    {
        $status = $request->input('status');

        // Misalnya kamu update order di Firebase Realtime Database
        $firebaseUrl = "https://<project-id>.firebaseio.com/order/{$orderId}.json";

        Http::patch($firebaseUrl, [
            'status' => $status,
            'updated_at' => now()->format('Y-m-d H:i:s')
        ]);

        if ($status === 'success') {
            // Ambil data order dari Firebase
            $order = Http::get($firebaseUrl)->json();
            $this->notifySellers($order);
        }

        return response()->json(['message' => 'Status updated successfully.']);
    }

    private function notifySellers(array $order)
    {
        foreach ($order['produk'] as $item) {
            $produkId = $item['id_produk'];

            // Ambil data produk dari Firebase
            $produk = Http::get("https://<project-id>.firebaseio.com/produk/{$produkId}.json")->json();

            if (!$produk || !isset($produk['uid'])) {
                continue;
            }

            $penjualUid = $produk['uid'];
            $penjual = Http::get("https://<project-id>.firebaseio.com/users/{$penjualUid}.json")->json();

            if ($penjual && !empty($penjual['email']) && !empty($penjual['emailIsVerified'])) {
                // Kirim email ke penjual
                Mail::raw(
                    "Pesanan baru untuk produk: {$produk['namaProduk']} dari {$order['alamat']}",
                    function ($message) use ($penjual) {
                        $message->to($penjual['email'])
                                ->subject('Pesanan Baru Berhasil');
                    }
                );
            }
        }
    }
}
