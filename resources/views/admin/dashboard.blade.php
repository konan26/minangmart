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
    <x-admin.navbar active="dashboard" />

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-8">System <span class="text-gold">Overview</span></h1>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="glass p-8 rounded-3xl border-l-4 border-gold">
                    <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Total Orders</div>
                    <div class="text-4xl font-bold">{{ number_format($totalOrdersCount) }}</div>
                    <div class="mt-4 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-history mr-1"></i> All time global orders
                    </div>
                </div>
                <div class="glass p-8 rounded-3xl border-l-4 border-green-500">
                    <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Total Revenue (QRIS)</div>
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
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Pembayaran</th>
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
                                <td class="px-8 py-6">
                                    @if($order->payment_status === 'awaiting_payment')
                                        <span class="px-3 py-1 rounded-full bg-orange-400/10 text-orange-400 text-[9px] font-black uppercase ring-1 ring-orange-400/20">Belum Bayar</span>
                                    @elseif($order->payment_status === 'verifying')
                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full bg-blue-400/10 text-blue-400 text-[9px] font-black uppercase ring-1 ring-blue-400/20">Verifikasi</span>
                                            @if($order->payment_receipt)
                                                <a href="{{ asset('storage/' . $order->payment_receipt) }}" target="_blank" class="text-blue-400 hover:text-white transition text-xs" title="Lihat Bukti">
                                                    <i class="fas fa-image"></i>
                                                </a>
                                                <form action="{{ route('orders.verifyPayment', $order->id) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-emerald-400 hover:text-white text-xs" title="Approve"><i class="fas fa-check"></i></button>
                                                </form>
                                                <form action="{{ route('orders.rejectPayment', $order->id) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-red-400 hover:text-white text-xs" title="Reject"><i class="fas fa-times"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    @elseif($order->payment_status === 'verified')
                                        <span class="px-3 py-1 rounded-full bg-emerald-400/10 text-emerald-400 text-[9px] font-black uppercase ring-1 ring-emerald-400/20">Lunas</span>
                                    @elseif($order->payment_status === 'invalid')
                                        <span class="px-3 py-1 rounded-full bg-red-400/10 text-red-400 text-[9px] font-black uppercase ring-1 ring-red-400/20">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($allOrders->hasPages())
                    <div class="p-6 border-t border-white/5">
                        {{ $allOrders->links() }}
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
