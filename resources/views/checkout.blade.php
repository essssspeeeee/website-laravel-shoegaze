<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity">
                <h1 class="font-extrabold text-2xl text-slate-950 tracking-tight">SHOEGAZE</h1>
            </a>
            <!-- Search & Icons -->
            <div class="flex items-center gap-4 md:gap-6">
                <!-- Search Input -->
                <form action="{{ route('search') }}" method="GET" class="hidden sm:flex items-center bg-slate-100 rounded-full px-4 py-2">
                    <input 
                        type="text" 
                        name="query"
                        value="{{ request('query') }}"
                        placeholder="Mau cari apa?" 
                        class="bg-transparent text-sm outline-none w-40 md:w-48 text-slate-900 placeholder-slate-500">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600 ml-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
                <!-- Shopping Cart Icon -->
                <a href="{{ route('cart') }}" class="text-slate-600 hover:text-slate-950 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </a>
                <!-- Profile Icon -->
                <a href="{{ route('profile') }}" class="text-slate-600 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <form method="POST" action="{{ route('checkout') }}" class="flex-grow max-w-6xl mx-auto w-full px-4 py-8" x-data="checkout" x-init="init()">
        @csrf
        @foreach($cart as $item)
            @if(isset($item['id']))
                <input type="hidden" name="selected_items[]" value="{{ $item['id'] }}">
            @endif
        @endforeach

        <h2 class="text-2xl font-bold mb-6 text-red-600">Check-out</h2>

        <!-- Alamat pengiriman -->
        <div class="bg-white rounded-xl shadow-sm mb-8 p-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.38 0 2.5-1.12 2.5-2.5S13.38 6 12 6 9.5 7.12 9.5 8.5 10.62 11 12 11z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9a9 9 0 0118 0c0 4.97-4.03 9-9 9z" />
                </svg>
                <div>
                    <p class="font-bold text-gray-800" x-text="selectedAddressObj.name + ' · ' + selectedAddressObj.phone"></p>
                    <p class="text-sm text-gray-500" x-text="selectedAddressObj.full"></p>
                </div>
            </div>
            <a href="#" class="text-red-600 font-bold" @click.prevent="openAddressModal()">Ubah</a>
        </div>

        <!-- Daftar produk + pengiriman dalam satu kartu -->
        <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden border border-gray-100" :class="{'ring-2 ring-blue-500': showShippingModal}">
            <div class="grid grid-cols-12 gap-4 p-4 bg-gray-50 border-b border-gray-200 font-bold text-xs uppercase tracking-wider text-gray-600 items-center">
                <div class="col-span-6">Produk</div>
                <div class="col-span-2 text-center">Harga</div>
                <div class="col-span-2 text-center">Kuantitas</div>
                <div class="col-span-1 text-right">Subtotal</div>
            </div>
            <div class="divide-y divide-gray-100">
                <template x-for="(item,id) in items" :key="id">
                    <div class="grid grid-cols-12 gap-4 p-4 items-center">
                        <div class="col-span-6 flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                                <img :src="'/' + item.image" alt="Sepatu" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="text-sm font-bold text-gray-800 block" x-text="item.name"></span>
                                <span class="text-xs text-gray-400">Varian: Default</span>
                            </div>
                        </div>
                        <div class="col-span-2 text-center text-sm font-semibold text-gray-700" x-text="formatRupiah(item.price)"></div>
                        <div class="col-span-2 text-center text-sm font-semibold text-gray-700" x-text="item.quantity"></div>
                        <div class="col-span-1 text-right text-sm font-black text-gray-900" x-text="formatRupiah(item.price * item.quantity)"></div>
                    </div>
                </template>
                <div x-show="Object.keys(items).length === 0" class="p-12 text-center flex flex-col items-center" x-cloak>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-500 font-medium">Keranjang belanjamu masih kosong.</p>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 text-right font-bold">
                Total Pesanan (<span x-text="Object.keys(items).length"></span> Produk): <span x-text="formatRupiah(subtotal)"></span>
            </div>

            <!-- bagian opsi pengiriman di dalam kartu yang sama -->
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Opsi Pengiriman</h3>
                <div class="flex items-center justify-between p-4 border rounded-lg bg-green-50 border-green-500">
                    <div>
                        <div class="text-xs text-gray-500" x-text="selectedShippingObj.desc"></div>
                        <div class="font-bold" x-text="selectedShippingObj.name"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-800" x-text="formatRupiah(selectedShippingObj.cost)"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-5H3v5a2 2 0 002 2h2a2 2 0 002-2zM7 12V5a2 2 0 012-2h6l3 4v5m-2 0h-4m-4 0h-4" />
                        </svg>
                    </div>
                </div>
                <a href="#" class="text-sm text-gray-500 font-bold" @click.prevent="openShippingModal()">Lihat Semua &gt;</a>
            </div>
        </div>

        <!-- Metode pembayaran -->
        <div class="bg-white rounded-xl shadow-sm mb-8 p-6 border border-gray-100">
            <h3 class="text-lg font-bold mb-4">Metode Pembayaran</h3>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="cursor-pointer rounded-3xl border p-5 transition duration-150 ease-in-out hover:border-red-500" :class="paymentMethod === 'qris' ? 'border-red-600 bg-red-50 shadow-sm' : 'border-gray-200 bg-white'">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold">QRIS</div>
                            <div class="text-xs text-gray-500 mt-1">Bayar melalui QRIS.</div>
                        </div>
                        <div class="h-5 w-5 rounded-full border transition" :class="paymentMethod === 'qris' ? 'bg-red-600 border-red-600' : 'bg-white border-gray-300'"></div>
                    </div>
                    <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="sr-only">
                </label>
                <label class="cursor-pointer rounded-3xl border p-5 transition duration-150 ease-in-out hover:border-red-500" :class="paymentMethod === 'cod' ? 'border-red-600 bg-red-50 shadow-sm' : 'border-gray-200 bg-white'">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold">COD</div>
                            <div class="text-xs text-gray-500 mt-1">Bayar saat barang tiba.</div>
                        </div>
                        <div class="h-5 w-5 rounded-full border transition" :class="paymentMethod === 'cod' ? 'bg-red-600 border-red-600' : 'bg-white border-gray-300'"></div>
                    </div>
                    <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="sr-only">
                </label>
            </div>

        </div>

        <!-- Detail pembayaran -->
        <div class="bg-white rounded-xl shadow-sm mb-8 p-6 border border-gray-100">
            <h3 class="text-lg font-bold mb-4">Detail Pembayaran</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Order Subtotal</span><span x-text="formatRupiah(subtotal)"></span></div>
                <div class="flex justify-between"><span>Shipping Subtotal</span><span x-text="formatRupiah(shippingCost)"></span></div>
                <div class="flex justify-between font-bold border-t pt-2"><span>Total</span><span x-text="formatRupiah(grandTotal)"></span></div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between">
            <a href="{{ route('cart') }}" class="px-6 py-3 border border-gray-300 rounded-xl font-bold hover:bg-gray-100 transition">Kembali</a>
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition">Beli Sekarang</button>
        </div>
    </form>

    <footer class="w-full bg-zinc-900 flex items-center justify-center py-6 mt-auto">
        <span class="text-zinc-400 text-xs font-sans">© FAUZAAN ESPE 2026. Seluruh hak dilindungi undang-undang.</span>
    </footer>

    <!-- hidden container for PHP data -->
    <div id="cart-data" data-items='@json($cart)' class="hidden"></div>
    <div id="address-data" data-addresses='@json($addresses)' class="hidden"></div>

    <!-- modal untuk memilih opsi pengiriman -->
    <div x-show="showShippingModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl overflow-hidden">
            <div class="bg-red-600 p-4">
                <h2 class="text-white text-lg font-bold">Opsi Pengiriman</h2>
                <p class="text-red-100 text-sm">Pilih metode pengiriman yang paling sesuai untuk pesanan Anda</p>
            </div>
            <div class="p-6 space-y-4">
                <template x-for="(option,idx) in shippingOptions" :key="idx">
                    <div class="p-4 border rounded-lg cursor-pointer" :class="{'border-green-500 bg-green-50': tempShipping === option.id}" @click="tempShipping = option.id">
                        <div class="flex justify-between items-center">
                            <div>
                                <template x-if="option.header">
                                    <div class="text-xs text-gray-500" x-text="option.header"></div>
                                </template>
                                <span class="font-bold" x-text="option.name"></span><br>
                                <span class="text-xs text-gray-500" x-text="option.desc"></span>
                            </div>
                            <span class="font-bold" x-text="formatRupiah(option.cost)"></span>
                        </div>
                    </div>
                </template>
            </div>
            <div class="p-4 flex justify-end gap-4 bg-gray-100">
                <button class="px-4 py-2 bg-gray-300 rounded" @click="cancelShipping()">Batal</button>
                <button class="px-4 py-2 bg-red-600 text-white rounded" @click="confirmShipping()">Konfirmasi Pilihan Pengiriman</button>
            </div>
        </div>
    </div>

    <!-- address selection modal -->
    <div x-show="showAddressModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-40">
        <div class="bg-white w-full max-w-2xl rounded-xl overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-bold">Pilih Alamat</h2>
            </div>
            <div class="p-6 space-y-4">
                <template x-for="addr in addresses" :key="addr.id">
                    <div class="p-4 border rounded-lg flex justify-between items-start" :class="{'bg-green-50 border-green-500': selectedAddressId === addr.id}">
                        <div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.38 0 2.5-1.12 2.5-2.5S13.38 6 12 6 9.5 7.12 9.5 8.5 10.62 11 12 11z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9a9 9 0 0118 0c0 4.97-4.03 9-9 9z" />
                                </svg>
                                <span class="font-bold" x-text="addr.name + ' · ' + addr.phone"></span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1" x-text="addr.full"></p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <button class="text-red-600 text-sm" @click="openEditAddress(addr)">Edit</button>
                            <input type="radio" name="seladdr" class="form-radio" :checked="selectedAddressId === addr.id" @click="selectAddress(addr.id)">
                        </div>
                    </div>
                </template>
            </div>
            <div class="p-4 bg-gray-100 flex justify-end gap-4">
                <button class="px-6 py-2 border rounded" @click="showAddressModal=false">Batal</button>
                <button class="px-6 py-2 bg-red-600 text-white rounded" @click="confirmAddress()">Pilih</button>
            </div>
        </div>
    </div>
    <!-- MODAL 1: PILIH ALAMAT (3 slot) -->
    <div id="modal-pilih-alamat" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-8 flex flex-col">
            <h2 class="text-xl font-bold text-center mb-6">Pilih Alamat</h2>
            <div class="space-y-4 flex-1 overflow-y-auto max-h-[50vh]">
                <!-- Slot 1: Alamat Utama (Profil) -->
                <div class="relative flex items-center gap-4 p-6 border rounded-3xl transition-all select-none bg-white"
                    :class="selectedAddressId === 1 ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200'"
                    :style="(profileAddressEmpty ? 'opacity:0.7;pointer-events:none;' : 'cursor:pointer;')"
                    @click="!profileAddressEmpty && selectAddress(1)">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.38 0 2.5-1.12 2.5-2.5S13.38 6 12 6 9.5 7.12 9.5 8.5 10.62 11 12 11z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9a9 9 0 0118 0c0 4.97-4.03 9-9 9z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-white bg-red-500 rounded-full px-3 py-1">Utama</span>
                            <span class="text-xs font-semibold text-slate-700">Alamat Utama (Profil)</span>
                        </div>
                        <template x-if="!profileAddressEmpty">
                            <div>
                                <div class="font-semibold text-slate-900">
                                    <span>{{ Auth::user()->name }}</span>
                                    <span class="text-xs text-slate-400 font-normal">({{ Auth::user()->phone }})</span>
                                </div>
                                <div class="text-sm text-slate-500">{{ Auth::user()->address }}</div>
                            </div>
                        </template>
                        <template x-if="profileAddressEmpty">
                            <div class="text-red-500 text-sm font-semibold">Alamat profil belum diisi! Silakan lengkapi di menu Profil.</div>
                        </template>
                    </div>
                    <button type="button" class="absolute top-3 right-3 text-red-500 text-xs font-bold px-3 py-1 rounded-full border border-red-100 bg-red-50 hover:bg-red-100 transition-all"
                        @click.stop="openEditAddressModal({name: '{{ Auth::user()->name }}', phone: '{{ Auth::user()->phone }}', full: `{{ trim(preg_replace('/\s+/', ' ', Auth::user()->address ?? '')) }}`})"
                        class="absolute top-3 right-3 text-red-500 text-xs font-bold px-3 py-1 rounded-full border border-red-100 bg-red-50 hover:bg-red-100 transition-all pointer-events-auto relative z-50"
                    >Edit</button>
                </div>
                <!-- Slot 2 & 3: Alamat Cadangan -->
                <template x-for="slot in [2,3]" :key="slot">
                    <div>
                        <template x-if="addresses[slot-2]">
                            <div class="relative flex items-center gap-4 p-6 border rounded-3xl transition-all cursor-pointer select-none bg-white"
                                :class="selectedAddressId === slot ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200'"
                                @click="selectAddress(slot)">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.38 0 2.5-1.12 2.5-2.5S13.38 6 12 6 9.5 7.12 9.5 8.5 10.62 11 12 11z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9a9 9 0 0118 0c0 4.97-4.03 9-9 9z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-900">
                                        <span x-text="addresses[slot-2].name"></span>
                                        <span class="text-xs text-slate-400 font-normal">(<span x-text="addresses[slot-2].phone"></span>)</span>
                                    </div>
                                    <div class="text-sm text-slate-500" x-text="addresses[slot-2].full"></div>
                                </div>
                                <button type="button" class="absolute top-3 right-3 text-red-500 text-xs font-bold px-3 py-1 rounded-full border border-red-100 bg-red-50 hover:bg-red-100 transition-all"
                                    @click.stop="openEditAddressModal(addresses[slot-2])"
                                    class="absolute top-3 right-3 text-red-500 text-xs font-bold px-3 py-1 rounded-full border border-red-100 bg-red-50 hover:bg-red-100 transition-all pointer-events-auto relative z-50"
                                >Edit</button>
                            </div>
                        </template>
                        <template x-if="!addresses[slot-2]">
                            <div class="relative flex items-center justify-center gap-2 p-6 border-2 border-dashed border-slate-300 rounded-3xl transition-all cursor-pointer select-none text-slate-400 bg-white"
                                @click="openEditAddressModal({})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="w-full text-center font-semibold">+ Tambah Alamat Cadangan</span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            <div class="flex gap-4 mt-8">
                <button type="button" class="flex-1 border border-slate-300 text-slate-700 rounded-full py-3 font-medium hover:bg-slate-100 transition-all" onclick="closeAllModals()">Batal</button>
                <button type="button" class="flex-1 bg-red-500 text-white rounded-full py-3 font-semibold shadow-md hover:bg-red-600 transition-all" @click="confirmAddressModal()">Pilih</button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: EDIT ALAMAT -->
    <div id="modal-edit-alamat" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-0 flex flex-col">
            <form id="form-edit-alamat" class="p-8 space-y-6">
                <h2 class="text-xl font-bold text-center mb-6 text-slate-950">Edit Alamat</h2>
                <div>
                    <label class="block text-sm font-semibold text-slate-950 mb-2">Nama Lengkap</label>
                    <input type="text" id="edit-nama" name="name" x-model="editingAddress.name" class="w-full bg-slate-100 rounded-full py-3 px-6 text-sm outline-none border-0 focus:ring-2 focus:ring-red-200" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-950 mb-2">Nomor Telepon</label>
                    <input type="text" id="edit-telp" name="phone" x-model="editingAddress.phone" class="w-full bg-slate-100 rounded-full py-3 px-6 text-sm outline-none border-0 focus:ring-2 focus:ring-red-200" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-950 mb-2">Alamat Lengkap</label>
                    <input type="text" id="edit-alamat" name="full" x-model="editingAddress.full" class="w-full bg-slate-100 rounded-full py-3 px-6 text-sm outline-none border-0 focus:ring-2 focus:ring-red-200" />
                </div>
                <div class="flex gap-4 mt-8">
                    <button type="button" class="flex-1 bg-slate-100 text-slate-700 rounded-full py-2.5 px-6 font-medium hover:bg-slate-200 transition-all border-0" onclick="closeAllModals()">Batal</button>
                    <button type="submit" class="flex-1 bg-red-500 text-white rounded-full py-2.5 px-8 font-semibold shadow-md hover:bg-red-600 transition-all border-0">Ubah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- address edit modal -->
    <div x-show="showAddressEditModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-bold">Edit Alamat</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                        <input type="text" x-model="editingAddress.name" class="w-full border bg-gray-100 px-4 py-2 rounded" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Nomor Telepon</label>
                        <input type="text" x-model="editingAddress.phone" class="w-full border bg-gray-100 px-4 py-2 rounded" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Provinsi, Kota, Kecamatan, Kode Pos</label>
                        <input type="text" x-model="editingAddress.provinsi" class="w-full border bg-gray-100 px-4 py-2 rounded" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Nama Jalan, Gedung, No. Rumah</label>
                        <input type="text" x-model="editingAddress.jalan" class="w-full border bg-gray-100 px-4 py-2 rounded" />
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-100 flex justify-end gap-4">
                <button class="px-6 py-2 border rounded" @click="cancelAddressEdit()">Batal</button>
                <button class="px-6 py-2 bg-red-600 text-white rounded" @click="saveAddressEdit()">Ubah</button>
            </div>
        </div>
    </div>

    <script>
        function checkout() {
            return {
                items: {},
                shippingOptions: [
                    {id: 'reguler', header: 'Garansi Tepat Waktu', name: 'Reguler', desc: 'Paket tiba dalam 1-3 hari pemesanan', cost: 8000},
                    {id: 'standar', name: 'Pengiriman Standar', desc: 'Hemat Kargo', cost: 6500},
                    {id: 'ekspres', name: 'Pengiriman Ekspres', desc: 'Instant', cost: 15000},
                ],
                selectedShipping: 'reguler',
                paymentMethod: 'qris',
                subtotal: 0,
                shippingCost: 0,
                grandTotal: 0,
                showShippingModal: false,
                tempShipping: null,

                // address selection/edit state
                addresses: [],
                selectedAddressId: null,
                showAddressModal: false,
                showAddressEditModal: false,
                editingAddress: null,

                init() {
                    this.items = JSON.parse(document.getElementById('cart-data').dataset.items);
                    this.addresses = JSON.parse(document.getElementById('address-data').dataset.addresses);
                    this.selectedAddressId = this.addresses.length ? this.addresses[0].id : null;
                    this.calculateTotal();
                },
                
                get selectedShippingObj() {
                    return this.shippingOptions.find(o => o.id === this.selectedShipping) || {name:'',cost:0,desc:''};
                },

                get selectedAddressObj() {
                    return this.addresses.find(a => a.id === this.selectedAddressId) || {name:'',phone:'',full:'-'};
                },

                openAddressModal() {
                    this.showAddressModal = true;
                },
                selectAddress(id) {
                    this.selectedAddressId = id;
                },
                confirmAddress() {
                    this.showAddressModal = false;
                },

                openEditAddress(address) {
                    // make a shallow copy for editing
                    this.editingAddress = {...address};
                    this.showAddressEditModal = true;
                },
                cancelAddressEdit() {
                    this.showAddressEditModal = false;
                },
                saveAddressEdit() {
                    // rebuild full string
                    this.editingAddress.full = (this.editingAddress.provinsi || '') + ' ' + (this.editingAddress.jalan || '');
                    const idx = this.addresses.findIndex(a => a.id === this.editingAddress.id);
                    if(idx !== -1) this.addresses.splice(idx, 1, this.editingAddress);
                    // if currently selected, update selection object implicitly via getter
                    this.showAddressEditModal = false;
                },

                openShippingModal() {
                    this.tempShipping = this.selectedShipping;
                    this.showShippingModal = true;
                },
                cancelShipping() {
                    this.showShippingModal = false;
                },
                confirmShipping() {
                    this.selectedShipping = this.tempShipping;
                    this.calculateTotal();
                    this.showShippingModal = false;
                },

                calculateTotal() {
                    this.subtotal = Object.values(this.items)
                        .reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    const selected = this.shippingOptions.find(o => o.id === this.selectedShipping);
                    this.shippingCost = selected ? selected.cost : 0;
                    this.grandTotal = this.subtotal + this.shippingCost;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                }
            }
        }
    </script>
    <script>
        // Shoegaze minimal modal logic for 3 address slots
        function closeAllModals() {
            document.getElementById('modal-pilih-alamat').classList.add('hidden');
            document.getElementById('modal-edit-alamat').classList.add('hidden');
        }
        function openPilihAlamatModal() {
            document.getElementById('modal-pilih-alamat').classList.remove('hidden');
            document.getElementById('modal-edit-alamat').classList.add('hidden');
        }
        function openEditAddressModal(data) {
            document.getElementById('modal-pilih-alamat').classList.add('hidden');
            document.getElementById('modal-edit-alamat').classList.remove('hidden');
            // Fill modal fields with address data if exists
            document.getElementById('edit-nama').value = data && data.name ? data.name : '';
            document.getElementById('edit-telp').value = data && data.phone ? data.phone : '';
            document.getElementById('edit-alamat').value = data && data.full ? data.full : '';
            window.editingSlot = data && data.slot ? data.slot : null;
        }
        function confirmAddressModal() {
            // If selected slot is empty, open edit modal
            if(window.selectedAddressId === 1 && !window.profileAddress) {
                openEditAddressModal(1); return;
            }
            if((window.selectedAddressId === 2 || window.selectedAddressId === 3) && (!window.addresses || !window.addresses[window.selectedAddressId-2])) {
                openEditAddressModal(window.selectedAddressId); return;
            }
            closeAllModals();
        }

        // Alpine.js checkout logic for 3 address slots
        function checkout() {
            return {
                items: {},
                shippingOptions: [
                    {id: 'reguler', header: 'Garansi Tepat Waktu', name: 'Reguler', desc: 'Paket tiba dalam 1-3 hari pemesanan', cost: 8000},
                    {id: 'standar', name: 'Pengiriman Standar', desc: 'Hemat Kargo', cost: 6500},
                    {id: 'ekspres', name: 'Pengiriman Ekspres', desc: 'Instant', cost: 15000},
                ],
                selectedShipping: 'reguler',
                paymentMethod: 'qris',
                subtotal: 0,
                shippingCost: 0,
                grandTotal: 0,
                showShippingModal: false,
                tempShipping: null,

                // 3 slot address system
                addresses: [], // slot 2 & 3
                selectedAddressId: 1, // 1: profile, 2: slot2, 3: slot3
                profileAddress: null, // slot 1
                profileAddressEmpty: false,
                showAddressModal: false,
                showAddressEditModal: false,
                editingAddress: null,

                init() {
                    this.items = JSON.parse(document.getElementById('cart-data').dataset.items);
                    this.addresses = JSON.parse(document.getElementById('address-data').dataset.addresses) || [];
                    // Slot 1: from PHP user profile
                    this.profileAddress = {
                        name: '{{ Auth::user()->name ?? '' }}',
                        phone: '{{ Auth::user()->phone ?? '' }}',
                        full: `{{ trim(preg_replace('/\s+/', ' ', Auth::user()->address ?? '')) }}`
                    };
                    this.profileAddressEmpty = !this.profileAddress.full || this.profileAddress.full === '-';
                    window.profileAddress = this.profileAddress;
                    window.addresses = this.addresses;
                    window.selectedAddressId = this.selectedAddressId;
                    this.calculateTotal();
                },
                
                get selectedShippingObj() {
                    return this.shippingOptions.find(o => o.id === this.selectedShipping) || {name:'',cost:0,desc:''};
                },

                get selectedAddressObj() {
                    if(this.selectedAddressId === 1) return this.profileAddress && !this.profileAddressEmpty ? this.profileAddress : {name:'',phone:'',full:'-'};
                    if(this.selectedAddressId === 2 && this.addresses[0]) return this.addresses[0];
                    if(this.selectedAddressId === 3 && this.addresses[1]) return this.addresses[1];
                    return {name:'',phone:'',full:'-'};
                },

                openAddressModal() {
                    openPilihAlamatModal();
                },
                selectAddress(id) {
                    this.selectedAddressId = id;
                    window.selectedAddressId = id;
                },
                confirmAddress() {
                    confirmAddressModal();
                },

                openEditAddress(address) {
                    openEditAddressModal(address);
                },
                cancelAddressEdit() {
                    closeAllModals();
                },
                saveAddressEdit() {
                    // Save edited address to correct slot
                    const slot = window.editingSlot;
                    const name = document.getElementById('edit-nama').value;
                    const phone = document.getElementById('edit-telp').value;
                    const full = document.getElementById('edit-alamat').value;
                    if(slot === 1) {
                        this.profileAddress = {name, phone, full};
                        this.profileAddressEmpty = !full;
                        window.profileAddress = this.profileAddress;
                    } else if(slot === 2 || slot === 3) {
                        this.addresses[slot-2] = {name, phone, full};
                        window.addresses = this.addresses;
                    }
                    closeAllModals();
                },

                openShippingModal() {
                    this.tempShipping = this.selectedShipping;
                    this.showShippingModal = true;
                },
                cancelShipping() {
                    this.showShippingModal = false;
                },
                confirmShipping() {
                    this.selectedShipping = this.tempShipping;
                    this.calculateTotal();
                    this.showShippingModal = false;
                },

                calculateTotal() {
                    this.subtotal = Object.values(this.items)
                        .reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    const selected = this.shippingOptions.find(o => o.id === this.selectedShipping);
                    this.shippingCost = selected ? selected.cost : 0;
                    this.grandTotal = this.subtotal + this.shippingCost;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                }
            }
        }
    </script>
</body>
</html>