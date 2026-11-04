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
                extend: { colors: { brand: '#ff8c32', brandDark: '#e07320' } }
            }
        }
    </script>
</head>
<body class="bg-neutral-950 text-white font-sans min-h-screen p-8">

    <div class="max-w-3xl mx-auto">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8 pb-4 border-b border-neutral-800">
            <h1 class="text-2xl font-black text-brand">✉️ Request a Movie / TV Show</h1>
            <a href="{{ route('home') }}" class="text-xs font-bold bg-neutral-800 hover:bg-neutral-700 text-white px-4 py-2 rounded-full border border-neutral-700 transition">
                ← Back to Home
            </a>
        </div>

        <!-- FORM -->
        <form action="{{ route('movie.request.store') }}" method="POST" class="bg-neutral-900 p-6 rounded-2xl border border-neutral-800 shadow-xl mb-12">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Movie / TV Show Title</label>
                <input type="text" name="title" required placeholder="e.g. Avatar: The Way of Water" class="w-full bg-neutral-800 text-sm text-white px-4 py-3 rounded-xl border border-neutral-700 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Media Type</label>
                <select name="type" class="w-full bg-neutral-800 text-sm text-white px-4 py-3 rounded-xl border border-neutral-700 focus:outline-none focus:ring-2 focus:ring-brand">
                    <option value="movie">Movie</option>
                    <option value="tv">TV Show</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Additional Notes / Overview (Optional)</label>
                <textarea name="overview" rows="3" placeholder="Specify release year or season..." class="w-full bg-neutral-800 text-sm text-white px-4 py-3 rounded-xl border border-neutral-700 focus:outline-none focus:ring-2 focus:ring-brand"></textarea>
            </div>

            <button type="submit" class="w-full bg-brand hover:bg-brandDark text-neutral-950 font-black text-sm py-3.5 rounded-xl transition shadow-lg">
                Submit Request
            </button>
        </form>

        <!-- USER PERSONAL REQUEST HISTORY -->
        @php
            $allRequests = session()->get('user_movie_requests', []);
            $currentUser = session('user')['username'] ?? '';
            $myRequests = array_filter($allRequests, function($r) use ($currentUser) {
                return isset($r['requested_by']) && $r['requested_by'] === $currentUser;
            });
        @endphp

        <h2 class="text-lg font-black text-white mb-4 flex items-center gap-2">
            🕒 My Request History
        </h2>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-neutral-800/60 text-[10px] font-black uppercase text-gray-400 tracking-wider border-b border-neutral-800">
                        <th class="p-3.5">Title</th>
                        <th class="p-3.5">Type</th>
                        <th class="p-3.5">Requested Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800 text-xs">
                    @forelse($myRequests as $req)
                        <tr class="hover:bg-neutral-800/40 transition">
                            <td class="p-3.5 font-bold text-white">{{ $req['title'] }}</td>
                            <td class="p-3.5 uppercase text-brand font-bold text-[10px]">{{ $req['type'] }}</td>
                            <td class="p-3.5 text-gray-400 font-mono">{{ $req['created_at'] ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-500 font-semibold">
                                Wala ka pang naisusumiteng movie request.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>