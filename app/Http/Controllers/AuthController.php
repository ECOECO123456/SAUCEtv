<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // Secret Admin Authentication
        if ($username === 'adminuser' && $password === 'admin123') {
            session([
                'user' => [
                    'username' => 'adminuser',
                    'role' => 'admin'
                ]
            ]);
            return redirect()->route('home')->with('success', 'Logged in as Admin!');
        }

        // Regular User Mock Authentication
        if (!empty($username) && strlen($password) >= 4) {
            session([
                'user' => [
                    'username' => $username,
                    'role' => 'user'
                ]
            ]);
            return redirect()->route('home')->with('success', 'Welcome back, ' . $username . '!');
        }

        return back()->with('error', 'Invalid username or password.');
    }

    public function register(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!empty($username) && strlen($password) >= 4) {
            session([
                'user' => [
                    'username' => $username,
                    'role' => 'user'
                ]
            ]);
            return redirect()->route('home')->with('success', 'Account created successfully!');
        }

        return back()->with('error', 'Please provide a valid username and password (at least 4 characters).');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}