<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Menghilangkan spin button pada input number agar lebih bersih */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <header class="bg-white shadow-sm py-4 px-8 flex justify-between items-center">
        <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity">
            <h1 class="text-2xl font-black italic tracking-wider">SHOEGAZE</h1>
        </a>
    </header>

    <main class="flex-grow max-w-6xl mx-auto w-full px-4 py-8" 
          x-data="cart()" x-init="calculateTotal()">
        
        <h2 class="text-2xl font-bold mb-6">Keranjang Belanja</h2>

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
                <template x-for="(item, index) in items" :key="item.id">
                    <div class="grid grid-cols-12 gap-4 p-4 items-center transition-colors hover:bg-gray-50">
                        <div class="col-span-6 flex items-center gap-4">
                            <input type="checkbox" x-model="item.selected" @change="calculateTotal()" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500 cursor-pointer">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                                <img src="https://via.placeholder.com/100?text=Shoe" alt="Sepatu" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="text-sm font-bold text-gray-800 block" x-text="item.name"></span>
                                <span class="text-xs text-gray-400">Varian: Default</span>
                            </div>
                        </div>

                        <div class="col-span-2 text-center text-sm font-semibold text-gray-700" x-text="formatRupiah(item.price)"></div>

                        <div class="col-span-2 flex justify-center items-center">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                <button @click="decreaseQty(index)" class="px-3 py-1 bg-gray-50 hover:bg-gray-200 font-bold transition">-</button>
                                <input type="number" x-model="item.qty" @change="updateQty(index, $event.target.value)" class="w-10 text-center text-sm border-none focus:ring-0 p-1 font-bold">
                                <button @click="increaseQty(index)" class="px-3 py-1 bg-gray-50 hover:bg-gray-200 font-bold transition">+</button>
                            </div>
                        </div>

                        <div class="col-span-1 text-right text-sm font-black text-gray-900" x-text="formatRupiah(item.price * item.qty)"></div>

                        <div class="col-span-1 text-center">
                            <button @click="removeItem(index)" class="text-gray-400 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
                
                <div x-show="items.length === 0" class="p-12 text-center flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-500 font-medium">Keranjang belanjamu masih kosong.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start gap-8">
            
            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 font-bold hover:text-black transition group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali Belanja
            </a>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 w-full md:w-96 p-6">
                <h3 class="text-lg font-bold mb-4 border-b border-gray-50 pb-4">Ringkasan Pesanan</h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-bold text-gray-800" x-text="formatRupiah(totalAmount)"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Biaya Pengiriman</span>
                        <span class="text-green-600 font-bold uppercase text-xs">Gratis</span>
                    </div>
                    <div class="pt-4 border-t border-dashed border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Total Akhir</span>
                            <span class="text-xl font-black text-red-600" x-text="formatRupiah(totalAmount)"></span>
                        </div>
                    </div>
                </div>
                <button class="w-full mt-6 bg-black hover:bg-gray-800 text-white font-black py-4 rounded-xl transition-all active:scale-95 shadow-lg shadow-gray-200 uppercase tracking-widest text-sm">
                    Checkout Sekarang
                </button>
            </div>
        </div>
    </main>

    <footer class="bg-[#1f2328] text-center p-6 mt-auto">
        <p class="text-gray-500 text-xs font-medium tracking-widest uppercase">© FAUZAN ESPE 2026. Seluruh hak dilindungi undang-undang.</p>
    </footer>

    <script>
        function cart() {
            return {
                selectAll: false,
                totalAmount: 0,
                // Data dummy: ganti dengan data dari database/session nanti
                items: [
                    { id: 1, name: '910 NINETEN GEIST EKIDEN ELITE', price: 899900, qty: 1, selected: true },
                    { id: 2, name: 'HAZE FLOW ORANGE/PINK', price: 699900, qty: 1, selected: false },
                ],
                
                toggleAll() {
                    this.items.forEach(item => item.selected = this.selectAll);
                    this.calculateTotal();
                },

                increaseQty(index) {
                    this.items[index].qty++;
                    this.calculateTotal();
                },

                decreaseQty(index) {
                    if (this.items[index].qty > 1) {
                        this.items[index].qty--;
                        this.calculateTotal();
                    }
                },

                updateQty(index, val) {
                    let n = parseInt(val);
                    if(isNaN(n) || n < 1) n = 1;
                    this.items[index].qty = n;
                    this.calculateTotal();
                },

                removeItem(index) {
                    if(confirm('Hapus item ini dari keranjang?')) {
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    }
                },

                calculateTotal() {
                    this.totalAmount = this.items
                        .filter(item => item.selected)
                        .reduce((sum, item) => sum + (item.price * item.qty), 0);
                    
                    if(this.items.length > 0) {
                        this.selectAll = this.items.every(item => item.selected);
                    } else {
                        this.selectAll = false;
                    }
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