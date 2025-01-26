<?php

namespace App\Http\Controllers;  

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show sign-in page
    public function showSignInPage()
    {
        return view('sign-in');
    }

    // Handle the sign-in form submission
    public function signIn(Request $request)
    {
        // Validate the input data
        $request->validate([
            'user_id' => 'required|exists:users,user_id', // Ensure user_id exists in the 'users' table
            'password' => 'required|min:6', // Ensure password is provided and has at least 6 characters
        ]);
    
        // Get the credentials from the request
        $credentials = $request->only('user_id', 'password');
    
        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect to the intended page or dashboard
            return redirect()->intended('/dashboard');
        }
    
        // Authentication failed, redirect back with error message
        return back()->withErrors(['user_id' => 'Invalid credentials']);
    }

    // Handle the logout functionality
    public function logout()
    {
        Auth::logout();  // Log the user out
        return redirect()->route('auth.signin.page');  // Redirect to the sign-in page
    }
}
