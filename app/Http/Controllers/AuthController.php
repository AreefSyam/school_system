<?php
namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        // Allow only unauthenticated users to access login and registration, except for the logout.
        $this->middleware('guest')->except('logout');
    }

    // Return the login view.
    public function login()
    {
        return view('auth.login');
    }

    // Handle the user login attempt.
    public function postLogin(Request $request)
    {
        $request->validate([
            'email'    => "required|email",
            'password' => "required|min:6",
        ]);

        $credentials = $request->only("email", "password");
        $remember    = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            // Redirect the user to the correct dashboard based on their role
            return $this->redirectToDashboard();
        }

        // Redirect back with an error if authentication fails.
        return redirect(route('login'))->with('error', 'Invalid email or password. Please try again.');
    }

    // Redirect users to their specific dashboard based on their role.
    protected function redirectToDashboard()
    {
        // Get the authenticated user
        $user = Auth::user();
        // Check the user's role and redirect accordingly
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome Admin');
        } elseif ($user->role === 'teacher') {
            return redirect()->intended(route('teacher.dashboard'))->with('success', 'Welcome Teacher');
        }
        // Default redirect if no role-specific dashboard exists.
        return redirect('/')->with('error', 'You do not have access to the system.');
    }

    // Return the forgot password view.
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Process the forgot password request.
    public function postForgotPassword(Request $request)
    {
        // Validate that an email was provided
        $request->validate([
            'email' => 'required|email',
        ]);

        // Attempt to find the user by email
        $userEmail = User::getEmailSingle($request->email);

        // Debugging to check the result
        if ($userEmail === null) {
            return redirect()->back()->with('error', 'Email not found in the system');
        }

        // Generate a reset token and send it via email.
        if (isset($userEmail->email)) {
            $userEmail->remember_token = Str::random(30);
            $userEmail->save();
            // Send the forgot password email
            Mail::to($userEmail->email)->send(new ForgotPasswordMail($userEmail));

            return redirect()->back()->with('success', 'Please check your email and reset your password');
        }

        return redirect()->back()->with('error', 'Invalid email address provided');
    }

    // Display the password reset view.
    public function reset($remember_token)
    {
        $user = User::getTokenSingle($remember_token);
        // if User found
        if ($user) {
            $data['user']  = $user;
            $data['email'] = $user->email; // Pass the email
            return view('auth.reset', $data);
        } else {
            abort(404); // if user not found
        }
    }

    // Handle the password update after reset.
    public function postReset($token, Request $request)
    {
        // Ensure new passwords match
        if ($request->password == $request->confirm_password) {

            // Attempt to find and update user's password
            $user = User::getTokenSingle($token);
            if ($user) {
                // Update password
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect('login')->with('success', 'Password successfully updated.');
            }
            // Handle case where no valid user is found
            return redirect()->back()->with('error', 'Invalid token or user not found.');
        } else {
            // Handle password mismatch
            return redirect()->back()->with('error', 'Password and confirm password do not match.');
        }
    }

    // Logout the current user and invalidate the session.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out.');
    }

    /*
    ** Future References for Register Page and Register Post
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
    */
}
