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
                <a href="{{ route('customer.home') }}" class="hover:text-gold transition font-bold">HOME</a>
                <a href="#menu" class="hover:text-gold transition font-bold">MENU</a>
                <a href="{{ route('customer.orders') }}" class="hover:text-gold transition font-bold">MY ORDERS</a>
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
        <img src="https://images.unsplash.com/photo-1600015835779-c6b36ddeac1f?qw=2000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Minang Cuisine Hero">
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
            @forelse(\App\Models\Product::withCount('favorites')->withAvg('reviews', 'rating')->latest()->get() as $product)
            <div class="group trending-card glass p-4 rounded-[2.5rem] border border-white/5 hover:border-gold/30 transition {{ $product->stock <= 0 ? 'opacity-60' : '' }}">
                <div class="relative overflow-hidden rounded-[2rem] aspect-square mb-6">

                    <!-- Stock Badge -->
                    <div class="absolute top-4 right-14 z-10">
                        @if($product->stock > 0)
                            <span class="px-3 py-1 rounded-full bg-emerald-500/90 text-white text-[8px] font-black uppercase tracking-widest shadow-lg backdrop-blur-sm">
                                {{ $product->stock }} Porsi
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-500/90 text-white text-[8px] font-black uppercase tracking-widest shadow-lg backdrop-blur-sm">
                                Habis
                            </span>
                        @endif
                    </div>
                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $product->name }}">
                    @if($product->stock <= 0)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="text-white font-black text-lg uppercase tracking-widest bg-red-500/80 px-6 py-2 rounded-full">Stok Habis</span>
                        </div>
                    @endif
                    <!-- Favorite Button -->
                    @php $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists(); @endphp
                    <button onclick="toggleFavorite({{ $product->id }})" class="absolute top-4 right-4 w-10 h-10 rounded-full glass border border-white/10 flex items-center justify-center {{ $isFavorited ? 'text-red-500' : 'text-white' }} hover:text-red-500 transition group/fav">
                        <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart transition-all duration-300"></i>
                        <span class="absolute -bottom-8 bg-gold text-navy text-[8px] font-black px-2 py-1 rounded hidden group-hover/fav:block">
                            {{ $product->favorites_count }} FAVORITES
                        </span>
                    </button>
                    <!-- Star Rating Display & Input -->
                    <div class="absolute bottom-4 left-4 glass px-3 py-1 rounded-full border border-white/10 flex items-center space-x-2 text-[10px] font-black group/rating">
                        <div class="flex items-center space-x-0.5 mr-1 interactive-stars" data-product-id="{{ $product->id }}">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star cursor-pointer transition-all duration-300 {{ round($product->reviews_avg_rating) >= $i ? 'text-gold' : 'text-gray-600' }} hover:scale-125" onclick="submitRating({{ $product->id }}, {{ $i }})"></i>
                            @endfor
                        </div>
                        <span class="text-gold avg-rating-{{ $product->id }}">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span>
                        <span class="text-gray-400 count-rating-{{ $product->id }}">({{ $product->reviews->count() }})</span>
                    </div>
                </div>
                <div class="px-2 pb-2">
                    <h3 class="font-bold text-xl mb-1">{{ $product->name }}</h3>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest leading-relaxed line-clamp-2 mb-4">{{ $product->description }}</p>
                    <div class="flex items-center justify-between mt-auto">
                        <p class="text-gold font-bold">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if($product->stock > 0)
                            <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')" class="bg-gold text-navy px-4 py-2 rounded-full text-[10px] font-black uppercase hover:bg-white hover:scale-105 transition flex items-center">
                                <i class="fas fa-cart-plus mr-2"></i> Add To Cart
                            </button>
                        @else
                            <button disabled class="bg-gray-700 text-gray-500 px-4 py-2 rounded-full text-[10px] font-black uppercase cursor-not-allowed flex items-center">
                                <i class="fas fa-ban mr-2"></i> Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center border-2 border-dashed border-white/10 rounded-[3rem]">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Belum Ada Menu</h3>
                <p class="text-gray-500 max-w-md">Saat ini belum ada produk/menu yang ditambahkan ke dalam sistem. Silakan minta Petugas untuk menambahkan menu makanan.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Toast Notification -->
    <div id="toast-notification" class="fixed bottom-8 right-8 z-50 transform translate-y-[200%] opacity-0 transition-all duration-500 pointer-events-none flex items-center gap-3 bg-[#0b1a33] border-l-4 border-gold text-white px-6 py-4 rounded-2xl shadow-[0_0_40px_rgba(212,175,55,0.15)]">
        <div class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center">
            <i class="fas fa-cart-shopping text-gold text-sm"></i>
        </div>
        <div>
            <p class="font-bold text-sm" id="toast-title">Sukses!</p>
            <p class="text-xs text-gray-400" id="toast-message">Item added to cart.</p>
        </div>
    </div>

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
            const btn = event.currentTarget;
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
                const icon = btn.querySelector('i');
                const span = btn.querySelector('span');
                
                if (data.status === 'added') {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    btn.classList.remove('text-white');
                    btn.classList.add('text-red-500');
                    showToast('Produk ditambahkan ke favorit! ❤️');
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    btn.classList.remove('text-red-500');
                    btn.classList.add('text-white');
                    showToast('Produk dihapus dari favorit.');
                }
                span.innerText = data.count + ' FAVORITES';
            })
            .catch(error => console.error('Error:', error));
        }

        function submitRating(productId, rating) {
            fetch("{{ route('review.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ 
                    product_id: productId,
                    rating: rating,
                    comment: "" // Default empty comment for quick rating
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the text display
                    document.querySelector(`.avg-rating-${productId}`).innerText = data.avg_rating;
                    document.querySelector(`.count-rating-${productId}`).innerText = `(${data.review_count})`;
                    
                    // Update the star visuals
                    const starsContainer = document.querySelector(`.interactive-stars[data-product-id="${productId}"]`);
                    const stars = starsContainer.querySelectorAll('i');
                    const roundedRating = Math.round(data.avg_rating);
                    
                    stars.forEach((star, index) => {
                        if (index < roundedRating) {
                            star.classList.remove('text-gray-600');
                            star.classList.add('text-gold');
                        } else {
                            star.classList.remove('text-gold');
                            star.classList.add('text-gray-600');
                        }
                    });

                    showToast('Rating berhasil disimpan! ⭐');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal memberikan rating.');
            });
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
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => { throw data; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-badge').innerText = data.cart_count;
                    showToast(name + ' telah ditambahkan ke keranjang!');
                }
            })
            .catch(error => {
                alert(error.message || 'Gagal menambahkan ke keranjang.');
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toast-notification');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-y-[200%]', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-[200%]', 'opacity-0');
            }, 3000);
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
