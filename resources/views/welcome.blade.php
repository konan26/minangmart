<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - The Art of Minang Cuisine</title>
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
                <a href="#" class="gold-link">HOME</a>
                <a href="javascript:void(0)" onclick="handleGuestAction('Silakan login untuk melihat menu!')" class="hover:text-gold transition">MENU</a>
                <a href="javascript:void(0)" onclick="handleGuestAction('Silakan login untuk melakukan pemesanan!')" class="hover:text-gold transition">ORDER</a>
                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gold transition uppercase">ADMIN DASHBOARD</a>
                    @elseif(auth()->user()->hasRole('petugas'))
                        <a href="{{ route('petugas.dashboard') }}" class="hover:text-gold transition uppercase">DASHBOARD</a>
                    @else
                        <a href="{{ route('customer.home') }}" class="hover:text-gold transition uppercase">HOME</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-gold transition uppercase">LOGOUT</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-gold transition uppercase">LOGIN</a>
                    <a href="{{ route('register') }}" class="btn-gold uppercase">SIGN UP</a>
                @endauth
                <div class="flex items-center space-x-4 ml-4">
                    <a href="javascript:void(0)" onclick="handleGuestAction('Silakan login untuk mengakses keranjang!')"><i class="fas fa-shopping-cart text-lg hover:text-gold transition"></i></a>
                    <a href="javascript:void(0)" onclick="handleGuestAction('Silakan login untuk melihat profil!')"><i class="fas fa-user-circle text-xl hover:text-gold transition"></i></a>
                </div>
            </div>
        </div>
    </nav>



    <!-- Hero Section -->
    <header class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <img src="https://images.unsplash.com/photo-1600015835779-c6b36ddeac1f?qw=2000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Minang Cuisine Hero">
        <div class="absolute inset-0 hero-overlay"></div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-6xl md:text-8xl font-bold mb-6 tracking-tight leading-tight">
                The Art of <br> <span class="text-gold italic">Minang</span> Cuisine
            </h1>
            <p class="text-lg md:text-xl text-gray-300 mb-10 font-light leading-relaxed">
                Handpicked flavors for an exquisite dining experience. <br>
                Kelezatan tradisi dari Ranah Minang yang melegenda.
            </p>
            <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-6">
                <a href="javascript:void(0)" onclick="handleGuestAction('Silakan login untuk melihat menu favorit!')" class="btn-gold">Our Favorites</a>
                <a href="javascript:void(0)" onclick="handleGuestAction('Silakan login untuk memesan secara online!')" class="px-8 py-3 glass rounded-full font-bold text-lg border border-white/20 hover:bg-white/10 transition">Order Online</a>
            </div>
        </div>
    </header>

    <!-- Favorites / Lauk Tambahan Grid -->
    <section class="py-12 px-6 max-w-7xl mx-auto -mt-20 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div onclick="handleGuestAction('Silakan login untuk memesan Menu Favorit!')" class="glass p-8 rounded-3xl flex items-center justify-between group cursor-pointer hover:border-gold transition">
                <div>
                    <h3 class="text-2xl font-bold mb-1">MENU FAVORIT</h3>
                    <p class="text-gray-400 text-sm uppercase tracking-widest">Aneka Nasi Padang</p>
                </div>
                <img src="https://images.unsplash.com/photo-1610192244261-3f33de3f55e4?q=80&w=200&auto=format&fit=crop" class="w-24 h-24 rounded-full object-cover border-4 border-white/10 group-hover:border-gold transition" alt="Favorite">
            </div>
            <div onclick="handleGuestAction('Silakan login untuk memesan Lauk Tambahan!')" class="glass p-8 rounded-3xl flex items-center justify-between group cursor-pointer hover:border-gold transition">
                <div>
                    <h3 class="text-2xl font-bold mb-1">LAUK TAMBAHAN</h3>
                    <p class="text-gray-400 text-sm uppercase tracking-widest">Sate Padang & Gulai</p>
                </div>
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=200&auto=format&fit=crop" class="w-24 h-24 rounded-full object-cover border-4 border-white/10 group-hover:border-gold transition" alt="Additional">
            </div>
        </div>
    </section>

    <!-- Trending Section -->
    <section id="menu" class="py-24 px-6 max-w-7xl mx-auto text-white">
        <div class="flex items-center justify-between mb-16">
            <div>
                <h2 class="text-4xl font-bold mb-4">Trending this Week</h2>
                <div class="w-24 h-1 bg-gold"></div>
            </div>
            <a href="#" class="gold-link font-medium flex items-center">View All Menu <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
            @forelse($products as $product)
                <div class="group trending-card relative overflow-hidden glass p-4 rounded-[2.5rem] border border-white/5 hover:border-gold/30 transition">
                    <div class="relative overflow-hidden rounded-[2rem] aspect-square mb-6">
                        @if($loop->first)
                            <div class="absolute top-4 left-4 z-10 glass px-4 py-1.5 rounded-full text-[10px] font-bold tracking-widest uppercase border border-white/20">Trending</div>
                        @endif
                        
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $product->name }}">
                        
                        <!-- Favorite Button (Display Only or Prompt Login) -->
                        <button onclick="handleGuestAction()" class="absolute top-4 right-4 w-10 h-10 rounded-full glass border border-white/10 flex items-center justify-center text-white hover:text-red-500 transition group/fav">
                            <i class="fa-regular fa-heart transition-all duration-300"></i>
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
                        <p class="text-gold font-bold tracking-wide">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center glass rounded-3xl border border-white/10">
                    <p class="text-gray-500 uppercase tracking-widest text-xs font-bold">No products available at the moment.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Welcome Back Banner -->
    <section class="py-12 px-6">
        <div class="max-w-7xl mx-auto rounded-[3rem] p-12 md:p-20 flex flex-col md:flex-row items-center justify-between overflow-hidden relative border border-white/10 glass">
            <div class="relative z-10 md:w-3/5 mb-12 md:mb-0 text-center md:text-left">
                <h2 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">WELCOME <br>BACK <span class="text-gold font-light italic">Citarasa Minang</span></h2>
                <p class="text-gray-400 text-lg mb-10 max-w-md uppercase tracking-widest font-light">Kelezatan Tradisi dari Ranah Minang</p>
                <button onclick="handleGuestAction('Silakan login untuk mulai memesan!')" class="btn-gold px-12 py-4 text-lg">Order Now</button>
            </div>
            <div class="md:w-2/5 flex justify-center">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gold opacity-10 blur-3xl rounded-full"></div>
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1000&auto=format&fit=crop" class="relative z-10 w-80 h-80 md:w-[450px] md:h-[450px] object-cover rounded-full border-[12px] border-white/5" alt="Featured Plate">
                </div>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <footer class="py-20 border-t border-white/10 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-gray-500 text-sm">
            <div class="flex items-center space-x-3 mb-8 md:mb-0">
                <div class="w-8 h-8 bg-gold/20 rounded-full flex items-center justify-center font-bold text-gold">M</div>
                <span class="text-white font-bold tracking-widest">MINANGMART</span>
            </div>
            <p>&copy; 2026 MINANGMART. All rights reserved.</p>
            <div class="flex space-x-8 mt-8 md:mt-0">
                <a href="#" class="hover:text-gold transition"><i class="fab fa-instagram text-xl"></i></a>
                <a href="#" class="hover:text-gold transition"><i class="fab fa-twitter text-xl"></i></a>
                <a href="#" class="hover:text-gold transition"><i class="fab fa-facebook-f text-xl"></i></a>
            </div>
        </div>
    </footer>
    <!-- Toast Notification -->
    <div id="toast-notification" class="fixed bottom-8 right-8 z-50 transform translate-y-[200%] opacity-0 transition-all duration-500 pointer-events-none flex items-center gap-3 bg-[#0b1a33] border-l-4 border-gold text-white px-6 py-4 rounded-2xl shadow-[0_0_40px_rgba(212,175,55,0.15)]">
        <div class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center">
            <i class="fas fa-info-circle text-gold text-sm"></i>
        </div>
        <div>
            <p class="font-bold text-sm" id="toast-title">Perhatian!</p>
            <p class="text-xs text-gray-400" id="toast-message">Silakan login untuk melanjutkan.</p>
        </div>
    </div>

    <script>
        function handleGuestAction(message = 'Silakan Login/Daftar untuk memberikan Like atau Rating! 😊') {
            showToast(message);
            setTimeout(() => {
                window.location.href = "{{ route('login') }}";
            }, 2000);
        }

        function showToast(message) {
            const toast = document.getElementById('toast-notification');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-y-[200%]', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-[200%]', 'opacity-0');
            }, 3000);
        }
    </script>
</body>
</html>