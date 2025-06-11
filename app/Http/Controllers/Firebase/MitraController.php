<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use App\Models\User;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\Auth\EmailNotFound;

class MitraController extends Controller
{
    //
    protected $auth;
    protected $database;
    protected $refTableNameUsers, $refTableNameMitras, $refTableNameOrders, $refTableNameProduk, $refTableNamePengiriman;

    public function __construct(Auth $auth, Database $database) {

        $this->auth = $auth;
        $this->database = $database;
        $this->refTableNameUsers = 'users';
        $this->refTableNameMitras = 'mitras';
        $this->refTableNameOrders = 'orders';
        $this->refTableNameProduk = 'produk';
        $this->refTableNamePengiriman = 'pengiriman';

    }

    // Mengalihkan ke dashboard
    function index() {
        // Ambil status session setelah login
        $uid = session('session.uid');
        $idRole = session('session.idRole');

        // Ambil data user
        $mitraRef = $this->database->getReference($this->refTableNameUsers . '/' . $uid);
        $mitraSnapshot = $mitraRef->getValue();
        $mitraData = $mitraSnapshot ?? [];

        // Ambil data role
        $roleData = $this->database->getReference('roles/' . $idRole)->getValue();
        $mitraRole = $roleData['role'] ?? 'Tidak diketahui';

        // Ambil data produk
        $dataProduk = $this->database->getReference($this->refTableNameProduk)->getValue() ?? [];
        $produks = [];

        if ($dataProduk) {
            foreach ($dataProduk as $key => $item) {
                if (isset($item['uid']) && $item['uid'] === $uid) {
                    $produks[$key] = $item;
                }
            }
        }

        // Hitung total produk
        $totalProduk = count($produks);

        // Filter produk yang valid (tidak null dan tidak kosong)
        $filteredProduk = array_filter($produks, function ($item) {
            return !is_null($item) && !empty($item);
        });

        // Ambil data mitra
        $mitraProfileRef = $this->database->getReference($this->refTableNameMitras . '/' . $uid);
        $mitraProfile = $mitraProfileRef->getValue();

        // Ambil data pesanan dengan status pesanan = "dikemas"
        $dataPesanans = $this->database->getReference($this->refTableNameOrders);
        $pesanansSnapshot = $dataPesanans->getValue();
        $pesanans = $pesanansSnapshot ?? [];

        $totalPesanan = count($pesanans);
        $filteredPesanan = array_filter($pesanans, function ($item) {
            return !is_null($item) && !empty($item);
        });

        $filteredPesanans = [];

        if ($pesanans) {
            foreach ($pesanans as $orderId => $pesanan) {
                if (isset($pesanan['statusPesanan']) && (strtolower($pesanan['statusPesanan']) === 'dikemas')) {
                    $filteredPesanans[$orderId] = $pesanan;
                }
            }
        }

        $totalPesananYangHarusDikirim = count($filteredPesanans);

        if (empty($filteredPesanans)) {
            $filteredPesanans = [];
            error_log("Tidak ada pesanan dengan status 'Dikemas'.");
        }

        // Ambil data pengiriman
        $dataProduk = $this->database->getReference($this->refTableNamePengiriman)->getValue() ?? [];
        $pengiriman = [];

        $totalPengiriman = count($pengiriman);

        // Cek apakah data mitra belum tersedia
        $tokoBelumLengkap = false;
        $statusVerifikasi = null;

        if (!$mitraProfile) {
            // Belum isi data toko
            $tokoBelumLengkap = true;
        } else {
            // Sudah isi, cek statusnya
            $statusVerifikasi = $mitraProfile['statusVerifikasiToko'] ?? 'pending';

            if ($statusVerifikasi !== 'accepted') {
                $tokoBelumLengkap = true;
            }
        }
        
        return view('mitra.pages.index', compact(
        'uid',
        'mitraData',
        'mitraRole',
        'tokoBelumLengkap',
        'statusVerifikasi',
        'filteredPesanans', 
        'filteredProduk', 
        'totalProduk',
        'totalPesanan',
        'filteredPesanan',
        'totalPesananYangHarusDikirim',
        'totalPengiriman'));
        
    }

    // Mengirim email verifikasi
    function kirimVerifikasi() {
        return view('mitra.pages.mitra-register');
    }

