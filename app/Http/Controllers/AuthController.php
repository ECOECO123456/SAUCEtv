<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        // 1. Suriin muna kung naka-lockout ang user dahil sa sunod-sunod na maling subok
        $lockoutTime = session('lockout_until');
        if ($lockoutTime && now()->lt($lockoutTime)) {
            $remainingSeconds = now()->diffInSeconds($lockoutTime);
            return back()->with('error', "Masyadong maraming maling subok. Mangyaring maghintay ng {$remainingSeconds} segundo bago sumubukang muli.");
        }

        $username = $request->input('username');
        $password = $request->input('password');

        // Secret Admin Authentication
        if ($username === 'adminuser' && $password === 'admin123') {
            // I-reset ang mga failed attempts kapag nagtagumpay
            session()->forget(['login_attempts', 'lockout_until']);
            
            session([
                'user' => [
                    'id' => 1,
                    'username' => 'adminuser',
                    'role' => 'admin',
                    'is_admin' => 1
                ]
            ]);
            return redirect()->route('home')->with('success', 'Logged in as Admin!');
        }

        // Hanapin ang user sa database
        $user = DB::table('users')->where('username', $username)->orWhere('email', $username)->first();

        // Suriin kung umiiral ang user at kung tugma ang hashed password
        if ($user && Hash::check($password, $user->password)) {
            // I-reset ang mga failed attempts kapag matagumpay ang pag-login
            session()->forget(['login_attempts', 'lockout_until']);

            session([
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->is_admin ? 'admin' : 'user',
                    'is_admin' => $user->is_admin ?? 0
                ]
            ]);
            return redirect()->route('home')->with('success', 'Welcome back, ' . $user->username . '!');
        }

        // 2. Kung nagkamali sa password o username, dagdagan ang counter
        $attempts = session('login_attempts', 0) + 1;
        session(['login_attempts' => $attempts]);

        // 3. Kung umabot na sa 3 beses na nagkamali, i-lock sa loob ng 30 segundo
        if ($attempts >= 3) {
            session(['lockout_until' => now()->addSeconds(30)]);
            session()->forget('login_attempts'); // I-reset ang counter para sa susunod na ban cycle
            return back()->with('error', 'Mali ang password ng 3 beses. Naka-lock ang pag-login sa loob ng 30 segundo.');
        }

        $remainingAttempts = 3 - $attempts;
        return back()->with('error', "Invalid username or password. Mayroon ka pang {$remainingAttempts} na natitirang subok bago ma-lock.");
    }

    public function register(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!empty($username) && strlen($password) >= 4) {
            // I-save ang bagong user sa database gamit ang hashed password at PostgreSQL primary key fix
            $userId = DB::table('users')->insertGetId([
                'username' => $username,
                'password' => Hash::make($password),
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id'); // <--- Idinagdag ang 'id' para sa PostgreSQL auto-increment compatibility

            session([
                'user' => [
                    'id' => $userId,
                    'username' => $username,
                    'role' => 'user',
                    'is_admin' => 0
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