@props(['active' => 'dashboard'])

<nav class="fixed w-full z-50 glass border-b border-white/10 px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center font-bold text-[#000b1a]">
                <i class="fas fa-crown"></i>
            </div>
            <span class="text-xl font-bold tracking-wider">ADMIN PANEL</span>
        </div>
        
@php
    $menus = \App\Models\NavigationMenu::where('is_visible', true)->orderBy('sort_order')->get();
@endphp

        <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">
            @foreach($menus as $menu)
                <a href="{{ route($menu->route) }}" class="{{ $active === $menu->active_key ? 'text-gold border-b-2 border-gold pb-1' : 'hover:text-gold transition' }} uppercase flex items-center">
                    @if($menu->icon)
                        <i class="{{ $menu->icon }} mr-2 text-[10px]"></i>
                    @endif
                    {{ $menu->label }}
                </a>
            @endforeach
            <div class="flex items-center space-x-4 ml-4">
                <div class="flex items-center space-x-2 glass px-4 py-2 rounded-full border border-white/10">
                    <i class="fas fa-user-shield text-gold"></i>
                    <span class="text-xs font-bold">ADMIN</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gold transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
