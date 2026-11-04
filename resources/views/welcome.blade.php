<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAUCEtv - Watch Free Movies & TV Shows</title>
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
        
        <!-- SEARCH BAR WITH LIVE AUTOCOMPLETE -->
        <div class="relative w-1/3 hidden md:block">
            <form action="{{ route('home') }}" method="GET">
                <input 
                    type="text" 
                    id="searchInput"
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Search movies, TV shows..." 
                    autocomplete="off"
                    class="w-full bg-neutral-800 text-xs text-white placeholder-gray-400 rounded-full px-5 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand border border-neutral-700 transition"
                >
            </form>
            <div id="autocompleteResults" class="hidden absolute left-0 right-0 top-12 bg-neutral-900 border border-neutral-800 rounded-2xl shadow-2xl overflow-hidden z-50"></div>
        </div>

        <div class="flex items-center gap-3">
            @if(session()->has('user'))
                <span class="text-xs font-bold text-gray-300 bg-neutral-900 px-3 py-2 rounded-full border border-neutral-800 hidden sm:inline-block">
                    👤 {{ session('user')['username'] }} @if(session('user')['role'] === 'admin') <span class="text-brand">(Admin)</span> @endif
                </span>

                @if(session('user')['role'] === 'admin')
                    <!-- ADMIN REQUESTS & HISTORY ICON BUTTON -->
                    <a href="{{ route('admin.requests') }}" title="Admin Requests History" class="relative bg-neutral-800 hover:bg-neutral-700 text-gray-300 p-2.5 rounded-full border border-neutral-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        @if(($pendingRequestsCount ?? 0) > 0)
                            <span class="absolute -top-1 -right-1 bg-brand text-neutral-950 text-[9px] font-black px-1.5 py-0.2 rounded-full">{{ $pendingRequestsCount }}</span>
                        @endif
                    </a>
                @else
                    <!-- USER REQUEST MOVIE BUTTON -->
                    <a href="{{ route('movie.request.form') }}" title="Request a Movie" class="bg-neutral-800 hover:bg-neutral-700 text-gray-300 p-2.5 rounded-full border border-neutral-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>

                    <!-- USER REQUEST HISTORY FOLDER ICON BUTTON -->
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

    <!-- MAIN CONTENT -->
    <main class="pt-24 pb-16 px-6 max-w-7xl mx-auto w-full flex-grow">

        @if(isset($heroMovie) && empty($search) && ($selectedGenre === 'All' || empty($selectedGenre)))
            @php
                $heroTitle = $heroMovie['title'] ?? $heroMovie['name'] ?? 'Featured Title';
                $heroOverview = $heroMovie['overview'] ?? 'Watch standard and trending titles in full high definition quality.';
                $heroPoster = !empty($heroMovie['full_poster']) ? $heroMovie['full_poster'] : (!empty($heroMovie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $heroMovie['poster_path'] : 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=500&auto=format&fit=crop&q=80');
                $heroType = $heroMovie['type'] ?? (isset($heroMovie['name']) ? 'tv' : 'movie');
            @endphp
            <div class="relative w-full h-[400px] rounded-3xl overflow-hidden mb-12 shadow-2xl border border-neutral-800 flex items-end">
                <img src="{{ $heroPoster }}" alt="{{ $heroTitle }}" class="absolute inset-0 w-full h-full object-cover filter brightness-50">
                <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/60 to-transparent"></div>
                
                <div class="relative z-10 p-8 max-w-2xl">
                    <span class="bg-brand text-neutral-950 font-black text-[10px] px-3 py-1 rounded-full uppercase tracking-wider mb-3 inline-block">Featured Today</span>
                    <h1 class="text-4xl font-black text-white tracking-tight mb-3">{{ $heroTitle }}</h1>
                    <p class="text-xs text-gray-300 line-clamp-3 mb-6 leading-relaxed">{{ $heroOverview }}</p>
                    <a href="{{ route('movie.show', ['id' => $heroMovie['id'], 'type' => $heroType, 'title' => $heroTitle]) }}" class="bg-brand hover:bg-brandDark text-neutral-950 font-black text-xs px-6 py-3 rounded-xl transition inline-flex items-center gap-2 shadow-lg">
                        ▶ Watch Now
                    </a>
                </div>
            </div>
        @endif

        <!-- GENRE FILTER BUTTONS -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 scrollbar-none">
            @foreach($genres as $genreName => $genreId)
                <a href="{{ route('home', ['genre' => $genreName]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition border {{ ($selectedGenre ?? 'All') === $genreName ? 'bg-brand text-neutral-950 border-brand' : 'bg-neutral-900 text-gray-400 border-neutral-800 hover:text-white hover:bg-neutral-800' }}">
                    {{ $genreName }}
                </a>
            @endforeach
        </div>

        <!-- CATALOG TITLE -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-black tracking-tight flex items-center gap-2">
                🎬 {{ !empty($search) ? 'Search Results for "'.$search.'"' : (($selectedGenre ?? 'All') !== 'All' ? $selectedGenre . ' Titles' : 'Popular & Trending Catalog') }}
            </h2>
            <span class="text-xs text-gray-500 font-semibold">{{ count($movies) }} Items Shown</span>
        </div>

        <!-- MOVIE GRID LISTING -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($movies as $m)
                @php
                    $mTitle = $m['title'] ?? $m['name'] ?? 'Untitled';
                    $mRating = $m['vote_average'] ?? 8.0;
                    $mYear = substr($m['release_date'] ?? $m['first_air_date'] ?? '2024', 0, 4);
                    $mType = $m['type'] ?? (isset($m['name']) ? 'tv' : 'movie');
                    $mPoster = !empty($m['full_poster']) ? $m['full_poster'] : (!empty($m['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $m['poster_path'] : 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=500&auto=format&fit=crop&q=80');
                @endphp
                <div class="group relative bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-800/80 hover:border-brand/50 transition duration-300 shadow-md flex flex-col justify-between">
                    <a href="{{ route('movie.show', ['id' => $m['id'], 'type' => $mType, 'title' => $mTitle]) }}" class="block overflow-hidden relative aspect-[2/3]">
                        <img src="{{ $mPoster }}" alt="{{ $mTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-2 right-2 bg-neutral-950/80 backdrop-blur-md px-2 py-1 rounded-md border border-neutral-800 text-[10px] font-bold text-yellow-400">
                            ★ {{ number_format((float)$mRating, 1) }}
                        </div>
                    </a>

                    <div class="p-4 flex flex-col justify-between flex-grow">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-500 tracking-wider block mb-1">{{ $mType === 'tv' ? 'TV Series' : 'Movie' }} • {{ $mYear }}</span>
                            <h3 class="text-xs font-bold text-white line-clamp-1 group-hover:text-brand transition">{{ $mTitle }}</h3>
                        </div>

                        @if(session()->has('user') && session('user')['role'] === 'admin' && isset($m['posted_by']))
                            <a href="{{ route('admin.movie.delete', ['id' => $m['id']]) }}" onclick="return confirm('Delete movie from catalog?')" class="mt-3 text-[10px] text-red-400 font-bold bg-red-500/10 hover:bg-red-500/20 py-1.5 rounded-lg text-center border border-red-500/20 transition block">
                                Delete Item
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-neutral-900/50 rounded-3xl border border-neutral-800">
                    <p class="text-sm text-gray-400 font-bold">No movies found matching your query.</p>
                </div>
            @endforelse
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-neutral-900 bg-neutral-950 py-6 text-center text-xs text-gray-600">
        &copy; {{ date('Y') }} SAUCEtv Streaming Portal. All rights reserved.
    </footer>

    <!-- FLOATING ISLAND NOTIFICATION -->
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

    <!-- LIVE SEARCH AUTOCOMPLETE JAVASCRIPT -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const resultsBox = document.getElementById('autocompleteResults');

        if (searchInput && resultsBox) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                if (query.length < 2) {
                    resultsBox.classList.add('hidden');
                    return;
                }

                fetch(`/suggestions?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            resultsBox.innerHTML = data.map(item => `
                                <a href="${item.url}" class="block px-4 py-3 hover:bg-neutral-800 text-xs font-semibold text-gray-200 border-b border-neutral-800/60 last:border-0 transition flex items-center justify-between">
                                    <span>${item.title}</span>
                                    <span class="text-[10px] text-brand bg-neutral-950 border border-neutral-800 px-2 py-0.5 rounded uppercase font-bold">${item.type}</span>
                                </a>
                            `).join('');
                            resultsBox.classList.remove('hidden');
                        } else {
                            resultsBox.classList.add('hidden');
                        }
                    })
                    .catch(() => resultsBox.classList.add('hidden'));
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>