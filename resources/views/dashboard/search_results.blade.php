<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-white text-gray-900">
    
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
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] px-1.5 rounded-full border-2 border-white">2</span>
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
                    
                    <a href="/history" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">
                        Pesanan Saya
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
    <main class="max-w-7xl mx-auto px-6 md:px-16 py-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-8">Hasil pencarian untuk: <span class="text-red-500">"{{ $query }}"</span></h2>

        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-slate-200 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-slate-400 font-semibold text-lg mb-4">Duh, sepatunya belum mendarat di gudang kami.</p>
                <a href="/home" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-2 rounded-full shadow transition">Kembali Belanja</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition flex flex-col overflow-hidden">
                    <a href="{{ route('product.detail', $product->id) }}" class="block">
                        <div class="bg-slate-100 flex items-center justify-center h-48 md:h-56 overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300' }}" alt="{{ $product->name }}" class="object-contain w-full h-full group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-base text-slate-900 mb-1 truncate">{{ $product->name }}</h3>
                            <p class="text-slate-400 text-xs mb-2">{{ $product->category ?? 'Sepatu' }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-red-500 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="bg-slate-950 text-white pt-12 pb-4 mt-24">
        <div class="max-w-7xl mx-auto px-6 md:px-16 grid grid-cols-1 md:grid-cols-3 gap-10 pb-10">
            <!-- Tentang -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="font-extrabold text-xl tracking-tight">SHOEGAZE</span>
                </div>
                <p class="text-slate-400 text-sm">Sepatu original, harga rasional. Temukan gaya terbaikmu di Shoegaze Store.</p>
            </div>
            <!-- Bantuan -->
            <div>
                <h4 class="font-bold mb-3">Bantuan</h4>
                <ul class="space-y-2 text-slate-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">Cara Order</a></li>
                    <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                </ul>
            </div>
            <!-- Sosial Media -->
            <div>
                <h4 class="font-bold mb-3">Sosial Media</h4>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-pink-400 transition" aria-label="Instagram">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="20" height="20" x="2" y="2" rx="5"/>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
                            <circle cx="17.5" cy="6.5" r="1.5"/>
                        </svg>
                    </a>
                    <a href="#" class="hover:text-blue-400 transition" aria-label="Twitter">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0022.4.36a9.09 9.09 0 01-2.88 1.1A4.52 4.52 0 0016.11 0c-2.5 0-4.52 2.02-4.52 4.52 0 .35.04.7.11 1.03C7.69 5.4 4.07 3.7 1.64 1.15c-.38.65-.6 1.4-.6 2.2 0 1.52.77 2.86 1.95 3.65A4.48 4.48 0 01.96 6v.06c0 2.13 1.52 3.91 3.54 4.31-.37.1-.76.16-1.16.16-.28 0-.55-.03-.82-.08.56 1.74 2.17 3.01 4.08 3.05A9.05 9.05 0 010 19.54a12.8 12.8 0 006.92 2.03c8.3 0 12.85-6.88 12.85-12.85 0-.2 0-.41-.02-.61A9.22 9.22 0 0024 4.59a9.1 9.1 0 01-2.6.71A4.48 4.48 0 0023 3z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-6 text-center text-xs text-slate-400">
            © 2026 Shoegaze Store
        </div>
    </footer>

</body>
</html>