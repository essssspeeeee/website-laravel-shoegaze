@extends('layouts.app')

@section('title', 'Keranjang - SHOEGAZE')

@section('content')

    <main class="flex-grow max-w-6xl mx-auto w-full px-4 py-8"
          x-data="cart" x-init="init()" x-cloak>

        <h2 class="text-2xl font-bold mb-6">Keranjang Belanja</h2>



        <form id="cart-form" action="{{ route('checkout') }}" method="GET" class="space-y-8">
            @csrf
            
            <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden border border-gray-100">
                <div class="grid grid-cols-12 gap-4 p-4 bg-gray-50 border-b border-gray-200 font-bold text-xs uppercase tracking-wider text-gray-600 items-center">
                    <div class="col-span-6 flex items-center gap-4">
                        <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500 cursor-pointer">
                        <span>Produk</span>
                    </div>
                    <div class="col-span-2 text-center">Harga</div>
                    <div class="col-span-2 text-center">Kuantitas</div>
                    <div class="col-span-1 text-right">Subtotal</div>
                    <div class="col-span-1 text-center">Aksi</div>
                </div>

                <div class="divide-y divide-gray-100">
                    <template x-for="(item, key) in items" :key="key">
                        <div class="grid grid-cols-12 gap-4 p-4 items-center transition-colors hover:bg-gray-50">
                            <div class="col-span-6 flex items-center gap-4">
                                <input 
                                    type="checkbox" 
                                    name="selected_items[]"
                                    :value="item.id"
                                    x-model="item.selected" 
                                    @change="calculateTotal()" 
                                    class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500 cursor-pointer">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                                    <img :src="item.image" alt="Sepatu" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-800 block" x-text="item.name"></span>
                                    <span class="text-xs text-gray-500" x-text="'Ukuran: ' + (item.size ? item.size : 'Belum dipilih')"></span>
                                </div>
                            </div>

                            <div class="col-span-2 text-center text-sm font-semibold text-gray-700" x-text="formatRupiah(item.price)"></div>

                            <div class="col-span-2 flex justify-center items-center">
                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                    <button type="button" @click="decreaseQty(key)" :disabled="parseInt(item.quantity) <= 1" class="px-3 py-1 bg-gray-50 hover:bg-gray-200 font-bold transition disabled:opacity-50 disabled:cursor-not-allowed">-</button>
                                    <div class="flex flex-col items-center">
                                        <input 
                                            type="number" 
                                            :name="`quantities[${key}]`"
                                            x-model.number="item.quantity" 
                                            @input="updateQty(key)"
                                            @change="updateQty(key)"
                                            class="w-10 text-center text-sm border-none focus:ring-0 p-1 font-bold" 
                                            min="1"
                                            :max="parseInt(item.maxStock) > 0 ? parseInt(item.maxStock) : 999999"
                                            inputmode="numeric">
                                        <p x-show="parseInt(item.maxStock) > 0 && parseInt(item.quantity) >= parseInt(item.maxStock)" class="text-xs text-red-600 mt-1">Stok terbatas!</p>
                                    </div>
                                    <button type="button" @click="increaseQty(key)" :disabled="parseInt(item.maxStock) > 0 && parseInt(item.quantity) >= parseInt(item.maxStock)" class="px-3 py-1 bg-gray-50 hover:bg-gray-200 font-bold transition disabled:opacity-50 disabled:cursor-not-allowed">+</button>
                                </div>
                            </div>

                            <div class="col-span-1 text-right text-sm font-black text-gray-900" x-text="formatRupiah(item.price * item.quantity)"></div>

                            <div class="col-span-1 text-center">
                                <button type="button" @click="removeItem(key)" class="text-gray-400 hover:text-red-500 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="Object.keys(items).length === 0" class="p-12 text-center flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-gray-500 font-medium">Keranjang belanjamu masih kosong.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 w-full md:w-96 p-6">
                    <h3 class="text-lg font-bold mb-4 border-b border-gray-50 pb-4">Ringkasan Pesanan</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal Terpilih</span>
                            <span class="font-bold text-gray-800" x-text="formatRupiah(totalAmount)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Biaya Pengiriman</span>
                            <span class="font-bold text-gray-800" x-text="formatRupiah(shippingCost)"></span>
                        </div>
                        <div class="pt-4 border-t border-dashed border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-bold text-gray-900">Total Akhir</span>
                                <span class="text-xl font-black text-red-600" x-text="formatRupiah(totalAmount + shippingCost)"></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" @click="submitCheckout()" :disabled="getSelectedCount() === 0" class="w-full mt-6 bg-black hover:bg-gray-800 disabled:bg-gray-400 text-white font-black py-4 rounded-xl transition-all active:scale-95 shadow-lg shadow-gray-200 uppercase tracking-widest text-sm text-center block" x-text="getSelectedCount() > 0 ? 'Checkout Sekarang' : 'Pilih Produk Terlebih Dahulu'">
                    </button>
                </div>
            </div>
        </form>
    </main>

