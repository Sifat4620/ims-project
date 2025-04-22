<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Show sign-in page
    public function showSignInPage()
    {
        return view('sign-in');
    }

    // Handle the sign-in form submission
    // In AuthController
    public function signIn(Request $request)
    {
        // Validate the input
        $credentials = $request->only('user_id', 'password');
        
        // Attempt login with provided credentials
        if (Auth::attempt($credentials)) {
            // The user is authenticated, retrieve user details
            $user = Auth::user(); // Get the authenticated user
    
            // Access user information
            $userName = $user->name;
            $userEmail = $user->email;
            $userRoles = $user->roles; // Assuming you are using Bouncer for roles
    
            // You can also log this information if needed (useful for debugging)
            Log::info('User Logged In:', ['name' => $userName, 'email' => $userEmail, 'roles' => $userRoles]);
    
            // Redirect to the dashboard, passing along the user data if needed
            return redirect()->route('dashboard');
        }
        
        // If authentication fails, return with an error
        return back()->withErrors(['user_id' => 'Invalid credentials provided.']);
    }
    


    // Handle the logout functionality
    public function logout(Request $request)
    {
        // Invalidate the session and regenerate the token to protect against session fixation attacks
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log the user out
        Auth::logout();

        // Optionally, log the logout event
        Log::info('User logged out', ['user_id' => Auth::id()]);

        // Redirect the user to the sign-in page
        return redirect()->route('auth.signin.page');
    }
}
