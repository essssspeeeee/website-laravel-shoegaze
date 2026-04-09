<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Get month and year from request, default to current
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));

        // Query transactions for the selected month and year
        $query = Transaction::whereYear('created_at', $year)
                           ->whereMonth('created_at', $month);

        // Calculate metrics
        $totalTransactions = (clone $query)->count();

        $totalRevenue = (clone $query)->where('status', 'valid')->sum('total');

        $validCount = (clone $query)->where('status', 'valid')->count();

        $pendingCount = (clone $query)->where('status', 'pending')->count();

        // Get daily sales data for chart
        $dailySales = Transaction::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'valid')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Prepare chart data
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);
        $chartLabels = [];
        $chartData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $chartLabels[] = $day;
            $chartData[] = $dailySales[$date] ?? 0;
        }

        return view('dashboard.sales-report', compact(
            'month',
            'year',
            'totalTransactions',
            'totalRevenue',
            'validCount',
            'pendingCount',
            'rejectedCount',
            'transactions',
            'chartLabels',
            'transactions'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));

        $query = Transaction::whereYear('created_at', $year)
                           ->whereMonth('created_at', $month);

        $totalTransactions = (clone $query)->count();
        $totalRevenue = (clone $query)->where('status', 'valid')->sum('total');
        $validCount = (clone $query)->where('status', 'valid')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $rejectedCount = (clone $query)->where('status', 'cancelled')->count();

        $transactions = Transaction::with('user')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        $monthNames = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $data = [
            'month' => $monthNames[$month],
            'year' => $year,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'validCount' => $validCount,
            'pendingCount' => $pendingCount,
            'rejectedCount' => $rejectedCount,
            'transactions' => $transactions
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', $data);
        $filename = "laporan_penjualan_{$month}_{$year}.pdf";

        return $pdf->download($filename);
    }
}