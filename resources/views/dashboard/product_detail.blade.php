@extends('layouts.app')

@section('title', 'Detail Produk - SHOEGAZE')

@section('content')
@if($errors->any())
    <div class="max-w-8xl mx-auto px-6 md:px-16 py-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- TAMBAHKAN INI DI BAWAHNYA --}}
@if(session('error'))
    <div class="max-w-8xl mx-auto px-6 md:px-16 py-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    </div>
@endif
<div class="min-h-screen flex flex-col">
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

                    <form id="product_form" method="POST" action="{{ route('cart.add', $product->id) }}" class="space-y-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size" id="selected_size" value="">
                        <input type="hidden" name="quantity" id="selected_quantity" value="1">

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
                                name="action"
                                value="buy_now"
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
                                name="action"
                                value="cart"
                                data-ajax="true"
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

    <div x-data="{ show: {{ session('success') ? 'true' : 'false' }}, message: '{{ session('success') ?? '' }}' }"
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
</div>

@push('styles')
<style>
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
@endpush

@push('scripts')
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
        const sizeInfo = document.getElementById('size_info');
        const buyButton = document.getElementById('buy_button');
        const addCartButton = document.getElementById('add_cart_button');
        const decreaseButton = document.getElementById('qty_decrease');
        const increaseButton = document.getElementById('qty_increase');

        let stockData = @json($product->stock);
        if (typeof stockData === 'string') {
            try {
                stockData = JSON.parse(stockData);
            } catch (e) {
                stockData = {};
            }
        }
        if (!stockData || typeof stockData !== 'object') {
            stockData = {};
        }

        let selectedSize = null;
        let selectedStock = 0;
        let qty = 1;

        function updateButtons() {
            const enabled = Boolean(selectedSize) && selectedStock > 0;
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
            const sizeKey = button.dataset.size;
            let stock = 0;
            if (stockData && Object.prototype.hasOwnProperty.call(stockData, sizeKey)) {
                stock = Number(stockData[sizeKey]);
            } else {
                stock = Number(button.dataset.stock || 0);
            }
            if (Number.isNaN(stock) || stock < 0) {
                stock = 0;
            }
            button.dataset.stock = stock;

            if (stock <= 0) {
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.disabled = true;
            }

            button.addEventListener('click', function () {
                if (button.disabled) return;

                selectedSize = sizeKey;
                selectedStock = stock;
                qty = 1;
                selectedSizeInput.value = selectedSize;
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

        quantityDisplay.addEventListener('input', function () {
            qty = parseInt(quantityDisplay.value) || 1;
            updateQuantity();
        });

        buyButton.addEventListener('click', function () {
            // Action set via button name/value
        });

        addCartButton.addEventListener('click', function (e) {
            e.preventDefault();
            if (addCartButton.disabled) return;

            const formData = new FormData(form);
            formData.append('action', 'cart');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Trigger the success notification
                    window.dispatchEvent(new CustomEvent('add-to-cart', { detail: { product: data.message } }));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menambahkan ke keranjang.');
            });
        });

        // Cari bagian ini di bagian bawah file detail.blade.php Anda
form.addEventListener('submit', function (event) {
    // 1. CEK: Apakah ini klik tombol 'Beli Sekarang' atau 'Tambah Keranjang'?
    // event.submitter adalah tombol yang baru saja Anda klik
    const actionType = event.submitter.getAttribute('value');

    // --- DI SINI LETAK KODENYA ---
    if (actionType === 'buy_now') {
        // Jika klik Beli Sekarang, redirect ke GET checkout.direct dengan query params
        event.preventDefault();
        const productId = "{{ $product->id }}";
        const size = selectedSize;
        const quantity = qty;
        window.location.href = "{{ route('checkout.direct') }}?product_id=" + productId + "&size=" + size + "&quantity=" + quantity;
        return;
    } else {
        // Jika klik Tambah Keranjang, form akan dikirim ke route cart.add (seperti biasa)
        form.action = "{{ route('cart.add', $product->id) }}";
    }
    // ----------------------------

    // Kode validasi di bawah ini jangan dihapus, biarkan saja
    if (!selectedSize) {
        alert('Silakan pilih ukuran terlebih dahulu!');
        event.preventDefault();
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
@endpush
@endsection
