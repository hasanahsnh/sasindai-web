<?php

namespace App\Http\Controllers\Firebase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Exception\FirebaseException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KatalogController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $storage;
    public function __construct(Database $database, Storage $storage) {
        $this->database = $database;
        $this->refTableName = 'katalog';
        $this->storage = $storage;
    }

    function index() {
        $katalogs = $this->database->getReference($this->refTableName)->getValue();
        return view('admin.pages.s-katalog', compact('katalogs'));

        if ($katalogs === null) {
            $katalogs = [];
        }
        dd($katalogs);
    }

    function countMotif() {
        $totalKatalogs = $this->database->getReference($this->refTableName)->getValue();
        if (empty($totalKatalogs)) {
            return 0;
        }
        $filteredKatalogs = array_filter($totalKatalogs, function ($item) {
            return !is_null($item) && !empty($item);
        });

        return count($filteredKatalogs);
    }

    function store(Request $request) {

        $request->validate([
            'nama_motif' => 'required|string|max:255',
            'filosofi_motif' => 'required|string|max:65535',
            'sumber_filosofi' => 'required|string|max:255',
            'sumber_gambar' => 'required|string|max:255',
        ]);
    
        if ($request->hasFile('gambar_motif')) {
            $file = $request->file('gambar_motif');
            $fileName = 'motif/' . $file->getClientOriginalName();
            $this->storage->getBucket()->upload(
                fopen($file->getRealPath(), 'r'),
                ['name' => $fileName]
            );

            $gambarKatalogUrl = 'https://storage.googleapis.com/sascode-aa3b7.appspot.com/' . $fileName;
        } else {
            $gambarKatalogUrl = null;
        }
    
        $existingItems = $this->database->getReference($this->refTableName)->getValue();
        $newId = 1;
        if ($existingItems !== null) {
            $existingIds = array_keys($existingItems);
            $newId = max($existingIds) + 1;
        }
    
        $postData = [
            'motif' => $request->nama_motif,
            'filosofi' => $request->filosofi_motif,
            'sumberFilosofi' => $request->sumber_filosofi,
            'sumberGambar' => $request->sumber_gambar,
            'gambarUrl' => $gambarKatalogUrl,
        ];
        //dd($postData);
    
        $this->database->getReference("{$this->refTableName}/{$newId}")->set($postData);
    
        return redirect()->route('katalog')->with('success', 'Motif berhasil disimpan');
    }

    function edit($id) {
        $key = $id;
        $editData = $this->database->getReference($this->refTableName)->getChild($key)->getValue();
        if ($editData) {
            return view('admin.pages.katalog', compact('editData', 'key'));
        } else {
            return redirect()->route('katalog')->with('status', 'ID Motif tidak ditemukan');
        }
    }

    function update(Request $request, $id) {
        $key = $id;
        
        $request->validate([
            'nama_motif' => 'required|string|max:255',
            'filosofi_motif' => 'required|string|max:1000',
            'sumber_filosofi' => 'required|string|max:255',
            'sumber_gambar' => 'required|string|max:255',
        ]);

        $updateData = [
            'motif' => $request->nama_motif,
            'filosofi' => $request->filosofi_motif,
            'sumberFilosofi' => $request->sumber_filosofi,
            'sumberGambar' => $request->sumber_gambar,
        ];
    
        if ($request->hasFile('gambar_motif')) {
            $file = $request->file('gambar_motif');
    
            try {
                $fileName = 'motif/' . $file->getClientOriginalName();
                $this->storage->getBucket()->upload(
                    fopen($file->getRealPath(), 'r'),
                    ['name' => $fileName]
                );
    
                $fotoBeritaUrl = 'https://storage.googleapis.com/sascode-aa3b7.appspot.com/' . $fileName;
                $updateData['gambarUrl'] = $fotoBeritaUrl;
            } catch (FirebaseException $e) {
                return redirect()->route('katalog')->with('error', 'Gagal mengunggah gambar: ' . $e->getMessage());
            }
        }
    
        $resUpdate = $this->database->getReference($this->refTableName.'/'.$key)->update($updateData);
                
        if ($resUpdate) {
            return redirect()->route('katalog')->with('success', 'Motif berhasil disimpan');
        } else {
            return redirect()->route('katalog')->with('error', 'Motif gagal disimpan');
        }
    }

    function destroy($id) {
        $key = $id;
        $hapusData = $this->database->getReference($this->refTableName . '/' . $key)->remove();
        if ($hapusData) {
            return redirect()->route('katalog')->with('success', 'Motif berhasil dihapus');
        } else {
            return redirect()->route('katalog')->with('error', 'Motif gagal dihapus');
        }
    }

    function downloadDataKatalog()
    {
        $items = $this->database->getReference($this->refTableName)->getValue();

        if ($items === null || empty($items)) {
            return redirect()->back()->with('error', 'Tidak ada data yang tersedia untuk diunduh.');
        }

        $csvData = "ID, Motif, Filosofi, Sumber Arti, Sumber Gambar\n";
        $images = [];

        foreach ($items as $id => $item) {
            if (is_array($item) && isset($item['motif'], $item['filosofi'], $item['sumberFilosofi'], $item['sumberGambar'])) {
                $csvRow = [
                    $id,
                    $item['motif'],
                    $item['filosofi'],
                    $item['sumberFilosofi'],
                    $item['sumberGambar'],
                ];
    
                $escapedRow = array_map(function ($value) {
                    if (preg_match('/[,"\n\r]/', $value)) {
                        $value = '"' . str_replace('"', '""', $value) . '"';
                    }
                    return $value;
                }, $csvRow);
    
                $csvData .= implode(",", $escapedRow) . "\n";

                $namaMotif = $item['motif'];

                if (!empty($item['gambarUrl'])) {
                    $imageContentMotif = @file_get_contents($item['gambarUrl']);
                    if ($imageContentMotif !== false) {
                        $images["foto motif/{$namaMotif}.png"] = $imageContentMotif;
                    }
                }

                if (!empty($item['qrCodeUrl'])) {
                    $imageContentQR = @file_get_contents($item['qrCodeUrl']);
                    if ($imageContentQR !== false) {
                        $images["QR Code/{$namaMotif}.png"] = $imageContentQR;
                    }
                }
            }
        }

        $zip = new \ZipArchive();
        $zipFileName = storage_path('app/public/data-motif.zip');

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $zip->addFromString('data-motif.csv', $csvData);

            foreach ($images as $fileName => $content) {
                $zip->addFromString($fileName, $content);
            }

            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file zip.');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }


    function show($id) {
        $key = $id;
        $motifData = $this->database->getReference("{$this->refTableName}/{$id}")->getValue();
        if (!$motifData) {
            return redirect()->route('katalog')->with('error', 'Data Motif tidak ditemukan');
        }
        return view('pengunjung.pages.motif-show', compact('motifData'));
    }
}
