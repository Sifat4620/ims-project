<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleAssignmentController extends Controller
{
    // Display all users with their role(s)
    public function index()
    {
        // Retrieve all users along with their associated roles
        $users = User::with('role')->get(); // Assuming each user has one role

        // Get all available roles for assignment
        $roles = Role::all(); // You can filter roles based on your requirements

        // Set a static title
        $title = 'Role Assignment';

        // Return the view with users, roles, and title
        return view('users-grid', compact('title', 'users', 'roles'));
    }

  
}
