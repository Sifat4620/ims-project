<?php

namespace App\Http\Controllers;

use App\Models\User;  // Import the User model
use App\Models\Role;  // Import the Role model
use Illuminate\Http\Request;

class ViewProfileController extends Controller
{
    // Show the profile for the given user
    public function index($userId)
    {
        // Retrieve the user from the database by ID, or fail with a 404 error
        $user = User::findOrFail($userId); // This will automatically throw a 404 error if user is not found
        
        // Retrieve all roles from the roles table
        $roles = Role::all(); // You may want to adjust this to only retrieve relevant roles, or role assignments for this user
        
        // Set a title for the view
        $title = 'View Profile';
        
        // Return the 'view-profile' view, passing user data, roles, and title
        return view('view-profile', compact('title', 'user', 'roles'));
    }
}
