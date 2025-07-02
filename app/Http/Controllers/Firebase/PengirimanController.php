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

class PengirimanController extends Controller
{
    protected $database;
    protected $refTableName, $refTableNamePengiriman;
    protected $auth;
    protected $storage;

    public function __construct(Database $database, Auth $auth, Storage $storage) {
        $this->database = $database;
        $this->auth = $auth;
        $this->refTableName = 'orders';
        $this->refTableNamePengiriman = 'pengiriman';
        $this->storage = $storage;
    }

   
   public function index(Request $request) {
        try {
            $uid = session('session.uid');

            if (!$uid) {
                return redirect()->back()->with('error', 'UID tidak ditemukan dalam session.');
            }

            // Ambil semua data pesanan
            $dataPengiriman = $this->database->getReference($this->refTableNamePengiriman)->getValue() ?? [];

            // Ambil data mitra
            $dataMitraProfileRef = $this->database->getReference('mitras/' . $uid);
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

            return view('mitra.pages.pengiriman', compact(
                'dataPengiriman',
                'dataMitraProfile',
                'tokoBelumLengkap',
                'statusVerifikasi'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil data pesanan: ' . $e->getMessage());
        }
    }

    public function createPengiriman(Request $request) {
        $request->validate([
            'resi_pesanan' => 'required',
        ]);

        $idPengiriman = (string) Str::uuid();

        $postData = [
            'idPengiriman' => $idPengiriman,
            'idPesanan' => $request->id_pesanan,
            'kurirPesanan' => $request->kurir_pesanan,
            'resiPesanan' => $request->resi_pesanan,
            'statusPengiriman' => 'Aktif'
        ];

        $this->database->getReference("{$this->refTableNamePengiriman}/{$idPengiriman}")->set($postData);

        $this->database->getReference("orders/{$request->id_pesanan}/statusPesanan")->set('dikirim');

        return redirect()->back()->with('success', 'Data pengiriman berhasil disimpan');
    }
}
