<?php

namespace App\Http\Controllers;

use App\Models\User; // Import the User model
use App\Models\Role; // Import the Role model
use Illuminate\Http\Request;

class UserListController extends Controller
{
        public function index()
    {
        $users = \DB::table('users')
            ->leftJoin('roles', 'users.designation', '=', 'roles.id') // Join on designation
            ->select('users.*', 'roles.designation as role_name') // Select role name
            ->get();

        $title = 'User List';

        return view('users-list', compact('title', 'users'));
    }
}
