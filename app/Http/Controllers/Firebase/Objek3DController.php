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

            if ($role === 'ROLE_ADMIN') {
                return view('admin.pages.input-objek-3d', compact('objek3d'));
            } elseif ($role === 'ROLE_MITRA') {
                return view('mitra.pages.input-objek-3d', compact('objek3d'));
            }

            abort(403);
    }

    function store(Request $request) {

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

        $updatedAt = Carbon::now()->format('Y-m-d H:i:s');

        $postData = [
            'idObjek' => $uniqueIdProduk,
            'namaObjek' => $request->nama_objek,
            'deskripsiObjek' => $request->deskripsi,
            'glbUrl' => $glbUrl,
            'previewObjek' => $previewObjekUrl,
            'createdAt' => now()->toDateTimeString(),
        ];

        //dd($postData);
        $this->database->getReference($this->refTableName . '/' . $uniqueIdProduk)->set($postData);

        return redirect()->route('objek.3d')->with('success', 'Objek berhasil disimpan.');
    }
}
