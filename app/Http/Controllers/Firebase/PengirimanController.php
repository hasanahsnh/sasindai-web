<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Kreait\Firebase\Contract\Database;

class PengirimanController extends Controller
{
    protected $database;
    protected $refTableName;

    public function __construct(Database $database) {
        $this->database = $database;
        $this->refTableName = 'pengiriman';
    }

    function index() {
        /*$dataPesanans = $this->database->getReference($this->refTableName)->getValue();
        return response()->json([
            'success' => true,
            'data' => $dataPesanans
        ]);*/

        return view('mitra.pages.pengiriman');
    }

    function createPengiriman(Request $request) {
        $request->validate([
            'resi_pesanan' => 'required',
            'kurir_pesanan' => 'required'
        ]);

        $idPengiriman = (string) Str::uuid();

        $postData = [
            'idPengiriman' => $idPengiriman,
            'idPesanan' => $request->id_pesanan,
            'kurirPesanan' => $request->kurir_pesanan,
            'resiPpesanan' => $request->resi_pesanan
        ];

        $this->database->getReference("{$this->refTableName}/{$idPengiriman}")->set($postData);

        $this->database->getReference("orders/{$request->id_pesanan}/statusPesanan")->set('dikirim');

        return redirect()->back()->with('success', 'Data pengiriman berhasil disimpan');
    }
}
