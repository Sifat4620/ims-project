<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model
use App\Models\Role; // Import the Role model to fetch designations
use Illuminate\Support\Facades\Storage; // To handle file storage
use Illuminate\Support\Facades\Hash; // For hashing the password

class UserCreationController extends Controller
{
    public function index()
    {
        $title = 'Create User';
        
        
        $roles = Role::all();

        return view('add-user', compact('title', 'roles'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'full_name' => 'required|string|max:255',
            'user_id' => 'required|string|max:255|unique:users,user_id',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100', // validation for the designation
            'description' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:6', // Removed confirmation
        ]);

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            // Use 'full_name' as the file name
            $imageName = $request->full_name . '.' . $request->file('profile_image')->getClientOriginalExtension();

            // Save the file to the 'public/assets/profile_image' folder
            $imagePath = $request->file('profile_image')->storeAs('public/assets/profile_image', $imageName);
        } else {
            // Default image if no file is uploaded
            $imagePath = 'assets/profile_image/default_avatar.png'; // Default image path
        }

        // Create new user record
        $user = new User([
            'full_name' => $request->input('full_name'),
            'user_id' => $request->input('user_id'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'department' => $request->input('department'),
            'designation' => $request->input('designation'),
            'description' => $request->input('description'),
            'profile_image' => $imagePath,
            'password' => Hash::make($request->input('password')), // Hash the password before saving
        ]);

        $user->save();

        return redirect()->route('user.create')->with('success', 'User created successfully!');
    }
}
