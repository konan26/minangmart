<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - Customer Home</title>
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
                    <i class="fas fa-utensils"></i>
                </div>
                <span class="text-xl font-bold tracking-wider">MINANGMART</span>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">
                <a href="{{ url('/') }}" class="hover:text-gold transition font-bold">HOME</a>
                <a href="#menu" class="hover:text-gold transition font-bold">MENU</a>
                <a href="#" class="hover:text-gold transition font-bold">MY ORDERS</a>
                <a href="{{ route('profile.edit') }}" class="hover:text-gold transition uppercase font-bold">PROFILE</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gold transition uppercase font-bold">LOGOUT</button>
                </form>
                <div class="flex items-center space-x-4 ml-4">
                    <!-- Cart Indicator (Enhanced) -->
                    <div class="relative group cursor-pointer flex items-center space-x-2 bg-gold/10 px-4 py-2 rounded-full border border-gold/30 hover:bg-gold hover:text-navy transition shadow-lg shadow-gold/5" onclick="window.location.href='{{ route('cart.index') }}'">
                        <i class="fas fa-shopping-bag text-gold group-hover:text-navy transition"></i>
                        <span id="cart-count-wrapper" class="text-xs font-black uppercase tracking-tighter text-gold group-hover:text-navy transition">
                            <span id="cart-badge">0</span> ITEMS
                        </span>
                    </div>
                    <div class="flex items-center space-x-2 glass px-4 py-2 rounded-full border border-white/10 hover:border-gold/50 transition">
                        <i class="fas fa-user-circle text-gold"></i>
                        <span class="text-xs font-bold">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Simplified for logged in users) -->
    <header class="relative h-[50vh] flex items-center justify-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?q=80&w=2000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Minang Cuisine Hero">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="relative z-10 text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Welcome Back, <span class="text-gold italic">{{ explode(' ', auth()->user()->name)[0] }}</span></h1>
            <p class="text-gray-300 max-w-xl mx-auto font-light tracking-widest uppercase text-xs">The Best Minang Food Delivery in Town</p>
        </div>
    </header>

    <!-- Trending Section (Actual Content) -->
    <section id="menu" class="py-24 px-6 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-16">
            <div>
                <h2 class="text-4xl font-bold mb-4 uppercase tracking-tighter">Recommended <span class="text-gold">Items</span></h2>
                <div class="w-24 h-1 bg-gold"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
            @foreach(\App\Models\Product::withCount('favorites')->withAvg('reviews', 'rating')->latest()->get() as $product)
            <div class="group trending-card glass p-4 rounded-[2.5rem] border border-white/5 hover:border-gold/30 transition">
                <div class="relative overflow-hidden rounded-[2rem] aspect-square mb-6">
                    <div class="absolute top-4 left-4 z-10">
                        <span class="px-3 py-1 rounded-full bg-gold text-navy text-[8px] font-black uppercase tracking-widest shadow-lg">
                            {{ str_replace('_', ' ', $product->category ?? 'General') }}
                        </span>
                    </div>
                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $product->name }}">
                    <!-- Favorite Button -->
                    @php $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists(); @endphp
                    <button onclick="toggleFavorite({{ $product->id }})" class="absolute top-4 right-4 w-10 h-10 rounded-full glass border border-white/10 flex items-center justify-center {{ $isFavorited ? 'text-red-500' : 'text-white' }} hover:text-red-500 transition group/fav">
                        <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart transition-all duration-300"></i>
                        <span class="absolute -bottom-8 bg-gold text-navy text-[8px] font-black px-2 py-1 rounded hidden group-hover/fav:block">
                            {{ $product->favorites_count }} FAVORITES
                        </span>
                    </button>
                    <!-- Star Rating Display -->
                    <div class="absolute bottom-4 left-4 glass px-3 py-1 rounded-full border border-white/10 flex items-center space-x-2 text-[10px] font-black">
                        <i class="fas fa-star text-gold"></i>
                        <span class="text-gold">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span>
                        <span class="text-gray-400">({{ $product->reviews->count() }})</span>
                    </div>
                </div>
                <div class="px-2 pb-2">
                    <h3 class="font-bold text-xl mb-1">{{ $product->name }}</h3>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest leading-relaxed line-clamp-2 mb-4">{{ $product->description }}</p>
                    <div class="flex items-center justify-between mt-auto">
                        <p class="text-gold font-bold">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                        <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')" class="bg-gold text-navy px-4 py-2 rounded-full text-[10px] font-black uppercase hover:bg-white hover:scale-105 transition flex items-center">
                            <i class="fas fa-cart-plus mr-2"></i> Add To Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/10 px-6 mt-12">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-gray-500 text-sm">
            <p>&copy; 2026 MINANGMART. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-gold transition">Privacy Policy</a>
                <a href="#" class="hover:text-gold transition">Terms of Service</a>
            </div>
        </div>
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
                const btn = event.currentTarget;
                const icon = btn.querySelector('i');
                const span = btn.querySelector('span');
                
                if (data.status === 'added') {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    btn.classList.remove('text-white');
                    btn.classList.add('text-red-500');
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    btn.classList.remove('text-red-500');
                    btn.classList.add('text-white');
                }
                span.innerText = data.count + ' FAVORITES';
            })
            .catch(error => console.error('Error:', error));
        }

        function addToCart(id, name, price, image) {
            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id, name, price, image })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-badge').innerText = data.cart_count;
                    // Notification logic can be added here
                }
            })
            .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetch("{{ route('cart.count') }}")
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-badge').innerText = data.count || 0;
            });
        });
    </script>
</body>
</html>
