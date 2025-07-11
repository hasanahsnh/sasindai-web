<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Kreait\Firebase\Exception\FirebaseException;

class PesananController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $auth;
    protected $storage;

    public function __construct(Database $database, Auth $auth, Storage $storage) {
        $this->database = $database;
        $this->auth = $auth;
        $this->refTableName = 'orders';
        $this->storage = $storage;
    }

   
   public function index(Request $request) {
    try {
        $uid = session('session.uid');

        if (!$uid) {
            return redirect()->back()->with('error', 'UID tidak ditemukan dalam session.');
        }

        // Ambil filter status pesanan dari query string
        $statusPesananFilter = $request->query('status_pesanan');

        // Ambil semua data pesanan
        $dataPesanans = $this->database->getReference($this->refTableName)->getValue() ?? [];

        $filteredPesanan = [];

        foreach ($dataPesanans as $key => $item) {

            // Terapkan filter status pesanan jika ada
            if ($statusPesananFilter && strtolower($item['statusPesanan'] ?? '') !== strtolower($statusPesananFilter)) {
                continue;
            }

            $item['orderId'] = $key;
            $filteredPesanan[$key] = $item;
        }

        // Ambil data mitra
        $dataMitraProfileRef = $this->database->getReference('mitra/' . $uid);
        $dataMitraProfile = $dataMitraProfileRef->getValue();

        // Cek apakah toko sudah lengkap
        $tokoBelumLengkap = false;
        $statusVerifikasi = null;

        if (!$dataMitraProfile) {
            $tokoBelumLengkap = true;
        } else {
            $statusVerifikasi = $dataMitraProfile['statusVerifikasiToko'] ?? 'pending';
            if ($statusVerifikasi !== 'accepted') {
                $tokoBelumLengkap = true;
            }
        }

        return view('mitra.pages.pesanan', compact(
            'filteredPesanan',
            'dataMitraProfile',
            'tokoBelumLengkap',
            'statusVerifikasi',
            'statusPesananFilter'
        ));

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengambil data pesanan: ' . $e->getMessage());
    }
}



    public function printRincianPesanan($order_id) {
        // Ambil data pesanan dari Firebase
        $order = $this->database
            ->getReference($this->refTableName . '/' . $order_id)
            ->getValue();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Load view Blade ke PDF
        $pdf = Pdf::loadView('rincian-pesanan', [
            'order' => $order
        ]);

        // Return PDF ke browser untuk diunduh
        return $pdf->download('invoice_' . $order_id . '.pdf');
    }
}
