<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - Petugas Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .swipe-track {
            position: relative;
            height: 54px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 27px;
            padding: 4px;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }

        .swipe-label {
            position: absolute;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, 0.3);
            pointer-events: none;
            transition: opacity 0.3s;
            text-transform: uppercase;
        }

        .swipe-handle {
            position: absolute;
            left: 4px;
            top: 4px;
            bottom: 4px;
            aspect-ratio: 1/1;
            background: #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000b1a;
            cursor: grab;
            z-index: 10;
            transition: transform 0.1s ease-out, background 0.3s;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .swipe-handle:active { cursor: grabbing; }

        .swipe-success {
            background: #10b981 !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3) !important;
        }
        
        .swipe-bg-fill {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            background: rgba(212, 175, 55, 0.1);
            width: 0%;
            border-radius: 27px;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-[#000b1a] text-white antialiased">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass border-b border-white/10 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center font-bold text-[#000b1a]">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <span class="text-xl font-bold tracking-wider">OFFICER DASHBOARD</span>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">
                <a href="#" onclick="switchTab('active')" id="nav-active" class="text-gold border-b-2 border-gold pb-1 uppercase font-bold">ACTIVE ORDERS</a>
                <a href="#" onclick="switchTab('history')" id="nav-history" class="hover:text-gold transition uppercase font-bold text-gray-500">HISTORY</a>
                <a href="#" onclick="switchTab('products')" id="nav-products" class="hover:text-gold transition uppercase font-bold text-gray-500">MANAGE PRODUCTS</a>
                <a href="#" onclick="switchTab('reviews')" id="nav-reviews" class="hover:text-gold transition uppercase font-bold text-gray-500">CUSTOMER REVIEWS</a>
                <div class="flex items-center space-x-4 ml-4">
                    <div class="flex items-center space-x-2 glass px-4 py-2 rounded-full border border-white/10">
                        <i class="fas fa-user-tag text-gold"></i>
                        <span class="text-xs font-bold">{{ auth()->user()->name }}</span>
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
            <h1 class="text-4xl font-bold mb-8">Service <span class="text-gold">Operations</span></h1>
            
            <!-- Operational Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="glass p-8 rounded-3xl border-l-4 border-orange-500 hover:border-gold transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Active Orders</div>
                            <div class="text-5xl font-bold">{{ $orders->whereIn('status', ['pending', 'preparing', 'delivering'])->count() }}</div>
                        </div>
                        <i class="fas fa-hourglass-half text-4xl text-orange-500/50"></i>
                    </div>
                </div>
                <div class="glass p-8 rounded-3xl border-l-4 border-green-500 hover:border-gold transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Completed All Time</div>
                            <div class="text-5xl font-bold text-gold">{{ $orders->where('status', 'completed')->count() }}</div>
                        </div>
                        <i class="fas fa-check-circle text-4xl text-green-500/50"></i>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div id="active-orders-section" class="glass rounded-[2rem] overflow-hidden border border-white/10 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold">Active <span class="text-gold">Operations</span></h2>
                    <span class="px-4 py-1 rounded-full bg-orange-500/10 text-orange-400 text-[10px] font-black tracking-widest ring-1 ring-orange-500/20">
                        {{ $orders->whereIn('status', ['pending', 'preparing', 'delivering'])->count() }} PENDING
                    </span>
                </div>
                
                @if(session('success'))
                    <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-100 px-6 py-4 rounded-2xl mb-8">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-500/20 border border-red-500 text-red-100 px-6 py-4 rounded-2xl mb-8">
                        <ul class="list-disc list-inside text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    @forelse($orders as $order)
                        <div class="flex flex-col lg:flex-row items-start justify-between p-6 glass rounded-2xl border border-white/5 hover:border-gold/30 transition group">
                            <div class="mb-4 lg:mb-0 w-full lg:w-2/3">
                                <div class="flex items-center space-x-2 mb-2 flex-wrap gap-y-2">
                                    <h4 class="font-bold text-xl text-gold group-hover:text-white transition">Order #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h4>
                                    <span class="px-2 py-0.5 rounded-full bg-gold/10 text-gold text-[10px] font-bold uppercase">{{ $order->status }}</span>
                                    {{-- Payment Status Badge --}}
                                    @if($order->payment_status === 'awaiting_payment')
                                        <span class="px-2 py-0.5 rounded-full bg-orange-500/10 text-orange-400 text-[10px] font-bold uppercase border border-orange-500/20">
                                            <i class="fas fa-clock mr-1"></i>Belum Bayar
                                        </span>
                                    @elseif($order->payment_status === 'verifying')
                                        <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-bold uppercase border border-blue-500/20">
                                            <i class="fas fa-search mr-1"></i>Perlu Verifikasi
                                        </span>
                                    @elseif($order->payment_status === 'verified')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase border border-emerald-500/20">
                                            <i class="fas fa-check mr-1"></i>Lunas
                                        </span>
                                    @elseif($order->payment_status === 'invalid')
                                        <span class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 text-[10px] font-bold uppercase border border-red-500/20">
                                            <i class="fas fa-times mr-1"></i>Ditolak
                                        </span>
                                    @endif
                                </div>
                                <p class="text-white text-sm mb-1 font-bold">Customer: <span class="text-gray-300 font-normal">{{ $order->user->name }}</span></p>
                                <p class="text-white text-sm mb-1 font-bold">Address: <span class="text-gray-400 font-normal italic">{{ $order->address }}</span></p>
                                
                                @if($order->notes)
                                    <div class="mt-4 p-4 bg-orange-500/5 border border-orange-500/20 rounded-xl">
                                        <p class="text-[10px] text-orange-400 font-black uppercase tracking-widest mb-1">Customer Notes:</p>
                                        <p class="text-sm text-gray-300 italic">"{{ $order->notes }}"</p>
                                    </div>
                                @endif

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($order->items as $item)
                                        <span class="text-[10px] bg-white/5 px-2 py-1 rounded border border-white/5 font-bold">{{ $item->quantity }}x {{ $item->product_name }}</span>
                                    @endforeach
                                </div>

                                {{-- Payment Verification Actions --}}
                                @if($order->payment_status === 'verifying' && $order->payment_receipt)
                                    <div class="mt-4 p-4 bg-blue-500/5 border border-blue-500/20 rounded-xl">
                                        <p class="text-[10px] text-blue-400 font-black uppercase tracking-widest mb-3">Bukti Pembayaran:</p>
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <button onclick="openReceiptModal('{{ asset('storage/' . $order->payment_receipt) }}')" class="px-4 py-2 bg-white/5 rounded-xl text-xs font-bold text-white hover:bg-white/10 transition border border-white/10">
                                                <i class="fas fa-image mr-2"></i>Lihat Bukti
                                            </button>
                                            <form action="{{ route('orders.verifyPayment', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve pembayaran ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-emerald-500/20 rounded-xl text-xs font-bold text-emerald-400 hover:bg-emerald-500/30 transition border border-emerald-500/30">
                                                    <i class="fas fa-check mr-2"></i>Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('orders.rejectPayment', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak bukti pembayaran ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-red-500/20 rounded-xl text-xs font-bold text-red-400 hover:bg-red-500/30 transition border border-red-500/30">
                                                    <i class="fas fa-times mr-2"></i>Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="w-full lg:w-1/3 flex flex-col items-end gap-4">
                                @if($order->status != 'completed')
                                    @if($order->payment_status === 'verified')
                                        @php
                                            $nextStatus = [
                                                'pending' => 'preparing',
                                                'preparing' => 'delivering',
                                                'delivering' => 'completed'
                                            ];
                                            $labels = [
                                                'pending' => 'Swipe to Prepare',
                                                'preparing' => 'Swipe to Deliver',
                                                'delivering' => 'Swipe to Complete'
                                            ];
                                            $currentNext = $nextStatus[$order->status];
                                        @endphp
                                        <div class="w-full max-w-[250px]">
                                            <div class="swipe-track" id="track-{{ $order->id }}">
                                                <div class="swipe-bg-fill" id="fill-{{ $order->id }}"></div>
                                                <span class="swipe-label" id="label-{{ $order->id }}">{{ $labels[$order->status] }}</span>
                                                <div class="swipe-handle" 
                                                     id="handle-{{ $order->id }}" 
                                                     onmousedown="initSwipe(event, {{ $order->id }}, '{{ $currentNext }}')"
                                                     ontouchstart="initSwipe(event, {{ $order->id }}, '{{ $currentNext }}')">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full max-w-[250px] text-center">
                                            <div class="px-4 py-3 bg-white/[0.03] rounded-full border border-white/10 text-gray-600 text-[10px] font-bold uppercase tracking-widest">
                                                <i class="fas fa-lock mr-2"></i>Menunggu Pembayaran
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 uppercase tracking-widest">Order Total</p>
                                    <p class="text-lg font-black text-gold">IDR {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-4xl text-gray-700 mb-4 block"></i>
                            <p class="text-gray-500 italic">No active orders found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- History Section (Hidden by default) -->
            <div id="history-orders-section" class="glass rounded-[2rem] overflow-hidden border border-white/10 p-8 hidden">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold">Order <span class="text-gold">History</span></h2>
                    <span class="px-4 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black tracking-widest ring-1 ring-emerald-500/20">
                        {{ $orders->where('status', 'completed')->count() }} COMPLETED
                    </span>
                </div>

                <div class="space-y-6">
                    @forelse($orders->where('status', 'completed') as $order)
                        <div class="flex flex-col lg:flex-row items-center justify-between p-6 bg-white/[0.02] rounded-2xl border border-white/5 opacity-80">
                            <div class="mb-4 lg:mb-0 w-full lg:w-2/3">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-bold text-xl text-gray-400">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h4>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[9px] font-bold uppercase">Completed</span>
                                </div>
                                <p class="text-white text-sm mb-1 font-bold">Customer: <span class="text-gray-300 font-normal">{{ $order->user->name }}</span></p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($order->items as $item)
                                        <span class="text-[9px] bg-white/5 px-2 py-0.5 rounded border border-white/5 text-gray-500">{{ $item->quantity }}x {{ $item->product_name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="w-full lg:w-1/3 text-right">
                                <p class="text-xs text-gray-600 uppercase tracking-widest mb-1">Total Paid</p>
                                <p class="text-lg font-black text-gray-400">IDR {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-history text-4xl text-gray-700 mb-4 block"></i>
                            <p class="text-gray-500 italic">No completed orders yet.</p>
                        </div>
                    @endforelse
                </div>
                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-8 pt-6 border-t border-white/5">
                        {{ $orders->appends(['reviews_page' => request('reviews_page')])->links() }}
                    </div>
                @endif
            </div>

            <!-- Products Section -->
            <div id="products-section" class="glass rounded-[2rem] overflow-hidden border border-white/10 p-8 hidden">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold">Manage <span class="text-gold">Products</span></h2>
                    <button onclick="openModal('addProductModal')" class="bg-gold text-[#000b1a] px-6 py-2 rounded-full text-xs font-black hover:bg-white transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> ADD PRODUCT
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-gold/30 transition group relative">
                            <div class="relative h-48 mb-4 rounded-2xl overflow-hidden shadow-lg">
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                                    <button onclick="openEditModal({{ $product }})" class="w-10 h-10 bg-white text-navy rounded-full flex items-center justify-center hover:bg-gold transition shadow-xl">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('petugas.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow-xl">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <h3 class="font-bold text-white text-lg mb-1">{{ $product->name }}</h3>
                            <p class="text-gold font-black mb-2">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-2 mb-3">
                                @if($product->stock > 0)
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black border border-emerald-500/20">
                                        <i class="fas fa-box mr-1"></i>{{ $product->stock }} Porsi
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-400 text-[10px] font-black border border-red-500/20">
                                        <i class="fas fa-ban mr-1"></i>Stok Habis
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-400 text-xs leading-relaxed line-clamp-2 italic">"{{ $product->description }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviews Section -->
            <div id="reviews-section" class="glass rounded-[2rem] overflow-hidden border border-white/10 p-8 hidden">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold">Customer <span class="text-gold">Reviews</span></h2>
                    <span class="px-4 py-1 rounded-full bg-gold/10 text-gold text-[10px] font-black tracking-widest ring-1 ring-gold/20">
                        {{ $reviews->count() }} FEEDBACKS
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    @forelse($reviews as $review)
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-white/[0.02] rounded-2xl border border-white/5 hover:border-gold/20 transition">
                            <div class="w-16 h-16 rounded-2xl bg-gold/10 flex items-center justify-center text-gold text-2xl font-black shrink-0 border border-gold/20">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-black text-white text-lg">{{ $review->user->name }}</h4>
                                        <p class="text-xs text-gray-500">reviewing <span class="text-gold font-bold">{{ $review->product->name }}</span></p>
                                    </div>
                                    <div class="flex gap-1 bg-black/40 px-3 py-1.5 rounded-full border border-white/5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-[10px] {{ $i <= $review->rating ? 'text-gold' : 'text-gray-700' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="bg-white/[0.03] p-4 rounded-xl border border-white/5 italic text-gray-300 text-sm">
                                    "{{ $review->comment }}"
                                </div>
                                <p class="text-[10px] text-gray-600 mt-4 uppercase tracking-[0.2em] font-bold">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20">
                            <i class="fas fa-comment-slash text-5xl text-gray-800 mb-4 block"></i>
                            <p class="text-gray-500 italic">No customer reviews yet.</p>
                        </div>
                    @endforelse
                </div>
                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="mt-8 pt-6 border-t border-white/5">
                        {{ $reviews->appends(['orders_page' => request('orders_page')])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div class="bg-[#0b1a33] border border-white/10 rounded-[3rem] p-8 max-w-md w-full relative shadow-2xl">
            <button onclick="closeModal('addProductModal')" class="absolute top-6 right-6 text-gray-500 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                <i class="fas fa-plus-circle text-gold"></i> ADD NEW PRODUCT
            </h3>
            <form action="{{ route('petugas.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Product Name</label>
                    <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition placeholder-gray-700" placeholder="e.g. Sate Padang">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Price (IDR)</label>
                        <input type="number" name="price" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition placeholder-gray-700" placeholder="e.g. 25000">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Stock (Porsi)</label>
                        <input type="number" name="stock" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition placeholder-gray-700" placeholder="e.g. 50">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Description</label>
                    <textarea name="description" required rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition placeholder-gray-700" placeholder="Describe the item..."></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Product Image</label>
                    <div class="relative group mt-1">
                        <input type="file" name="image" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-gold file:text-navy hover:file:bg-white transition cursor-pointer">
                    </div>
                </div>
                <button type="submit" class="w-full bg-gold text-navy font-black py-4 rounded-2xl hover:bg-white transition mt-4 shadow-lg shadow-gold/20">
                    SAVE PRODUCT
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div class="bg-[#0b1a33] border border-white/10 rounded-[3rem] p-8 max-w-md w-full relative shadow-2xl">
            <button onclick="closeModal('editProductModal')" class="absolute top-6 right-6 text-gray-500 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                <i class="fas fa-edit text-gold"></i> EDIT PRODUCT
            </h3>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Product Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Price (IDR)</label>
                        <input type="number" name="price" id="edit_price" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Stock (Porsi)</label>
                        <input type="number" name="stock" id="edit_stock" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Description</label>
                    <textarea name="description" id="edit_description" required rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold outline-none transition"></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 block">Change Image (Optional)</label>
                    <input type="file" name="image" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-gold file:text-navy hover:file:bg-white transition cursor-pointer">
                </div>
                <button type="submit" class="w-full bg-gold text-navy font-black py-4 rounded-2xl hover:bg-white transition mt-4 shadow-lg shadow-gold/20">
                    UPDATE PRODUCT
                </button>
            </form>
        </div>
    </div>

    <!-- Receipt Preview Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div class="bg-[#0b1a33] border border-white/10 rounded-[2rem] p-6 max-w-lg w-full relative shadow-2xl">
            <button onclick="closeReceiptModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition text-xl">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-xl font-black text-white mb-4 flex items-center gap-3">
                <i class="fas fa-receipt text-gold"></i> Bukti Pembayaran
            </h3>
            <div class="rounded-2xl overflow-hidden border border-white/10">
                <img id="receiptImage" src="" class="w-full object-contain max-h-[60vh]" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>

    <footer class="py-12 border-t border-white/10 px-6 mt-12 text-center text-gray-500 text-sm">
        <p>&copy; 2026 MINANGMART OFFICER. All rights reserved.</p>
    </footer>
    <script>
        let isDragging = false;
        let currentOrderId = null;
        let currentTargetStatus = '';
        let startX = 0;
        let currentX = 0;
        let maxDelta = 0;

        function initSwipe(e, orderId, nextStatus) {
            isDragging = true;
            currentOrderId = orderId;
            currentTargetStatus = nextStatus;
            startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
            const track = document.getElementById('track-' + orderId);
            const handle = document.getElementById('handle-' + orderId);
            maxDelta = track.offsetWidth - handle.offsetWidth - 8; // 4px padding each side

            document.addEventListener('mousemove', handleSwipe);
            document.addEventListener('mouseup', endSwipe);
            document.addEventListener('touchmove', handleSwipe, { passive: false });
            document.addEventListener('touchend', endSwipe);
        }

        function handleSwipe(e) {
            if (!isDragging) return;
            if (e.type === 'touchmove') e.preventDefault();
            
            const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
            let delta = clientX - startX;
            delta = Math.max(0, Math.min(delta, maxDelta));
            
            const handle = document.getElementById('handle-' + currentOrderId);
            const fill = document.getElementById('fill-' + currentOrderId);
            const label = document.getElementById('label-' + currentOrderId);
            
            handle.style.transform = `translateX(${delta}px)`;
            fill.style.width = `${(delta / maxDelta) * 100}%`;
            label.style.opacity = 1 - (delta / maxDelta) * 1.5;

            if (delta >= maxDelta * 0.95) {
                handle.classList.add('swipe-success');
            } else {
                handle.classList.remove('swipe-success');
            }
        }

        function endSwipe() {
            if (!isDragging) return;
            isDragging = false;

            const handle = document.getElementById('handle-' + currentOrderId);
            const fill = document.getElementById('fill-' + currentOrderId);
            const label = document.getElementById('label-' + currentOrderId);
            const track = document.getElementById('track-' + currentOrderId);

            const style = window.getComputedStyle(handle);
            const matrix = new WebKitCSSMatrix(style.transform);
            const currentDelta = matrix.m41;

            if (currentDelta >= maxDelta * 0.9) {
                // Success - Complete Swipe
                handle.style.transform = `translateX(${maxDelta}px)`;
                fill.style.width = '100%';
                updateOrderStatus(currentOrderId, currentTargetStatus);
            } else {
                // Fail - Snap back
                handle.style.transform = 'translateX(0)';
                fill.style.width = '0%';
                label.style.opacity = 1;
            }

            document.removeEventListener('mousemove', handleSwipe);
            document.removeEventListener('mouseup', endSwipe);
            document.removeEventListener('touchmove', handleSwipe);
            document.removeEventListener('touchend', endSwipe);
        }

        function updateOrderStatus(orderId, status) {
            fetch(`/orders/${orderId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                // Reload or update UI
                location.reload(); 
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengupdate status. Silakan coba lagi.');
                location.reload();
            });
        }

        function switchTab(tab) {
            const sections = {
                'active': 'active-orders-section',
                'history': 'history-orders-section',
                'products': 'products-section',
                'reviews': 'reviews-section'
            };

            const navs = {
                'active': 'nav-active',
                'history': 'nav-history',
                'products': 'nav-products',
                'reviews': 'nav-reviews'
            };

            // Hide all sections
            Object.values(sections).forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });

            // Reset all navs
            Object.values(navs).forEach(id => {
                document.getElementById(id).className = 'hover:text-gold transition uppercase font-bold text-gray-500';
            });

            // Show selected section
            document.getElementById(sections[tab]).classList.remove('hidden');
            
            // Activate selected nav
            document.getElementById(navs[tab]).className = 'text-gold border-b-2 border-gold pb-1 uppercase font-bold';
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function openEditModal(product) {
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_stock').value = product.stock;
            document.getElementById('edit_description').value = product.description;

            // The route should be /petugas/products/{id}
            document.getElementById('editForm').action = `/petugas/products/${product.id}`;
            openModal('editProductModal');
        }

        function openReceiptModal(imageUrl) {
            document.getElementById('receiptImage').src = imageUrl;
            document.getElementById('receiptModal').classList.remove('hidden');
            document.getElementById('receiptModal').classList.add('flex');
        }

        function closeReceiptModal() {
            document.getElementById('receiptModal').classList.add('hidden');
            document.getElementById('receiptModal').classList.remove('flex');
        }

        // Auto-refresh logic (every 30 seconds)
        setInterval(function() {
            // Only refresh if no modal is currently open
            const isAnyModalOpen = !document.getElementById('addProductModal').classList.contains('hidden') ||
                                   !document.getElementById('editProductModal').classList.contains('hidden') ||
                                   !document.getElementById('receiptModal').classList.contains('hidden');
            
            // Only refresh if we are on the main orders tab (not products or reviews)
            const isOrdersTabActive = !document.getElementById('orders-section').classList.contains('hidden');

            // Do not refresh if user has started dragging a swipe handle
            const isSwiping = isDragging;

            if (!isAnyModalOpen && isOrdersTabActive && !isSwiping) {
                window.location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
