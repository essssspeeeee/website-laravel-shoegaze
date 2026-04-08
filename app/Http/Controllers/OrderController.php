<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status', 'all');

        $query = Transaction::with(['items.product'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($statusFilter !== 'all') {
            if ($statusFilter === 'menunggu') {
                $query->whereIn('status', ['waiting', 'pending']);
            } elseif ($statusFilter === 'dikemas') {
                $query->whereIn('status', ['diproses', 'packed']);
            } elseif ($statusFilter === 'dikirim') {
                $query->where('status', 'shipping');
            } elseif ($statusFilter === 'selesai') {
                $query->where('status', 'valid');
            }
        }

        $orders = $query->get();

        // decode stock data by each product (if JSON string)
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product && isset($item->product->stock)) {
                    if (is_string($item->product->stock)) {
                        $decoded = json_decode($item->product->stock, true);
                        $item->product->stock = is_array($decoded) ? $decoded : [];
                    }
                }
            }
        }

        return view('dashboard.orders', compact('orders', 'statusFilter'));
    }
}
