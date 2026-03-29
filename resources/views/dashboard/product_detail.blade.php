<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; }

        .thumbnail-btn {
            aspect-ratio: 1 / 1;
            border: 1px solid #E5E7EB;
            border-radius: 1.25rem;
            transition: border-color 0.2s ease, transform 0.2s ease;
        }

        .thumbnail-btn:hover {
            border-color: #E55353;
            transform: translateY(-1px);
        }

        .border-danger {
            border-color: #E55353 !important;
        }

        .size-button {
            background: #ffffff;
            border: 1px solid #D1D5DB;
            color: #111827;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .size-button.active {
            background: #E55353;
            color: #ffffff;
            border-color: #E55353;
        }

        .size-button.disabled {
            background: #F3F4F6;
            color: #9CA3AF;
            border-color: #E5E7EB;
            cursor: not-allowed;
        }

        .btn-buy {
            background: #E55353;
            color: #ffffff;
            font-weight: 700;
        }

        button:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="antialiased text-gray-900 flex flex-col min-h-screen">

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


    <main class="max-w-8xl mx-auto px-6 md:px-16 py-10 flex-grow">
        <h2 class="text-3xl font-bold mb-10">Detail Produk</h2>

        <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_0.9fr] gap-5 xl:gap-12">
            <div x-data="gallery()" class="flex flex-col md:flex-row gap-5">
                <div class="flex flex-col gap-4 w-[100px]">
                    @foreach($product->images ?? [] as $index => $image)
                        <button type="button"
                            @click="setMain({{ $index }})"
                            :class="['thumbnail-btn overflow-hidden', selectedImage === {{ $index }} ? 'border-[#E55353]' : 'border-gray-200']">
                            <img src="{{ asset('storage/' . $image) }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>

                <div class="flex-1 bg-[#f6f6f6] rounded-[28px] p-6 flex items-center justify-center min-h-[520px]">
                    <img :src="mainImage" class="max-w-full max-h-full object-contain drop-shadow-2xl">
                </div>
            </div>

            <div class="flex flex-col justify-between">
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tight leading-tight">
                            {{ strtoupper($product->name) }}
                        </h1>
                        <p class="text-3xl font-semibold text-gray-900">Rp {{ number_format($product->price,0,',','.') }}</p>
                    </div>

                    <div class="text-sm text-gray-600 leading-relaxed space-y-4">
                        <p>{{ $product->description ?? 'Deskripsi produk tidak tersedia.' }}</p>
                    </div>

                    <form id="product_form" action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size" id="selected_size" value="">
                        <input type="hidden" name="quantity" id="selected_quantity" value="1">
                        <input type="hidden" name="action" id="selected_action" value="cart">

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold">Ukuran</span>
                                <span class="text-sm text-gray-500">Pilih ukuran yang tersedia</span>
                            </div>

                            <div class="grid grid-cols-5 gap-3 max-w-[280px]" id="size_buttons">
                                @foreach(['39','40','41','42','43'] as $size)
                                    @php $stockValue = $product->stock[$size] ?? 0; @endphp
                                    <button type="button"
                                        class="size-button h-12 rounded-[18px] {{ $stockValue <= 0 ? 'disabled' : '' }}"
                                        data-size="{{ $size }}"
                                        data-stock="{{ $stockValue }}"
                                        {{ $stockValue <= 0 ? 'disabled' : '' }}>
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>

                            <p class="text-sm text-gray-500" id="size_info">Silakan pilih ukuran terlebih dahulu.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-[auto_1fr] items-center">
                            <div class="flex items-center border border-gray-300 rounded-2xl overflow-hidden max-w-[200px] h-14">
                                <button type="button" id="qty_decrease"
                                    class="px-4 h-full text-lg font-bold text-gray-700 hover:bg-gray-100 transition-colors flex items-center justify-center">
                                    -
                                </button>
                                <input type="text" id="quantity_display" value="1" class="w-16 h-full text-center border-x border-gray-300 outline-none text-sm" readonly>
                                <button type="button" id="qty_increase"
                                    class="px-4 h-full text-lg font-bold text-gray-700 hover:bg-gray-100 transition-colors flex items-center justify-center">
                                    +
                                </button>
                            </div>

                            <button type="submit"
                                id="buy_button"
                                data-action="buy"
                                disabled
                                class="w-full btn-buy h-14 rounded-2xl shadow-lg shadow-[#E55353]/20 transition-all hover:bg-[#d04141] disabled:opacity-50 disabled:cursor-not-allowed">
                                Beli Sekarang
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <a href="{{ url()->previous() }}" class="block border border-gray-300 text-center py-3 rounded-2xl font-semibold hover:bg-gray-50 transition-all">
                                Kembali
                            </a>
                            <button type="submit"
                                id="add_cart_button"
                                data-action="cart"
                                disabled
                                class="block w-full border border-gray-300 py-3 rounded-2xl font-semibold hover:bg-gray-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full bg-zinc-900 flex items-center justify-center py-6 mt-auto">
        <span class="text-zinc-400 text-xs font-sans">© FAUZAAN ESPE 2026. Seluruh hak dilindungi undang-undang.</span>
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
        function gallery() {
            const rawImages = @json($product->images ?? []) || [];
            const basePath = '{{ asset('storage') }}';
            const images = rawImages.length
                ? rawImages.map(image => image ? basePath + '/' + image : null).filter(Boolean)
                : [];

            return {
                images: images.length ? images : ['https://via.placeholder.com/500'],
                mainImage: images.length ? images[0] : 'https://via.placeholder.com/500',
                selectedImage: 0,
                setMain(i) {
                    this.mainImage = this.images[i];
                    this.selectedImage = i;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('product_form');
            const sizeButtons = document.querySelectorAll('#size_buttons button[data-size]');
            const selectedSizeInput = document.getElementById('selected_size');
            const quantityDisplay = document.getElementById('quantity_display');
            const selectedQuantityInput = document.getElementById('selected_quantity');
            const selectedActionInput = document.getElementById('selected_action');
            const sizeInfo = document.getElementById('size_info');
            const buyButton = document.getElementById('buy_button');
            const addCartButton = document.getElementById('add_cart_button');
            const decreaseButton = document.getElementById('qty_decrease');
            const increaseButton = document.getElementById('qty_increase');

            let selectedSize = null;
            let selectedStock = 0;
            let qty = 1;

            function updateButtons() {
                const enabled = Boolean(selectedSize);
                buyButton.disabled = !enabled;
                addCartButton.disabled = !enabled;
            }

            function updateQuantity() {
                if (qty < 1) qty = 1;
                if (selectedSize && qty > selectedStock) qty = selectedStock;
                quantityDisplay.value = qty;
                selectedQuantityInput.value = qty;
            }

            function updateSizeInfo() {
                if (!selectedSize) {
                    sizeInfo.textContent = 'Silakan pilih ukuran terlebih dahulu.';
                } else if (selectedStock === 0) {
                    sizeInfo.textContent = 'Stok habis';
                } else {
                    sizeInfo.textContent = 'Sisa ' + selectedStock + ' pasang';
                }
            }

            function clearActiveSize() {
                sizeButtons.forEach(button => {
                    button.classList.remove('border-danger');
                });
            }

            sizeButtons.forEach(button => {
                const stock = parseInt(button.dataset.stock || '0', 10);
                if (stock <= 0) {
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                }

                button.addEventListener('click', function () {
                    if (button.disabled) return;

                    selectedSize = button.dataset.size;
                    selectedStock = stock;
                    qty = 1;
                    selectedSizeInput.value = selectedSize;
                    selectedActionInput.value = 'cart';
                    clearActiveSize();
                    button.classList.add('border-danger');
                    updateQuantity();
                    updateSizeInfo();
                    updateButtons();
                });
            });

            decreaseButton.addEventListener('click', function () {
                if (!selectedSize) return;
                if (qty > 1) {
                    qty -= 1;
                    updateQuantity();
                }
            });

            increaseButton.addEventListener('click', function () {
                if (!selectedSize) return;
                if (qty < selectedStock) {
                    qty += 1;
                    updateQuantity();
                }
            });

            buyButton.addEventListener('click', function () {
                selectedActionInput.value = 'buy';
            });

            addCartButton.addEventListener('click', function () {
                selectedActionInput.value = 'cart';
            });

            form.addEventListener('submit', function (event) {
                if (!selectedSize) {
                    event.preventDefault();
                    alert('Silakan pilih ukuran terlebih dahulu!');
                    return;
                }

                selectedSizeInput.value = selectedSize;
                updateQuantity();
            });

            updateButtons();
            updateQuantity();
            updateSizeInfo();
        });
    </script>
</body>
</html>