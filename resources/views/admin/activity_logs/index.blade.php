<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - Activity Logs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#000b1a] text-white antialiased">
    <!-- Navbar -->
    <x-admin.navbar active="activity_logs" />

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-8">Petugas <span class="text-gold">Activity Logs</span></h1>
            
            <div class="glass rounded-[2rem] overflow-hidden border border-white/10">
                <div class="p-8 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold">Recent <span class="text-gold">System Activities</span></h2>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">{{ $logs->total() }} TOTAL ENTRIES</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Time</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Petugas</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Action</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($logs as $log)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="px-8 py-6 text-xs text-gray-400 whitespace-nowrap">
                                    {{ $log->created_at->format('d M Y, H:i:s') }}
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-gold/10 flex items-center justify-center text-[10px] font-bold text-gold">
                                            {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold block text-sm">{{ $log->user->name }}</span>
                                            <span class="text-[9px] text-gray-500 uppercase">{{ $log->user->username }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $badgeColor = 'blue-400';
                                        if (Str::contains($log->action, 'Hapus')) $badgeColor = 'red-400';
                                        if (Str::contains($log->action, 'Tambah')) $badgeColor = 'emerald-400';
                                        if (Str::contains($log->action, 'Verifikasi')) $badgeColor = 'gold';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full bg-{{ $badgeColor }}/10 text-{{ $badgeColor }} text-[9px] font-black uppercase ring-1 ring-{{ $badgeColor }}/20">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-gray-400 text-xs max-w-md">
                                    {{ $log->details }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-gray-500">
                                    <i class="fas fa-history text-4xl mb-4 block opacity-20"></i>
                                    No activity logs found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($logs->hasPages())
                <div class="p-6 border-t border-white/5">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>

    <footer class="py-12 border-t border-white/10 px-6 mt-12 text-center text-gray-500 text-sm">
        <p>&copy; 2026 MINANGMART ADMIN. All rights reserved.</p>
    </footer>
</body>
</html>
