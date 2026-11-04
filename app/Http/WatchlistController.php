<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WatchlistController extends Controller
{
    // Folder View: Lahat ng Watchlist
    public function index()
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $watchlist = DB::table('watchlist')
            ->where('user_id', $user['id'] ?? 1)
            ->get();

        return view('watchlist.index', compact('watchlist'));
    }

    // Folder View: Plan to Watch
    public function plan()
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $watchlist = DB::table('watchlist')
            ->where('user_id', $user['id'] ?? 1)
            ->where('status', 'plan')
            ->get();

        return view('watchlist.plan', compact('watchlist'));
    }

    // Folder View: Watched
    public function watched()
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $watchlist = DB::table('watchlist')
            ->where('user_id', $user['id'] ?? 1)
            ->where('status', 'watched')
            ->get();

        return view('watchlist.watched', compact('watchlist'));
    }

    // Pag-save o Update ng status (Plan to Watch / Watched)
    public function store(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user['id'] ?? 1;
        $movieId = $request->input('id');
        $status = $request->input('status', 'plan');

        $exists = DB::table('watchlist')
            ->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();

        if ($exists) {
            DB::table('watchlist')
                ->where('user_id', $userId)
                ->where('movie_id', $movieId)
                ->update(['status' => $status]);
        } else {
            DB::table('watchlist')->insert([
                'user_id' => $userId,
                'movie_id' => $movieId,
                'title' => $request->input('title'),
                'poster_path' => $request->input('poster_path'),
                'type' => $request->input('type', 'movie'),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $folderName = $status === 'watched' ? 'Watched' : 'Plan to Watch';
        return back()->with('success', "Naidagdag sa iyong {$folderName} folder!");
    }
}