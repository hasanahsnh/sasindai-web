<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    function index() {
        /*$dataPesanans = $this->database->getReference($this->refTableName)->getValue();
        return response()->json([
            'success' => true,
            'data' => $dataPesanans
        ]);*/

        return view('mitra.pages.pengiriman');
    }
}
