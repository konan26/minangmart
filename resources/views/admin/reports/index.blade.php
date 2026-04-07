<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - Admin Reports</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .progress-bar { transition: width 1s ease-in-out; }
    </style>
</head>
<body class="bg-[#000b1a] text-white antialiased">
    <!-- Navbar -->
    <x-admin.navbar active="reports" />

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-black mb-1 tracking-tighter">Analytical <span class="text-gold">Insights</span></h1>
            <p class="text-gray-500 uppercase tracking-[0.3em] text-[10px] mb-8 font-bold">Business performance & data overview</p>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                <div class="glass p-8 rounded-[2rem] border border-white/5 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-gold/10 text-6xl group-hover:scale-110 transition duration-500">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <p class="text-gray-500 text-[9px] uppercase tracking-widest font-black mb-2">Total Gross Revenue</p>
                    <h3 class="text-3xl font-black text-gold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="mt-4 text-[9px] text-gray-500 font-bold uppercase">All Completed Transactions</p>
                </div>
                <div class="glass p-8 rounded-[2rem] border border-white/5 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-emerald-500/10 text-6xl group-hover:scale-110 transition duration-500">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <p class="text-gray-500 text-[9px] uppercase tracking-widest font-black mb-2">Net Product Sales</p>
                    <h3 class="text-3xl font-black text-white">Rp {{ number_format($netProductRevenue, 0, ',', '.') }}</h3>
                    <p class="mt-4 text-[9px] text-gray-500 font-bold uppercase">Excluding Delivery Fees</p>
                </div>
                <div class="glass p-8 rounded-[2rem] border border-white/5 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-blue-500/10 text-6xl group-hover:scale-110 transition duration-500">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <p class="text-gray-500 text-[9px] uppercase tracking-widest font-black mb-2">Shipping Earnings</p>
                    <h3 class="text-3xl font-black text-white">Rp {{ number_format($totalShipping, 0, ',', '.') }}</h3>
                    <p class="mt-4 text-[9px] text-gray-500 font-bold uppercase">Total Courier Commission</p>
                </div>
                <div class="glass p-8 rounded-[2rem] border border-white/5 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-purple-500/10 text-6xl group-hover:scale-110 transition duration-500">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <p class="text-gray-500 text-[9px] uppercase tracking-widest font-black mb-2">Completion Rate</p>
                    <h3 class="text-3xl font-black text-white">
                        @if($orderCount > 0)
                            {{ round(($completedCount / $orderCount) * 100) }}%
                        @else
                            0%
                        @endif
                    </h3>
                    <p class="mt-4 text-[9px] text-gray-500 font-bold uppercase">{{ $completedCount }} / {{ $orderCount }} Orders Processed</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Top Products -->
                <div class="md:col-span-2 glass rounded-[2.5rem] border border-white/5 overflow-hidden">
                    <div class="p-8 border-b border-white/5 flex items-center justify-between bg-white/[0.01]">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight"><span class="text-white">Top 5 Best</span> <span class="text-gold">Sellers</span></h2>
                            <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-1">Based on global order volume</p>
                        </div>
                        <i class="fas fa-medal text-gold"></i>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white/5">
                                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400"># RANK</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">PRODUCT NAME</th>
                                    <th class="px-8 py-5 text-right text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">UNIT SOLD</th>
                                    <th class="px-8 py-5 text-right text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">TOTAL REVENUE</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($topProducts as $index => $item)
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="px-8 py-6 text-sm font-black text-gold/50">{{ $index + 1 }}</td>
                                    <td class="px-8 py-6">
                                        <span class="font-bold text-sm block">{{ $item->product_name }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span class="bg-gold/10 text-gold px-3 py-1 rounded-full text-xs font-black">{{ $item->total_qty }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right font-black text-sm">
                                        Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-gray-500 text-sm italic">No product data available yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Distribution Stats -->
                <div class="space-y-8">
                    <!-- Courier Distribution -->
                    <div class="glass p-8 rounded-[2.5rem] border border-white/5">
                        <h3 class="text-lg font-black uppercase tracking-widest mb-6 flex items-center">
                            <i class="fas fa-shipping-fast mr-3 text-gold"></i> Service Type
                        </h3>
                        <div class="space-y-6">
                            @php $totalCouriers = $courierStats->sum('count'); @endphp
                            @foreach($courierStats as $stat)
                            @php $percentage = $totalCouriers > 0 ? ($stat->count / $totalCouriers) * 100 : 0; @endphp
                            <div>
                                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest mb-2">
                                    <span class="text-white">{{ $stat->courier_type }}</span>
                                    <span class="text-gold">{{ round($percentage) }}%</span>
                                </div>
                                <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gold rounded-full progress-bar" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                            @if($courierStats->isEmpty())
                                <p class="text-gray-600 text-xs text-center py-4 italic">No courier data used.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Petugas Performance (New) -->
                    <div class="glass p-8 rounded-[2.5rem] border border-white/5">
                        <h3 class="text-lg font-black uppercase tracking-widest mb-6 flex items-center">
                            <i class="fas fa-user-tie mr-3 text-gold"></i> Petugas Performance
                        </h3>
                        <div class="space-y-6">
                            @foreach($petugasStats as $stat)
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5">
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $stat->name }}</p>
                                    <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mt-1">{{ $stat->order_count }} Orders Handled</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gold font-black text-sm">Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                            @if($petugasStats->isEmpty())
                                <p class="text-gray-600 text-xs text-center py-4 italic">No performance data yet.</p>
                            @endif
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </main>

    <footer class="py-12 border-t border-white/10 px-6 mt-20 text-center">
        <p class="text-gray-500 uppercase tracking-widest text-[9px] font-black">&copy; 2026 MINANGMART SYSTEMS - ANALYTICS ENGINE</p>
    </footer>
</body>
</html>
