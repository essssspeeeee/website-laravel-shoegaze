<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // parse filters
        $month = $request->input('bulan');
        $year = $request->input('tahun');

        $transQuery = Transaction::query();
        if ($month) {
            $transQuery->whereMonth('created_at', $month);
        }
        if ($year) {
            $transQuery->whereYear('created_at', $year);
        }

        $transactions = $transQuery->with(['user', 'items.product'])->get();

        // flatten items for table rows
        $rows = [];
        foreach ($transactions as $trans) {
            foreach ($trans->items as $item) {
                $rows[] = [
                    'date' => $trans->created_at,
                    'id' => $trans->id,
                    'customer' => $trans->user->name,
                    'product' => $item->product->name,
                    'qty' => $item->quantity,
                    'total' => $item->quantity * $item->price,
                    'status' => $trans->status,
                ];
            }
            // if transaction has no items we'll still push one row
            if ($trans->items->isEmpty()) {
                $rows[] = [
                    'date' => $trans->created_at,
                    'id' => $trans->id,
                    'customer' => $trans->user->name,
                    'product' => '-',
                    'qty' => '-',
                    'total' => $trans->total,
                    'status' => $trans->status,
                ];
            }
        }

        // statistics
        $statsQuery = (clone $transQuery);
        $totalOrders = $statsQuery->count();
        $doneOrders = (clone $statsQuery)->where('status', 'valid')->count();
        $pendingOrders = (clone $statsQuery)->where('status', 'waiting')->count();
        // no explicit delivered status so treat valid as delivered
        $deliveredOrders = $doneOrders;

        return view('dashboard.history', compact(
            'rows', 'totalOrders', 'doneOrders', 'deliveredOrders', 'pendingOrders', 'month', 'year'
        ));
    }
}