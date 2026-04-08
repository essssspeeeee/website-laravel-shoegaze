<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where('id', 'like', "%{$term}%")
                  ->orWhereHas('user', function ($q) use ($term) {
                      $q->where('name', 'like', "%{$term}%");
                  });
        }

        $orders = $query->orderBy('id', 'desc')->paginate(10);

        return view('dashboard.orders', compact('orders'));
    }

    public function update(Request $request, Transaction $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,valid,cancelled',
        ]);

        $statusMapping = [
            'pending' => 'pending',
            'processing' => 'packed',
            'shipping' => 'shipping',
            'valid' => 'valid',
            'cancelled' => 'cancelled',
        ];

        $order->status = $statusMapping[$request->status] ?? $order->status;
        $order->save();

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.orders.index' : 'admin.orders.index';
        return redirect()->route($route)
                         ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function kirimPesanan(Transaction $order)
    {
        if (! in_array($order->status, ['diproses', 'packed'], true)) {
            abort(403);
        }

        $order->status = 'shipping';
        $order->save();

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.orders.index' : 'admin.orders.index';
        return redirect()->route($route)
                         ->with('success', 'Status pesanan berhasil diperbarui menjadi Dikirim.');
    }
}
