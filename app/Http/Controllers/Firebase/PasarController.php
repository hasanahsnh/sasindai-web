<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;

class PasarController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $storage;
    public function __construct(Database $database, Storage $storage) {
        $this->database = $database;
        $this->refTableName = 'mitras';
        $this->storage = $storage;
    }

    function index() {
        $pasars = $this->database->getReference($this->refTableName)->getValue();
        return view('admin.pages.ka-pasar', compact('pasars'));
    }

    function countPasar() {
        $totalPasar = $this->database->getReference($this->refTableName)->getValue();
        if (empty($totalPasar)) {
            return 0;
        }
        $filteredPasars = array_filter($totalPasar, function ($item) {
            return !is_null($item) && !empty($item);
        });

        return count($filteredPasars);
    }

    function perbaruiStatus(Request $request) {
        $request->validate([
            'key' => 'required|string',
            'statusVerifikasi' => 'required|in:pending,rejected,accepted',
        ]);
    
        $this->database
            ->getReference('mitras/' . $request->key . '/statusVerifikasiToko')
            ->set($request->statusVerifikasi);

        return back()->with('success', 'Status verifikasi toko berhasil diperbarui.');
    }

    function downloadDataPasar() {

        $items = $this->database->getReference($this->refTableName)->getValue();

        if ($items === null || empty($items)) {
            return redirect()->back()->with('error', 'Tidak ada data yang tersedia untuk diunduh.');
        }

        $csvData = "ID, Nama Toko, Alamat, No. Telp, Deskripsi, Latitude, Longitude\n";
        $images = [];

        foreach ($items as $id => $item) {
            if (is_array($item) && isset($item['namaToko'], $item['alamatToko'], $item['noTelpToko'], $item['deskripsiToko'], $item['latitude'], $item['longitude'])) {
                $csvRow = [
                    $id,
                    $item['namaToko'],
                    $item['alamatToko'],
                    $item['noTelpToko'],
                    $item['deskripsiToko'],
                    $item['latitude'],
                    $item['longitude']
                ];

                $escapedRow = array_map(function ($value) {
                    if (preg_match('/[,"\n\r]/', $value)) {
                        $value = '"' . str_replace('"', '""', $value) . '"';
                    }
                    return $value;
                }, $csvRow);

                $csvData .= implode(",", $escapedRow) . "\n";

                $namaToko = $item['namaToko'];

                if (!empty($item['fotoTokoUrl'])) {
                    if (is_array($item['fotoTokoUrl'])) {
                        foreach ($item['fotoTokoUrl'] as $index => $url) {
                            if (is_string($url)) {
                                $imageContent = @file_get_contents($url);
                                if ($imageContent !== false) {
                                    $images["foto toko/{$namaToko}_{$index}.png"] = $imageContent;
                                }
                            }
                        }
                    } elseif (is_string($item['fotoTokoUrl'])) {
                        $imageContent = @file_get_contents($item['fotoTokoUrl']);
                        if ($imageContent !== false) {
                            $images["foto toko/{$namaToko}.png"] = $imageContent;
                        }
                    }
                }
            }
        }

        $zip = new \ZipArchive();
        $zipFileName = storage_path('app/public/data-pasar.zip');

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $zip->addFromString('data-pasar.csv', $csvData);

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
