<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Movie - SAUCEtv</title>
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
<body class="bg-neutral-950 text-white font-sans flex items-center justify-center min-h-screen px-4 py-12">

    <div class="max-w-lg w-full bg-neutral-900 border border-neutral-800 p-8 rounded-3xl shadow-2xl space-y-6">
        <div class="flex justify-between items-center border-b border-neutral-800 pb-4">
            <div>
                <h1 class="text-2xl font-black text-brand">➕ Post a Movie</h1>
                <p class="text-xs text-gray-400">Add a new movie or TV show to SAUCEtv catalog.</p>
            </div>
            <a href="{{ route('home') }}" class="text-xs font-bold text-gray-400 hover:text-white transition">&larr; Back</a>
        </div>

        <form action="{{ route('movie.post.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1">Movie / TV Show Title</label>
                <input type="text" name="title" required placeholder="e.g. Spider-Man: No Way Home" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-1">Type</label>
                    <select name="type" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand">
                        <option value="movie">Movie</option>
                        <option value="tv">TV Show / Series</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-1">Release Year</label>
                    <input type="number" name="release_year" value="2024" required class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1">Poster Image URL</label>
                <input type="url" name="poster_url" required placeholder="https://image.tmdb.org/t/p/w500/..." class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1">Rating (1.0 - 10.0)</label>
                <input type="number" step="0.1" name="rating" value="8.5" min="1" max="10" required class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1">Overview / Description</label>
                <textarea name="overview" rows="3" required placeholder="Write brief plot or summary..." class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand"></textarea>
            </div>

            <button type="submit" class="w-full bg-brand hover:bg-brandDark text-neutral-950 font-bold py-3 rounded-xl transition text-sm shadow-lg">
                🚀 Publish Movie to Catalog
            </button>
        </form>
    </div>

</body>
</html>