    function kirimUlangVerifikasi(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $auth = app(Auth::class);

        try {
            $auth->sendEmailVerificationLink($request->email);
            return view('mitra.pages.mitra-register');
        } catch (EmailNotFound $e) {
            return back()->with('error', 'Email tidak ditemukan atau belum terdaftar.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengirim ulang email verifikasi.');
        }
    }

    // Register mitra
    function mitraRegister(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'nama_lengkap' => 'required|string|max:50',
            'no_telp' => 'required'
        ]);

        $auth = app(Auth::class);
        
        try {
            $firebaseUser = $auth->createUser([
                'email' => $request->email,
                'password' => $request->password,
            ]);
    
            try {
                $this->database
                    ->getReference($this->refTableNameUsers . '/' . $firebaseUser->uid)
                    ->set([
                        'uid' => $firebaseUser->uid,
                        'email' => $request->email,
                        'namaLengkap' => $request->nama_lengkap,
                        'noTelp' => $request->no_telp,
                        'authMethod' => 'email/password',
                        'role' => 'ROLE_MITRA',
                        'emailIsVerified' => false,
                    ]);
            } catch (\Throwable $e) {
                logger("Gagal simpan ke database: " . $e->getMessage());
            }

            // Kirim email verifikasi
            try {
                $auth->sendEmailVerificationLink($request->email);
                return back()->with('success', 'Email verifkasi telah dikirim ke email Anda.');
            } catch (EmailNotFound $e) {
                logger("Gagal kirim email verifikasi: " . $e->getMessage());
            }

        } catch (AuthException $e) {
            return back()->withErrors(['firebase' => 'Gagal membuat akun: ' . $e->getMessage()]);
        }
    }

    function dataToko(Request $request) {
        // Ambil status session setelah login
        $uid = session('session.uid');
        $idRole = session('session.idRole');
        $email = session('session.email');

        // Ambil data user
        $dataMitraRef = $this->database->getReference($this->refTableNameUsers . '/' . $uid);
        $dataMitraSnapshot = $dataMitraRef->getValue();
        $dataMitraData = $dataMitraSnapshot ?? [];

        // Ambil data role
        $dataRoleData = $this->database->getReference('roles/' . $idRole)->getValue();
        $dataMitraRole = $dataRoleData['role'] ?? 'Tidak diketahui';

        // Ambil data mitra
        $dataMitraProfileRef = $this->database->getReference($this->refTableNameMitras . '/' . $uid);
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

        return view('mitra.pages.data-toko', compact( 'uid', 'dataMitraData', 'dataMitraRole', 'dataMitraProfile', 'tokoBelumLengkap', 'statusVerifikasi'));
    }

    function perbaruiDataToko(Request $request) {
        $request->validate([
            'nama_toko' => 'required|string|max:50',
            'alamat_toko' => 'required|string|max:255',
            'no_telepon' => 'required|max:16',
            'bank' => 'required',
            'no_rekening' => 'required',
        ]);

        $uid = session('session.uid');
        if (!$uid) {
            return redirect()->back()->withErrors(['uid' => 'UID tidak ditemukan dalam sesi']);
        }

        $userRef = $this->database->getReference('users/' . $uid)->getValue();
        if (!$userRef || !isset($userRef['namaLengkap'])) {
            return redirect()->back()->with(['error' => 'Data pengguna tidak ditemukan atau tidak lengkap']);
        }

        $namaLengkap = $userRef['namaLengkap'];
        $uniqueIdToko = substr($uid, 0, 4) . 'mitra' . date('dmy');

        $data = [
            'uid' => $uid,
            'idToko' => $uniqueIdToko,
            'namaLengkap' => $namaLengkap,
            'namaToko' => $request->nama_toko,
            'alamatToko' => $request->alamat_toko,
            'noTelp' => $request->no_telepon,
            'bank' => $request->bank,
            'noRekening' => $request->no_rekening,
            'statusVerifikasiToko' => 'pending'
        ];

        //dd($data);

        try {
            $this->database->getReference("mitras/{$uid}")->set($data);
            return redirect()->route('data-toko')->with('success', 'Data Toko berhasil ditambah');
        } catch(FirebaseException $e) {
            return redirect()->route('data-toko')->with('error', 'Gagal menambah data toko' . $e->getMessage());
        }

    }

}
