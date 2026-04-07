<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minangmart - Kelola Petugas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#000b1a] text-white antialiased">
    <!-- Navbar -->
    <x-admin.navbar active="petugas" />

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-8">Kelola <span class="text-gold">Petugas</span></h1>

            @if(session('success'))
                <div class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 p-4 rounded-xl mb-8 flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 text-red-500 border border-red-500/20 p-4 rounded-xl mb-8 flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 text-red-500 border border-red-500/20 p-4 rounded-xl mb-8">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Data Petugas -->
                <div class="md:col-span-2 glass rounded-[2rem] overflow-hidden border border-white/10">
                    <div class="p-8 border-b border-white/10 flex items-center justify-between">
                        <h2 class="text-2xl font-bold">Daftar Akun <span class="text-gold">Petugas</span></h2>
                        <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">{{ $petugasUsers->count() }} TOTAL PETUGAS</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white/5">
                                    <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Nama Lengkap</th>
                                    <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400">Kontak</th>
                                    <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-gray-400 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($petugasUsers as $petugas)
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-[12px] font-bold">
                                                {{ strtoupper(substr($petugas->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold block text-sm">{{ $petugas->name }}</span>
                                                <span class="text-[9px] text-gray-500 uppercase">{{ $petugas->username }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="font-bold block text-xs">{{ $petugas->email }}</span>
                                        <span class="text-[10px] text-gray-500">{{ $petugas->phone_number }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <form action="{{ route('admin.petugas.destroy', $petugas) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Petugas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400 p-2 border border-red-500/30 rounded-full hover:bg-red-500/10 transition">
                                                <i class="fas fa-trash-alt w-4 h-4 flex items-center justify-center text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-6 text-center text-gray-500 text-sm">
                                        Belum ada data Petugas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Tambah Petugas -->
                <div class="glass p-8 rounded-[2rem] border border-white/10 h-max">
                    <h2 class="text-2xl font-bold mb-6">Tambah <span class="text-gold">Petugas</span></h2>
                    <form method="POST" action="{{ route('admin.petugas.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Nomor Telepon</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Password</label>
                            <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition">
                        </div>
                        <div class="mb-6">
                            <label class="block text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition">
                        </div>
                        <button type="submit" class="w-full bg-gold text-[#000b1a] font-bold uppercase tracking-widest py-3 rounded-xl hover:bg-yellow-400 transition">
                            Simpan Petugas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-12 border-t border-white/10 px-6 mt-12 text-center text-gray-500 text-sm">
        <p>&copy; 2026 MINANGMART ADMIN. All rights reserved.</p>
    </footer>
</body>
</html>
