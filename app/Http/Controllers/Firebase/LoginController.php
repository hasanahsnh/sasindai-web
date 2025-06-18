<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\AuthException;

class LoginController extends Controller
{
    protected $auth;
    protected $database;
    protected $refTableName;

    public function __construct(Auth $auth, Database $database) {

        $this->auth = $auth;
        $this->database = $database;
        $this->refTableName = 'users';

    }

    public function login(Request $request)
    {
        // Set leeway untuk menghindari masalah perbedaan waktu
        $leewayInSeconds = 8 * 60 * 60; // UTC dan Asia/Makassar

        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $email = $validatedData['email'];
        $password = $validatedData['password'];

        try {
            // Metode sign in
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);

            // Mendapatkan ID Token
            $idToken = $signInResult->idToken();
            $uid = $signInResult->firebaseUserId();

            // Mulai verify token
            $verifiedIdToken = $this->auth->verifyIdToken($idToken, false, $leewayInSeconds);

            if ($verifiedIdToken) {
                // Mengambil UID dari token
                $uid = $verifiedIdToken->claims()->get('sub');
                // Mengambil data bawaan auth (seperti uid, email, display name, dsb) tersedia dan bukan data inputan manual
                $user = $this->auth->getUser($uid);

                // Ambil data role di users
                $userRef = $this->database->getReference($this->refTableName . '/' . $uid)->getValue();
                $idRole = $userRef['role'] ?? null;
                $namaLengkap = $userRef['namaLengkap'] ?? null;

                if (!$user->emailVerified) {
                    return redirect()->route('login')->with('error', 'Email belum diverifikasi. Silakan cek kotak masuk Anda.');
                } elseif ($user->emailVerified) {
                    $this->database
                    ->getReference($this->refTableName . '/' . $uid)
                    ->update(['emailIsVerified' => true]);
                }

                if (!$idRole) {
                    return redirect()->back()->with('error', 'Autentikasi gagal: Data pengguna tidak ditemukan');
                } else {
                    session()->put('session', [
                        'uid' => $user->uid,
                        'email' => $user->email,
                        'idRole' => $idRole,
                        'namaLengkap' => $namaLengkap,
                    ]);

                    return match ($idRole) {
                        'ROLE_ADMIN' => redirect('/home')->with('success', 'Berhasil masuk sebagai Administrator'),
                        'ROLE_MITRA' => redirect('/dashboard-mitra')->with('success', 'Selamat datang mitra'),
                        default => redirect()->back()->with('error', 'Autentikasi gagal')
                    };
                }

            } else {
                return redirect()->back()->with('error', 'Gagal memverifikasi token');
            }
        } catch (AuthException $e) {
            return redirect()->back()->with('error', 'Login gagal: ' . $e->getMessage());
        }

    }

    public function logout()
    {
        session()->flush();
        return redirect('/masuk')->with('success', 'Logged out successfully.');
    }

    // Menampilkan halaman form reset password
    function resetPassword() {
        return view('password-reset');
    }
    

    function passwordReset(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $auth = app(Auth::class);
            $auth->sendPasswordResetLink($request->email);
    
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return back()->withErrors(['email' => 'Email tidak ditemukan di Firebase.']);
        }
    }
}
