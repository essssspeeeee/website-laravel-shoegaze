<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .size-btn.active { background-color: #db4444; color: white; border-color: #db4444; }
    </style>
</head>
<body class="antialiased text-gray-900 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-100 py-4 px-6 md:px-16 flex items-center justify-between sticky top-0 z-50">
        <div class="flex-shrink-0">
            <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity">
                <h1 class="text-2xl font-black tracking-tighter uppercase italic">SHOEGAZE</h1>
            </a>
        </div>
        
        <div class="hidden md:flex flex-1 max-w-md mx-10">
            <div class="relative w-full">
                <input type="text" placeholder="Mau cari apa di SHOEGAZE?" class="w-full bg-[#f6f6f6] border border-transparent rounded-md py-2 px-4 pr-10 text-xs focus:bg-white transition-all outline-none">
                <div class="absolute right-3 top-2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-6">
            <a href="{{ route('cart') }}" class="relative text-gray-700 hover:text-black transition-colors focus:outline-none p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="absolute top-0 -right-1 bg-red-600 text-white text-[10px] font-bold px-1.5 rounded-full border-2 border-white">
                    {{ count(session('cart', [])) }}
                </span>
            </a>

            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 md:px-16 py-10 flex-grow">
        <h2 class="text-3xl font-bold mb-10">Detail Produk</h2>

        <div class="flex flex-col md:flex-row gap-10">
            <div class="flex gap-4 w-full md:w-1/2">
                <div class="flex flex-col gap-4">
                    <div class="w-24 h-24 bg-[#f6f6f6] rounded-md p-2 cursor-pointer border border-transparent hover:border-gray-300">
                        <img src="https://via.placeholder.com/100" alt="thumb1" class="w-full h-full object-contain">
                    </div>
                    <div class="w-24 h-24 bg-[#f6f6f6] rounded-md p-2 cursor-pointer border border-transparent hover:border-gray-300">
                        <img src="https://via.placeholder.com/100" alt="thumb2" class="w-full h-full object-contain">
                    </div>
                    <div class="w-24 h-24 bg-[#f6f6f6] rounded-md p-2 cursor-pointer border border-transparent hover:border-gray-300">
                        <img src="https://via.placeholder.com/100" alt="thumb3" class="w-full h-full object-contain">
                    </div>
                    <div class="w-24 h-24 bg-[#f6f6f6] rounded-md p-2 cursor-pointer border border-transparent hover:border-gray-300">
                        <img src="https://via.placeholder.com/100" alt="sizechart" class="w-full h-full object-contain">
                    </div>
                </div>
                <div class="flex-1 bg-[#f6f6f6] rounded-xl flex items-center justify-center p-10 h-[500px]">
                    <img src="https://via.placeholder.com/500" id="mainImg" class="max-w-full max-h-full object-contain drop-shadow-2xl">
                </div>
            </div>

            <div class="w-full md:w-1/2 flex flex-col">
                <h1 class="text-2xl font-bold uppercase tracking-wide leading-tight mb-2">
                    {{ strtoupper($product->name) }}
                </h1>
                <p class="text-2xl font-medium text-gray-900 mb-6">Rp {{ number_format($product->price,0,',','.') }}</p>
                
                <div class="text-sm text-gray-600 leading-relaxed mb-8 space-y-4">
                    <p>{{ $product->description ?? 'Deskripsi produk tidak tersedia.' }}</p>
                </div>

                <hr class="mb-6">

                <div class="flex items-center gap-4 mb-8">
                    <span class="text-lg font-semibold">Ukuran:</span>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([39, 40, 41, 42, 43, 44, 45] as $size)
                            <button onclick="selectSize(this)" class="size-btn w-10 h-10 border border-gray-300 rounded flex items-center justify-center text-sm font-medium transition-all hover:border-[#db4444]">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex border border-gray-300 rounded overflow-hidden">
                        <button onclick="changeQty(-1)" class="px-4 py-2 hover:bg-gray-100 border-r">-</button>
                        <input type="number" id="qty" value="1" class="w-12 text-center outline-none" readonly>
                        <button onclick="changeQty(1)" class="px-4 py-2 hover:bg-gray-100 border-l">+</button>
                    </div>
                    <button class="flex-1 bg-[#db4444] text-white font-bold py-3 rounded-md hover:bg-red-600 transition-all shadow-lg shadow-red-200">
                        Beli Sekarang
                    </button>
                </div>

                <div class="flex gap-4">
                    <a href="{{ url()->previous() }}" class="flex-1 border border-gray-300 text-center py-3 rounded-md font-semibold hover:bg-gray-50 transition-all">
                        Kembali
                    </a>
                    
                    <button 
                        type="button"
                        onclick="addToCart('{{ $product->id }}', '{{ $product->name }}')" 
                        class="flex-1 border border-gray-300 py-3 rounded-md font-semibold hover:bg-gray-50 transition-all focus:ring-2 focus:ring-gray-200">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-[#2b2d2f] text-gray-400 py-6 text-center mt-auto">
        <p class="text-[11px] font-medium opacity-80">&copy; FAUZAAN ESPE 2026. Seluruh hak dilindungi undang-undang.</p>
    </footer>

    <div x-data="{ show: false, message: '' }" 
         @add-to-cart.window="message = $event.detail.product; show = true; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         class="fixed bottom-8 right-8 z-[100] bg-white shadow-[0_20px_50px_rgba(0,0,0,0.15)] rounded-2xl p-5 flex items-center gap-4 min-w-[300px] border border-gray-50">
        
        <div class="bg-green-500 p-2 rounded-xl text-white flex-shrink-0 shadow-lg shadow-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-black uppercase tracking-wider text-gray-400 mb-0.5">Berhasil</p>
            <p class="text-sm font-bold text-gray-800" x-text="message"></p>
        </div>
        <button @click="show = false" class="ml-auto text-gray-300 hover:text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <script>
        // Fitur Pemilihan Ukuran
        function selectSize(btn) {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        // Fitur Tambah/Kurang Jumlah
        function changeQty(amt) {
            const input = document.getElementById('qty');
            let val = parseInt(input.value) + amt;
            if(val < 1) val = 1;
            input.value = val;
        }

        // Fitur Tambah ke Keranjang
        function addToCart(productId, productName) {
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if(response.ok) {
                    // Memicu event AlpineJS untuk memunculkan pop-up hijau
                    window.dispatchEvent(new CustomEvent('add-to-cart', { 
                        detail: { product: productName } 
                    }));
                } else {
                    console.error("Gagal menambahkan barang");
                }
            })
            .catch(error => {
                console.error('Error Jaringan:', error);
            });
        }
    </script>
</body>
</html>