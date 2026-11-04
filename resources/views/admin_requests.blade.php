<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Movie Request History</title>
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

    <div class="max-w-6xl mx-auto">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8 pb-4 border-b border-neutral-800">
            <div>
                <h1 class="text-2xl font-black text-brand flex items-center gap-2">📜 User Request History (Admin)</h1>
                <p class="text-xs text-gray-400 mt-1">Listahan ng lahat ng isinumiteng movie requests ng mga user.</p>
            </div>
            <a href="{{ route('home') }}" class="text-xs font-bold bg-neutral-800 hover:bg-neutral-700 text-white px-4 py-2 rounded-full border border-neutral-700 transition">
                ← Back to Home
            </a>
        </div>

        <!-- HISTORY TABLE -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden shadow-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-neutral-800/60 text-[11px] font-black uppercase text-gray-400 tracking-wider border-b border-neutral-800">
                        <th class="p-4">Title & Type</th>
                        <th class="p-4">Requested By</th>
                        <th class="p-4">Date & Time</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800 text-xs">
                    @forelse($requests as $req)
                        <tr class="hover:bg-neutral-800/40 transition">
                            <td class="p-4">
                                <span class="font-bold text-white block">{{ $req['title'] }}</span>
                                <span class="text-[10px] text-brand uppercase font-bold">{{ $req['type'] }}</span>
                            </td>
                            <td class="p-4 font-semibold text-emerald-400">
                                👤 {{ $req['requested_by'] ?? 'Guest User' }}
                            </td>
                            <td class="p-4 text-gray-400 font-mono">
                                📅 {{ $req['created_at'] ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="/admin/requests/approve/{{ $req['id'] }}" class="bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-600/40 px-3 py-1.5 rounded-lg text-[10px] font-bold transition">
                                        ✓ Approve
                                    </a>
                                    <a href="/admin/requests/delete/{{ $req['id'] }}" onclick="return confirm('Dismiss request?')" class="bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600/40 px-3 py-1.5 rounded-lg text-[10px] font-bold transition">
                                        ✕ Dismiss
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500 font-bold">
                                Walang anumang pending o pumasok na movie requests sa kasalukuyan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>