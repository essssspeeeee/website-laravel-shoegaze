@extends(auth()->user()->role === 'petugas' ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Kelola Produk - SHOEGAZE')

@section('content')
@php
    $prefix = auth()->user()->role === 'petugas' ? 'staff' : 'admin';
@endphp

<div x-data="productManager('{{ $prefix }}')">
    <!-- Toast message -->
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
             class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    <header class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800">Kelola Produk</h2>
        <button type="button" @click="showAddModal = true" class="bg-[#2d4a99] hover:bg-[#3d5bb0] text-white px-4 py-2 rounded">+ Tambah Produk</button>
    </header>

    <form method="GET" action="{{ route($prefix . '.products.index') }}" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
               class="w-full md:w-1/3 px-3 py-2 border rounded" />
    </form>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
        <table class="w-full text-left text-[13px]">
            <thead class="bg-[#dfe6f1] text-[#2d4a99] text-[11px] uppercase font-bold">
                <tr>
                    <th class="px-5 py-3 border-b">No</th>
                    <th class="px-5 py-3 border-b">Gambar</th>
                    <th class="px-5 py-3 border-b">Nama Produk</th>
                    <th class="px-5 py-3 border-b">Harga</th>
                    <th class="px-5 py-3 border-b">Stok</th>
                    <th class="px-5 py-3 border-b">Status</th>
                    <th class="px-5 py-3 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $index => $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">{{ $products->firstItem() + $index }}</td>
                        <td class="px-5 py-3">
                            @php
                                $imgPath = null;
                                if(is_array($product->images) && count($product->images)) {
                                    $imgPath = $product->images[0];
                                }
                            @endphp
                            @if($imgPath)
                                <img src="{{ asset('storage/' . $imgPath) }}" alt="" class="w-12 h-12 object-cover rounded">
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-700">{{ $product->name }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($product->price,0,',','.') }}</td>
                        <td class="px-5 py-3">
                            @php $total = is_array($product->stock) ? array_sum($product->stock) : $product->stock; @endphp
                            {{ $total }}
                        </td>
                        <td class="px-5 py-3">{{ $total > 0 ? 'Tersedia' : 'Habis' }}</td>
                        <td class="px-5 py-3 space-x-2">
                            <button type="button" @click="openEdit({{ json_encode($product) }})" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-[12px]">Edit</button>
                            <button type="button" @click="promptDelete({{ $product->id }})" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-[12px]">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- delete confirmation modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <!-- hidden form for deletion -->
        <form id="deleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        <div @click.away="cancelDelete()" class="bg-white p-6 rounded-lg max-w-sm w-full">
            <h3 class="text-lg font-bold mb-4">Konfirmasi Hapus</h3>
            <p>Anda yakin ingin menghapus produk ini?</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" @click="cancelDelete()" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                <button type="button" @click="performDelete()" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
            </div>
        </div>
    </div>

    {{ $products->withQueryString()->links() }}

    <!-- Add Product Modal -->
    <div x-show="showAddModal || showEditModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <!-- backdrop, clicking outside cancels -->
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="cancel()" class="bg-white w-full max-w-2xl p-4 rounded-lg relative max-h-[90vh] overflow-y-auto shadow-xl ring-1 ring-black ring-opacity-5">
            <button @click="cancel()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-semibold mb-3 border-b pb-2" x-text="showEditModal ? 'Edit Produk' : 'Tambah Produk Baru'"></h3>
            <form :action="showEditModal ? '/'+prefix+'/products/' + editProduct.id : '{{ route($prefix . '.products.store') }}'"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <template x-if="showEditModal">
                    <input type="hidden" name="_method" value="PATCH">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- left column -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium">Nama Produk</label>
                            <input type="text" name="name" x-model="form.name"
                                   class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#2d4a99] focus:border-[#2d4a99]" required>
                            @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Harga (Rp)</label>
                            <input type="number" name="price" x-model="form.price" min="0"
                                   class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#2d4a99] focus:border-[#2d4a99]" required>
                            @error('price')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium">Deskripsi produk (opsional)</label>
                            <textarea name="description" x-model="form.description" rows="3" class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#2d4a99] focus:border-[#2d4a99]"></textarea>
                            @error('description')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- right column -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Jumlah Stok per Ukuran</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(['39','40','41','42','43'] as $size)
                                <div>
                                    <label class="text-xs">Size {{ $size }}</label>
                                    <input type="number" name="stock[{{ $size }}]" x-model.number="form.stock['{{ $size }}']" min="0" class="w-full border px-3 py-2 rounded" required>
                                </div>
                                @endforeach
                            </div>
                            @error('stock')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            @error('stock.*')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Upload Gambar Produk (max 5)</label>
                            <input type="file" name="images[]" accept="image/*" multiple class="w-full" />
                            <small class="text-gray-500">Pilih hingga 5 file JPG/PNG, masing-masing maksimal 5MB.</small>
                            @error('images')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            @error('images.*')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="cancel()" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#2d4a99] text-white rounded"
                            x-text="showEditModal ? 'Simpan Perubahan' : 'Simpan Produk'"></button>
                </div>
            </form>
        </div>
    </div>

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ensure prefix available in Alpine state
                window.__prefix = '{{ $prefix }}';
                @if(old('_method') == 'PATCH')
                    document.querySelector('[x-data]').__x.$data.showEditModal = true;
                    // populate form with old input
                    let data = {{ json_encode(old()) }};
                    Object.assign(document.querySelector('[x-data]').__x.$data.form, {
                        name: data.name || '',
                        price: data.price || '',
                        stock: data.stock ? Object.assign({ '39':0,'40':0,'41':0,'42':0,'43':0 }, data.stock) : { '39':0,'40':0,'41':0,'42':0,'43':0 },
                        description: data.description || ''
                    });
                @else
                    document.querySelector('[x-data]').__x.$data.showAddModal = true;
                    let data = {{ json_encode(old()) }};
                    Object.assign(document.querySelector('[x-data]').__x.$data.form, {
                        name: data.name || '',
                        price: data.price || '',
                        stock: data.stock ? Object.assign({ '39':0,'40':0,'41':0,'42':0,'43':0 }, data.stock) : { '39':0,'40':0,'41':0,'42':0,'43':0 },
                        description: data.description || ''
                    });
                @endif
            });
        </script>
    @endif

</div>

@endsection

@push('scripts')
<script>
function productManager(prefix) {
    return {
        prefix,
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        deleteId: null,
        editProduct: {},
        form: { name: '', price: '', stock: { '39':0,'40':0,'41':0,'42':0,'43':0 }, description: '' },
        openEdit(product) {
            this.editProduct = product;
            this.form.name = product.name;
            this.form.price = product.price;
            // ensure stock object has all sizes
            this.form.stock = Object.assign({ '39':0,'40':0,'41':0,'42':0,'43':0 }, product.stock || {});
            this.form.description = product.description || '';
            this.showEditModal = true;
        },
        cancel() {
            this.showAddModal = this.showEditModal = false;
            this.form = { name: '', price: '', stock: { '39':0,'40':0,'41':0,'42':0,'43':0 }, description: '' };
        },
        promptDelete(id) {
            this.deleteId = id;
            this.showDeleteModal = true;
        },
        cancelDelete() {
            this.showDeleteModal = false;
            this.deleteId = null;
        },
        performDelete() {
            if(this.deleteId) {
                let form = document.getElementById('deleteForm');
                form.action = `/${this.prefix}/products/${this.deleteId}`;
                form.submit();
            }
        }
    }
}
</script>
@endpush
