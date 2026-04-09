@extends('layouts.admin')

@section('title', 'Laporan Penjualan - SHOEGAZE')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-slate-900 mb-8">Laporan Penjualan</h1>

    <!-- Filter Section -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 mb-8">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl font-semibold text-slate-800">Filter Laporan</h2>
            <a href="{{ route('admin.sales-report.download-pdf', ['month' => $month, 'year' => $year]) }}"
               class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
        </div>
        <form method="GET" action="{{ route('admin.sales-report') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="month" class="block text-sm font-medium text-slate-700 mb-2">Bulan</label>
                <select id="month" name="month" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Pilih Bulan</option>
                    <option value="01" {{ $month == '01' ? 'selected' : '' }}>Januari</option>
                    <option value="02" {{ $month == '02' ? 'selected' : '' }}>Februari</option>
                    <option value="03" {{ $month == '03' ? 'selected' : '' }}>Maret</option>
                    <option value="04" {{ $month == '04' ? 'selected' : '' }}>April</option>
                    <option value="05" {{ $month == '05' ? 'selected' : '' }}>Mei</option>
                    <option value="06" {{ $month == '06' ? 'selected' : '' }}>Juni</option>
                    <option value="07" {{ $month == '07' ? 'selected' : '' }}>Juli</option>
                    <option value="08" {{ $month == '08' ? 'selected' : '' }}>Agustus</option>
                    <option value="09" {{ $month == '09' ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $month == '10' ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ $month == '11' ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $month == '12' ? 'selected' : '' }}>Desember</option>
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium text-slate-700 mb-2">Tahun</label>
                <select id="year" name="year" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Pilih Tahun</option>
                    <option value="2023" {{ $year == '2023' ? 'selected' : '' }}>2023</option>
                    <option value="2024" {{ $year == '2024' ? 'selected' : '' }}>2024</option>
                    <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>2025</option>
                    <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>2026</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Transaksi -->
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Transaksi</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $totalTransactions }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Valid -->
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Valid</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $validCount }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Pending</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $pendingCount }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Ditolak</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $rejectedCount }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 mb-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Grafik Penjualan Harian</h2>
        <div class="h-80">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Placeholder for future content -->
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Detail Transaksi</h2>
        @if($transactions->isEmpty())
            <p class="text-slate-500 text-center">Tidak ada transaksi untuk periode ini.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Metode Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">#{{ $transaction->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $transaction->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $transaction->payment_method ?? $transaction->method ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'valid' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'packed' => 'bg-blue-100 text-blue-800',
                                            'shipping' => 'bg-purple-100 text-purple-800',
                                        ];
                                        $statusLabels = [
                                            'valid' => 'Valid',
                                            'pending' => 'Pending',
                                            'cancelled' => 'Ditolak',
                                            'packed' => 'Dikemas',
                                            'shipping' => 'Dikirim',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Penjualan Harian (Rp)',
                    data: @json($chartData),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(239, 68, 68)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    });
</script>
@endsection