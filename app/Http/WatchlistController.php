<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WatchlistController extends Controller
{
    // Ipakita ang buong Watchlist folder
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

    // Ipakita ang Plan to Watch folder
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

    // Ipakita ang Watched folder
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

    // I-save ang pelikula sa Plan to Watch o Watched
    public function store(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user['id'] ?? 1;
        $movieId = $request->input('id');
        $status = $request->input('status', 'plan'); // 'plan' o 'watched'

        // Suriin kung naka-save na sa database para sa user na ito
        $exists = DB::table('watchlist')
            ->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();

        if ($exists) {
            // I-update ang status kung sakaling lumipat ng folder
            DB::table('watchlist')
                ->where('user_id', $userId)
                ->where('movie_id', $movieId)
                ->update(['status' => $status]);
        } else {
            // I-insert ang bagong record
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
        return back()->with('success', "Matagumpay na naidagdag sa iyong {$folderName} folder!");
    }
}