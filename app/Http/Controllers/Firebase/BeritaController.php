<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BeritaController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $storage;
    public function __construct(Database $database, Storage $storage) {
        $this->database = $database;
        $this->refTableName = 'berita';
        $this->storage = $storage;
    }
    function index() {
        $beritas = $this->database->getReference($this->refTableName)->getValue();
        return view('admin.pages.berita', compact('beritas'));

        if ($beritas === null) {
            $beritas = [];
        }
        dd($beritas);
    }

    function countArtikel() {
        $totalArtikels = $this->database->getReference($this->refTableName)->getValue();
        if (empty($totalArtikels)) {
            return 0;
        }
        $filteredArtikels = array_filter($totalArtikels, function ($item) {
            return !is_null($item) && !empty($item);
        });

        return count($filteredArtikels);
    }

    function store(Request $request) {

        $request->validate([
            'judul_artikel' => 'required|string|max:255',
            'jurnalis' => 'required|string|max:255',
            'konten_berita' => 'required|string|max:1000',
            'tanggal_terbit' => 'required|date',
        ]);
    
        if ($request->hasFile('foto_berita')) {
            $file = $request->file('foto_berita');
            $uploadedFile = $this->storage->getBucket()->upload(
                fopen($file->getRealPath(), 'r'),
                ['name' => 'berita/' . $file->getClientOriginalName()]
            );
            $fotoBeritaUrl = $uploadedFile->info()['mediaLink'];
        } else {
            $fotoBeritaUrl = null;
        }
    
        $existingItems = $this->database->getReference($this->refTableName)->getValue();
        $newId = 1;
        if ($existingItems !== null) {
            $existingIds = array_keys($existingItems);
            $newId = max($existingIds) + 1;
        }

        $tanggalTerbitFormatted = Carbon::parse($request->tanggal_terbit)->translatedFormat('d F Y');
    
        $postData = [
            'judulArtikel' => $request->judul_artikel,
            'jurnalis' => $request->jurnalis,
            'kontenBerita' => $request->konten_berita,
            'tanggalTerbit' =>$tanggalTerbitFormatted,
            'fotoBeritaUrl' => $fotoBeritaUrl,
        ];

        //dd($postData);
        $this->database->getReference("{$this->refTableName}/{$newId}")->set($postData);
    
        return redirect()->route('berita')->with('success', 'Artikel berhasil disimpan');
    }

    function edit($id) {
        $key = $id;
        $editData = $this->database->getReference($this->refTableName)->getChild($key)->getValue();
        if ($editData) {
            return view('admin.pages.berita', compact('editData', 'key'));
        } else {
            return redirect()->route('berita')->with('error', 'ID Berita tidak ditemukan');
        }
    }

    function update(Request $request, $id) {
        $key = $id;

        $request->validate([
            'judul_artikel' => 'required|string|max:255',
            'jurnalis' => 'required|string|max:255',
            'konten_berita' => 'required|string|max:1000',
            'tanggal_terbit' => 'date',
        ]);

        $tanggalTerbitFormatted = Carbon::parse($request->tanggal_terbit)->translatedFormat('d F Y');

        $updateData = [
            'judulArtikel' => $request->judul_artikel,
            'jurnalis' => $request->jurnalis,
            'kontenBerita' => $request->konten_berita,
            'tanggalTerbit' => $tanggalTerbitFormatted
        ];

        if($request->hasFile('foto_berita')) {
            $file = $request->file('foto_berita');

            try {
                $uploadedFile = $this->storage->getBucket()->upload(
                    file_get_contents($file->getRealPath()),
                    ['name' => 'berita/' . $file->getClientOriginalName()]
                );

                $fotoBeritaUrl = $uploadedFile->info()['mediaLink'];
                $updateData['fotoBeritaUrl'] = $fotoBeritaUrl;
            } catch (FirebaseException $e) {
                return redirect()->route('berita')->with('error', 'Gagal mengunggah gambar: ' . $e->getMessage());
            }
        } 

        $resUpdate = $this->database->getReference($this->refTableName.'/'.$key)->update($updateData);
        if ($resUpdate) {
            return redirect()->route('berita')->with('success', 'Artikel berhasil disimpan');
        } else {
            return redirect()->route('berita')->with('error', 'Katalog gagal disimpan');
        }
    }

    function destroy($id) {
        $key = $id;
        $hapusData = $this->database->getReference($this->refTableName.'/'.$key)->remove();
        if ($hapusData) {
            return redirect()->route('berita')->with('success', 'Berita berhasil dihapus');
        } else {
            return redirect()->route('berita')->with('error', 'Berita gagal dihapus');
        }
    }

    function downloadDataBerita() {

        $items = $this->database->getReference($this->refTableName)->getValue();

        if ($items === null || empty($items)) {
            return redirect()->back()->with('error', 'Tidak ada data yang tersedia untuk diunduh.');
        }

        $csvData = "ID, Judul Artikel, Jurnalis, Konten Berita, Tanggal Terbit\n";
        $images = [];

        foreach ($items as $id => $item) {
            if (is_array($item) && isset($item['judulArtikel'], $item['jurnalis'], $item['kontenBerita'], $item['tanggalTerbit'])) {
                $csvRow = [
                    $id,
                    $item['judulArtikel'],
                    $item['jurnalis'],
                    $item['kontenBerita'],
                    $item['tanggalTerbit'],
                ];

                $escapedRow = array_map(function ($value) {
                    if (preg_match('/[,"\n\r]/', $value)) {
                        $value = '"' . str_replace('"', '""', $value) . '"';
                    }
                    return $value;
                }, $csvRow);
    
                $csvData .= implode(",", $escapedRow) . "\n";

                $judulArtikel = $item['judulArtikel'];

                if (!empty($item['fotoBeritaUrl'])) {
                    $imageContent = @file_get_contents($item['fotoBeritaUrl']);
                    if ($imageContent !== false) {
                        $images["foto artikel/{$judulArtikel}.png"] = $imageContent;
                    }
                }
            }
        }

        $zip = new \ZipArchive();
        $zipFileName = storage_path('app/public/data-artikel.zip');

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $zip->addFromString('data-artikel.csv', $csvData);

            foreach ($images as $fileName => $content) {
                $zip->addFromString($fileName, $content);
            }

            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file zip.');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }

}
