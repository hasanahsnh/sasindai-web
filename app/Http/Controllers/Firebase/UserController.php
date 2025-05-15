<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

class UserController extends Controller
{
    protected $database;
    protected $refTableName;
    protected $storage;
    protected $auth;

    public function __construct(Auth $auth, Database $database) {
        $this->database = $database;
        $this->refTableName = 'users';
        $this->auth = $auth;
    }

    function getActiverUser() {
        $users = $this->database->getReference($this->refTableName)->getValue();
        if (empty($users)) {
            return 0;
        }
        $activeUsers = array_filter($users, function ($users) {
            return isset($users['statusLogin']) && $users['statusLogin'] === 'active';
        });

        return count($activeUsers);
    }

    function index() {
        return view('pengunjung.pages.index');
    }

    function usersManagement() {
        $users = $this->database->getReference($this->refTableName)->getValue();
        
        return view('admin.pages.users', compact('users'));
    }
}
