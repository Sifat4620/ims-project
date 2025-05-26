<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Role; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Database\Eloquent\Model;
use DB;


class UserCreationController extends Controller
{
    // Show the create user form with roles data
    public function index()
    {
        $title = 'Create User';
    
        // Fetch all roles for designation dropdown
        $roles = DB::select('SELECT * FROM roles');
    
        // Debugging the roles
        // dd($roles);  // This will dump the roles before returning the view
    
        // Return the 'add-user' view with title and roles
        return view('add-user.add-user', compact('title', 'roles'));
    }
    

    // Store the new user in the database
    public function store(Request $request)
    {
        // Validate incoming request data with custom error messages
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'user_id' => 'required|string|max:255|unique:users,user_id',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8', 
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
    
        // Create the user and store the data
        $user = User::create([
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
    
        // Query the role by ID (since title corresponds to role ID)
        $role = DB::table('roles')->where('id', $request->title)->first();  // Query by role ID
    
        // If the role exists, assign it to the user
        if ($role) {
            $user->assignRole($role->name);  // Use the role name to assign it
        } else {
            dd('Role not found');  // Debugging if role is not found
        }
    
        $assignedRole = DB::table('assigned_roles')
        ->where('entity_id', $user->id)   // Use entity_id instead of user_id
        ->first();
    
        // dd($assignedRole);  
    
        // Return success message
        return redirect()->route('user.create')->with('success', 'User created successfully.');
    }
    

}
