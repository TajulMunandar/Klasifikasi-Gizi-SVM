<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validate form input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Create a new user
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'is_admin' => 2,
        ]);

        // Redirect to login page
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        // Validate login input
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect to dashboard
            return redirect('/dashboard');
        }

        // Authentication failed, redirect back with error
        return redirect()->route('login')->withErrors(['username' => 'Invalid credentials.']);
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
