<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - Minangmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #000b1a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .text-gold { color: #d4af37; }
        .bg-gold { background-color: #d4af37; }
        .border-gold { border-color: #d4af37; }
        .qris-glow {
            box-shadow: 0 0 40px rgba(212, 175, 55, 0.15), 0 0 80px rgba(212, 175, 55, 0.05);
        }
        @keyframes pulse-gold {
            0%, 100% { box-shadow: 0 0 20px rgba(212, 175, 55, 0.2); }
            50% { box-shadow: 0 0 40px rgba(212, 175, 55, 0.4); }
        }
        .pulse-animation { animation: pulse-gold 2s ease-in-out infinite; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-in-delay { animation: fadeInUp 0.6s ease-out 0.2s forwards; opacity: 0; }
        .animate-in-delay-2 { animation: fadeInUp 0.6s ease-out 0.4s forwards; opacity: 0; }
    </style>
</head>
<body class="min-h-screen">
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

    <main class="pt-28 pb-20 px-6">
        <div class="max-w-2xl mx-auto">

            @if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-100 px-6 py-4 rounded-2xl mb-8 flex items-center animate-in">
                    <i class="fas fa-check-circle mr-4 text-xl"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header -->
            <div class="text-center mb-10 animate-in">
                <div class="w-16 h-16 bg-gold/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-gold/30">
                    <i class="fas fa-qrcode text-gold text-2xl"></i>
                </div>
                <h1 class="text-3xl font-black uppercase tracking-tight">Pembayaran <span class="text-gold">QRIS</span></h1>
                <p class="text-gray-500 text-sm mt-2">Order <span class="text-gold font-bold">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></p>
            </div>

            <!-- Countdown Timer -->
            @if($order->payment_status === 'awaiting_payment')
                <div class="bg-amber-500/10 border border-amber-500/30 rounded-3xl p-6 mb-8 flex items-center justify-between animate-in-delay qris-glow">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-amber-500/20 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-amber-500 text-xl animate-pulse"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-amber-500 uppercase tracking-[0.2em] font-black">Sisa Waktu Pembayaran</p>
                            <p id="countdown" class="text-2xl font-black text-white tabular-nums mt-1">10:00</p>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold mb-1">Batas Waktu</p>
                        <p class="text-sm font-black text-gray-300">
                            {{ $order->created_at->addMinutes(\App\Models\Order::PAYMENT_TIMEOUT_MINUTES)->format('H:i:s') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- QRIS Card -->
            <div class="glass rounded-[2.5rem] border border-white/10 overflow-hidden qris-glow animate-in-delay">
                <!-- Order Summary -->
                <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">Total Pembayaran</p>
                            <p class="text-3xl font-black text-gold mt-1">IDR {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">Status</p>
                            @if($order->payment_status === 'awaiting_payment')
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-orange-500/10 text-orange-400 text-[10px] font-black uppercase tracking-wider border border-orange-500/20">
                                    Menunggu Pembayaran
                                </span>
                            @elseif($order->payment_status === 'verifying')
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-wider border border-blue-500/20">
                                    Sedang Diverifikasi
                                </span>
                            @elseif($order->payment_status === 'verified')
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-wider border border-emerald-500/20">
                                    Terverifikasi
                                </span>
                            @elseif($order->payment_status === 'invalid')
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-red-500/10 text-red-400 text-[10px] font-black uppercase tracking-wider border border-red-500/20">
                                    Ditolak - Upload Ulang
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- QRIS QR Code -->
                <div class="px-8 py-10 flex flex-col items-center">
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mb-6">Scan QR Code untuk Pembayaran</p>
                    
                    <div class="bg-white p-4 rounded-3xl pulse-animation mb-6 overflow-hidden">
                        <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Payment" class="w-full max-w-[280px] mx-auto rounded-xl">
                    </div>

                    <p class="text-gold font-black text-sm tracking-wider uppercase">MINANGMART</p>
                    <p class="text-gray-500 text-xs mt-1">Scan menggunakan aplikasi e-wallet atau m-banking Anda</p>
                </div>

                <!-- Instructions -->
                <div class="px-8 py-6 border-t border-white/5 bg-white/[0.01]">
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold mb-4">Cara Pembayaran</p>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-gold text-[10px] font-black">1</span>
                            </div>
                            <p class="text-sm text-gray-400">Buka aplikasi <span class="text-white font-bold">e-wallet</span> atau <span class="text-white font-bold">mobile banking</span> Anda</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-gold text-[10px] font-black">2</span>
                            </div>
                            <p class="text-sm text-gray-400">Scan <span class="text-white font-bold">QR Code</span> di atas dan bayar sejumlah <span class="text-gold font-bold">IDR {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-gold text-[10px] font-black">3</span>
                            </div>
                            <p class="text-sm text-gray-400"><span class="text-white font-bold">Screenshot</span> bukti pembayaran yang berhasil</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-gold text-[10px] font-black">4</span>
                            </div>
                            <p class="text-sm text-gray-400"><span class="text-white font-bold">Upload</span> bukti pembayaran di form bawah ini</p>
                        </div>
                    </div>
                </div>

                <!-- Upload Section -->
                @if(in_array($order->payment_status, ['awaiting_payment', 'invalid']))
                <div class="px-8 py-8 border-t border-white/5">
                    @if($order->payment_status === 'invalid')
                        <div class="bg-red-500/10 border border-red-500/30 text-red-300 px-5 py-3 rounded-2xl mb-6 text-sm flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3 text-red-400"></i>
                            Bukti pembayaran sebelumnya ditolak. Silakan unggah bukti yang valid.
                        </div>
                    @endif

                    <form action="{{ route('orders.uploadReceipt', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="block text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold mb-3">Upload Bukti Pembayaran</label>
                        
                        <div class="relative group mb-6">
                            <div id="drop-zone" class="border-2 border-dashed border-white/10 rounded-2xl p-8 text-center cursor-pointer hover:border-gold/30 transition group-hover:bg-white/[0.02]">
                                <div id="upload-placeholder">
                                    <i class="fas fa-cloud-arrow-up text-3xl text-gray-700 mb-3 block"></i>
                                    <p class="text-sm text-gray-500">Klik atau drag file ke sini</p>
                                    <p class="text-[10px] text-gray-700 mt-1">JPG, PNG • Max 2MB</p>
                                </div>
                                <div id="upload-preview" class="hidden">
                                    <img id="preview-img" src="" class="max-h-40 mx-auto rounded-xl border border-white/10 mb-3" alt="Preview">
                                    <p id="file-name" class="text-xs text-gold font-bold"></p>
                                </div>
                            </div>
                            <input type="file" name="payment_receipt" id="receipt-input" accept="image/jpeg,image/png,image/jpg" required 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>

                        @error('payment_receipt')
                            <p class="text-red-400 text-xs mb-4">{{ $message }}</p>
                        @enderror
                        
                        <button type="submit" id="submit-btn" class="w-full bg-gold text-[#000b1a] py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-white hover:scale-[1.02] transition shadow-2xl shadow-gold/20 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-paper-plane mr-3"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
                @elseif($order->payment_status === 'verifying')
                <div class="px-8 py-10 border-t border-white/5 text-center">
                    <div class="w-16 h-16 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-500/30">
                        <i class="fas fa-hourglass-half text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Bukti Sedang Diverifikasi</h3>
                    <p class="text-gray-500 text-sm">Tim kami sedang memeriksa bukti pembayaran Anda. Harap tunggu.</p>
                    @if($order->payment_receipt)
                        <div class="mt-6">
                            <img src="{{ asset('storage/' . $order->payment_receipt) }}" class="max-h-48 mx-auto rounded-2xl border border-white/10 shadow-lg" alt="Bukti Pembayaran">
                        </div>
                    @endif
                    <a href="{{ route('customer.orders') }}" class="inline-block mt-6 bg-white/5 text-white px-8 py-3 rounded-full font-bold text-sm hover:bg-white/10 transition border border-white/10">
                        <i class="fas fa-arrow-left mr-2"></i> Lihat Semua Pesanan
                    </a>
                </div>
                @elseif($order->payment_status === 'verified')
                <div class="px-8 py-10 border-t border-white/5 text-center">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-500/30">
                        <i class="fas fa-check-circle text-emerald-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Pembayaran Terverifikasi!</h3>
                    <p class="text-gray-500 text-sm">Pesanan Anda sedang diproses. Terima kasih!</p>
                    <a href="{{ route('customer.orders') }}" class="inline-block mt-6 bg-gold text-[#000b1a] px-8 py-3 rounded-full font-black text-sm hover:bg-white transition shadow-lg shadow-gold/20">
                        <i class="fas fa-receipt mr-2"></i> Lihat Pesanan Saya
                    </a>
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="mt-8 glass rounded-[2rem] border border-white/5 p-8 animate-in-delay-2">
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold mb-4">Detail Pesanan</p>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl overflow-hidden border border-white/10 flex-shrink-0">
                                <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}" class="w-full h-full object-cover" alt="{{ $item->product_name }}">
                            </div>
                            <span class="font-bold text-gray-300">{{ $item->product_name }}</span>
                            <span class="text-gray-600">x{{ $item->quantity }}</span>
                        </div>
                        <span class="text-gray-400 font-bold">IDR {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-white/5 mt-4 pt-4 flex justify-between">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Total</span>
                    <span class="text-lg font-black text-gold">IDR {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </main>

    <script>
        @if($order->payment_status === 'awaiting_payment')
            const expiresAt = {{ $order->created_at->addMinutes(\App\Models\Order::PAYMENT_TIMEOUT_MINUTES)->timestamp * 1000 }};
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiresAt - now;
                
                const countdownEl = document.getElementById('countdown');
                if (!countdownEl) return;

                if (distance < 0) {
                    clearInterval(timerInterval);
                    countdownEl.innerHTML = "EXPIRED";
                    countdownEl.classList.add('text-red-500');
                    window.location.reload();
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                countdownEl.innerHTML = 
                    (minutes < 10 ? '0' : '') + minutes + ":" + 
                    (seconds < 10 ? '0' : '') + seconds;
            }
            
            const timerInterval = setInterval(updateCountdown, 1000);
            updateCountdown();
        @endif

        const input = document.getElementById('receipt-input');
        const placeholder = document.getElementById('upload-placeholder');
        const preview = document.getElementById('upload-preview');
        const previewImg = document.getElementById('preview-img');
        const fileName = document.getElementById('file-name');
        const submitBtn = document.getElementById('submit-btn');

        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        previewImg.src = ev.target.result;
                        placeholder.classList.add('hidden');
                        preview.classList.remove('hidden');
                        fileName.textContent = file.name;
                        submitBtn.disabled = false;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>
