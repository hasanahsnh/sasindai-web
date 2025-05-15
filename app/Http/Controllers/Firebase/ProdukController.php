<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Kreait\Firebase\Exception\FirebaseException;

class ProdukController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $auth;
    protected $storage;
    //

    public function __construct(Database $database, Auth $auth, Storage $storage) {
        $this->database = $database;
        $this->auth = $auth;
        $this->refTableName = 'produk';
        $this->storage = $storage;
    }

    function index() {
        // Ambil data user dari session
        $uid = session('session.uid');

        $dataProduk = $this->database->getReference($this->refTableName)->getValue();
        
        $produks = [];

        if ($dataProduk) {
            foreach ($dataProduk as $key => $item) {
                if (isset($item['uid']) && $item['uid'] === $uid) {
                    $produks[$key] = $item;
                }
            }
        }

        // Ambil data mitra
        $dataMitraProfileRef = $this->database->getReference('mitra/' . $uid);
        $dataMitraProfile = $dataMitraProfileRef->getValue();

        // Cek apakah data mitra belum tersedia
        $tokoBelumLengkap = false;
        $statusVerifikasi = null;

        if (!$dataMitraProfile) {
            // Belum isi data toko
            $tokoBelumLengkap = true;
        } else {
            // Sudah isi, cek statusnya
            $statusVerifikasi = $dataMitraProfile['statusVerifikasiToko'] ?? 'pending';

            if ($statusVerifikasi !== 'accepted') {
                $tokoBelumLengkap = true;
            }
        }

        return view('mitra.pages.produk', compact('produks', 'dataMitraProfile', 'tokoBelumLengkap', 'statusVerifikasi'));
    }

    function tambahProduk(Request $request) {
        // Input validate section
        $request->validate([
            'nama_produk' => 'required|string|max:50',
            'foto_produk' => 'required|array',
            'foto_produk.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi_produk' => 'required|string',
            'varian.nama' => 'required|array',
            'varian.nama.*' => 'required|string',
            'varian.gambar' => 'required|array',
            'varian.gambar.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'varian.size' => 'required|array',
            'varian.size.*' => 'required|string',
            'varian.harga' => 'required|array',
            'varian.harga.*' => 'required|numeric|min:0',
            'varian.stok' => 'required|array',
            'varian.stok.*' => 'required|integer|min:0',
            'varian.berat' => 'required|array',
            'varian.berat.*' => 'required|numeric|min:0',
        ]);
        // End input validate

        $uid = session('session.uid');

        if (!$uid) {
            return redirect()->back()->withErrors(['uid' => 'UID tidak ditemukan dalam sesi']);
        }

        $uniqueIdProduk = substr($uid, 0, 4) . '_' . Str::slug($request->nama_produk, '_') . '_' . Str::random(4);
        $terjual = 0;

        $storage = app('firebase.storage');
        $defaultBucket = $storage->getBucket();
        $fotoUrl = [];

        // Siapkan unggah foto produk
        foreach ($request->file('foto_produk') as $foto) {
            $filename = 'foto_produk/' . $uniqueIdProduk . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
            $defaultBucket->upload(
                file_get_contents($foto->getRealPath()),
                ['name' => $filename]
            );
            $url = 'https://storage.googleapis.com/' . $defaultBucket->name() . '/' . $filename;
            $fotoUrl[] = $url;
        }

        // Upload dan siapkan varian
        $dataVarian = [];
        $totalStok = 0;

        foreach ($request->varian['nama'] as $i => $namaVarian) {
            $gambar = $request->file('varian.gambar')[$i];
            $gambarFilename = 'varian_gambar/' . $uniqueIdProduk . '_varian_' . $i . '_' . Str::random(8) . '.' . $gambar->getClientOriginalExtension();
            $defaultBucket->upload(file_get_contents($gambar->getRealPath()), ['name' => $gambarFilename]);
            $urlGambarVarian = 'https://storage.googleapis.com/' . $defaultBucket->name() . '/' . $gambarFilename;
    
            $dataVarian[] = [
                'nama' => $namaVarian,
                'size' => $request->varian['size'][$i],
                'harga' => (int)$request->varian['harga'][$i],
                'stok' => (int)$request->varian['stok'][$i],
                'berat' => max(0, (float) str_replace(',', '.', $request->varian['berat'][$i])),
                'gambar' => $urlGambarVarian,
            ];
    
            $totalStok += (int)$request->varian['stok'][$i];
        }

        // Post data berdasarkan jenis size
        $data = [
            'idProduk' => $uniqueIdProduk,
            'uid' => $uid,
            'namaProduk' => $request->nama_produk,
            'urlFotoProduk' => $fotoUrl,
            'deskripsiProduk' => $request->deskripsi_produk,
            'varian' => $dataVarian,
            'terjual' => $terjual,
            'sisaStok' => $totalStok,
            'createAt' => Carbon::now()->toDateTimeString(),
            'updateAt' => Carbon::now()->toDateTimeString(),

        ];

        // debug
        //dd($data);
        // End post data

        try {
            $this->database->getReference($this->refTableName . '/' . $uniqueIdProduk)->set($data);
            return redirect()->back()->with('success', 'Produk berhasil disimpan!');
        } catch (FirebaseException $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan produk' . $e->getMessage());
        }
    }

    function editProduk(Request $request) {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:50',
            'foto_produk' => 'sometimes|array',
            'foto_produk.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi_produk' => 'required|string',
            'varian.nama' => 'required|array',
            'varian.nama.*' => 'required|string',
            'varian.gambar' => 'sometimes|array',
            'varian.gambar.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'varian.size' => 'required|array',
            'varian.size.*' => 'required|string',
            'varian.harga' => 'required|array',
            'varian.harga.*' => 'required|numeric|min:0',
            'varian.stok' => 'required|array',
            'varian.stok.*' => 'required|integer|min:0',
            'varian.berat' => 'required|array',
            'varian.berat.*' => 'required|numeric|min:0',

            'key' => 'required|string'
        ]);
    
        $key = $request->input('key');
        $uid = session('session.uid');
        if (!$uid) {
            return redirect()->back()->withErrors(['uid' => 'UID tidak ditemukan dalam sesi']);
        }
    
        $ref = $this->database->getReference($this->refTableName . '/' . $key);
        $produkLama = $ref->getValue();
    
        if (!$produkLama) {
            return redirect()->back()->withErrors(['produk' => 'Produk tidak ditemukan']);
        }
    
        $storage = app('firebase.storage');
        $defaultBucket = $storage->getBucket();
        $fotoUrl = $produkLama['urlFotoProduk'] ?? [];
    
        // Jika ada foto baru, unggah ulang
        if ($request->hasFile('foto_produk')) {
            $fotoUrl = [];
            foreach ($request->file('foto_produk') as $foto) {
                $filename = 'foto_produk/' . $key . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $defaultBucket->upload(file_get_contents($foto->getRealPath()), ['name' => $filename]);
                $url = 'https://storage.googleapis.com/' . $defaultBucket->name() . '/' . $filename;
                $fotoUrl[] = $url;
            }
        }
    
        // Siapkan data varian
        $dataVarian = [];
        $totalStok = 0;
    
        foreach ($request->varian['nama'] as $i => $namaVarian) {
            if (isset($request->file('varian.gambar')[$i])) {
                $gambar = $request->file('varian.gambar')[$i];
                $gambarFilename = 'varian_gambar/' . $key . '_varian_' . $i . '_' . Str::random(8) . '.' . $gambar->getClientOriginalExtension();
                $defaultBucket->upload(file_get_contents($gambar->getRealPath()), ['name' => $gambarFilename]);
                $urlGambarVarian = 'https://storage.googleapis.com/' . $defaultBucket->name() . '/' . $gambarFilename;
            } else {
                $urlGambarVarian = $produkLama['varian'][$i]['gambar'] ?? null;
            }
    
            $dataVarian[] = [
                'nama' => $namaVarian,
                'size' => $request->varian['size'][$i],
                'harga' => (int)$request->varian['harga'][$i],
                'stok' => (int)$request->varian['stok'][$i],
                'berat' => max(0, (float) str_replace(',', '.', $request->varian['berat'][$i])),
                'gambar' => $urlGambarVarian,
            ];
    
            $totalStok += (int)$request->varian['stok'][$i];
        }
    
        $dataUpdate = [
            'namaProduk' => $request->nama_produk,
            'urlFotoProduk' => $fotoUrl,
            'deskripsiProduk' => $request->deskripsi_produk,
            'varian' => $dataVarian,
            'sisaStok' => $totalStok,
            'updateAt' => Carbon::now()->toDateTimeString(),
        ];
    
        try {
            $ref->update($dataUpdate);
            return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
        } catch (FirebaseException $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }
    
}
