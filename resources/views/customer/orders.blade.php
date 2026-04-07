<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Minangmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #000b1a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .text-gold { color: #d4af37; }
        .bg-gold { background-color: #d4af37; }
        .status-badge { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; padding: 4px 12px; border-radius: 99px; }
    </style>
</head>
<body class="min-h-screen pb-20">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('customer.home') }}" class="text-2xl font-black tracking-tighter hover:scale-105 transition">
                MINANG<span class="text-gold">MART</span>
            </a>
            <div class="flex items-center space-x-6 text-sm font-bold tracking-widest">
                <a href="{{ route('customer.home') }}" class="hover:text-gold transition">HOME</a>
                <a href="{{ route('profile.edit') }}" class="hover:text-gold transition">PROFILE</a>
            </div>
        </div>
    </nav>

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-12">
                <h1 class="text-4xl font-bold">My <span class="text-gold">Orders</span></h1>
                <div class="glass px-6 py-2 rounded-full border border-white/10 flex items-center space-x-3">
                    <i class="fas fa-clock-rotate-left text-gold"></i>
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Riwayat Transaksi</span>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-100 px-6 py-4 rounded-2xl mb-12 flex items-center">
                <i class="fas fa-check-circle mr-4 text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <div class="space-y-8">
                @foreach($orders as $order)
                    <div class="glass rounded-[2rem] border border-white/5 overflow-hidden group hover:border-gold/20 transition duration-500">
                        <!-- Order Header -->
                        <div class="bg-white/5 px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center space-x-4 mb-1">
                                    <span class="font-black text-xl">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span id="badge-{{ $order->id }}" class="status-badge 
                                        {{ $order->status == 'pending' ? 'bg-orange-500/10 text-orange-400 border border-orange-500/30' : '' }}
                                        {{ $order->status == 'preparing' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/30' : '' }}
                                        {{ $order->status == 'delivering' ? 'bg-gold/10 text-gold border border-gold/30' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : '' }}
                                    ">
                                        @if($order->status == 'pending') PENDING
                                        @elseif($order->status == 'preparing') SEDANG DIBUAT
                                        @elseif($order->status == 'delivering') DALAM PERJALANAN
                                        @elseif($order->status == 'completed') DITERIMA
                                        @endif
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em]">{{ $order->created_at->format('M d, Y • H:i') }}</p>
                            </div>
                            <div class="flex items-center space-x-8">
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 uppercase tracking-widest">Total Price</p>
                                    <p class="font-black text-gold text-lg">IDR {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                                <!-- Payment Status Badge -->
                                <div class="text-right">
                                    @if($order->payment_status === 'awaiting_payment' && $order->status !== 'cancelled')
                                        <div class="flex flex-col gap-2">
                                            <a href="{{ route('orders.payment', $order->id) }}" class="inline-block px-4 py-2 rounded-full bg-orange-500/10 text-orange-400 text-[10px] font-black uppercase tracking-wider border border-orange-500/20 hover:bg-orange-500/20 transition text-center">
                                                <i class="fas fa-qrcode mr-1"></i> Bayar Sekarang
                                            </a>
                                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.')">
                                                @csrf
                                                <button type="submit" class="w-full inline-block px-4 py-2 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-wider border border-red-500/20 hover:bg-red-500/20 transition text-center">
                                                    <i class="fas fa-times mr-1"></i> Batalkan
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($order->payment_status === 'verifying')
                                        <span class="inline-block px-4 py-2 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-wider border border-blue-500/20">
                                            <i class="fas fa-hourglass-half mr-1"></i> Diverifikasi
                                        </span>
                                    @elseif($order->payment_status === 'verified')
                                        <span class="inline-block px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-wider border border-emerald-500/20">
                                            <i class="fas fa-check-circle mr-1"></i> Lunas
                                        </span>
                                    @elseif($order->payment_status === 'invalid')
                                        <a href="{{ route('orders.payment', $order->id) }}" class="inline-block px-4 py-2 rounded-full bg-red-500/10 text-red-400 text-[10px] font-black uppercase tracking-wider border border-red-500/20 hover:bg-red-500/20 transition">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Upload Ulang
                                        </a>
                                    @elseif($order->status === 'cancelled')
                                        <span class="inline-block px-4 py-2 rounded-full bg-gray-500/10 text-gray-500 text-[10px] font-black uppercase tracking-wider border border-gray-500/20">
                                            <i class="fas fa-ban mr-1"></i> Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Progress Tracker -->
                        <div class="px-8 py-10 border-b border-white/5">
                            <div class="relative flex items-center justify-between max-w-2xl mx-auto">
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-white/10 -z-10"></div>
                                <div id="progress-{{ $order->id }}" class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-gold transition-all duration-1000 -z-10" 
                                     style="width: {{ $order->status == 'pending' ? '0' : ($order->status == 'preparing' ? '33.3%' : ($order->status == 'delivering' ? '66.6%' : '100%')) }}"></div>
                                
                                <div class="flex flex-col items-center">
                                    <div id="step-1-{{ $order->id }}" class="w-10 h-10 rounded-full flex items-center justify-center border-2 {{ $order->status != 'none' ? 'bg-gold border-gold text-navy' : 'bg-[#000b1a] border-white/10 text-gray-700' }}">
                                        <i class="fas fa-receipt text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-black uppercase mt-3 tracking-widest {{ $order->status != 'none' ? 'text-gold' : 'text-gray-600' }}">Pending</span>
                                    <span class="text-[8px] text-gray-500 font-bold mt-1">{{ $order->created_at->format('H:i') }}</span>
                                </div>

                                <div class="flex flex-col items-center">
                                    <div id="step-2-{{ $order->id }}" class="w-10 h-10 rounded-full flex items-center justify-center border-2 {{ in_array($order->status, ['preparing', 'delivering', 'completed']) ? 'bg-gold border-gold text-navy' : 'bg-[#000b1a] border-white/10 text-gray-700' }}">
                                        <i class="fas fa-fire-burner text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-black uppercase mt-3 tracking-widest {{ in_array($order->status, ['preparing', 'delivering', 'completed']) ? 'text-gold' : 'text-gray-600' }}">Dibuat</span>
                                    <span id="time-preparing-{{ $order->id }}" class="text-[8px] text-gray-500 font-bold mt-1">{{ $order->preparing_at ? $order->preparing_at->format('H:i') : '--:--' }}</span>
                                </div>

                                <div class="flex flex-col items-center">
                                    <div id="step-3-{{ $order->id }}" class="w-10 h-10 rounded-full flex items-center justify-center border-2 {{ in_array($order->status, ['delivering', 'completed']) ? 'bg-gold border-gold text-navy' : 'bg-[#000b1a] border-white/10 text-gray-700' }}">
                                        <i class="fas fa-truck-fast text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-black uppercase mt-3 tracking-widest {{ in_array($order->status, ['delivering', 'completed']) ? 'text-gold' : 'text-gray-600' }}">Perjalanan</span>
                                    <span id="time-delivering-{{ $order->id }}" class="text-[8px] text-gray-500 font-bold mt-1">{{ $order->delivering_at ? $order->delivering_at->format('H:i') : '--:--' }}</span>
                                </div>

                                <div class="flex flex-col items-center">
                                    <div id="step-4-{{ $order->id }}" class="w-10 h-10 rounded-full flex items-center justify-center border-2 {{ $order->status == 'completed' ? 'bg-gold border-gold text-navy' : 'bg-[#000b1a] border-white/10 text-gray-700' }}">
                                        <i class="fas fa-house-circle-check text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-black uppercase mt-3 tracking-widest {{ $order->status == 'completed' ? 'text-gold' : 'text-gray-600' }}">Diterima</span>
                                    <span id="time-completed-{{ $order->id }}" class="text-[8px] text-gray-500 font-bold mt-1">{{ $order->completed_at ? $order->completed_at->format('H:i') : '--:--' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Items List -->
                        @if($order->notes)
                            <div class="px-8 py-4 bg-orange-500/5 border-b border-white/5 flex items-start space-x-4">
                                <i class="fas fa-sticky-note text-orange-400 mt-1 text-xs"></i>
                                <div>
                                    <p class="text-[9px] font-black uppercase text-orange-400 tracking-widest mb-1">Your Instructions:</p>
                                    <p class="text-xs text-gray-400 font-medium whitespace-pre-wrap italic leading-relaxed">"{{ $order->notes }}"</p>
                                </div>
                            </div>
                        @endif
                        <div class="px-8 py-6 space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-8 h-8 rounded-lg overflow-hidden flex-shrink-0">
                                            <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}" class="w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition" alt="{{ $item->product_name }}">
                                        </div>
                                        <span class="font-bold text-gray-300 group-hover:text-white transition">{{ $item->product_name }}</span>
                                        <span class="text-gray-600">x{{ $item->quantity }}</span>
                                    </div>
                                    <span class="text-gray-500">IDR {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="glass p-20 rounded-[3rem] border border-white/5 border-dashed text-center">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-receipt text-gray-700 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">No Orders Yet</h2>
                <p class="text-gray-500">You haven't placed any orders yet. Time for some Rendang?</p>
            </div>
        @endif
    </main>
    <script>
        function toggleRatingForm(itemId) {
            const form = document.getElementById('rating-form-' + itemId);
            form.classList.toggle('hidden');
        }

        function setRating(itemId, value) {
            document.getElementById('rating-input-' + itemId).value = value;
            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById('star-' + itemId + '-' + i);
                if (i <= value) {
                    star.classList.remove('text-gray-700');
                    star.classList.add('text-gold');
                } else {
                    star.classList.remove('text-gold');
                    star.classList.add('text-gray-700');
                }
            }
        }

        // Real-time Sync Logic
        function syncOrderStatuses() {
            fetch("{{ route('api.orders.statuses') }}")
            .then(response => response.json())
            .then(data => {
                data.orders.forEach(order => {
                    const statusBadge = document.getElementById(`badge-${order.id}`);
                    const progressBar = document.getElementById(`progress-${order.id}`);
                    
                    if (statusBadge && progressBar) {
                        // Update Badge
                        const statusLabels = {
                            'pending': 'PENDING',
                            'preparing': 'SEDANG DIBUAT',
                            'delivering': 'DALAM PERJALANAN',
                            'completed': 'DITERIMA'
                        };
                        const statusClasses = {
                            'pending': 'bg-orange-500/10 text-orange-400 border border-orange-500/30',
                            'preparing': 'bg-blue-500/10 text-blue-400 border border-blue-500/30',
                            'delivering': 'bg-gold/10 text-gold border border-gold/30',
                            'completed': 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30'
                        };

                        statusBadge.innerText = statusLabels[order.status];
                        statusBadge.className = 'status-badge ' + statusClasses[order.status];

                        // Update Progress Bar Width
                        const widths = { 'pending': '0', 'preparing': '33.3%', 'delivering': '66.6%', 'completed': '100%' };
                        progressBar.style.width = widths[order.status];

                        // Update Step Icons
                        const activeIcon = 'bg-gold border-gold text-navy';
                        const inactiveIcon = 'bg-[#000b1a] border-white/10 text-gray-700';

                        document.getElementById(`step-1-${order.id}`).className = `w-10 h-10 rounded-full flex items-center justify-center border-2 ${activeIcon}`;
                        document.getElementById(`step-2-${order.id}`).className = `w-10 h-10 rounded-full flex items-center justify-center border-2 ${['preparing', 'delivering', 'completed'].includes(order.status) ? activeIcon : inactiveIcon}`;
                        document.getElementById(`step-3-${order.id}`).className = `w-10 h-10 rounded-full flex items-center justify-center border-2 ${['delivering', 'completed'].includes(order.status) ? activeIcon : inactiveIcon}`;
                        document.getElementById(`step-4-${order.id}`).className = `w-10 h-10 rounded-full flex items-center justify-center border-2 ${order.status === 'completed' ? activeIcon : inactiveIcon}`;

                        // Update Timestamps
                        if (order.preparing_time) document.getElementById(`time-preparing-${order.id}`).innerText = order.preparing_time;
                        if (order.delivering_time) document.getElementById(`time-delivering-${order.id}`).innerText = order.delivering_time;
                        if (order.completed_time) document.getElementById(`time-completed-${order.id}`).innerText = order.completed_time;
                    }
                });
            })
            .catch(err => console.error('Sync Error:', err));
        }

        // Start polling every 5 seconds
        setInterval(syncOrderStatuses, 5000);
    </script>
</body>
</html>
