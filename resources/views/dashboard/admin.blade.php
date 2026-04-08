@extends('layouts.admin')

@section('title', 'Admin Dashboard - SHOEGAZE')

@section('content')
    <header class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
        <div class="flex items-center gap-2">
            <span class="text-[12px] font-medium text-gray-400 italic">Admin</span>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total Produk</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $totalProducts }}</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total Pesanan</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $totalOrders }}</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total User</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $totalUsers }}</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total Pendapatan</p>
            <h3 class="text-xl font-black text-gray-800">Rp {{ number_format($totalRevenue,0,',','.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-md font-bold text-gray-800">Pesanan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px]">
                <thead class="bg-[#dfe6f1] text-[#2d4a99] text-[11px] uppercase font-bold">
                    <tr>
                        <th class="px-5 py-3 border-b">ID Pesanan</th>
                        <th class="px-5 py-3 border-b">Nama Customer</th>
                        <th class="px-5 py-3 border-b">Tanggal</th>
                        <th class="px-5 py-3 border-b">Status</th>
                        <th class="px-5 py-3 border-b">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($latestOrders as $ord)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-semibold text-gray-500">#{{ $ord->id }}</td>
                        <td class="px-5 py-3">{{ $ord->user->name }}</td>
                        <td class="px-5 py-3">{{ \Carbon\Carbon::parse($ord->created_at)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 italic text-gray-600">
                            @if($ord->status=='waiting') Menunggu Konfirmasi
                            @elseif($ord->status=='valid') Selesai
                            @elseif($ord->status=='rejected') Ditolak
                            @else {{ $ord->status }}
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium">Rp {{ number_format($ord->total,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection