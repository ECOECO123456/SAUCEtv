<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] ?? $movie['name'] ?? 'Watch' }} - SAUCEtv</title>
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
    <header class="flex justify-between items-center px-8 py-4 bg-neutral-900/90 backdrop-blur-md fixed top-0 w-full z-50 border-b border-neutral-800">
        <a href="{{ route('home') }}" class="text-3xl font-black tracking-wider text-brand">SAUCE<span class="text-white">tv</span></a>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="text-xs font-bold bg-neutral-800 hover:bg-neutral-700 text-gray-300 px-4 py-2 rounded-full border border-neutral-700 transition">
                &larr; Back to Catalog
            </a>
            @if(session()->has('user'))
                <span class="text-xs font-bold text-gray-300 bg-neutral-900 px-3 py-2 rounded-full border border-neutral-800">
                    👤 {{ session('user')['username'] }} @if(session('user')['role'] === 'admin') <span class="text-brand">(Admin)</span> @endif
                </span>
                <a href="{{ route('logout') }}" class="text-xs font-bold bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600/40 px-3 py-2 rounded-full transition">
                    Logout
                </a>
            @else
                <a href="{{ route('login') }}" class="text-xs font-bold bg-brand hover:bg-brandDark text-neutral-950 px-4 py-2 rounded-full transition flex items-center gap-1 shadow-md">
                    🔑 Login
                </a>
            @endif
        </div>
    </header>

    <!-- MAIN STREAMING & CONTENT SECTION -->
    <main class="max-w-6xl mx-auto px-6 pt-28 pb-16 w-full flex-grow">

        @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/50 text-red-400 text-xs p-4 rounded-2xl text-center font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500/50 text-green-400 text-xs p-4 rounded-2xl text-center font-bold">
                {{ session('success') }}
            </div>
        @endif

        @php
            $title = $movie['title'] ?? $movie['name'] ?? 'Selected Show';
            $rating = $movie['vote_average'] ?? 8.0;
            $releaseDate = $movie['release_date'] ?? $movie['first_air_date'] ?? '2024';
            $overview = $movie['overview'] ?? 'Watch ' . $title . ' online in full HD on SAUCEtv.';
            $poster = !empty($movie['full_poster']) ? $movie['full_poster'] : (!empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=500&auto=format&fit=crop&q=80');
            $mediaType = $type ?? 'movie';
            $id = $movie['id'];
        @endphp

        <!-- SERVER SELECTION CONTROLS -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-4 mb-4 flex flex-wrap items-center justify-between gap-3 shadow-md">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                    🖥️ Select Server:
                </span>
                <span class="text-[10px] bg-neutral-950 text-brand px-2 py-0.5 rounded border border-neutral-800 font-semibold" id="activeServerLabel">Server 1 (Vidsrc)</span>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                @if(!empty($movie['video_url']))
                    <button onclick="switchServer('local', '{{ $movie['video_url'] }}')" class="server-btn active-server px-3 py-1.5 rounded-xl text-xs font-bold transition border border-neutral-700 bg-brand text-neutral-950">
                        ⚡ Admin Upload File
                    </button>
                @endif
                <button onclick="switchServer('vidsrc', 'https://vidsrc.to/embed/{{ $mediaType }}/{{ $id }}')" class="server-btn px-3 py-1.5 rounded-xl text-xs font-bold transition border border-neutral-800 bg-neutral-950 text-gray-300 hover:text-white hover:bg-neutral-800">
                    Server 1 (Vidsrc)
                </button>
                <button onclick="switchServer('vidsrcme', 'https://vidsrc.me/embed/{{ $mediaType }}?id={{ $id }}')" class="server-btn px-3 py-1.5 rounded-xl text-xs font-bold transition border border-neutral-800 bg-neutral-950 text-gray-300 hover:text-white hover:bg-neutral-800">
                    Server 2 (Vidsrc.me)
                </button>
                <button onclick="switchServer('superembed', 'https://multiembed.mov/?video_id={{ $id }}&tmdb=1')" class="server-btn px-3 py-1.5 rounded-xl text-xs font-bold transition border border-neutral-800 bg-neutral-950 text-gray-300 hover:text-white hover:bg-neutral-800">
                    Server 3 (MultiEmbed)
                </button>
            </div>
        </div>

        <!-- VIDEO PLAYER CONTAINER -->
        <div class="relative w-full aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl border border-neutral-800 mb-8" id="playerContainer">
            @if(!empty($movie['video_url']))
                <!-- LOCAL UPLOADED VIDEO PLAYER DEFAULT -->
                <video id="localVideoPlayer" controls autoplay class="w-full h-full object-contain" poster="{{ $poster }}">
                    <source src="{{ $movie['video_url'] }}" type="video/mp4">
                    Your browser does not support playing this video.
                </video>
            @else
                <!-- EMBEDDED STREAMING PLAYER DEFAULT -->
                <iframe 
                    id="embedIframe"
                    src="https://vidsrc.to/embed/{{ $mediaType }}/{{ $id }}" 
                    class="w-full h-full border-0" 
                    allowfullscreen>
                </iframe>
            @endif
        </div>

        <!-- MOVIE DETAILS & META INFORMATION -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-8 mb-12 shadow-xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight text-white mb-2">{{ $title }}</h1>
                    <div class="flex items-center gap-3 text-xs font-semibold text-gray-400">
                        <span class="bg-brand text-neutral-950 font-bold px-2.5 py-1 rounded-md">★ {{ number_format((float)$rating, 1) }}</span>
                        <span>{{ substr($releaseDate, 0, 4) }}</span>
                        <span class="uppercase bg-neutral-800 px-2.5 py-1 rounded-md text-gray-300">{{ $mediaType === 'tv' ? 'TV Series' : 'Movie' }}</span>
                        @if(isset($movie['posted_by']))
                            <span class="bg-brand/10 text-brand border border-brand/30 px-2.5 py-1 rounded-md">Uploaded by {{ $movie['posted_by'] }}</span>
                        @endif
                    </div>
                </div>

                <!-- ADD / REMOVE FROM MY LIBRARY BUTTON -->
                <button onclick="toggleLibrary('{{ $movie['id'] }}', '{{ addslashes($title) }}', '{{ $mediaType }}', '{{ $movie['poster_path'] ?? '' }}')" id="libraryBtn" class="bg-neutral-800 hover:bg-neutral-700 text-brand border border-neutral-700 text-xs font-bold px-5 py-3 rounded-xl transition flex items-center gap-2 shadow-md">
                    📁 <span id="libraryBtnText">Add to My Library</span>
                </button>
            </div>

            <p class="text-gray-300 text-sm leading-relaxed mb-6">{{ $overview }}</p>

            <!-- CAST MEMBERS LIST -->
            @if(isset($movie['credits']['cast']) && count($movie['credits']['cast']) > 0)
                <div class="border-t border-neutral-800 pt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Top Cast & Performers</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_slice($movie['credits']['cast'], 0, 8) as $actor)
                            <span class="bg-neutral-950 text-gray-300 text-xs font-medium px-3 py-1.5 rounded-lg border border-neutral-800">
                                👤 {{ $actor['name'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($movie['cast_names']))
                <div class="border-t border-neutral-800 pt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Featured Cast</h3>
                    <p class="text-xs text-gray-400">{{ $movie['cast_names'] }}</p>
                </div>
            @endif
        </div>

        <!-- REVIEWS & USER COMMENTS SECTION -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-8 shadow-xl">
            <h2 class="text-xl font-black tracking-tight mb-6 flex items-center gap-2">
                💬 Reviews & Discussions
            </h2>

            @if(session()->has('user'))
                <form action="{{ route('comment.add', ['id' => $movie['id']]) }}" method="POST" class="mb-8 space-y-4 bg-neutral-950 p-6 rounded-2xl border border-neutral-800">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Write a Review</label>
                        <textarea name="comment" rows="3" required placeholder="Share your thoughts about this title..." class="w-full bg-neutral-900 text-xs text-white placeholder-gray-500 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-brand border border-neutral-800"></textarea>
                    </div>
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-400">Your Rating:</span>
                            <select name="rating" class="bg-neutral-900 text-xs text-white rounded-lg px-3 py-1.5 border border-neutral-800 focus:outline-none focus:ring-1 focus:ring-brand">
                                <option value="10">10 / 10 Masterpiece</option>
                                <option value="9">9 / 10 Excellent</option>
                                <option value="8" selected>8 / 10 Great</option>
                                <option value="7">7 / 10 Good</option>
                                <option value="6">6 / 10 Decent</option>
                                <option value="5">5 / 10 Average</option>
                                <option value="4">4 / 10 Below Average</option>
                                <option value="3">3 / 10 Poor</option>
                                <option value="2">2 / 10 Bad</option>
                                <option value="1">1 / 10 Terrible</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-brand hover:bg-brandDark text-neutral-950 font-black text-xs px-6 py-2.5 rounded-xl transition shadow-md">
                            Submit Review
                        </button>
                    </div>
                </form>
            @else
                <div class="mb-8 p-4 bg-neutral-950 rounded-xl text-center border border-neutral-800">
                    <p class="text-xs text-gray-400">Want to join the conversation? <a href="{{ route('login') }}" class="text-brand font-bold underline">Log in here</a> to submit a review.</p>
                </div>
            @endif

            <!-- COMMENTS LIST CONTAINER -->
            <div class="space-y-4">
                @if(isset($comments) && count($comments) > 0)
                    @foreach($comments as $com)
                    <div class="bg-neutral-950 p-5 rounded-2xl border border-neutral-800 flex justify-between items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-bold text-brand">@ {{ $com->username }}</span>
                                <span class="text-[10px] bg-neutral-900 text-yellow-400 px-2 py-0.5 rounded font-bold border border-neutral-800">★ {{ $com->rating }}/10</span>
                                <span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed">{{ $com->comment }}</p>
                        </div>

                        @if(session()->has('user') && session('user')['role'] === 'admin')
                        <a href="{{ route('admin.comment.delete', ['id' => $com->id]) }}" onclick="return confirm('Delete this comment?')" class="text-red-400 hover:text-red-300 text-[10px] font-bold bg-red-500/10 px-2.5 py-1 rounded-lg border border-red-500/20 transition shrink-0">
                            Delete
                        </a>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8 bg-neutral-950/40 rounded-2xl border border-neutral-800/60">
                        <p class="text-xs text-gray-500 font-medium">No reviews written yet. Be the first to share your review!</p>
                    </div>
                @endif
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-neutral-900 bg-neutral-950 py-6 text-center text-xs text-gray-600">
        &copy; {{ date('Y') }} SAUCEtv Streaming Portal. All rights reserved.
    </footer>

    <!-- SERVER SWITCHING & LOCALSTORAGE JAVASCRIPT LOGIC -->
    <script>
        function switchServer(serverType, streamUrl) {
            const container = document.getElementById('playerContainer');
            const label = document.getElementById('activeServerLabel');

            if (serverType === 'local') {
                container.innerHTML = `
                    <video controls autoplay class="w-full h-full object-contain" poster="{{ $poster }}">
                        <source src="${streamUrl}" type="video/mp4">
                        Your browser does not support playing this video.
                    </video>
                `;
                label.innerText = 'Admin Upload File';
            } else {
                container.innerHTML = `
                    <iframe src="${streamUrl}" class="w-full h-full border-0" allowfullscreen></iframe>
                `;
                if (serverType === 'vidsrc') label.innerText = 'Server 1 (Vidsrc)';
                if (serverType === 'vidsrcme') label.innerText = 'Server 2 (Vidsrc.me)';
                if (serverType === 'superembed') label.innerText = 'Server 3 (MultiEmbed)';
            }

            // Update button styles
            const buttons = document.querySelectorAll('.server-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-brand', 'text-neutral-950');
                btn.classList.add('bg-neutral-950', 'text-gray-300');
            });
            event.target.classList.remove('bg-neutral-950', 'text-gray-300');
            event.target.classList.add('bg-brand', 'text-neutral-950');
        }

        const username = "{{ session('user')['username'] ?? 'guest' }}";
        const storageKey = `sauce_library_${username}`;

        function checkLibraryStatus() {
            if (username === 'guest') return;
            let library = JSON.parse(localStorage.getItem(storageKey)) || [];
            const movieId = "{{ $movie['id'] }}";
            const exists = library.some(item => item.id == movieId);
            const btnText = document.getElementById('libraryBtnText');
            if (exists) {
                btnText.innerText = "Remove from Library";
            } else {
                btnText.innerText = "Add to My Library";
            }
        }

        function toggleLibrary(id, title, type, posterPath) {
            if (username === 'guest') {
                alert('Please log in first to save movies to your library.');
                window.location.href = "{{ route('login') }}";
                return;
            }

            let library = JSON.parse(localStorage.getItem(storageKey)) || [];
            const index = library.findIndex(item => item.id == id);

            if (index > -1) {
                library.splice(index, 1);
                alert('Removed from your Library.');
            } else {
                library.push({
                    id: id,
                    title: title,
                    type: type,
                    poster_path: posterPath,
                    added_at: new Date().toLocaleDateString()
                });
                alert('Added to your Library!');
            }

            localStorage.setItem(storageKey, JSON.stringify(library));
            checkLibraryStatus();
        }

        window.onload = checkLibraryStatus;
    </script>
</body>
</html>