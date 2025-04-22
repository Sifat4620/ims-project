<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model
use App\Models\Role; // Import the Role model to fetch designations
use Illuminate\Support\Facades\Storage; // To handle file storage
use Illuminate\Support\Facades\Hash; // For hashing the password

class UserCreationController extends Controller
{
    // Show the create user form with roles data
    public function index()
    {
        $title = 'Create User';
        
        // Fetch all roles for designation dropdown
        $roles = Role::all();

        // Return the 'add-user' view with title and roles
        return view('add-user', compact('title', 'roles'));
    }

    // Store the new user in the database
    public function store(Request $request)
    {
        // Validate incoming request data with custom error messages
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'user_id' => 'required|string|max:255|unique:users,user_id',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8', // Removed confirmed rule
            'phone' => 'nullable|string|max:15',
            'department' => 'required|string|max:255',
            'title' => 'required|exists:roles,id', 
            'description' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'full_name.required' => 'The full name is required.',
            'user_id.required' => 'The employee ID is required.',
            'email.required' => 'The email address is required.',
            'password.required' => 'A password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'phone.max' => 'The phone number must not exceed 15 characters.',
            'department.required' => 'Please select a department.',
            'title.required' => 'Please select a designation.',
            'profile_image.image' => 'The uploaded file must be an image (jpeg, png, jpg).',
            'profile_image.max' => 'The image must not exceed 2MB.',
        ]);
        
        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Store the user data
        User::create([
            'full_name' => $request->full_name,
            'user_id' => $request->user_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'designation' => $request->title, 
            'description' => $request->description,
            'password' => Hash::make($request->password),
            'image' => $imagePath,
        ]);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

}
