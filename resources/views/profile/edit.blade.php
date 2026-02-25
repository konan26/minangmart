<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - My Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .input-gold {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        .input-gold:focus {
            border-color: #d4af37;
            outline: none;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
        }
    </style>
</head>
<body class="bg-[#000b1a] text-white antialiased">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass border-b border-white/10 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center font-bold text-[#000b1a]">
                    <i class="fas fa-user"></i>
                </div>
                <span class="text-xl font-bold tracking-wider uppercase">Profile Settings</span>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">
                <a href="{{ route('customer.home') }}" class="hover:text-gold transition font-bold">BACK TO HOME</a>
                <a href="{{ route('customer.orders') }}" class="hover:text-gold transition font-bold">MY ORDERS</a>
            </div>
        </div>
    </nav>

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Sidebar Info -->
            <div class="lg:col-span-1 space-y-8">
                <div class="glass p-8 rounded-[2.5rem] border border-white/10 text-center">
                    <div class="w-24 h-24 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-gold/30">
                        <i class="fas fa-user text-4xl text-gold"></i>
                    </div>
                    <h2 class="text-2xl font-bold mb-1">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-400 text-sm mb-6">{{ auth()->user()->email }}</p>
                    <div class="w-full h-px bg-white/10 mb-6"></div>
                    <div class="text-left space-y-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 uppercase tracking-widest text-[10px] font-bold">Member Since</span>
                            <span class="text-white">{{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Summary -->
                <div class="glass p-8 rounded-[2.5rem] border border-white/10">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-truck-fast text-gold mr-3"></i> Shipment Status
                        </h3>
                        <a href="{{ route('customer.orders') }}" class="text-[10px] text-gold hover:underline uppercase tracking-widest font-bold">View All</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentOrders as $order)
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/5 group hover:border-gold/30 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-bold text-gray-400">Order #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    @if($order->status == 'pending')
                                        <span class="px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-400 text-[9px] font-bold uppercase">Pending</span>
                                    @elseif($order->status == 'preparing')
                                        <span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 text-[9px] font-bold uppercase">Dibuat</span>
                                    @elseif($order->status == 'delivering')
                                        <span class="px-2 py-0.5 rounded-full bg-gold/20 text-gold text-[9px] font-bold uppercase">Perjalanan</span>
                                    @elseif($order->status == 'completed')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[9px] font-bold uppercase">Diterima</span>
                                    @endif
                                </div>
                                <p class="text-sm truncate text-gray-300">
                                    {{ $order->items->first()->product_name }} 
                                    @if($order->items->count() > 1) 
                                        & {{ $order->items->count() - 1 }} others...
                                    @endif
                                </p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-receipt text-gray-700"></i>
                                </div>
                                <p class="text-xs text-gray-500 italic">No recent orders found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Forms -->
            <div class="lg:col-span-2 space-y-12">
                <!-- My Favorites -->
                <div class="glass p-10 rounded-[2.5rem] border border-white/10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-bold uppercase tracking-widest">My <span class="text-gold">Favorites</span></h3>
                        <span class="bg-gold/10 text-gold px-4 py-1 rounded-full text-[10px] font-black tracking-widest">{{ $favorites->count() }} SAVED</span>
                    </div>

                    @if($favorites->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($favorites as $favorite)
                                @php $product = $favorite->product; @endphp
                                <div id="fav-card-{{ $product->id }}" class="group relative bg-white/[0.03] border border-white/5 rounded-[2rem] p-4 hover:border-gold/30 transition duration-500">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0">
                                            <img src="{{ $product->image }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $product->name }}">
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-bold text-lg mb-1">{{ $product->name }}</h4>
                                            <p class="text-gold font-bold text-sm">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <button onclick="toggleFavorite({{ $product->id }})" class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                    <a href="{{ route('customer.home') }}#menu" class="absolute inset-0 z-0"></a>
                                    <div class="relative z-10"></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 border-2 border-dashed border-white/5 rounded-[2rem]">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="far fa-heart text-gray-700 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm italic">You haven't favorited any products yet.</p>
                            <a href="{{ route('customer.home') }}#menu" class="inline-block mt-4 text-gold text-[10px] font-black uppercase tracking-widest hover:underline">Explore Menu</a>
                        </div>
                    @endif
                </div>

                <!-- Profile Information -->
                <div class="glass p-10 rounded-[2.5rem] border border-white/10">
                    <h3 class="text-2xl font-bold mb-8 uppercase tracking-widest">Profile <span class="text-gold">Information</span></h3>
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="glass p-10 rounded-[2.5rem] border border-white/10">
                    <h3 class="text-2xl font-bold mb-8 uppercase tracking-widest">Security <span class="text-gold">Settings</span></h3>
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="glass p-10 rounded-[2.5rem] border border-white/10 border-red-500/20">
                    <h3 class="text-2xl font-bold mb-8 uppercase tracking-widest text-red-500">Advanced <span class="text-gray-400">Options</span></h3>
                    <div class="max-w-xl text-gray-400">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-12 border-t border-white/10 px-6 mt-12 text-center text-gray-500 text-sm">
        <p>&copy; 2026 MINANGMART. All rights reserved.</p>
    </footer>
    <script>
        function toggleFavorite(productId) {
            fetch("{{ route('favorite.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'removed') {
                    const card = document.getElementById('fav-card-' + productId);
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(() => card.remove(), 300);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
