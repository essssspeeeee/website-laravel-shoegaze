@php
    $layout = auth()->user()->role === 'user'
        ? 'layouts.app'
        : (auth()->user()->role === 'petugas' ? 'layouts.staff' : 'layouts.admin');
@endphp

@extends($layout)

@section('title', auth()->user()->role === 'user' ? 'Riwayat Pesanan - SHOEGAZE' : 'Kelola Pesanan - SHOEGAZE')

@section('content')
@if(auth()->user()->role === 'user')
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50">
            {{ $errors->first() }}
        </div>
    @endif
    <main class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-slate-900 mb-6">Riwayat Pesanan</h1>

    @php
        $tabs = [
            'all' => 'Semua',
            'menunggu' => 'Menunggu',
            'dikemas' => 'Dikemas',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];
    @endphp

    <div class="flex gap-4 mb-8">
        @foreach($tabs as $key => $label)
            <a href="{{ route('orders.index', ['status' => $key]) }}" class="px-4 py-2 rounded-t-lg {{ ($statusFilter === $key ? 'text-slate-950 font-bold border-b-2 border-red-500' : 'text-slate-500') }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <div class="bg-white border border-slate-200 rounded-xl p-10 text-center text-slate-500">Belum ada pesanan untuk status ini.</div>
    @else
        @foreach($orders as $order)
            @php
                if ($order->status === 'pending') {
                    $badgeClass = 'bg-yellow-100 text-yellow-800';
                    $statusLabel = 'Menunggu Pembayaran';
                } elseif ($order->status === 'waiting') {
                    $badgeClass = 'bg-yellow-100 text-yellow-800';
                    $statusLabel = 'Menunggu Verifikasi Admin';
                } elseif (in_array($order->status, ['diproses', 'packed'])) {
                    $badgeClass = 'bg-blue-100 text-blue-800';
                    $statusLabel = 'Sedang Dikemas';
                } elseif ($order->status === 'shipping') {
                    $badgeClass = 'bg-blue-100 text-blue-800';
                    $statusLabel = 'Sedang Dikirim';
                } elseif ($order->status === 'valid') {
                    $badgeClass = 'bg-green-100 text-green-800';
                    $statusLabel = 'Selesai';
                } elseif (in_array($order->status, ['rejected', 'cancelled'])) {
                    $badgeClass = 'bg-red-100 text-red-800';
                    $statusLabel = 'Dibatalkan';
                } else {
                    $badgeClass = 'bg-slate-100 text-slate-700';
                    $statusLabel = ucfirst($order->status);
                }
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-sm text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</div>
                        <div class="text-lg font-bold text-slate-900">Order #{{ $order->id }}</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                        @php
                            $productName = optional($item->product)->name ?? 'Produk tidak tersedia';
                            $size = $item->size ?? 'N/A';
                            $quantity = $item->quantity;
                            $productImage = 'https://via.placeholder.com/80';
                            if (optional($item->product)->images && is_array($item->product->images) && count($item->product->images) > 0) {
                                $productImage = asset('img/product/' . $item->product->images[0]);
                            }
                        @endphp

                        <div class="flex items-center gap-3 border border-slate-100 rounded-xl p-3">
                            <img src="{{ $productImage }}" alt="{{ $productName }}" class="w-16 h-16 rounded-lg object-cover" />
                            <div class="flex-1">
                                <div class="font-bold text-slate-900">{{ $productName }}</div>
                                <div class="text-sm text-slate-500">Ukuran: {{ $size }} � Qty: {{ $quantity }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-right font-bold text-lg text-slate-900">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</div>

                <div class="mt-4 text-right flex flex-wrap justify-end gap-2">
                    @if(in_array($order->status, ['pending', 'waiting']))
                        <form method="POST" action="{{ route('orders.cancel', $order->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition">Batalkan</button>
                        </form>
                    @endif
                    @if($order->status === 'shipping')
                        <form method="POST" action="{{ route('orders.receive', $order->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition">Pesanan Diterima</button>
                        </form>
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center justify-center px-5 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">Lihat Detail</a>
                </div>
            </div>
        @endforeach
    @endif
</main>

@else
    @php
        $prefix = auth()->user()->role === 'petugas' ? 'staff' : 'admin';
    @endphp

    <div x-data="orderManager('{{ $prefix }}')">
        <!-- Toast -->
        @if(session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <header class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
            <h2 class="text-xl font-bold text-gray-800">Kelola Pesanan</h2>
        </header>

        <form method="GET" action="{{ route($prefix . '.orders.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..." class="w-full md:w-1/3 px-3 py-2 border rounded" />
        </form>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
            <div id="proof-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b">
            <h3 class="text-lg font-semibold">Bukti Pembayaran</h3>
            <button type="button" id="proof-close" class="text-slate-500 hover:text-slate-800">Tutup</button>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold">ID Transaksi</p>
                    <p id="proof-order-id" class="text-slate-700"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Customer</p>
                    <p id="proof-customer-name" class="text-slate-700"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Total</p>
                    <p id="proof-total" class="text-slate-700"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Metode</p>
                    <p id="proof-method" class="text-slate-700"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Tanggal</p>
                    <p id="proof-created-at" class="text-slate-700"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Status</p>
                    <p id="proof-status" class="text-slate-700"></p>
                </div>
            </div>
            <div>
                <img id="preview-image" src="" alt="Bukti pembayaran" class="w-full h-auto max-h-[550px] object-contain rounded-lg border border-slate-200" />
            </div>
        </div>
    </div>
</div>
            <table class="w-full text-left text-[13px]">
                <thead class="bg-[#dfe6f1] text-[#2d4a99] text-[11px] uppercase font-bold">
                    <tr>
                        <th class="px-5 py-3 border-b">ID Transaksi</th>
                        <th class="px-5 py-3 border-b">Customer</th>
                        <th class="px-5 py-3 border-b">Total</th>
                        <th class="px-5 py-3 border-b">Metode</th>
                        <th class="px-5 py-3 border-b">Bukti</th>
                        <th class="px-5 py-3 border-b">Status</th>
                        <th class="px-5 py-3 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">#{{ $order->id }}</td>
                        <td class="px-5 py-3">{{ $order->user->name }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($order->total,0,',','.') }}</td>
                        <td class="px-5 py-3">{{ $order->method }}</td>
                        @php
                            $paymentMethod = strtolower($order->payment_method ?? $order->method ?? '');
                            $proofExists = false;
                            $proofImageUrl = null;
                            $proofRaw = $order->payment_proof ?? $order->proof_image;

                            if (!empty($proofRaw)) {
                                $proofRaw = ltrim($proofRaw, '/');

                                if (\Illuminate\Support\Str::startsWith($proofRaw, 'storage/')) {
                                    $proofFile = \Illuminate\Support\Str::after($proofRaw, 'storage/');
                                    $proofExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($proofFile);
                                    $proofImageUrl = asset('storage/' . $proofFile);
                                } elseif (\Illuminate\Support\Str::startsWith($proofRaw, 'img/')) {
                                    $proofExists = file_exists(public_path($proofRaw));
                                    $proofImageUrl = asset($proofRaw);
                                } else {
                                    $publicPath = public_path($proofRaw);
                                    $storagePath = public_path('storage/' . $proofRaw);

                                    if (file_exists($publicPath)) {
                                        $proofExists = true;
                                        $proofImageUrl = asset($proofRaw);
                                    } elseif (file_exists($storagePath)) {
                                        $proofExists = true;
                                        $proofImageUrl = asset('storage/' . $proofRaw);
                                    } else {
                                        $proofImageUrl = asset($proofRaw);
                                    }
                                }
                            }
                        @endphp
                        <td class="px-5 py-3">
    @if($paymentMethod === 'qris')
        @if($proofExists)
            <button
                type="button"
                class="view-proof-btn px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-[12px]"
                data-image="{{ $proofImageUrl }}"
                data-order-id="{{ $order->id }}"
                data-customer-name="{{ e($order->user->name) }}"
                data-total="{{ number_format($order->total,0,',','.') }}"
                data-payment-method="{{ strtoupper($paymentMethod) }}"
                data-status="{{ $order->status }}"
                data-created-at="{{ $order->created_at->format('d M Y H:i') }}"
            >
                Lihat
            </button>
        @else
            <button type="button" class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-[12px] cursor-not-allowed" disabled>Belum diunggah</button>
        @endif
    @endif
</td>
                        <td class="px-5 py-3">
                            @if($order->status == 'waiting')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Menunggu Konfirmasi</span>
                            @elseif($order->status == 'diproses')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Diproses</span>
                            @elseif($order->status == 'pending')
                                <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Pending</span>
                            @elseif($order->status == 'packed')
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">Dikemas</span>
                            @elseif($order->status == 'shipping')
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">Dikirim</span>
                            @elseif($order->status == 'valid')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Diterima</span>
                            @elseif($order->status == 'rejected')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Ditolak</span>
                            @elseif($order->status == 'cancelled')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Dibatalkan</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $statusOptions = [
                                    'pending' => 'Menunggu Pembayaran',
                                    'processing' => 'Dikemas',
                                    'shipping' => 'Dikirim',
                                    'valid' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];

                                if (in_array($order->status, ['waiting', 'pending'], true)) {
                                    $currentStatus = 'pending';
                                } elseif (in_array($order->status, ['diproses', 'packed'], true)) {
                                    $currentStatus = 'processing';
                                } elseif ($order->status === 'shipping') {
                                    $currentStatus = 'shipping';
                                } elseif ($order->status === 'valid') {
                                    $currentStatus = 'valid';
                                } elseif (in_array($order->status, ['rejected', 'cancelled'], true)) {
                                    $currentStatus = 'cancelled';
                                } else {
                                    $currentStatus = 'pending';
                                }

                                $isLocked = $currentStatus === 'valid';
                            @endphp

                            <form method="POST" action="{{ route($prefix . '.orders.update', $order) }}" class="flex flex-wrap gap-2 items-center">
                                @csrf
                                @method('PATCH')

                                <select name="status" class="w-full md:w-auto px-3 py-2 border rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-red-500" {{ $isLocked ? 'disabled' : '' }}>
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>

                                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition {{ $isLocked ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $isLocked ? 'disabled' : '' }}>
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-3 text-center text-gray-500">Tidak ada transaksi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->withQueryString()->links() }}

        </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('proof-modal');
        const previewImage = document.getElementById('preview-image');
        const proofOrderId = document.getElementById('proof-order-id');
        const proofCustomer = document.getElementById('proof-customer-name');
        const proofTotal = document.getElementById('proof-total');
        const proofMethod = document.getElementById('proof-method');
        const proofStatus = document.getElementById('proof-status');
        const proofCreated = document.getElementById('proof-created-at');
        const proofClose = document.getElementById('proof-close');

        document.querySelectorAll('.view-proof-btn').forEach(button => {
            button.addEventListener('click', function () {
                const imageUrl = this.dataset.image || '';
                previewImage.src = imageUrl;
                previewImage.alt = imageUrl ? 'Bukti pembayaran' : 'Gambar tidak tersedia';
                proofOrderId.textContent = this.dataset.orderId || '-';
                proofCustomer.textContent = this.dataset.customerName || '-';
                proofTotal.textContent = this.dataset.total ? 'Rp ' + this.dataset.total : '-';
                proofMethod.textContent = this.dataset.paymentMethod || '-';
                proofStatus.textContent = this.dataset.status || '-';
                proofCreated.textContent = this.dataset.createdAt || '-';

                if (!imageUrl) {
                    previewImage.classList.add('opacity-50');
                } else {
                    previewImage.classList.remove('opacity-50');
                }

                modal.classList.remove('hidden');
            });
        });

        proofClose.addEventListener('click', function () {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        });
    });
    </script>
    @endpush
@endif
@endsection
