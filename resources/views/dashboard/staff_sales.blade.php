@extends('layouts.staff')

@section('title', 'Laporan Penjualan - SHOEGAZE')

@section('content')
    <h2 class="text-xl font-bold mb-4">Laporan Penjualan</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <h3 class="text-sm font-semibold text-gray-500">Total Transaksi</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($total ?? 0) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <h3 class="text-sm font-semibold text-green-500">Valid</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($valid ?? 0) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <h3 class="text-sm font-semibold text-yellow-500">Menunggu</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($waiting ?? 0) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <h3 class="text-sm font-semibold text-red-500">Ditolak</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($rejected ?? 0) }}</p>
        </div>
    </div>

    <p class="text-gray-600">Untuk detail transaksi silakan gunakan menu <strong>Kelola Pesanan</strong>.</p>
@endsection