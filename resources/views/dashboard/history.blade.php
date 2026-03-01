@extends(auth()->user()->role === 'petugas' ? 'layouts.staff' : 'layouts.admin')

@php use Carbon\Carbon; @endphp

@section('title', 'Riwayat Pesanan - SHOEGAZE')

@section('content')
<div>
    <header class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800">Riwayat Pesanan</h2>
    </header>

    @php $prefix = auth()->user()->role === 'petugas' ? 'staff' : 'admin'; @endphp
    <form method="GET" action="{{ route($prefix . '.history') }}" class="mb-4 flex flex-wrap gap-2">
        <select name="bulan" class="px-3 py-2 border rounded">
            <option value="">Bulan</option>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
        <select name="tahun" class="px-3 py-2 border rounded">
            <option value="">Tahun</option>
            @foreach(range(date('Y'), date('Y')-5) as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-[#2d4a99] text-white rounded">Filter</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total Pesanan</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $totalOrders }}</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Pesanan Selesai</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $doneOrders }}</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Pesanan Dikirim</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $deliveredOrders }}</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Pesanan Pending</p>
            <h3 class="text-xl font-black text-gray-800">{{ $pendingOrders }}</h3>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
        <table class="w-full text-left text-[13px]">
            <thead class="bg-[#dfe6f1] text-[#2d4a99] text-[11px] uppercase font-bold">
                <tr>
                    <th class="px-5 py-3 border-b">No</th>
                    <th class="px-5 py-3 border-b">Tanggal</th>
                    <th class="px-5 py-3 border-b">ID Pesanan</th>
                    <th class="px-5 py-3 border-b">Nama Customer</th>
                    <th class="px-5 py-3 border-b">Produk</th>
                    <th class="px-5 py-3 border-b">Qty</th>
                    <th class="px-5 py-3 border-b">Total Harga</th>
                    <th class="px-5 py-3 border-b">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $index => $row)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">{{ $index+1 }}</td>
                    <td class="px-5 py-3">{{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}</td>
                    <td class="px-5 py-3">#{{ $row['id'] }}</td>
                    <td class="px-5 py-3">{{ $row['customer'] }}</td>
                    <td class="px-5 py-3">{{ $row['product'] }}</td>
                    <td class="px-5 py-3">{{ $row['qty'] }}</td>
                    <td class="px-5 py-3">Rp {{ number_format($row['total'],0,',','.') }}</td>
                    <td class="px-5 py-3">
                        @if($row['status'] == 'valid')
                            Selesai
                        @elseif($row['status'] == 'waiting')
                            Pending
                        @elseif($row['status'] == 'rejected')
                            Ditolak
                        @else
                            {{ $row['status'] }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection