<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Request History - SAUCEtv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#ff8c32',
                        brandDark: '#e07320',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-neutral-950 text-white font-sans selection:bg-brand selection:text-black min-h-screen flex flex-col justify-between">

    <!-- HEADER NAVIGATION -->
    <header class="flex justify-between items-center px-8 py-4 bg-neutral-900/90 backdrop-blur-md fixed top-0 w-full z-40 border-b border-neutral-800">
        <a href="{{ route('home') }}" class="text-3xl font-black tracking-wider text-brand">SAUCE<span class="text-white">tv</span></a>
        
        <div class="flex items-center gap-3">
            @if(session()->has('user'))
                <span class="text-xs font-bold text-gray-300 bg-neutral-900 px-3 py-2 rounded-full border border-neutral-800 hidden sm:inline-block">
                    👤 {{ session('user')['username'] ?? 'User' }}
                </span>
                <a href="{{ route('movie.request.form') }}" class="bg-neutral-800 hover:bg-neutral-700 text-gray-300 px-4 py-2 rounded-full text-xs font-bold transition flex items-center gap-1.5 border border-neutral-700">
                    ➕ Request Movie
                </a>
                <a href="{{ route('logout') }}" title="Logout" class="bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600/40 p-2.5 rounded-full transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-xs font-bold bg-brand hover:bg-brandDark text-neutral-950 px-4 py-2 rounded-full transition shadow-md">
                    🔑 Login
                </a>
            @endif
        </div>
    </header>

    <!-- MAIN CONTENT - HISTORY TABLE -->
    <main class="pt-32 pb-16 px-6 max-w-4xl mx-auto w-full flex-grow">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-black tracking-tight flex items-center gap-2">
                    📁 My Request History
                </h1>
                <p class="text-xs text-gray-400 mt-1">Lahat ng mga pelikula o palabas na iyong hiniling na ipalabas sa SAUCEtv.</p>
            </div>
            <a href="{{ route('home') }}" class="bg-neutral-900 hover:bg-neutral-800 text-gray-300 text-xs font-bold px-4 py-2.5 rounded-full border border-neutral-800 transition">
                ← Back to Home
            </a>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-neutral-950/60 border-b border-neutral-800 text-[11px] font-black text-gray-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Title & Admin Response</th>
                            <th class="py-4 px-6">Type</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Requested Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/60 text-sm">
                        @forelse($requests as $req)
                            <tr class="hover:bg-neutral-800/40 transition align-top">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-white block">
                                        {{ $req->title }}
                                    </span>
                                    @if($req->description)
                                        <p class="text-xs text-gray-400 font-normal mt-0.5">{{ $req->description }}</p>
                                    @endif

                                    <!-- BAGONG DAGDAG: Admin Response / Emoji Section -->
                                    @if(!empty($req->admin_response) || !empty($req->admin_emoji))
                                        <div class="mt-3 p-3 bg-neutral-950/60 border border-neutral-800 rounded-xl space-y-1">
                                            <span class="text-[10px] font-bold text-brand uppercase tracking-wider block">Tugon ng Admin:</span>
                                            <p class="text-xs text-gray-300 flex items-center gap-2">
                                                @if(!empty($req->admin_emoji))
                                                    <span class="text-lg">{{ $req->admin_emoji }}</span>
                                                @endif
                                                <span>{{ $req->admin_response ?? 'Walang naka-attach na mensahe.' }}</span>
                                            </p>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-neutral-800 text-gray-300 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase border border-neutral-700">
                                        {{ $req->type }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($req->status === 'pending')
                                        <span class="bg-amber-500/10 text-amber-400 border border-amber-500/30 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                            Pending
                                        </span>
                                    @elseif($req->status === 'approved')
                                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                            Approved
                                        </span>
                                    @elseif($req->status === 'completed')
                                        <span class="bg-blue-500/10 text-blue-400 border border-blue-500/30 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                            Completed
                                        </span>
                                    @else
                                        <span class="bg-red-500/10 text-red-400 border border-red-500/30 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right text-xs text-gray-400 font-medium">
                                    {{ date('M d, Y h:i A', strtotime($req->created_at)) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500 text-xs font-semibold">
                                    Wala ka pang naisusumiteng movie request.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-neutral-900 bg-neutral-950 py-6 text-center text-xs text-gray-600">
        &copy; {{ date('Y') }} SAUCEtv Streaming Portal. All rights reserved.
    </footer>

</body>
</html>