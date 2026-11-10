<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Idinagdag para mahanap ang user ID mula sa custom session

class WatchlistController extends Controller
{
    /**
     * Kunin ang ID ng user mula sa Laravel Auth o sa custom session.
     */
    private function getUserId()
    {
        // 1. Kung gumagamit ng Laravel native Auth
        if (Auth::check()) {
            return Auth::id();
        }

        // 2. Kung gumagamit ng custom session ('user' => ['username' => '...'])
        if (session()->has('user')) {
            $username = session('user')['username'] ?? null;
            if ($username) {
                $user = User::where('username', $username)->first();
                if ($user) {
                    return $user->id;
                }
            }
        }

        return null;
    }

    /**
     * Ipakita ang buong Watchlist page na may Tab Filtering (All, Plan to Watch, Watched).
     */
    public function index(Request $request)
    {
        $userId = $this->getUserId();

        // Kung walang nakalogin sa alinman sa dalawa, i-redirect sa login page
        if (!$userId) {
            return redirect()->route('login');
        }

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
        $userId = $this->getUserId();

        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

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