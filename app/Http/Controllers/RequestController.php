<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    // Ipakita ang form para sa pag-request ng pelikula kasama ang pending count para sa admin
    public function create()
    {
        $pendingRequestsCount = DB::table('movie_requests')->where('status', 'pending')->count();
        return view('request_movie', compact('pendingRequestsCount'));
    }

    // I-save ang request ng user sa database at manatili sa pahina
    public function store(Request $request)
    {
        $user = session('user');
        $userId = $user['id'] ?? 1;

        DB::table('movie_requests')->insert([
            'user_id' => $userId,
            'title' => $request->input('title'),
            'type' => $request->input('type', 'movie'),
            'description' => $request->input('description'),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mananatili sa request form page at magpapadala ng success message para sa floating toast
        return redirect()->back()->with('success', 'Request Submitted');
    }

    // Ipakita ang kasaysayan ng mga request ng user
    public function history()
    {
        $user = session('user');
        $userId = $user['id'] ?? 1;
        $pendingRequestsCount = DB::table('movie_requests')->where('status', 'pending')->count();

        $requests = DB::table('movie_requests')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user_requests_history', compact('requests', 'pendingRequestsCount'));
    }

    // Ipakita ang listahan ng mga request sa Admin Panel
    public function adminRequests()
    {
        $pendingRequestsCount = DB::table('movie_requests')->where('status', 'pending')->count();
        $requests = DB::table('movie_requests')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_requests', compact('requests', 'pendingRequestsCount'));
    }
}