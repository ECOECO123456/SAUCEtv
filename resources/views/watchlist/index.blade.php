<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Watchlist - SAUCEtv</title>
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
<body class="bg-neutral-950 text-white font-sans min-h-screen flex flex-col justify-between">

    <!-- HEADER NAVIGATION -->
    <header class="flex justify-between items-center px-8 py-4 bg-neutral-900/90 backdrop-blur-md fixed top-0 w-full z-50 border-b border-neutral-800">
        <a href="{{ route('home') }}" class="text-3xl font-black tracking-wider text-brand">SAUCE<span class="text-white">tv</span></a>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="text-xs font-bold bg-neutral-800 hover:bg-neutral-700 text-gray-300 px-4 py-2 rounded-full border border-neutral-700 transition">
                &larr; Back to Catalog
            </a>
            @if(session()->has('user'))
                <span class="text-xs font-bold text-gray-300 bg-neutral-900 px-3 py-2 rounded-full border border-neutral-800">
                    👤 {{ session('user')['username'] }}
                </span>
            @endif
        </div>
    </header>

    <!-- MAIN WATCHLIST CONTENT -->
    <main class="max-w-6xl mx-auto px-6 pt-28 pb-16 w-full flex-grow">
        
        <!-- TITLE & TAB NAVIGATION BAR -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-neutral-800 pb-6">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-2">
                    📁 My Watchlist
                </h1>
                <p class="text-xs text-gray-400 mt-1">Manage all your saved movies and TV shows in one place.</p>
            </div>

            <!-- TABS FOR SWITCHING BETWEEN ALL, PLAN TO WATCH, AND WATCHED -->
            @php $currentTab = request('status', 'all'); @endphp
            <div class="flex items-center gap-2 bg-neutral-900 p-1.5 rounded-2xl border border-neutral-800 self-start md:self-auto">
                <a href="{{ route('watchlist.index') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentTab === 'all' ? 'bg-brand text-neutral-950 shadow-md' : 'text-gray-400 hover:text-white' }}">
                    🎬 All ({{ count($watchlist) }})
                </a>
                <a href="{{ route('watchlist.index', ['status' => 'plan']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentTab === 'plan' ? 'bg-brand text-neutral-950 shadow-md' : 'text-gray-400 hover:text-white' }}">
                    📌 Plan to Watch
                </a>
                <a href="{{ route('watchlist.index', ['status' => 'watched']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentTab === 'watched' ? 'bg-brand text-neutral-950 shadow-md' : 'text-gray-400 hover:text-white' }}">
                    ✅ Watched
                </a>
            </div>
        </div>

        <!-- MOVIES GRID DISPLAY -->
        @if(isset($watchlist) && count($watchlist) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($watchlist as $item)
                    @php
                        // Tiyaking tama ang media type at ID
                        $mediaType = (!empty($item->type) && $item->type !== 'movie') ? $item->type : 'movie';
                        $targetId = $item->movie_id ?? $item->id;
                    @endphp

                    <div class="bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden shadow-lg p-3 flex flex-col justify-between hover:border-neutral-700 transition">
                        <div>
                            <!-- BUONG POSTER AY CLINICKABLE LINK PATUNGO SA MOVIE SHOW PAGE -->
                            <a href="{{ route('show', ['type' => $mediaType, 'id' => $targetId]) }}" class="block relative mb-3 rounded-xl overflow-hidden group">
                                <img src="{{ $item->poster_path ? (str_starts_with($item->poster_path, 'http') ? $item->poster_path : 'https://image.tmdb.org/t/p/w500' . $item->poster_path) : 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=500' }}" 
                                     alt="{{ $item->title }}" 
                                     class="w-full h-72 object-cover rounded-xl transition duration-300 group-hover:scale-105">
                                
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-xs font-bold text-white transition">
                                    ▶ Play Now
                                </div>
                            </a>

                            <!-- CLINICKABLE PARIN ANG TITLE -->
                            <a href="{{ route('show', ['type' => $mediaType, 'id' => $targetId]) }}" class="block hover:text-brand transition">
                                <h2 class="text-xs font-bold text-white truncate" title="{{ $item->title }}">{{ $item->title }}</h2>
                            </a>
                        </div>

                        <!-- STATUS BADGE -->
                        <div class="mt-3 flex items-center justify-between pt-2 border-t border-neutral-800/80">
                            @if($item->status === 'watched')
                                <span class="text-[10px] bg-emerald-950/80 text-emerald-400 border border-emerald-800/50 px-2.5 py-0.5 rounded-full font-bold">
                                    ✅ Watched
                                </span>
                            @else
                                <span class="text-[10px] bg-amber-950/80 text-amber-400 border border-amber-800/50 px-2.5 py-0.5 rounded-full font-bold">
                                    📌 Plan to Watch
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- EMPTY STATE -->
            <div class="text-center py-20 bg-neutral-900/60 rounded-3xl border border-neutral-800/80 my-8">
                <div class="w-16 h-16 bg-neutral-800 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl text-gray-400">
                    📂
                </div>
                <h3 class="text-base font-bold text-white mb-1">Walang nahanap na pelikula</h3>
                <p class="text-gray-500 text-xs font-medium mb-6">Wala ka pang idinaragdag sa kategoryang ito.</p>
                <a href="{{ route('home') }}" class="inline-block bg-brand hover:bg-brandDark text-neutral-950 text-xs font-bold px-6 py-3 rounded-xl transition shadow-md">
                    Mag-browse ng Catalog
                </a>
            </div>
        @endif

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-neutral-900 bg-neutral-950 py-6 text-center text-xs text-gray-600">
        &copy; {{ date('Y') }} SAUCEtv Streaming Portal. All rights reserved.
    </footer>

</body>
</html>