<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library - SAUCEtv</title>
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
<body class="bg-neutral-950 text-white font-sans selection:bg-brand selection:text-black">

    <header class="flex justify-between items-center px-8 py-5 bg-neutral-900/80 backdrop-blur-md fixed top-0 w-full z-50 border-b border-neutral-800">
        <a href="{{ route('home') }}" class="text-3xl font-black tracking-wider text-brand">SAUCE<span class="text-white">tv</span></a>
        
        <div class="flex items-center space-x-3">
            <span class="text-xs font-bold text-gray-300 bg-neutral-900 px-3 py-2 rounded-full border border-neutral-800">
                👤 {{ session('user')['username'] ?? 'User' }}
            </span>
            <a href="{{ route('home') }}" class="text-xs font-bold bg-neutral-800 hover:bg-neutral-700 text-gray-300 px-4 py-2 rounded-full border border-neutral-700 transition">&larr; Back to Catalog</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-24 space-y-8">
        <div>
            <h1 class="text-3xl font-black tracking-tight mb-2">📁 Personal Library</h1>
            <p class="text-gray-400 text-sm">Your personal saved titles for <span class="text-brand font-bold">{{ session('user')['username'] }}</span>.</p>
        </div>

        <div class="flex border-b border-neutral-800 gap-6 text-sm font-bold">
            <button onclick="switchTab('planToWatch')" id="tab-plan" class="pb-3 border-b-2 border-brand text-brand transition">📌 Plan to Watch</button>
            <button onclick="switchTab('watched')" id="tab-watched" class="pb-3 border-b-2 border-transparent text-gray-400 hover:text-white transition">✓ Watched</button>
        </div>

        <div id="libraryContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6"></div>
    </main>

    <script>
        const currentUser = "{{ session('user')['username'] ?? 'guest' }}";
        let activeTab = 'planToWatch';

        function getUserKey(folder) {
            return `${currentUser}_${folder}`;
        }

        function getStorage(folder) {
            return JSON.parse(localStorage.getItem(getUserKey(folder))) || [];
        }

        function renderLibrary() {
            const list = getStorage(activeTab);
            const container = document.getElementById('libraryContainer');
            container.innerHTML = '';

            if (list.length === 0) {
                container.innerHTML = `<div class="col-span-full text-center py-16 bg-neutral-900/50 rounded-2xl border border-neutral-800 text-gray-500 font-medium text-xs">No titles in this list yet.</div>`;
                return;
            }

            list.forEach(movie => {
                container.innerHTML += `
                    <div class="bg-neutral-900 rounded-xl overflow-hidden border border-neutral-800 hover:border-brand transition group shadow-lg flex flex-col relative">
                        <a href="/movie/${movie.id}?type=${movie.type}" class="flex flex-col h-full">
                            <div class="relative aspect-[2/3] w-full bg-neutral-800 overflow-hidden">
                                <img src="${movie.poster}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="${movie.title}">
                                <div class="absolute top-2 right-2 bg-neutral-950/80 backdrop-blur-md px-2 py-1 rounded-md text-[10px] font-bold text-brand border border-neutral-800">
                                    ★ ${movie.rating}
                                </div>
                            </div>
                            <div class="p-4 flex flex-col justify-between flex-grow">
                                <h3 class="font-bold text-sm text-white group-hover:text-brand transition line-clamp-1">${movie.title}</h3>
                                <p class="text-[11px] text-gray-400 mt-1 font-semibold">${movie.date} • ${movie.type.toUpperCase()}</p>
                            </div>
                        </a>
                    </div>
                `;
            });
        }

        function switchTab(tab) {
            activeTab = tab;
            document.getElementById('tab-plan').className = tab === 'planToWatch' ? 'pb-3 border-b-2 border-brand text-brand transition' : 'pb-3 border-b-2 border-transparent text-gray-400 hover:text-white transition';
            document.getElementById('tab-watched').className = tab === 'watched' ? 'pb-3 border-b-2 border-brand text-brand transition' : 'pb-3 border-b-2 border-transparent text-gray-400 hover:text-white transition';
            renderLibrary();
        }

        document.addEventListener('DOMContentLoaded', renderLibrary);
    </script>
</body>
</html>