<?php

namespace App\Http\Controllers;  

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\BouncerFacade as Bouncer;


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
            // After authentication, check roles with Bouncer
            $user = Auth::user();
            
            if (Bouncer::is($user)->an('Admin')) {
                // If the user is an Admin, redirect to the dashboard
                return redirect()->route('dashboard');
            } else {
                // If not an Admin, redirect or show a message
                return redirect()->route('home')->with('message', 'You are not an Admin');
            }
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
