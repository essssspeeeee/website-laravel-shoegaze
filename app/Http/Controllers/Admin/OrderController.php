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
            'status' => 'required|in:waiting,valid,rejected',
        ]);

        $order->status = $request->status;
        $order->save();

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.orders.index' : 'admin.orders.index';
        return redirect()->route($route)
                         ->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
