<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    public function index(Request $request)
    {
        // Get the number of records per page (default is 10)
        $perPage = $request->get('perPage', 10);

        // Get the search query
        $search = $request->get('search', '');

        // Query users, with optional search and pagination, assuming all users are active
        $users = \DB::table('users')
            ->leftJoin('roles', 'users.designation', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->where(function($query) use ($search) {
                // Search in multiple fields (e.g., name and email)
                $query->where('users.full_name', 'like', '%' . $search . '%')
                      ->orWhere('users.email', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);

        // Pass data to the view
        $title = 'User List';

        return view('users-list.users-list', compact('title', 'users', 'perPage', 'search'));
    }
}
