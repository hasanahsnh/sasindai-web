<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;

class LayananDanFiturAplikasiController extends Controller
{

    protected $database;
    protected $refTableName;
    public function __construct(Database $database) {
        $this->database = $database;
        $this->refTableName = 'layanan';
    }

    public function index()
    {
        $layanan = $this->database->getReference($this->refTableName)->getValue();
        return view('admin.pages.layanan-fitur', compact('layanan'));
    }

    public function tambahLayanan(Request $request)
    {
        $request->validate([
            'fitur' => 'required|string|max:255',
            'status_fitur' => 'required|in:aktif,non-aktif',
        ]);

        $layananId = (string) Str::uuid();

        $fiturBaru = [
            'id' => $layananId,
            'fitur' => $request->fitur,
            'statusFitur' => $request->status_fitur,
        ];

        //dd($fiturBaru);

        $this->database
            ->getReference($this->refTableName . '/' . $layananId)
            ->set($fiturBaru);

        return redirect()->back()->with('success', 'Fitur berhasil ditambahkan.');
    }

    public function perbaruiStatusLayanan(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'status_fitur' => 'required|in:aktif,non-aktif',
        ]);

        $this->database
            ->getReference($this->refTableName . '/' . $request->key)
            ->update([
                'statusFitur' => $request->status_fitur,
            ]);

        return redirect()->back()->with('success', 'Fitur berhasil diperbarui.');
    }
}
