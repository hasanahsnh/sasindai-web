<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;

class RolesController extends Controller
{
    //
    protected $database;
    protected $refTableName;

    public function __construct(Database $database) {
        $this->database = $database;
        $this->refTableName = 'roles';
    }

    function index() {
        $roles = $this->database->getReference($this->refTableName)->getValue();
        return view('admin.pages.roles', compact('roles'));
    
    }

    function store(Request $request) {
        // Post data
        $request->validate([
            'id_role' => 'required|string|max:20',
            'role' => 'required|string|max:50'
        ]);

        $postData = [
            'idRole' => $request->id_role,
            'role' => $request->role
        ];

       // dd($postData);

        try {
            $this->database->getReference("{$this->refTableName}/{$postData['idRole']}")->set($postData);
            return redirect()->route('roles')->with('success', 'Data role berhasil ditambah');
        } catch(FirebaseException $e) {
            return redirect()->route('roles')->with('error', 'Gagal menambah data role' . $e->getMessage());
        }
    }

    function edit() {
        // Get data

    }

    function update() {
        // Put data
    }

    function delete() {
        // Delete data
    }

    
}
