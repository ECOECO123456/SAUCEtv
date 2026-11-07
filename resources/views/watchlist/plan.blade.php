<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan to Watch - SAUCEtv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#ff8c32',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-neutral-950 text-white font-sans min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b border-neutral-800 pb-4">
            <h1 class="text-3xl font-black text-brand tracking-wider">📌 Plan to Watch</h1>
            <a href="{{ route('home') }}" class="text-xs font-bold bg-neutral-900 hover:bg-neutral-800 text-gray-300 px-4 py-2 rounded-full border border-neutral-800 transition">
                &larr; Back to Catalog
            </a>
        </div>

        @if(isset($watchlist) && count($watchlist) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($watchlist as $item)
                    <div class="bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden shadow-lg p-3 flex flex-col justify-between">
                        <div>
                            <img src="{{ $item->poster_path ? 'https://image.tmdb.org/t/p/w500' . $item->poster_path : 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=500' }}" 
                                 alt="{{ $item->title }}" 
                                 class="w-full h-72 object-cover rounded-xl mb-3">
                            <h2 class="text-xs font-bold text-white truncate">{{ $item->title }}</h2>
                        </div>
                        <span class="text-[10px] text-brand font-semibold mt-2 uppercase tracking-wide">Plan to Watch</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-neutral-900 rounded-3xl border border-neutral-800">
                <p class="text-gray-400 text-sm font-medium">Wala pang laman ang iyong Plan to Watch folder.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block bg-brand text-neutral-950 text-xs font-bold px-5 py-2.5 rounded-xl transition">
                    Mag-browse ng Pelikula
                </a>
            </div>
        @endif
    </div>
</body>
</html>