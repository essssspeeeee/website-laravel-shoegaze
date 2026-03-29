<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoegaze - Dashboard User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-gray-900">

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

    <main class="max-w-7xl mx-auto px-6 md:px-12 py-8">
        
        <div class="mb-14 relative rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <img src="{{ asset('images/main-banner.jpg') }}" alt="Promo" class="w-full object-cover h-[300px] md:h-[400px]">
            <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent flex flex-col justify-center px-10 md:px-16 text-white">
                <span class="text-sm font-semibold tracking-wider">910 NINETEN</span>
                <h2 class="text-4xl font-black italic uppercase leading-none mt-2">GEIST EKIDEN ELITE</h2>
                <p class="mt-5 text-sm font-bold border-b-2 border-white w-max pb-1 hover:text-gray-200 cursor-pointer">Shop Now</p>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-4 h-9 bg-[#db4444] rounded-sm"></div>
                <span class="text-[#db4444] font-bold text-sm">Produk Kami</span>
            </div>
            <h2 class="text-3xl font-bold tracking-tight">Jelajahi Produk Kami</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-24">
            @foreach ($products as $product)
            <div class="group bg-white rounded-xl p-4 shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-xl transition-all duration-300 border border-gray-50 flex flex-col h-full">
                <a href="{{ route('product.detail', $product->id) }}" class="block flex-1">
                    <div class="aspect-square bg-[#f5f6f8] rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                        @php
                            $firstImage = is_array($product->images) && count($product->images) ? $product->images[0] : null;
                        @endphp
                        <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('images/default-product.png') }}"
                             alt="{{ $product->name }}" class="w-4/5 transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="font-bold text-[11px] uppercase mb-1 h-8 line-clamp-2">{{ $product->name }}</h3>
                </a>
                
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[#00a651] font-bold text-sm italic">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    
                    <button 
                        onclick="addToCart('{{ $product->id }}', '{{ $product->name }}')"
                        class="p-2 bg-white border border-gray-200 rounded-full hover:bg-gray-50 transition-colors shadow-sm group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 group-hover:text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-4 h-9 bg-[#db4444] rounded-sm"></div>
                <span class="text-[#db4444] font-bold text-sm">Unggulan</span>
            </div>
            <h2 class="text-3xl font-bold mb-8 tracking-tight">Produk Baru</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-auto md:h-[600px] mb-10">
            <div class="relative rounded-2xl overflow-hidden group bg-gray-100">
                <img src="{{ asset('images/ads1.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute top-10 left-10 bg-white p-6 rounded-lg shadow-xl max-w-[240px]">
                    <h4 class="font-black text-2xl italic uppercase mb-1 leading-none tracking-tighter">KANZAKI 2.0</h4>
                    <p class="text-xs text-gray-500 font-medium">Run Strong. Run Confident.</p>
                </div>
            </div>
            
            <div class="grid grid-rows-2 gap-6">
                <div class="relative rounded-2xl overflow-hidden group bg-gray-100">
                    <img src="{{ asset('images/ads2.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute top-6 right-6 bg-white p-5 rounded-lg shadow-lg max-w-[200px]">
                        <h4 class="font-black text-sm uppercase mb-1">AURORUM CITY</h4>
                        <p class="text-[10px] text-gray-500 leading-tight">Light. Responsive. Built to Run Everyday.</p>
                    </div>
                </div>
                <div class="relative rounded-2xl overflow-hidden group bg-gray-100">
                    <img src="{{ asset('images/ads3.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute bottom-6 right-6 bg-white p-5 rounded-lg shadow-lg max-w-[220px]">
                        <h4 class="font-black text-sm italic uppercase mb-1">Haze Flow</h4>
                        <p class="text-[10px] text-gray-500 leading-relaxed">
                            <span class="font-semibold text-gray-800">Categories:</span> Road Running<br>
                            <span class="font-semibold text-gray-800">Best for:</span> Raceday (10K-42K)
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <div x-data="{ show: false, message: '' }" 
         @add-to-cart.window="message = $event.detail.product; show = true; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-5 right-5 z-[100] bg-white border-l-4 border-green-500 shadow-2xl rounded-lg p-4 flex items-center gap-4">
        
        <div class="bg-green-100 p-2 rounded-full text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-800">Berhasil ditambahkan!</p>
            <p class="text-xs text-gray-500" x-text="message"></p>
        </div>
    </div>

    <footer class="bg-[#2b2d2f] text-gray-400 py-8 text-center mt-10">
        <p class="text-[10px] tracking-[0.2em] uppercase opacity-70">&copy; © FAUZAN ESPE 2026. Seluruh hak dilindungi undang-undang</p>
    </footer>

    <script>
        function addToCart(productId, productName) {
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if(response.ok) {
                    return response.json();
                }
                throw new Error('Gagal menambahkan ke keranjang');
            }).then(data => {
                // update badge
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    if (data.count && data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                window.dispatchEvent(new CustomEvent('add-to-cart', { 
                    detail: { product: productName } 
                }));
            }).catch(error => {
                console.error('Error:', error);
                alert("Gagal menambahkan ke keranjang. Pastikan Anda sudah login.");
            });
        }
    </script>
</body>
</html>