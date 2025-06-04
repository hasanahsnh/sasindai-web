<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Firebase\UserController;
use App\Http\Controllers\Firebase\KatalogController;
use App\Http\Controllers\Firebase\PasarController;
use App\Http\Controllers\Firebase\BeritaController;
use Kreait\Firebase\Contract\Database;

class IndexController extends Controller
{
    protected $userController;
    protected $katalogController;
    protected $pasarController;
    protected $beritaController;
    protected $database;
    protected $refTableName;
    protected $auth;

    public function __construct(
        UserController $userController, 
        KatalogController $katalogController,
        PasarController $pasarController,
        BeritaController $beritaController,
        
        Database $database) {

        $this->userController = $userController;
        $this->katalogController = $katalogController;
        $this->pasarController = $pasarController;
        $this->beritaController = $beritaController;
        $this->database = $database;
        $this->refTableName = 'users';
    }

    function dashboard(Request $request) {
        // Ambil status session setelah login
        $uid = session('session.uid');
        $idRole = session('session.idRole');

        // Ambil data user
        $adminRef = $this->database->getReference($this->refTableName . '/' . $uid);
        $adminSnapshot = $adminRef->getValue();
        $adminData = $adminSnapshot ?? [];

        // Ambil data role
        $roleData = $this->database->getReference('roles/' . $idRole)->getValue();
        $adminRole = $roleData['role'] ?? 'Tidak diketahui';

        $totalActiveUsers = $this->userController->getActiverUser();
        $totalKatalogs = $this->katalogController->countMotif();
        $totalPasars = $this->pasarController->countPasar();
        $totalArtikels = $this->beritaController->countArtikel();
        
        return view('admin.pages.index', compact('totalActiveUsers', 'totalKatalogs', 'totalPasars', 'totalArtikels', 'uid', 'adminData', 'adminRole'));
    }

    
}
