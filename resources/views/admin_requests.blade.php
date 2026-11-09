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
                        <th class="p-4">Tugon at Status (Admin Response)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800 text-xs">
                    @forelse($requests as $req)
                        @php
                            // Sinusuportahan pareho ang object o array syntax para sa data
                            $id = is_array($req) ? $req['id'] : $req->id;
                            $title = is_array($req) ? $req['title'] : $req->title;
                            $type = is_array($req) ? $req['type'] : $req->type;
                            $requestedBy = is_array($req) ? ($req['requested_by'] ?? 'Guest User') : ($req->requested_by ?? 'Guest User');
                            $createdAt = is_array($req) ? ($req['created_at'] ?? 'N/A') : ($req->created_at ?? 'N/A');
                            $status = is_array($req) ? ($req['status'] ?? 'pending') : ($req->status ?? 'pending');
                            $adminEmoji = is_array($req) ? ($req['admin_emoji'] ?? '') : ($req->admin_emoji ?? '');
                        @endphp
                        <tr class="hover:bg-neutral-800/40 transition align-top">
                            <td class="p-4">
                                <span class="font-bold text-white block text-sm">{{ $title }}</span>
                                <span class="text-[10px] text-brand uppercase font-bold">{{ $type }}</span>
                                <div class="mt-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                        {{ $status == 'approved' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}
                                        {{ $status == 'pending' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : '' }}
                                        {{ $status == 'completed' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : '' }}
                                        {{ $status == 'rejected' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}">
                                        Status: {{ ucfirst($status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 font-semibold text-emerald-400">
                                👤 {{ $requestedBy }}
                            </td>
                            <td class="p-4 text-gray-400 font-mono">
                                📅 {{ $createdAt }}
                            </td>
                            <td class="p-4">
                                <!-- ADMIN RESPONSE FORM -->
                                <form action="{{ route('admin.requests.respond', $id) }}" method="POST" class="bg-neutral-950/60 p-3 rounded-xl border border-neutral-800 space-y-2">
                                    @csrf
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <!-- Status Select -->
                                        <div>
                                            <label class="block text-[10px] text-gray-400 mb-1 font-bold">Baguhin ang Status:</label>
                                            <select name="status" class="w-full bg-neutral-900 border border-neutral-700 text-white text-xs rounded-lg px-2 py-1.5 focus:outline-none focus:border-brand">
                                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>

                                        <!-- Emoji Select -->
                                        <div>
                                            <label class="block text-[10px] text-gray-400 mb-1 font-bold">Admin Emoji:</label>
                                            <select name="admin_emoji" class="w-full bg-neutral-900 border border-neutral-700 text-white text-xs rounded-lg px-2 py-1.5 focus:outline-none focus:border-brand">
                                                <option value="">(Wala)</option>
                                                <option value="👍" {{ $adminEmoji == '👍' ? 'selected' : '' }}>👍 Approved</option>
                                                <option value="❤️" {{ $adminEmoji == '❤️' ? 'selected' : '' }}>❤️ Love it</option>
                                                <option value="🍿" {{ $adminEmoji == '🍿' ? 'selected' : '' }}>🍿 Available na</option>
                                                <option value="❌" {{ $adminEmoji == '❌' ? 'selected' : '' }}>❌ Rejected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Action Submit & Quick Delete -->
                                    <div class="flex justify-between items-center pt-1">
                                        <button type="submit" class="bg-brand hover:bg-brandDark text-neutral-950 font-bold px-3 py-1.5 rounded-lg text-xs transition">
                                            I-save ang Tugon
                                        </button>
                                        <a href="/admin/requests/delete/{{ $id }}" onclick="return confirm('Sigurado ka bang nais mong burahin ang request na ito?')" class="text-red-400 hover:text-red-300 text-[10px] font-bold transition">
                                            Huwag I-approve / Tanggalin
                                        </a>
                                    </div>
                                </form>
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