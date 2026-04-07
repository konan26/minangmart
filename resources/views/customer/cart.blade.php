<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Minangmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #000b1a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .text-gold { color: #d4af37; }
        .bg-gold { background-color: #d4af37; }
        .border-gold { border-color: #d4af37; }
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
                <a href="{{ route('customer.orders') }}" class="hover:text-gold transition">MY ORDERS</a>
            </div>
        </div>
    </nav>

    <main class="pt-32 px-6 max-w-4xl mx-auto">
        <h1 class="text-4xl font-black mb-8 uppercase tracking-tighter">Your <span class="text-gold">Cart</span></h1>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-100 px-6 py-4 rounded-2xl mb-8">
                {{ session('error') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="space-y-6">
                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                    @php $total += $details['price'] * $details['quantity'] @endphp
                    <div id="cart-item-{{ $id }}" class="glass p-6 rounded-3xl border border-white/5 flex items-center justify-between group transition duration-500">
                        <div class="flex items-center space-x-6">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border border-white/10 group-hover:border-gold/30 transition">
                                <img src="{{ $details['image'] }}" class="w-full h-full object-cover" alt="{{ $details['name'] }}">
                            </div>
                            <div>
                                <h3 class="font-bold text-xl">{{ $details['name'] }}</h3>
                                <p class="text-gold font-bold">IDR {{ number_format($details['price'], 0, ',', '.') }}</p>
                                <div class="flex items-center space-x-3 mt-3">
                                    <button onclick="updateQuantity({{ $id }}, 'decrease')" class="w-6 h-6 rounded-full bg-white/10 hover:bg-gold hover:text-[#000b1a] flex items-center justify-center transition">
                                        <i class="fas fa-minus text-[10px]"></i>
                                    </button>
                                    <span id="qty-{{ $id }}" class="text-xs font-bold">{{ $details['quantity'] }}</span>
                                    <button onclick="updateQuantity({{ $id }}, 'increase')" class="w-6 h-6 rounded-full bg-white/10 hover:bg-gold hover:text-[#000b1a] flex items-center justify-center transition">
                                        <i class="fas fa-plus text-[10px]"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-8">
                            <div class="text-right">
                                <p class="text-xs text-gray-500 uppercase tracking-widest">Subtotal</p>
                                <p id="subtotal-{{ $id }}" class="font-bold">IDR {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                            </div>
                            <button onclick="removeFromCart({{ $id }})" class="text-gray-500 hover:text-red-500 transition">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <div class="mt-12 glass p-8 rounded-[2.5rem] border border-white/5 bg-white/[0.01]">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-6">
                        <div>
                            <p class="text-gray-500 uppercase tracking-widest text-xs">Total Order Value</p>
                            <h2 id="cart-total" class="text-4xl font-black text-gold">IDR {{ number_format($total, 0, ',', '.') }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 uppercase tracking-widest text-xs">Delivery to</p>
                            <p class="font-bold text-sm leading-relaxed">{{ auth()->user()->address ?? 'Update address in profile' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('cart.checkout') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        @php
                            $randomDistance = rand(2, 12);
                            $standardCost = $randomDistance * 2000;
                            $expressCost = $randomDistance * 5000;
                        @endphp

                        <!-- Courier Selection -->
                        <div class="mb-8">
                            <label class="block text-gray-400 uppercase tracking-widest text-[10px] font-black mb-3 ml-1">Pilih Kurir / Kecepatan Pengiriman (Jarak: {{ $randomDistance }} km)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="border border-white/10 bg-white/[0.03] rounded-2xl p-4 cursor-pointer hover:-translate-y-1 hover:shadow-lg hover:shadow-gold/10 hover:bg-white/[0.05] active:scale-95 transition-all duration-300 flex items-center relative group">
                                    <input type="radio" name="courier_type" value="standard" class="hidden peer" required onchange="calculateGrandTotal({{ $standardCost }})" />
                                    <div class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-gold peer-checked:shadow-[0_0_15px_rgba(212,175,55,0.3)] transition-all duration-300"></div>
                                    <div class="flex-1 ml-2 pointer-events-none transition-transform duration-300 group-hover:translate-x-1 peer-checked:translate-x-1">
                                        <h4 class="font-bold text-sm text-white peer-checked:text-gold transition-colors">Standard Delivery</h4>
                                        <p class="text-[10px] text-gray-400 mt-1">Estimasi 30-45 Menit</p>
                                    </div>
                                    <div class="text-right pointer-events-none transition-transform duration-300 peer-checked:scale-110 origin-right">
                                        <p class="text-xs font-bold text-gold">+ IDR {{ number_format($standardCost, 0, ',', '.') }}</p>
                                    </div>
                                </label>
                                
                                <label class="border border-white/10 bg-white/[0.03] rounded-2xl p-4 cursor-pointer hover:-translate-y-1 hover:shadow-lg hover:shadow-gold/10 hover:bg-white/[0.05] active:scale-95 transition-all duration-300 flex items-center relative group">
                                    <input type="radio" name="courier_type" value="express" class="hidden peer" required onchange="calculateGrandTotal({{ $expressCost }})" />
                                    <div class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-gold peer-checked:shadow-[0_0_15px_rgba(212,175,55,0.3)] transition-all duration-300"></div>
                                    <div class="flex-1 ml-2 pointer-events-none transition-transform duration-300 group-hover:translate-x-1 peer-checked:translate-x-1">
                                        <h4 class="font-bold text-sm text-white peer-checked:text-gold transition-colors">Express Delivery</h4>
                                        <p class="text-[10px] text-gray-400 mt-1">Estimasi 10-20 Menit</p>
                                    </div>
                                    <div class="text-right pointer-events-none transition-transform duration-300 peer-checked:scale-110 origin-right">
                                        <p class="text-xs font-bold text-gold">+ IDR {{ number_format($expressCost, 0, ',', '.') }}</p>
                                    </div>
                                </label>
                            </div>
                            <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                        </div>

                        <div class="mb-8">
                            <label for="notes" class="block text-gray-400 uppercase tracking-widest text-[10px] font-black mb-3 ml-1">Special Instructions / Notes</label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Nasi jangan terlalu pedas, atau tulis kata-kata untuk cetak foto..." 
                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-6 py-4 text-sm focus:border-gold outline-none focus:bg-white/[0.05] transition placeholder:text-gray-700"></textarea>
                        </div>

                        <!-- Address Reminder -->
                        <div class="mb-8 bg-blue-500/10 border border-blue-500/20 text-blue-300 px-5 py-4 rounded-2xl text-sm flex items-start shadow-inner">
                            <i class="fas fa-info-circle mt-0.5 mr-4 text-lg"></i>
                            <p class="leading-relaxed"><strong class="text-white block mb-1">Pengingat Penting!</strong> Pastikan Anda telah mengisi alamat sesuai dengan alamat rumah Anda yang sebenarnya (lengkap dengan patokan bila perlu) agar memudahkan kurir dalam melakukan pengantaran.</p>
                        </div>

                        @if(!empty(auth()->user()->address))
                            <button type="submit" class="w-full bg-gold text-[#000b1a] py-6 rounded-2xl font-black uppercase tracking-widest hover:bg-white hover:scale-[1.02] transition shadow-2xl shadow-gold/20 flex items-center justify-center">
                                <i class="fas fa-qrcode mr-4"></i> Checkout & Bayar via QRIS
                            </button>
                        @else
                            <a href="{{ route('profile.edit', ['redirect_to' => route('cart.index')]) }}" class="w-full bg-red-900/40 text-red-400 border border-red-500/50 py-6 rounded-2xl font-black uppercase tracking-widest flex items-center justify-center transition duration-300 hover:bg-red-900/80 hover:text-red-300 hover:shadow-lg hover:shadow-red-500/20 active:scale-95 cursor-pointer">
                                <i class="fas fa-arrow-right mr-4"></i> Isi Alamat Rumah Sekarang
                            </a>
                        @endif
                    </form>
                    <p class="text-center text-[10px] text-gray-600 mt-6 uppercase tracking-[0.2em]">Pembayaran non-tunai melalui QRIS</p>
                </div>
            </div>
        @else
            <div id="empty-cart-view" class="glass p-20 rounded-[3rem] border border-white/5 border-dashed text-center">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-basket text-gray-700 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Cart is Empty</h2>
                <p class="text-gray-500 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('customer.home') }}" class="inline-block bg-gold/10 text-gold px-8 py-3 rounded-full font-bold hover:bg-gold hover:text-navy transition">
                    Start Shopping
                </a>
            </div>
        @endif
    </main>
    <script>
        let baseTotal = {{ $total ?? 0 }};
        
        function calculateGrandTotal(cost) {
            document.getElementById('shipping_cost').value = cost;
            let grandTotal = baseTotal + cost;
            document.getElementById('cart-total').innerText = 'IDR ' + new Intl.NumberFormat('id-ID').format(grandTotal);
        }

        function updateQuantity(id, action) {
            fetch("{{ route('cart.update_quantity') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id, action })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => { throw data; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update UI dynamically
                    document.getElementById('qty-' + id).innerText = data.quantity;
                    document.getElementById('subtotal-' + id).innerText = 'IDR ' + new Intl.NumberFormat('id-ID').format(data.itemTotal);
                    
                    // Recalculate Grand Total
                    baseTotal = data.cartTotal;
                    let currentShipping = parseInt(document.getElementById('shipping_cost').value) || 0;
                    document.getElementById('cart-total').innerText = 'IDR ' + new Intl.NumberFormat('id-ID').format(baseTotal + currentShipping);
                }
            })
            .catch(error => {
                alert(error.message || 'Gagal mengubah jumlah.');
            });
        }

        function removeFromCart(id) {
            fetch("{{ route('cart.remove') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.getElementById('cart-item-' + id);
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(50px)';
                    setTimeout(() => {
                        window.location.reload(); // Simple reload for consistent UI state
                    }, 500);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
