<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

class KeuanganMitraController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $auth;
    //

    // !! Cek lagi perlu atau tidak
    public function __construct(Database $database) {
        $this->database = $database;
        $this->refTableName = "keuanganMitra";
    }

    function dataKeuangan() {
        return view('mitra.pages.data-keuangan');
    }
}
