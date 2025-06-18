<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Objek3DController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $storage;
    public function __construct(Database $database, Storage $storage) {
        $this->database = $database;
        $this->refTableName = 'objek3d';
        $this->storage = $storage;
    }

    function index() {
        $objek3d = $this->database->getReference($this->refTableName)->getValue();
        if ($objek3d === null) {
            $objek3d = [];
        }

        $role = session('session.idRole');
        $uid = session('session.uid');

        $statusVerifikasi = null;

        if ($role === 'ROLE_MITRA') {
            // Ambil status verifikasi toko
            $dataMitraProfileRef = $this->database->getReference('mitra/' . $uid);
            $dataMitraProfile = $dataMitraProfileRef->getValue();

            $statusVerifikasi = $dataMitraProfile['statusVerifikasiToko'] ?? 'pending';

            return view('mitra.pages.input-objek-3d', compact('objek3d', 'statusVerifikasi'));
        }

        if ($role === 'ROLE_ADMIN') {
            return view('admin.pages.input-objek-3d', compact('objek3d'));
        }

        abort(403);
    }


    function store(Request $request) {

        $role = session('session.idRole');

        $request->validate([
            'nama_objek' => 'required|string|max:255',
            'deskripsi_objek' => 'nullable|string',
            'file_objek' => 'required|file|max:10240',
            'preview_objek' => 'required|file|max:10240',
        ]);
    
        $uniqueIdProduk = Str::slug($request->nama_objek, '_') . '_' . Str::random(4);

        if ($request->hasFile('file_objek')) {
            $file = $request->file('file_objek');
            $uploadedFile = $this->storage->getBucket()->upload(
                fopen($file->getRealPath(), 'r'),
                ['name' => 'objek3d/' . $file->getClientOriginalName()]
            );

            $glbUrl = $uploadedFile->info()['mediaLink'];
        } else {
            return back()->withErrors(['file_objek' => 'File .glb tidak ditemukan.']);
        }

        if ($request->hasFile('preview_objek')) {
            $file = $request->file('preview_objek');
            $uploadedFile = $this->storage->getBucket()->upload(
                fopen($file->getRealPath(), 'r'),
                ['name' => 'previewObjek3d/' . $file->getClientOriginalName()]
            );

            $previewObjekUrl = $uploadedFile->info()['mediaLink'];
        } else {
            return back()->withErrors(['preview_objek' => 'File tidak ditemukan.']);
        }

        Log::info('Nama file:', [$file->getClientOriginalName()]);
        Log::info('Mime type:', [$file->getMimeType()]);
        Log::info('Extension:', [$file->getClientOriginalExtension()]);

        if ($role === 'ROLE_MITRA') {
            $createdBy = 'MITRA';
        } elseif ($role === 'ROLE_ADMIN') {
            $createdBy = 'ADMIN';
        } else {
            $createdBy = 'UNKNOWN'; // fallback optional
        }

        $postData = [
            'idObjek' => $uniqueIdProduk,
            'namaObjek' => $request->nama_objek,
            'deskripsiObjek' => $request->deskripsi_objek,
            'glbUrl' => $glbUrl,
            'previewObjek' => $previewObjekUrl,
            'createdAt' => now()->toDateTimeString(),
            'updatedAt' => now()->toDateTimeString(),
            'createdBy' => $createdBy
        ];

        //dd($postData);
        $this->database->getReference($this->refTableName . '/' . $uniqueIdProduk)->set($postData);

        return redirect()->route('objek.3d')->with('success', 'Objek berhasil disimpan.');
    }

    function update(Request $request)
    {
        $role = session('session.idRole');

        $request->validate([
            'nama_objek' => 'required|string|max:255',
            'deskripsi_objek' => 'nullable|string',
            'file_objek' => 'nullable|file|mimetypes:model/gltf-binary|max:10240',
            'preview_objek' => 'nullable|file|max:10240',

            'key' => 'required|string'
        ]);

        $key = $request->input('key');

        // Ambil data lama dulu dari Firebase
        $existingData = $this->database->getReference($this->refTableName . '/' . $key)->getValue();

        if (!$existingData) {
            return back()->withErrors(['error' => 'Data tidak ditemukan.']);
        }

        // Jika ada file objek baru, replace
        if ($request->hasFile('file_objek')) {
            $file = $request->file('file_objek');
            $uploadedFile = $this->storage->getBucket()->upload(
                fopen($file->getRealPath(), 'r'),
                ['name' => 'objek3d/' . $file->getClientOriginalName()]
            );

            $glbUrl = $uploadedFile->info()['mediaLink'];
        } else {
            $glbUrl = $existingData['glbUrl'] ?? null;
        }

        // Jika ada preview baru, replace
        if ($request->hasFile('preview_objek')) {
            $file = $request->file('preview_objek');
            $uploadedFile = $this->storage->getBucket()->upload(
                fopen($file->getRealPath(), 'r'),
                ['name' => 'previewObjek3d/' . $file->getClientOriginalName()]
            );

            $previewObjekUrl = $uploadedFile->info()['mediaLink'];
        } else {
            $previewObjekUrl = $existingData['previewObjek'] ?? null;
        }

        if ($role === 'ROLE_MITRA') {
            $updatedBy = 'MITRA';
        } elseif ($role === 'ROLE_ADMIN') {
            $updatedBy = 'ADMIN';
        } else {
            $updatedBy = 'UNKNOWN';
        }

        $updateData = [
            'namaObjek' => $request->nama_objek,
            'deskripsiObjek' => $request->deskripsi_objek,
            'glbUrl' => $glbUrl,
            'previewObjek' => $previewObjekUrl,
            'updatedAt' => now()->toDateTimeString(),
            'createdBy' => $existingData['createdBy'] ?? $updatedBy
        ];

        //dd($updateData);

        $this->database->getReference($this->refTableName . '/' . $key)->update($updateData);

        return redirect()->route('objek.3d')->with('success', 'Objek berhasil diperbarui.');
    }

    public function deleteModel($id) {
        $key = $id;
        $deleteModel = $this->database->getReference($this->refTableName . '/' . $key)->remove();
        if ($deleteModel) {
            return redirect()->back()->with('success', 'Model berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Model gagal dihapus');
        }
    }
}
