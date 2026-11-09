<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\NewMovieRequestNotification;
use Illuminate\Support\Facades\Notification;

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

        $requestId = DB::table('movie_requests')->insertGetId([
            'user_id' => $userId,
            'title' => $request->input('title'),
            'type' => $request->input('type', 'movie'),
            'description' => $request->input('description'),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Kunin ang bagong ginawang request bilang object para sa notification
        $movieRequest = DB::table('movie_requests')->where('id', $requestId)->first();

        // Hanapin ang mga admin (halimbawa: mga user na may is_admin == 1 o pwede mong i-adjust depende sa user role mo)
        $admins = User::where('is_admin', 1)->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewMovieRequestNotification($movieRequest));
        }

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

    // Magdagdag ng tugon (emoji) at mag-update ng status mula sa admin
    public function respondToRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_emoji' => 'nullable|string|max:10',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        DB::table('movie_requests')->where('id', $id)->update([
            'admin_emoji' => $validated['admin_emoji'] ?? null,
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Naisumite na ang tugon sa request.');
    }
}