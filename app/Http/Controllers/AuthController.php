<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function __construct()
    {
        // Only guests (unauthenticated users) can access login and register pages
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();  // Redirect to the correct dashboard if already logged in
        }
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => "required|email",
            'password' => "required|min:6",
        ]);

        $credentials = $request->only("email", "password");
        $remember = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            // Redirect the user to the correct dashboard based on their role
            return $this->redirectToDashboard();
        }

        return redirect(route('login'))->with('error', 'Invalid email or password. Please try again.');
    }

    // Redirect based on role
    protected function redirectToDashboard()
    {
        $user = Auth::user();  // Get the authenticated user

        // Check the user's role and redirect accordingly
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome Admin');
        } elseif ($user->role === 'teacher') {
            return redirect()->intended(route('teacher.dashboard'))->with('success', 'Welcome Teacher');
        }

        // Default redirect if no specific role matches
        return redirect('/')->with('error', 'You do not have access to the system.');
    }

    public function register()
    {
        return view("auth.register");
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role ?? 'teacher';  // Assign a default role

            if ($user->save()) {
                return redirect(route('login'))->with("success", "User created successfully. Please login.");
            }
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->with('error', 'The email has already been taken.');
            }
            return back()->with('error', 'There was an error creating the account. Please try again.');
        }
    }



    public function forgotPassword(Request $request)
    {
        return view('auth.forgot-password');
    }

    public function postForgotPassword(Request $request)
    {
        // Validate that an email was provided
        $request->validate([
            'email' => 'required|email'
        ]);

        // Attempt to find the user by email
        $userEmail = User::getEmailSingle($request->email);

        // Debugging to check the result
        if ($userEmail === null) {
            return redirect()->back()->with('error', 'Email not found in the system');
        }

        if (isset($userEmail->email)) {
            // Generate a token and send the email
            $userEmail->remember_token = Str::random(30);
            $userEmail->save();

            // Send the forgot password email
            Mail::to($userEmail->email)->send(new ForgotPasswordMail($userEmail));

            return redirect()->back()->with('success', 'Please check your email and reset your password');
        }

        return redirect()->back()->with('error', 'Invalid email address provided');
    }

    public function reset($remember_token)
    {
        $user = User::getTokenSingle($remember_token);
        if ($user) {
            $data['user'] = $user;
            $data['email'] = $user->email; // Pass the email
            return view('auth.reset', $data);
        } else {
            abort(404);
        }
    }


    public function postReset($token, Request $request)
    {
        // Check if passwords match
        if ($request->password == $request->confirm_password) {
            // Find the user by token
            $user = User::getTokenSingle($token);  // Use $token here

            if ($user) {
                // Update password
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect('login')->with('success', 'Password successfully updated.');
            }

            return redirect()->back()->with('error', 'Invalid token or user not found.');
        } else {
            return redirect()->back()->with('error', 'Password and confirm password do not match.');
        }
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out.');
    }
}
