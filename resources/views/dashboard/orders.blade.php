@extends(auth()->user()->role === 'petugas' ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Kelola Pesanan - SHOEGAZE')

@section('content')
@php
    // choose route prefix based on user role so staff and admin share the same view
    $prefix = auth()->user()->role === 'petugas' ? 'staff' : 'admin';
@endphp

<div x-data="orderManager('{{ $prefix }}')">
    <!-- Toast -->
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
             class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    <header class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800">Kelola Pesanan</h2>
    </header>

    <form method="GET" action="{{ route($prefix . '.orders.index') }}" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..."
               class="w-full md:w-1/3 px-3 py-2 border rounded" />
    </form>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
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
                    <td class="px-5 py-3">
                        @if($order->proof_image)
                            <button @click="openDetail(@json($order->toArray()))" class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-[12px]">Lihat</button>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($order->status == 'waiting')
                            <span class="text-yellow-600">Menunggu</span>
                        @elseif($order->status == 'valid')
                            <span class="text-green-600">Valid</span>
                        @else
                            <span class="text-red-600">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 space-x-2">
                        @if($order->status == 'waiting')
                            <form method="POST" action="{{ route($prefix . '.orders.update', $order) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="valid">
                                <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-[12px]">Terima</button>
                            </form>
                            <form method="POST" action="{{ route($prefix . '.orders.update', $order) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-[12px]">Tolak</button>
                            </form>
                        @else
                            -
                        @endif
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

    <!-- detail modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div @click.away="close()" class="bg-white w-full max-w-md p-6 rounded-lg relative">
            <h3 class="text-lg font-bold mb-4">Bukti Pembayaran</h3>
            <div class="mb-2">
                <strong>ID Transaksi:</strong> #<span x-text="selected.id"></span>
            </div>
            <div class="mb-2">
                <strong>Customer:</strong> <span x-text="selected.user.name"></span>
            </div>
            <div class="mb-2">
                <strong>Total:</strong> Rp <span x-text="formatMoney(selected.total)"></span>
            </div>
            <div class="mb-2">
                <strong>Metode:</strong> <span x-text="selected.method"></span>
            </div>
            <div class="mb-2">
                <strong>Tanggal:</strong> <span x-text="formatDate(selected.created_at)"></span>
            </div>
            <div class="mb-2">
                <strong>Status:</strong> <span x-text="statusText(selected.status)"></span>
            </div>
            <template x-if="selected.proof_image">
                <div class="mb-4">
                    <strong>Bukti:</strong>
                    <div class="mt-2">
                        <img :src="`/storage/${selected.proof_image}`" class="max-h-40 object-contain" alt="Bukti pembayaran">
                    </div>
                </div>
            </template>
            <div class="mt-4 flex justify-end space-x-2">
                <form :action="`/${prefix}/orders/${selected.id}`" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="valid">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Terima</button>
                </form>
                <form :action="`/${prefix}/orders/${selected.id}`" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Tolak</button>
                </form>
                <button @click="close()" class="px-4 py-2 bg-gray-200 rounded">Kembali</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function orderManager(prefix) {
    return {
        prefix,
        showModal: false,
        selected: {},
        openDetail(order) {
            // order object may have user data nested
            this.selected = order;
            this.showModal = true;
        },
        close() {
            this.showModal = false;
            this.selected = {};
        },
        formatMoney(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        },
        formatDate(datetime) {
            return new Date(datetime).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute:'2-digit' });
        },
        statusText(status) {
            if(status === 'waiting') return 'Menunggu Verifikasi';
            if(status === 'valid') return 'Valid';
            if(status === 'rejected') return 'Ditolak';
            return status;
        }
    }
}
</script>
@endpush
