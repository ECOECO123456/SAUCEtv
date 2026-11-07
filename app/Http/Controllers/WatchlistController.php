<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WatchlistController extends Controller
{
    /**
     * Ipakita ang buong Watchlist page na may Tab Filtering (All, Plan to Watch, Watched).
     */
    public function index(Request $request)
    {
        $user = session('user');
        $userId = $user['id'] ?? 1;
        $status = $request->query('status');

        $query = DB::table('watchlist')->where('user_id', $userId);

        // Filter ayon sa napiling tab (plan o watched)
        if (in_array($status, ['plan', 'watched'])) {
            $query->where('status', $status);
        }

        $watchlist = $query->orderBy('updated_at', 'desc')->get();

        return view('watchlist.index', compact('watchlist'));
    }

    /**
     * I-save o i-update ang pelikula sa Watchlist (Plan to Watch / Watched).
     */
    public function store(Request $request)
    {
        $user = session('user');
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
                ->update([
                    'status' => $status,
                    'updated_at' => now(),
                ]);
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
        $message = "Naidagdag sa iyong {$folderName} folder!";

        // Kung galing sa AJAX fetch request, magbalik ng JSON response para sa floating toast
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}