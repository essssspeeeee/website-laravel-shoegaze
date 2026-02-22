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
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #ffffff;
        }
        /* Menyembunyikan pop-up sebelum AlpineJS siap */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-gray-900">

    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 py-4 px-6 md:px-16 flex items-center justify-between shadow-sm">
        
        <div class="flex-shrink-0">
            <h1 class="text-2xl font-black tracking-tighter uppercase italic" style="letter-spacing: -0.05em;">SHOEGAZE</h1>
        </div>

        <div class="hidden md:flex flex-1 max-w-md mx-10">
            <div class="relative w-full">
                <input type="text" placeholder="Mau cari apa di SHOEGAZE?" 
                    class="w-full bg-[#f6f6f6] border border-transparent rounded-md py-2.5 px-4 pr-10 text-xs focus:ring-1 focus:ring-gray-300 focus:bg-white transition-all outline-none">
                <div class="absolute right-3 top-2.5 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-6">
            
            <button class="relative text-gray-700 hover:text-black transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>

            <div class="relative" x-data="{ open: false }">
                
                <button @click="open = !open" @click.outside="open = false" class="text-gray-700 hover:text-black transition-colors focus:outline-none flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>

                <div x-show="open" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-4 w-56 bg-white border border-gray-100 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] z-[60] py-2 overflow-hidden">
                    
                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Kelola Akun Saya
                    </a>

                    <div class="border-t border-gray-100 my-1"></div>

                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 md:px-12 py-8">
        
        <div class="mb-14 relative rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <img src="{{ asset('images/main-banner.jpg') }}" alt="Promo" class="w-full object-cover h-[300px] md:h-[400px]">
            <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent flex flex-col justify-center px-10 md:px-16">
                <div class="max-w-xs">
                    <span class="text-white text-sm font-semibold tracking-wider">910 NINETEN</span>
                    <h2 class="text-white text-4xl font-black italic uppercase leading-none mt-2">GEIST EKIDEN ELITE</h2>
                    <p class="text-white mt-5 text-sm font-bold border-b-2 border-white w-max pb-1 hover:text-gray-200 hover:border-gray-200 transition-colors cursor-pointer">Shop Now</p>
                </div>
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
            @for ($i = 1; $i <= 8; $i++)
            <div class="group bg-white rounded-xl p-4 shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-xl transition-all duration-300 border border-gray-50 relative flex flex-col justify-between h-full">
                <div>
                    <div class="aspect-square bg-[#f5f5f5] rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/shoe-sample.png') }}" alt="Product" class="w-4/5 transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="font-bold text-[11px] uppercase mb-1 leading-snug tracking-wide h-8 line-clamp-2">HAZE FLOW ORANGE/PINK/UNGU HITAM</h3>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[#00a651] font-bold text-sm italic tracking-tight">Rp 699.900,00</span>
                    <button class="border border-gray-200 p-2.5 rounded-full hover:bg-black hover:text-white transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            @endfor
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

    <footer class="bg-[#2b2d2f] text-gray-400 py-8 mt-10 text-center">
        <p class="text-[10px] tracking-[0.2em] font-medium uppercase opacity-70 hover:opacity-100 transition-opacity">
            &copy; FAUZAAN ESPE 2026. Seluruh hak dilindungi undang-undang.
        </p>
    </footer>

    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-10 right-10 bg-white border border-gray-100 p-3 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all z-50 text-gray-600 hover:text-black">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

</body>
</html>