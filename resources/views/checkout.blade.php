@extends('layouts.app')

@section('title', 'Checkout - Shoegaze')

@section('content')
@if($errors->any())
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
<div x-data="checkoutPage()" x-init="initCheckout()" class="min-h-screen bg-slate-50 py-8 text-slate-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div x-show="showNotification" x-cloak x-transition.opacity.duration.300ms x-transition.scale.duration.300ms class="fixed bottom-6 right-6 z-50 w-full max-w-sm rounded-[28px] px-5 py-4 shadow-2xl" :class="notificationType === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white'">
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    <template x-if="notificationType === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 00-1.408-1.42L8 11.142 4.704 7.84a1 1 0 10-1.408 1.42l4 4a1 1 0 001.408 0l8-8z" clip-rule="evenodd" />
                        </svg>
                    </template>
                    <template x-if="notificationType === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4a1 1 0 112 0v1a1 1 0 11-2 0v-1zm0-8a1 1 0 112 0v5a1 1 0 11-2 0V6z" clip-rule="evenodd" />
                        </svg>
                    </template>
                </div>
                <div>
                    <p class="font-semibold" x-text="notificationMessage"></p>
                </div>
            </div>
        </div>
        <div class="grid gap-6">
            <div class="bg-white rounded-[32px] shadow-[0_20px_70px_-40px_rgba(15,23,42,0.2)] p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm text-red-600 font-semibold uppercase tracking-[0.24em]">Check-out</p>
                        <h2 class="mt-3 text-xl sm:text-2xl font-semibold text-slate-900">Alamat Pengiriman</h2>
                        <p class="mt-3 text-sm text-slate-500">Gunakan alamat yang telah dipilih untuk pengiriman order.</p>
                    </div>
                    <button type="button" @click="showAddressModal = true" class="text-red-600 font-semibold hover:text-red-700">Ubah Alamat</button>
                </div>
                <div class="mt-6 rounded-[28px] border border-slate-200 bg-slate-50 p-5 sm:p-6">
                    <p class="text-sm font-semibold text-slate-900" x-text="activeAddress().name + ' · ' + activeAddress().phone"></p>
                    <p class="mt-2 text-base font-medium text-slate-900" x-text="activeAddress().jalan"></p>
                    <p class="mt-1 text-sm text-slate-500" x-text="activeAddress().provinsi"></p>
                </div>
            </div>

            <div class="bg-white rounded-[32px] shadow-[0_20px_70px_-40px_rgba(15,23,42,0.2)] overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">ShoeGaze</p>
                            <h3 class="mt-2 text-lg sm:text-xl font-semibold text-slate-900">Detail Produk</h3>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 space-y-5">
                    @foreach($cart as $item)
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-5 last:border-0 last:pb-0">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                <img src="{{ $item['image'] ?: asset('images/default-product.png') }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm uppercase tracking-[0.24em] text-slate-500 truncate">{{ $item['name'] }}</p>
                                <p class="mt-2 text-base font-semibold text-slate-900">Size {{ $item['size'] }}</p>
                                <p class="mt-1 text-sm text-slate-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-500">Qty {{ $item['quantity'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 pb-6">
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span>Total Pesanan ({{ collect($cart)->sum('quantity') }} Produk)</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format(collect($cart)->sum(function($item) { return $item['price'] * $item['quantity']; }), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[32px] shadow-[0_20px_70px_-40px_rgba(15,23,42,0.2)] p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Opsi Pengiriman</p>
                        <p class="mt-1 text-sm text-slate-500">Pilih metode kirim yang tersedia</p>
                    </div>
                    <button type="button" @click="showShippingModal = true" class="text-sm font-semibold text-slate-700 hover:text-slate-900">Lihat Semua &gt;</button>
                </div>
                <div class="mt-5 rounded-[28px] border border-emerald-200 bg-emerald-50/80 p-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-900" x-text="shippingTitle()"></p>
                        <p class="mt-1 text-sm text-slate-500" x-text="shippingNote()"></p>
                    </div>
                    <p class="text-sm font-semibold text-slate-900" x-text="formatCurrency(shippingCost())"></p>
                </div>
            </div>

            <div x-show="showShippingModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6">
                <div class="w-full max-w-lg rounded-[32px] bg-white shadow-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Opsi Pengiriman</p>
                            <h3 class="mt-3 text-xl font-semibold text-slate-900">Pilih metode pengiriman</h3>
                        </div>
                        <button type="button" @click="showShippingModal = false" class="rounded-full border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tutup</button>
                    </div>
                    <div class="p-5 space-y-3">
                        <div @click="selectShipping('reguler')" :class="selectedShipping === 'reguler' ? 'border-2 border-red-500 bg-red-50' : 'border border-slate-200 bg-slate-50'" class="rounded-[18px] p-4 cursor-pointer transition-colors duration-200">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Reguler</p>
                                    <p class="mt-1 text-sm text-slate-500">Paket tiba dalam 1-3 hari pemesanan</p>
                                </div>
                                <p class="text-sm font-semibold text-slate-900 whitespace-nowrap">Rp 8.000,00</p>
                            </div>
                        </div>
                        <div @click="selectShipping('standar')" :class="selectedShipping === 'standar' ? 'border-2 border-red-500 bg-red-50' : 'border border-slate-200 bg-slate-50'" class="rounded-[18px] p-4 cursor-pointer transition-colors duration-200">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Hemat Kargo</p>
                                    <p class="mt-1 text-sm text-slate-500">Estimasi 3-5 hari</p>
                                </div>
                                <p class="text-sm font-semibold text-slate-900 whitespace-nowrap">Rp 6.500,00</p>
                            </div>
                        </div>
                        <div @click="selectShipping('ekspres')" :class="selectedShipping === 'ekspres' ? 'border-2 border-red-500 bg-red-50' : 'border border-slate-200 bg-slate-50'" class="rounded-[18px] p-4 cursor-pointer transition-colors duration-200">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Instant</p>
                                    <p class="mt-1 text-sm text-slate-500">Tiba lebih cepat</p>
                                </div>
                                <p class="text-sm font-semibold text-slate-900 whitespace-nowrap">Rp 15.000,00</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                            <button type="button" @click="showShippingModal = false" class="rounded-[28px] border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                            <button type="button" @click="showShippingModal = false" class="rounded-[28px] bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="showAddressModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6">
                <div class="w-full max-w-lg rounded-[32px] bg-white shadow-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Pilih Alamat</p>
                            <h3 class="mt-3 text-xl font-semibold text-slate-900">Ubah alamat pengiriman</h3>
                        </div>
                        <button type="button" @click="showAddressModal = false" class="rounded-full border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tutup</button>
                    </div>
                    <div class="p-5 space-y-4">
                        <template x-for="(address, index) in checkoutAddresses" :key="address.id">
                            <div :class="address.is_empty ? 'border-dashed border-slate-300 bg-slate-50' : 'border border-slate-200 bg-slate-50'" class="rounded-[18px] p-4 transition-colors duration-200">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900" x-text="address.is_empty ? 'Slot ' + (index + 1) + ' - Tambah Alamat Baru' : address.name"></p>
                                        <p class="mt-2 text-sm text-slate-500" x-text="address.is_empty ? 'Masukkan alamat baru untuk pengiriman.' : address.phone"></p>
                                        <template x-if="!address.is_empty">
                                            <div class="mt-3 text-sm leading-6 text-slate-700">
                                                <p x-text="address.jalan"></p>
                                                <p class="mt-1 text-slate-500" x-text="address.provinsi"></p>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <button type="button" @click="selectAddress(index)" class="rounded-[28px] bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">Pilih</button>
                                        <button type="button" @click="openAddressForm(index)" class="rounded-[28px] bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700" x-text="address.is_empty ? 'Tambah' : 'Edit'"></button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                            <button type="button" @click="showAddressModal = false" class="rounded-[28px] border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="showAddressFormModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6">
                <div class="w-full max-w-md rounded-[32px] bg-white shadow-2xl overflow-hidden">
                    <form method="POST" action="{{ route('checkout.address.store') }}" class="space-y-4 p-5">
                        @csrf
                        <input type="hidden" name="slot_index" :value="currentFormSlot">
                        @foreach(request('selected_items', []) as $selectedId)
                            <input type="hidden" name="selected_items[]" value="{{ $selectedId }}">
                        @endforeach
                        <div class="flex items-center justify-between gap-4 pb-3 border-b border-slate-200">
                            <div>
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Edit/Tambah Alamat</p>
                                <h3 class="mt-3 text-xl font-semibold text-slate-900" x-text="currentFormSlot === 0 ? 'Alamat Utama' : 'Alamat Tambahan'"></h3>
                            </div>
                            <button type="button" @click="closeAddressForm()" class="rounded-full border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tutup</button>
                        </div>
                        <div class="grid gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" x-model="addressForm.name" class="w-full rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 focus:border-red-500 focus:outline-none" required>
                                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Nomor Telepon</label>
                                <input type="text" name="phone" x-model="addressForm.phone" class="w-full rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 focus:border-red-500 focus:outline-none" required>
                                @error('phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Alamat Lengkap</label>
                                <textarea name="jalan" x-model="addressForm.jalan" placeholder="Isi dengan Nama Jalan, No. Rumah, RT/RW, Kecamatan, Kota, Provinsi, Kode Pos" rows="4" class="w-full rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 focus:border-red-500 focus:outline-none" required></textarea>
                                @error('jalan')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                            <button type="button" @click="closeAddressForm()" class="rounded-[28px] border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                            <button type="submit" class="rounded-[28px] bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <form method="POST" action="{{ route('order.store') }}" class="space-y-6">
                @csrf
                @if(request('product_id'))
                    <input type="hidden" name="product_id" value="{{ request('product_id') }}">
                    <input type="hidden" name="quantity" value="{{ request('quantity', 1) }}">
                    <input type="hidden" name="size" value="{{ request('size') }}">
                @endif
                @foreach(request('selected_items', []) as $selectedId)
                    <input type="hidden" name="selected_items[]" value="{{ $selectedId }}">
                @endforeach
                <input type="hidden" name="shipping_method" :value="selectedShipping">
                <input type="hidden" name="shipping_cost" :value="shippingCost()">
                <input type="hidden" name="selected_address_index" :value="selectedAddressIndex">
                <input type="hidden" name="selected_address_name" :value="activeAddress().name">
                <input type="hidden" name="selected_address_phone" :value="activeAddress().phone">
                <input type="hidden" name="selected_address_jalan" :value="activeAddress().jalan">

                <div class="bg-white rounded-[32px] shadow-[0_20px_70px_-40px_rgba(15,23,42,0.2)] p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-slate-900">Metode Pembayaran</h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <label class="flex items-center gap-3 rounded-[28px] border border-slate-200 p-4 cursor-pointer hover:border-slate-300">
                            <input type="radio" name="payment_method" value="qris" class="h-4 w-4 text-red-600" checked>
                            <span class="text-sm font-medium text-slate-700">QRIS</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-[28px] border border-slate-200 p-4 cursor-pointer hover:border-slate-300">
                            <input type="radio" name="payment_method" value="cod" class="h-4 w-4 text-red-600">
                            <span class="text-sm font-medium text-slate-700">Cash On Delivery</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-[32px] shadow-[0_20px_70px_-40px_rgba(15,23,42,0.2)] p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-slate-900">Detail Pembayaran</h2>
                <div class="mt-6 space-y-4 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Order Subtotal</span>
                        <span x-text="formatCurrency(orderSubtotal)">Rp {{ number_format(collect($cart)->sum(function($item) { return $item['price'] * $item['quantity']; }), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping Subtotal</span>
                        <span x-text="formatCurrency(shippingCost())">Rp 8.000,00</span>
                    </div>
                    <div class="flex justify-between text-base font-semibold text-slate-900">
                        <span>Total Pembayaran</span>
                        <span x-text="formatCurrency(orderSubtotal + shippingCost())">Rp {{ number_format(collect($cart)->sum(function($item) { return $item['price'] * $item['quantity']; }) + 8000, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ session('checkout_previous_url', route('cart')) }}" class="inline-flex items-center justify-center rounded-[28px] border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Kembali</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-[28px] bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-500/10 hover:bg-red-700">Beli Sekarang</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function checkoutPage() {
        return {
            showShippingModal: false,
            showAddressModal: false,
            showAddressFormModal: false,
            selectedShipping: 'reguler',
            selectedAddressIndex: {{ $selectedAddressIndex }},
            checkoutAddresses: @json($checkoutAddresses),
            currentFormSlot: 0,
            addressForm: { name:'', phone:'', provinsi:'', jalan:'' },
            oldAddressForm: @json(old()),
            hasOldAddressForm: {{ $errors->any() ? 'true' : 'false' }},
            showNotification: false,
            notificationMessage: {!! json_encode(session('success') ?: session('error') ?: ($errors->any() ? 'Terjadi kesalahan pada form alamat. Silakan periksa kembali data Anda.' : '')) !!},
            notificationType: '{{ session('success') ? 'success' : (session('error') ? 'error' : ($errors->any() ? 'error' : 'success')) }}',
            orderSubtotal: {{ collect($cart)->sum(function($item) { return $item['price'] * $item['quantity']; }) }},
            shippingOptions: {
                reguler: {
                    title: 'Garansi Tepat Waktu',
                    note: 'Paket tiba dalam 1-3 hari pemesanan',
                    cost: 8000,
                },
                standar: {
                    title: 'Pengiriman Standar',
                    note: 'Estimasi 3-5 hari',
                    cost: 6500,
                },
                ekspres: {
                    title: 'Pengiriman Ekspres',
                    note: 'Tiba lebih cepat',
                    cost: 15000,
                },
            },
            activeAddress() {
                return this.checkoutAddresses[this.selectedAddressIndex] || this.checkoutAddresses[0];
            },
            selectShipping(key) {
                this.selectedShipping = key;
            },
            selectAddress(index) {
                this.selectedAddressIndex = index;
                this.showAddressModal = false;
            },
            openAddressForm(index) {
                this.currentFormSlot = index;
                const address = this.checkoutAddresses[index] || { name:'', phone:'', provinsi:'', jalan:'' };
                this.addressForm.name = address.name || '';
                this.addressForm.phone = address.phone || '';
                this.addressForm.provinsi = address.provinsi || '';
                this.addressForm.jalan = address.jalan || '';
                this.showAddressFormModal = true;
                this.showAddressModal = false;
            },
            closeAddressForm() {
                this.showAddressFormModal = false;
            },
            initCheckout() {
                this.loadOldAddressForm();
                if (this.notificationMessage) {
                    this.showNotification = true;
                    setTimeout(() => {
                        this.showNotification = false;
                    }, 4200);
                }
            },
            loadOldAddressForm() {
                if (this.hasOldAddressForm && this.oldAddressForm.slot_index !== undefined) {
                    this.currentFormSlot = parseInt(this.oldAddressForm.slot_index);
                    this.addressForm.name = this.oldAddressForm.name || '';
                    this.addressForm.phone = this.oldAddressForm.phone || '';
                    this.addressForm.provinsi = this.oldAddressForm.provinsi || '';
                    this.addressForm.jalan = this.oldAddressForm.jalan || '';
                    this.showAddressFormModal = true;
                    this.showAddressModal = false;
                }
            },
            shippingCost() {
                return this.shippingOptions[this.selectedShipping].cost;
            },
            shippingTitle() {
                return this.shippingOptions[this.selectedShipping].title;
            },
            shippingNote() {
                return this.shippingOptions[this.selectedShipping].note;
            },
            formatCurrency(value) {
                if (!value || isNaN(value)) {
                    value = 0;
                }
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0,
                }).format(value);
            },
        };
    }
</script>
@endsection
