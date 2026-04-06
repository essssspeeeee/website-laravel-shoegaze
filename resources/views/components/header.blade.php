<header class="bg-white border-b border-slate-100 sticky top-0 z-[100] py-4">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
        
        <div class="flex-shrink-0">
            <a href="/dashboard" class="text-2xl font-black tracking-tighter text-slate-950 uppercase">
                SHOEGAZE
            </a>
        </div>

        <div class="flex items-center gap-6">
            
            <form action="/search" method="GET" class="relative hidden md:block mb-0">
                <input type="text" 
                    name="q"
                    value="{{ request('q') }}"
                    class="w-64 lg:w-80 bg-slate-100/80 border-none rounded-full py-2 px-5 text-sm focus:ring-2 focus:ring-slate-200 transition-all" 
                    placeholder="Mau cari apa?">
                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>

            <a href="/cart" class="text-slate-700 hover:text-red-500 transition-colors relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] px-1.5 rounded-full border-2 border-white hidden">0</span>
            </a>

            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="text-slate-700 hover:text-red-500 transition-colors focus:outline-none flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>

                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    style="display: none;"
                    class="absolute right-0 mt-4 w-52 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-[110]">
                    
                    <div class="px-4 py-2 border-b border-slate-50 mb-1">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold italic">Akun Saya</p>
                        <p class="text-sm font-black text-slate-900 truncate capitalize">{{ Auth::user()->name }}</p>
                    </div>

                    <a href="/dashboard/profile" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">
                        Edit Profil
                    </a>
                    
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">
                        Riwayat Pesanan
                    </a>

                    <hr class="my-1 border-slate-50">

                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-red-500 font-bold hover:bg-red-50 transition-colors">
                            Keluar Akun
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
