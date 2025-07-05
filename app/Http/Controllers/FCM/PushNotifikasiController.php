<?php

namespace App\Http\Controllers\FCM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Kreait\Firebase\Contract\Database;

use Google\Auth\OAuth2;

class PushNotifikasiController extends Controller
{
    protected $database;
    protected $refTableNotifikasi;
    public function __construct(Database $database) {
        $this->database = $database;
        $this->refTableNotifikasi = 'notifikasi';
    }

    public function index()
    {
        $notifikasi = $this->database->getReference($this->refTableNotifikasi)->getValue();
        return view('admin.pages.push-notifikasi', compact('notifikasi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string|max:1000',
        ]);

        $id = (string) Str::uuid();
        $postData = [
            'id' => $id,
            'judulNotifikasi' => $request->judul,
            'pesanNotifikasi' => $request->isi,
            'waktu' => now()->toDateTimeString(),
        ];

        //dd($postData);

        // Simpan notifikasi ke Firebase Realtime Database
        $this->database->getReference($this->refTableNotifikasi . "/" . $id)->set($postData);

        $tokens = $this->ambilSemuaToken();

        if (empty($tokens)) {
            return back()->with('error', 'Tidak ada token yang tersedia.');
        }

        foreach ($tokens as $token) {
            $this->kirimFCMV1($token, $data['judul'], $data['isi']);
        }

        return back()->with('success', 'Notifikasi berhasil dikirim ke semua perangkat.');
    }

    private function ambilSemuaToken(): array
    {
        $pushTokens = $this->database->getReference('push_tokens')->getValue();
        $tokens = [];

        if ($pushTokens && is_array($pushTokens)) {
            $tokens = array_keys($pushTokens); // token disimpan sebagai key
        }

        return $tokens;
    }

    private function kirimFCMV1(string $token, string $title, string $body)
    {
        $serviceAccountPath = config('firebase.projects.app.credentials.file');

        //dd($serviceAccountPath, file_exists($serviceAccountPath));

        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

        $oauth = new OAuth2([
            'audience' => 'https://oauth2.googleapis.com/token',
            'issuer' => $serviceAccount['client_email'],
            'signingAlgorithm' => 'RS256',
            'signingKey' => $serviceAccount['private_key'],
            'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
            'scope' => ['https://www.googleapis.com/auth/firebase.messaging'],
        ]);

        $authToken = $oauth->fetchAuthToken();
        $accessToken = $authToken['access_token'] ?? null;

        if (!$accessToken) {
            return false;
        }

        $projectId = $serviceAccount['project_id'];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'android' => [
                    'priority' => 'high',
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        if ($response->failed()) {
            $body = $response->json();
            if (isset($body['error']['status']) && $body['error']['status'] === 'NOT_FOUND') {
                $this->database->getReference('push_tokens/' . $token)->remove();
                Log::warning("Token $token tidak valid, dihapus dari database.");
            }
        }

        return $response->successful();
    }

    public function hapusNotifikasi($id) {
        $key = $id;
        $hapusNotifikasi = $this->database->getReference($this->refTableNotifikasi . '/' . $key)->remove();
        if ($hapusNotifikasi) {
            return redirect()->back()->with('success', 'Pesan berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Pesan gagal dihapus');
        }
    }
}
