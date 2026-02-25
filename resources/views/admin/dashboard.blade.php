<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#000b1a] text-white antialiased">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass border-b border-white/10 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center font-bold text-[#000b1a]">
                    <i class="fas fa-crown"></i>
                </div>
                <span class="text-xl font-bold tracking-wider">ADMIN PANEL</span>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">
                <a href="{{ url('/') }}" class="hover:text-gold transition">VIEW SITE</a>
                <a href="#" class="text-gold border-b-2 border-gold pb-1 uppercase">DASHBOARD</a>
                <a href="#" class="hover:text-gold transition uppercase">REPORTS</a>
                <div class="flex items-center space-x-4 ml-4">
                    <div class="flex items-center space-x-2 glass px-4 py-2 rounded-full border border-white/10">
                        <i class="fas fa-user-shield text-gold"></i>
                        <span class="text-xs font-bold">ADMIN</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-gold transition"><i class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-8">System <span class="text-gold">Overview</span></h1>
            
            @php
                $allOrders = \App\Models\Order::with(['user', 'items'])->latest()->get();
                $totalRevenue = $allOrders->where('status', 'completed')->sum('total_price');
                $totalCustomers = \App\Models\User::role('customer')->count();
            @endphp
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="glass p-8 rounded-3xl border-l-4 border-gold">
                    <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Total Orders</div>
                    <div class="text-4xl font-bold">{{ number_format($allOrders->count()) }}</div>
                    <div class="mt-4 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-history mr-1"></i> All time global orders
                    </div>
                </div>
                <div class="glass p-8 rounded-3xl border-l-4 border-green-500">
                    <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Total Revenue (COD)</div>
                    <div class="text-4xl font-bold text-gold">Rp {{ number_format($totalRevenue / 1000000, 1) }}M</div>
                    <div class="mt-4 text-xs text-green-500 flex items-center">
                        <i class="fas fa-check-circle mr-1"></i> Completed transactions
                    </div>
                </div>
                <div class="glass p-8 rounded-3xl border-l-4 border-blue-500">
                    <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Active Customers</div>
                    <div class="text-4xl font-bold">{{ number_format($totalCustomers) }}</div>
                    <div class="mt-4 text-xs text-blue-400 flex items-center">
                        <i class="fas fa-users mr-1"></i> Registered customers
                    </div>
                </div>
            </div>

            <!-- Management Table -->
            <div class="glass rounded-[2rem] overflow-hidden border border-white/10">
                <div class="p-8 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold">Global <span class="text-gold">Transaction History</span></h2>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">{{ $allOrders->count() }} TOTAL RECORDS</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Order ID</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Customer</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Items</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Amount</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($allOrders as $order)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="px-8 py-6 text-sm font-bold text-gold">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-[10px] font-bold">
                                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold block text-sm">{{ $order->user->name }}</span>
                                            <span class="text-[9px] text-gray-500 uppercase">{{ $order->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-gray-400 text-xs">
                                    {{ $order->items->count() }} items
                                </td>
                                <td class="px-8 py-6 text-sm font-black whitespace-nowrap">
                                    IDR {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $colors = [
                                            'pending' => 'orange-400',
                                            'preparing' => 'blue-400',
                                            'delivering' => 'gold',
                                            'completed' => 'emerald-400'
                                        ];
                                        $color = $colors[$order->status] ?? 'gray-400';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full bg-{{ $color }}/10 text-{{ $color }} text-[9px] font-black uppercase ring-1 ring-{{ $color }}/20">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-12 border-t border-white/10 px-6 mt-12 text-center text-gray-500 text-sm">
        <p>&copy; 2026 MINANGMART ADMIN. All rights reserved.</p>
    </footer>
</body>
</html>
