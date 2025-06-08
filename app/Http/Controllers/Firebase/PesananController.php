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

class PesananController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $auth;
    protected $storage;

    public function __construct(Database $database, Auth $auth, Storage $storage) {
        $this->database = $database;
        $this->auth = $auth;
        $this->refTableName = 'orders';
        $this->storage = $storage;
    }

    public function index() {
        /*$dataPesanans = $this->database->getReference($this->refTableName)->getValue();
        return response()->json([
            'success' => true,
            'data' => $dataPesanans
        ]);*/

        return view('mitra.pages.pesanan');
    }

    public function callbackPesananMasuk() {
        
    }
}
