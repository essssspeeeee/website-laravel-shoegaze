<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Transaction;

class CartController extends Controller
{
    // Fungsi untuk menampilkan halaman keranjang
    public function index()
    {
        $cart = session()->get('cart', []);

        if (auth()->check()) {
            // load from database and override session for consistency
            $dbItems = CartItem::with('product')
                ->where('user_id', auth()->id())
                ->get();

            $cart = [];
            foreach ($dbItems as $item) {
                $cart[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                    'size' => $item->size,
                    'maxStock' => $item->product->stock[$item->size] ?? null,
                ];
            }

            session()->put('cart', $cart);
        }

        return view('cart', compact('cart'));
    }

    // Fungsi untuk menampilkan halaman checkout
    public function checkout(Request $request)
    {
        $selectedItems = $request->input('selected_items', $request->query('selected_items', []));
        if (!is_array($selectedItems)) {
            $selectedItems = [$selectedItems];
        }

        $selectedIds = array_values(array_filter(array_map('intval', $selectedItems), function ($id) {
            return $id > 0;
        }));

        if (empty($selectedIds)) {
            return redirect()->route('cart')->with('error', 'Pilih minimal satu produk terlebih dahulu sebelum checkout.');
        }

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'payment_method' => 'nullable|string|in:qris,cod',
            ]);

            $paymentMethod = $validated['payment_method'] ?? 'qris';
            $status = $paymentMethod === 'cod' ? 'diproses' : 'pending';

            $cartItems = CartItem::with('product')
                ->where('user_id', auth()->id())
                ->whereIn('id', $selectedIds)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Item yang dipilih tidak ditemukan.');
            }

            $total = $cartItems->reduce(function ($sum, $item) {
                return $sum + ($item->product->price * $item->quantity);
            }, 0);

            $transactionData = [
                'user_id' => auth()->id(),
                'total' => $total,
                'method' => $paymentMethod,
                'status' => $status,
            ];

            if (Schema::hasColumn('transactions', 'payment_method')) {
                $transactionData['payment_method'] = $paymentMethod;
            }

            $transaction = Transaction::create($transactionData);

            foreach ($cartItems as $cartItem) {
                $transaction->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            CartItem::where('user_id', auth()->id())
                ->whereIn('id', $selectedIds)
                ->delete();

            $sessionCart = session()->get('cart', []);
            $sessionCart = array_values(array_filter($sessionCart, function ($item) use ($selectedIds) {
                return !in_array((int) ($item['id'] ?? 0), $selectedIds, true);
            }));
            session()->put('cart', $sessionCart);

            return redirect()->route('orders.show', $transaction->id)
                             ->with('success', 'Transaksi berhasil dibuat. ' . ($paymentMethod === 'qris' ? 'Silakan upload bukti transfer setelah pesanan dibuat.' : ''));
        }

        $cart = [];

        if (auth()->check()) {
            $dbItems = CartItem::with('product')
                ->where('user_id', auth()->id())
                ->whereIn('id', $selectedIds)
                ->get();

            foreach ($dbItems as $item) {
                $cart[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                    'size' => $item->size,
                ];
            }
        } else {
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart) && isset($sessionCart[0]['id'])) {
                $cart = array_values(array_filter($sessionCart, function ($item) use ($selectedIds) {
                    return in_array((int) ($item['id'] ?? 0), $selectedIds, true);
                }));
            }
        }

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Tidak ada produk yang dipilih untuk checkout.');
        }

        $user = auth()->user();
        $addresses = [
            [
                'id' => 1,
                'name' => $user->name,
                'phone' => $user->phone,
                'provinsi' => 'Jawa Barat, Depok, Beji, 17530',
                'jalan' => 'Jl. Palakkai Raya No. 999 Kukusan',
                'full' => $user->address,
            ],
            [
                'id' => 2,
                'name' => $user->name,
                'phone' => $user->phone,
                'provinsi' => 'Jawa Barat, Depok, Beji, 17530',
                'jalan' => 'Jl. Palakkai Raya No. 999 Kukusan',
                'full' => $user->address,
            ],
            [
                'id' => 3,
                'name' => $user->name,
                'phone' => $user->phone,
                'provinsi' => 'Jawa Barat, Depok, Beji, 17530',
                'jalan' => 'Jl. Palakkai Raya No. 999 Kukusan',
                'full' => $user->address,
            ],
        ];

        return view('checkout', compact('cart', 'addresses'));
    }

    public function showOrder($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        if (auth()->id() !== $transaction->user_id) {
            abort(403);
        }

        return view('orders.show', compact('transaction'));
    }

    // remove item from cart
    public function remove($id, $size = null)
    {
        $cart = session()->get('cart', []);
        $cart = array_values(array_filter($cart, function ($item) use ($id, $size) {
            return !(
                (int) $item['product_id'] === (int) $id &&
                ($size === null || ($item['size'] ?? null) === $size)
            );
        }));
        session()->put('cart', $cart);

        if (auth()->check()) {
            $query = CartItem::where('user_id', auth()->id())
                ->where('product_id', $id);

            if ($size !== null) {
                $query->where('size', $size);
            }

            $query->delete();
        }

        return response()->json(['message' => 'Item dihapus']);
    }

    // Fungsi untuk menambah barang (Fitur pop-up ceklis)
    public function add(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Produk tidak ditemukan'], 404);
            }
            abort(404);
        }

        $size = $request->input('size');
        $quantity = max(1, (int) $request->input('quantity', 1));
        $action = $request->input('action');

        if (!$action) {
            $action = 'add_to_cart';
        }

        $cart = session()->get('cart', []);
        $foundIndex = null;

        foreach ($cart as $index => $item) {
            if ((int) $item['product_id'] === (int) $id && ($item['size'] ?? null) === $size) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex !== null) {
            $cart[$foundIndex]['quantity'] += $quantity;
            if (!isset($cart[$foundIndex]['maxStock'])) {
                $cart[$foundIndex]['maxStock'] = $product->stock[$size] ?? null;
            }
        } else {
            $cart[] = [
                'product_id' => $id,
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image,
                'size' => $size,
                'maxStock' => $product->stock[$size] ?? null,
            ];
        }

        session()->put('cart', $cart);

        if (auth()->check()) {
            $userId = auth()->id();
            $cartItem = CartItem::firstOrNew([
                'user_id' => $userId,
                'product_id' => $id,
                'size' => $size,
            ]);
            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $quantity;
            $cartItem->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Berhasil dimasukkan keranjang',
                'count' => count(session('cart', [])),
                'cart' => session('cart', []),
            ]);
        }

        if ($action === 'buy_now') {
            return redirect()->route('checkout');
        }

        return back()->with('success', 'Produk masuk keranjang');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $validated['quantity'];
        $cart = session()->get('cart', []);
        $foundIndex = null;

        foreach ($cart as $index => $item) {
            if (isset($item['id']) && (int) $item['id'] === (int) $id) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex === null) {
            return response()->json(['message' => 'Item keranjang tidak ditemukan.'], 404);
        }

        $maxStock = $cart[$foundIndex]['maxStock'] ?? null;
        if ($maxStock !== null && $quantity > $maxStock) {
            return response()->json([
                'message' => 'Jumlah melebihi stok tersedia.',
                'max_stock' => $maxStock,
            ], 422);
        }

        $cart[$foundIndex]['quantity'] = $quantity;
        session()->put('cart', $cart);

        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())
                ->where('id', $id)
                ->update(['quantity' => $quantity]);
        }

        $itemSubtotal = $cart[$foundIndex]['price'] * $quantity;
        $totalAmount = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return response()->json([
            'message' => 'Quantity berhasil diperbarui.',
            'item_subtotal' => $itemSubtotal,
            'total_amount' => $totalAmount,
            'quantity' => $quantity,
        ]);
    }
}