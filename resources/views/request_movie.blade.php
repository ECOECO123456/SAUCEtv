<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Movie - SAUCEtv</title>
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
                    👤 {{ session('user')['username'] }} @if(session('user')['role'] === 'admin') <span class="text-brand">(Admin)</span> @endif
                </span>

                @if(session('user')['role'] === 'admin')
                    <a href="{{ route('admin.requests') }}" title="Admin Requests History" class="relative bg-neutral-800 hover:bg-neutral-700 text-gray-300 p-2.5 rounded-full border border-neutral-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        @if(($pendingRequestsCount ?? 0) > 0)
                            <span class="absolute -top-1 -right-1 bg-brand text-neutral-950 text-[9px] font-black px-1.5 py-0.2 rounded-full">{{ $pendingRequestsCount }}</span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('watchlist.index') }}" title="My Watchlist Folder" class="bg-neutral-800 hover:bg-neutral-700 text-gray-300 p-2.5 rounded-full border border-neutral-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </a>
                    <a href="{{ route('user.requests.history') }}" title="My Request History" class="bg-neutral-800 hover:bg-neutral-700 text-gray-300 p-2.5 rounded-full border border-neutral-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </a>
                @endif

                <a href="{{ route('logout') }}" title="Logout" class="bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600/40 p-2.5 rounded-full transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-xs font-bold bg-brand hover:bg-brandDark text-neutral-950 px-4 py-2 rounded-full transition flex items-center gap-1 shadow-md">
                    🔑 Login
                </a>
            @endif
        </div>
    </header>

    <!-- MAIN CONTENT - REQUEST FORM -->
    <main class="pt-32 pb-16 px-6 max-w-xl mx-auto w-full flex-grow flex items-center justify-center">
        <!-- FORM CARD WITH RELATIVE POSITIONING -->
        <div class="relative bg-neutral-900 border border-neutral-800 rounded-3xl p-8 shadow-2xl w-full">
            
            <!-- EXIT / X BUTTON -->
            <a href="{{ route('home') }}" title="Bumalik sa Home" class="absolute top-5 right-5 flex items-center justify-center w-8 h-8 rounded-full bg-neutral-800 hover:bg-neutral-700 text-gray-400 hover:text-white border border-neutral-700 transition text-base font-bold shadow-md">
                &times;
            </a>

            <div class="mb-6 text-center pr-6">
                <span class="bg-brand/10 text-brand text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider border border-brand/20 inline-block mb-2">Community Request</span>
                <h1 class="text-2xl font-black text-white tracking-tight">Request a Movie or TV Show</h1>
                <p class="text-xs text-gray-400 mt-1">I-submit ang iyong pelikula o palabas na gustong mapanood sa SAUCEtv.</p>
            </div>

            <form action="{{ route('movie.request.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-300 mb-2 uppercase tracking-wider">Movie / Show Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        required 
                        placeholder="Hal. Interstellar, Avengers, etc."
                        class="w-full bg-neutral-950 text-sm text-white placeholder-gray-600 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand border border-neutral-800 transition"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-300 mb-2 uppercase tracking-wider">Content Type</label>
                    <select 
                        name="type" 
                        class="w-full bg-neutral-950 text-sm text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand border border-neutral-800 transition"
                    >
                        <option value="movie">Movie</option>
                        <option value="tv">TV Series</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-300 mb-2 uppercase tracking-wider">Description / Notes (Opsyonal)</label>
                    <textarea 
                        name="description" 
                        rows="4" 
                        placeholder="Magbigay ng kaunting detalye o taon kung bakit mo ito gustong ipalabas..."
                        class="w-full bg-neutral-950 text-sm text-white placeholder-gray-600 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand border border-neutral-800 transition resize-none"
                    ></textarea>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-brand hover:bg-brandDark text-neutral-950 font-black text-xs uppercase tracking-wider py-3.5 rounded-xl transition shadow-lg flex items-center justify-center gap-2"
                >
                    🚀 Submit Request
                </button>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-neutral-900 bg-neutral-950 py-6 text-center text-xs text-gray-600">
        &copy; {{ date('Y') }} SAUCEtv Streaming Portal. All rights reserved.
    </footer>

    <!-- FLOATING ISLAND NOTIFICATION & AUTO-RESET SCRIPT -->
    @if(session('success'))
    <div id="floatingIslandToast" class="fixed top-6 right-6 z-50 transform -translate-y-10 opacity-0 transition-all duration-500 ease-out flex items-center gap-4 bg-neutral-900/95 backdrop-blur-xl border border-neutral-700 text-white text-sm font-bold px-6 py-4 rounded-2xl shadow-2xl">
        <div class="w-9 h-9 rounded-full bg-emerald-500/20 border border-emerald-500/50 flex items-center justify-center text-emerald-400 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-[11px] text-emerald-400 font-extrabold uppercase tracking-wider">Success</span>
            <span class="text-gray-100 text-sm font-semibold">{{ session('success') }}</span>
        </div>
        <button onclick="closeToast()" class="ml-4 text-gray-400 hover:text-white transition text-lg font-bold">&times;</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Kusa nitong binubura o nire-reset ang mga textbox pagka-submit
            const form = document.querySelector('form');
            if (form) {
                form.reset();
            }

            // Pag-animate ng floating island notification
            const toast = document.getElementById("floatingIslandToast");
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove("-translate-y-10", "opacity-0");
                    toast.classList.add("translate-y-0", "opacity-100");
                }, 100);
                setTimeout(() => { closeToast(); }, 4000);
            }
        });

        function closeToast() {
            const toast = document.getElementById("floatingIslandToast");
            if (toast) {
                toast.classList.remove("translate-y-0", "opacity-100");
                toast.classList.add("-translate-y-10", "opacity-0");
                setTimeout(() => { toast.remove(); }, 500);
            }
        }
    </script>
    @endif
</body>
</html>