<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $usersQuery = User::with('roles');

        if ($search) {
            $usersQuery->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $usersQuery->paginate(20)->withQueryString();

        $roles = \Silber\Bouncer\Database\Role::orderBy('title')->get();

        $title = 'Role Assignment';

        return view('users-grid.users-grid', compact('title', 'users', 'roles'));
    }

}