<div id="cart-data" data-items='@json(session('cart', []))' class="hidden"></div>

@push('styles')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script>
    function cart() {
        return {
            selectAll: true,
            totalAmount: 0,
            shippingCost: 8000,
            items: {},
            updateUrlTemplate: '{{ route('cart.update', ['id' => 'CART_ID_PLACEHOLDER']) }}',
            
            init() {
                const cartData = JSON.parse(document.getElementById('cart-data').dataset.items);
                this.items = {};
                
                cartData.forEach(item => {
                    // Get maxStock from item, or from stock object by size, or default to 0
                    let maxStock = item.maxStock;
                    if (maxStock === null || maxStock === undefined) {
                        maxStock = item.stock && item.size ? (parseInt(item.stock[item.size]) || 0) : 0;
                    } else {
                        maxStock = parseInt(maxStock) || 0;
                    }
                    
                    // Ensure maxStock is at least greater than 0 if item has any stock
                    if (maxStock <= 0) {
                        maxStock = 0;
                    }
                    
                    const initialQty = parseInt(item.quantity) || 1;

                    const normalizedSize = item.size && ['39','40','41','42','43'].includes(String(item.size)) ? String(item.size) : null;
                    let normalizedPrice = parseInt(String(item.price).replace(/[^0-9]/g, '')) || 0;
                    // Additional safety check for NaN
                    if (isNaN(normalizedPrice)) {
                        normalizedPrice = 0;
                    }

                    // Ensure quantity doesn't exceed maxStock if maxStock is available
                    let quantity = initialQty;
                    if (maxStock > 0 && quantity > maxStock) {
                        console.warn(`Quantity for ${item.name} (${item.size}) capped to maxStock: ${maxStock}`);
                        quantity = maxStock;
                    }

                    this.items[`${item.product_id}_${normalizedSize ?? 'nosize'}`] = {
                        id: item.id ?? null,
                        product_id: item.product_id,
                        name: item.name,
                        size: normalizedSize,
                        image: item.image,
                        price: normalizedPrice,
                        quantity: quantity,
                        maxStock: maxStock,
                        selected: item.selected !== false
                    };
                });
                
                this.calculateTotal();
            },
            
            toggleAll() {
                Object.values(this.items).forEach(item => item.selected = this.selectAll);
                this.calculateTotal();
            },

            increaseQty(key) {
                const item = this.items[key];
                if (!item) return;
                
                const maxStock = parseInt(item.maxStock) || 0;
                const currentQty = parseInt(item.quantity) || 1;
                
                // Only prevent increase if maxStock is valid (> 0) and quantity at or above limit
                if (maxStock > 0 && currentQty >= maxStock) {
                    alert('Stok terbatas! Maksimal kuantitas untuk produk ini adalah ' + maxStock);
                    return;
                }
                
                item.quantity = currentQty + 1;
                this.calculateTotal();
                this.syncQuantity(item);
            },

            decreaseQty(key) {
                const item = this.items[key];
                if (!item) return;
                
                const qty = parseInt(item.quantity) || 1;
                if (qty > 1) {
                    item.quantity = qty - 1;
                    this.calculateTotal();
                    this.syncQuantity(item);
                }
            },

            updateQty(key) {
                const item = this.items[key];
                if (!item) return;
                
                let qty = parseInt(item.quantity) || 1;
                const maxStock = parseInt(item.maxStock) || 0;
                
                // Validate minimum quantity
                if (qty < 1) {
                    item.quantity = 1;
                    return;
                }
                
                // Validate maximum quantity based on stock
                if (maxStock > 0 && qty > maxStock) {
                    alert('Stok terbatas! Maksimal kuantitas untuk produk ini adalah ' + maxStock);
                    item.quantity = maxStock;
                    this.calculateTotal();
                    this.syncQuantity(item);
                    return;
                }
                
                item.quantity = qty;
                this.calculateTotal();
                this.syncQuantity(item);
            },

            removeItem(key) {
                if(confirm('Hapus item ini dari keranjang?')) {
                    const [productId, size] = key.split('_');
                    delete this.items[key];
                    
                    fetch(`/cart/remove/${productId}/${size}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    this.calculateTotal();
                }
            },

            calculateTotal() {
                this.totalAmount = Object.values(this.items)
                    .filter(item => item.selected)
                    .reduce((sum, item) => {
                        const cleanPrice = parseInt(String(item.price).replace(/[^0-9]/g, '')) || 0;
                        const price = isNaN(cleanPrice) ? 0 : cleanPrice;
                        const qty = isNaN(item.quantity) ? 0 : item.quantity;
                        return sum + (price * qty);
                    }, 0);
                
                let allSelected = Object.values(this.items).every(item => item.selected);
                this.selectAll = Object.keys(this.items).length > 0 ? allSelected : false;
            },

            getSelectedCount() {
                return Object.values(this.items).filter(item => item.selected).length;
            },

            syncQuantity(item) {
                if (!item.id) {
                    this.calculateTotal();
                    return;
                }

                const previousQuantity = item.quantity;
                const url = this.updateUrlTemplate.replace('CART_ID_PLACEHOLDER', item.id);
                
                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ quantity: item.quantity })
                })
                .then(response => {
                    // Handle response based on status code
                    if (!response.ok && response.status >= 500) {
                        throw new Error('Server error (HTTP ' + response.status + ')');
                    }
                    
                    // Try to parse as JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => ({ status: response.status, data }));
                    } else {
                        // If not JSON, treat as an error
                        throw new Error('Invalid response format from server');
                    }
                })
                .then(({ status, data }) => {
                    if (status === 422) {
                        // Validation error - stock limit
                        const maxStock = data.max_stock || item.maxStock || previousQuantity;
                        item.quantity = maxStock > 0 ? maxStock : previousQuantity;
                        alert(data.message || 'Jumlah melebihi stok tersedia.');
                        this.calculateTotal();
                        return;
                    }

                    if (status === 500) {
                        item.quantity = previousQuantity;
                        alert('Terjadi kesalahan server. Silahkan refresh halaman.');
                        this.calculateTotal();
                        return;
                    }

                    if (status >= 400) {
                        item.quantity = previousQuantity;
                        alert(data.message || 'Terjadi kesalahan. Silahkan coba lagi.');
                        this.calculateTotal();
                        return;
                    }

                    if (data.item_subtotal !== undefined || data.total_amount !== undefined) {
                        this.calculateTotal();
                    } else if (status === 200 || status === 201) {
                        this.calculateTotal();
                    }
                })
                .catch(error => {
                    console.error('Sync quantity error:', error);
                    item.quantity = previousQuantity;
                    alert('Kesalahan jaringan. Kuantitas dikembalikan ke nilai sebelumnya: ' + previousQuantity);
                    this.calculateTotal();
                });
            },

            submitCheckout() {
                const form = document.getElementById('cart-form');
                if (!form) {
                    alert('Form checkout tidak ditemukan.');
                    return;
                }

                const checkedBoxes = form.querySelectorAll('input[name="selected_items[]"]:checked');
                if (checkedBoxes.length === 0) {
                    alert('Pilih minimal satu produk dulu sebelum lanjut checkout.');
                    return;
                }

                // Check if any selected item exceeds stock
                for (const key in this.items) {
                    const item = this.items[key];
                    if (item.selected && item.maxStock > 0 && item.quantity > item.maxStock) {
                        alert('Kuantitas untuk ' + item.name + ' melebihi stok tersedia (' + item.maxStock + ').');
                        return;
                    }
                }

                form.submit();
            },

            formatRupiah(number) {
                // Handle NaN, null, undefined cases
                if (!number || isNaN(number)) {
                    number = 0;
                }
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }
        }
    }
</script>
@endpush

@endsection
