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

    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 py-4 px-6 md:px-16 flex items-center justify-between shadow-sm">
    <div class="flex-shrink-0">
        <a href="{{ route('home') }}">
            <h1 class="text-2xl font-black tracking-tighter uppercase italic" style="letter-spacing: -0.05em;">SHOEGAZE</h1>
        </a>
    </div>

    <div class="hidden md:flex flex-1 max-w-md mx-10"> 
        <form action="{{ route('search') }}" method="GET" class="relative w-full">
            <input type="text" name="query" value="{{ request('query') }}" placeholder="Mau cari apa di SHOEGAZE?" 
                   class="w-full bg-[#f6f6f6] border border-transparent rounded-md py-2 px-4 pr-10 text-xs focus:bg-white focus:border-gray-200 transition-all outline-none">
            <button type="submit" class="absolute right-3 top-2 text-gray-400 hover:text-[#db4444]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>
    
    <div class="flex items-center space-x-6">
        <a href="{{ route('cart') }}" class="relative text-gray-700 hover:text-black transition-colors focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="absolute -top-1 -right-1 bg-[#db4444] text-white text-[10px] font-bold px-1.5 rounded-full">
                    {{ count(session('cart')) }}
                </span>
            @endif
        </a>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false" class="text-gray-700 hover:text-black transition-colors focus:outline-none flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </button>

            <div x-show="open" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-2 z-[60]">
                
                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Halo,</p>
                    <p class="text-xs font-bold text-gray-800 truncate mt-1">{{ Auth::user()->name }}</p>
                </div>

               <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    Kelola Akun
</a>

                <hr class="my-1 border-gray-50">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

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
                    <div class="aspect-square bg-[#f5f5f5] rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                        {{-- Catatan: Pastikan asset() sesuai dengan letak folder fotomu --}}
                        <img src="{{ asset($product->image) }}" class="w-4/5 transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="font-bold text-[11px] uppercase mb-1 h-8 line-clamp-2">{{ $product->name }}</h3>
                </a>
                
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[#00a651] font-bold text-sm italic">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    
                    <button 
                        @click="addToCart('{{ $product->id }}', '{{ $product->name }}')"
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
                    window.dispatchEvent(new CustomEvent('add-to-cart', { 
                        detail: { product: productName } 
                    }));
                } else {
                    alert("Gagal menambahkan ke keranjang. Pastikan Anda sudah login.");
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>