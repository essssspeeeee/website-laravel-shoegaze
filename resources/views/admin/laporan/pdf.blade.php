<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - {{ $month }} {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            width: 20%;
        }
        .summary-label {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .status-valid {
            color: #28a745;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .status-cancelled {
            color: #dc3545;
            font-weight: bold;
        }
        .status-packed {
            color: #007bff;
            font-weight: bold;
        }
        .status-shipping {
            color: #6f42c1;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan Bulanan</h1>
        <p><strong>Periode:</strong> {{ $month }} {{ $year }}</p>
        <p><strong>Tanggal Generate:</strong> {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-row">
            <div class="summary-cell summary-label">Total Transaksi</div>
            <div class="summary-cell summary-label">Total Pendapatan</div>
            <div class="summary-cell summary-label">Valid</div>
            <div class="summary-cell summary-label">Pending</div>
            <div class="summary-cell summary-label">Ditolak</div>
        </div>
        <div class="summary-row">
            <div class="summary-cell summary-value">{{ $totalTransactions }}</div>
            <div class="summary-cell summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="summary-cell summary-value">{{ $validCount }}</div>
            <div class="summary-cell summary-value">{{ $pendingCount }}</div>
            <div class="summary-cell summary-value">{{ $rejectedCount }}</div>
        </div>
    </div>

    <h2 style="margin-bottom: 15px;">Detail Transaksi</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ID Order</th>
                <th style="width: 20%;">Nama Customer</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 15%;">Total</th>
                <th style="width: 20%;">Metode Pembayaran</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td class="text-center">#{{ $transaction->id }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td class="text-center">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                <td class="text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                <td>{{ $transaction->payment_method ?? $transaction->method ?? '-' }}</td>
                <td class="text-center">
                    @php
                        $statusLabels = [
                            'valid' => 'Valid',
                            'pending' => 'Pending',
                            'cancelled' => 'Ditolak',
                            'packed' => 'Dikemas',
                            'shipping' => 'Dikirim',
                        ];
                        $statusClasses = [
                            'valid' => 'status-valid',
                            'pending' => 'status-pending',
                            'cancelled' => 'status-cancelled',
                            'packed' => 'status-packed',
                            'shipping' => 'status-shipping',
                        ];
                    @endphp
                    <span class="{{ $statusClasses[$transaction->status] ?? '' }}">
                        {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh sistem SHOEGAZE</p>
        <p>&copy; {{ date('Y') }} SHOEGAZE. All rights reserved.</p>
    </div>
</body>
</html>