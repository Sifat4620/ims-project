<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\View\View;
use DB;

class ViewProfileController extends Controller
{
    /**
     * Display the profile view for the specified user.
     *
     * @param  int  $userId
     * @return \Illuminate\View\View
     */
    public function index(int $userId): View
    {
        // Retrieve the user with their roles or fail with 404
        $user = User::with('roles')->findOrFail($userId);
        // dd($user->designation);

        // Get all roles for dropdown or designation mapping
        $roles = DB::select('SELECT * FROM roles');

        // Get user's assigned roles
        $userRoles = $user->roles;

        // Get the user's primary role (first one, if any)
        $primaryRole = $userRoles->first();

        // Set page title
        $title = 'View Profile';

        // dd($roles);

        // Return the view with all necessary data
        return view('view-profile', compact(
            'title',
            'user',
            'roles',
            'userRoles',
            'primaryRole'
        ));
    }
}
