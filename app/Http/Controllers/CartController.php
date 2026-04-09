<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Transaction;

class CartController extends Controller
{
    // Fungsi untuk menampilkan halaman keranjang
    public function index()
    {
        $cart = [];

        if (auth()->check()) {
            $dbItems = CartItem::with('product')->where('user_id', auth()->id())->get();
            foreach ($dbItems as $item) {
                $cart[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'image' => $item->product->images ? asset('img/product/' . $item->product->images[0]) : 'https://via.placeholder.com/150x150?text=No+Image',
                    'size' => $item->size,
                    'maxStock' => $item->product->stock[$item->size] ?? 0,
                ];
            }
        } else {
            $cart = session()->get('cart', []);
            $originalCount = count($cart);

            // Buang item rusak / yang ukuran null
            $cart = array_values(array_filter($cart, function ($item) {
                return isset($item['size']) && in_array((string)$item['size'], ['39','40','41','42','43'], true);
            }));

            if (count($cart) !== $originalCount) {
                session()->flash('info', 'Item dengan ukuran tidak valid telah dihapus dari keranjang.');
            }

            // Set maxStock dan validasi price untuk setiap item
            foreach ($cart as &$item) {
                $product = Product::find($item['product_id']);
                if ($product && isset($item['size'])) {
                    $item['maxStock'] = $product->stock[$item['size']] ?? 0;
                    // Jika price tidak ada atau null, ambil dari product
                    if (!isset($item['price']) || $item['price'] === null) {
                        $item['price'] = $product->price;
                    }
                    // Jika image tidak ada, ambil dari product
                    if (!isset($item['image']) || $item['image'] === null) {
                        $item['image'] = $product->images ? asset('img/product/' . $product->images[0]) : 'https://via.placeholder.com/150x150?text=No+Image';
                    }
                    // Jika name tidak ada, ambil dari product
                    if (!isset($item['name']) || $item['name'] === null) {
                        $item['name'] = $product->name;
                    }
                } else {
                    $item['maxStock'] = 0;
                }
            }
            unset($item);

            session()->put('cart', $cart);
        }

        return view('cart', compact('cart'));
    }

    // Fungsi untuk menampilkan halaman checkout
    public function checkout(Request $request)
    {
        $isDirectPurchase = $request->query('product_id') !== null || $request->input('product_id') !== null;

        try {
            if ($request->isMethod('post')) {
                if ($isDirectPurchase) {
                    Log::info('Direct purchase initiated', [
                        'product_id' => $request->post('product_id'),
                        'quantity' => $request->post('quantity'),
                        'size' => $request->post('size'),
                        'user_id' => auth()->id(),
                    ]);

                    $validated = $request->validate([
                        'product_id' => 'required|integer|exists:products,id',
                        'quantity' => 'required|integer|min:1',
                        'size' => 'required|string|in:39,40,41,42,43',
                        'payment_method' => 'nullable|string|in:qris,cod',
                        'shipping_method' => 'nullable|string|in:reguler,standar,ekspres',
                        'shipping_cost' => 'nullable|numeric|min:0',
                        'selected_address_name' => 'required|string|max:255',
                        'selected_address_phone' => 'required|string|max:20',
                        'selected_address_jalan' => 'required|string|max:500',
                    ]);

                    $product = Product::findOrFail($validated['product_id']);
                    $paymentMethod = $validated['payment_method'] ?? 'qris';
                    $shippingMethod = $validated['shipping_method'] ?? 'reguler';
                    $shippingCost = $validated['shipping_cost'] ?? 8000;
                    $status = $paymentMethod === 'cod' ? 'diproses' : 'pending';

                    $stockArray = is_string($product->stock) ? json_decode($product->stock, true) : ($product->stock ?? []);
                    $stockArray = is_array($stockArray) ? $stockArray : [];
                    $stock = (int) ($stockArray[$validated['size']] ?? 0);

                    Log::info('Stock check for direct purchase POST', [
                        'product_id' => $validated['product_id'],
                        'size' => $validated['size'],
                        'quantity' => $validated['quantity'],
                        'stock_raw' => $product->stock,
                        'stock_decoded' => $stockArray,
                        'available_stock' => $stock,
                    ]);

                    if ($stock < $validated['quantity']) {
                        return redirect()->back()->with('error', 'Stok produk ' . $product->name . ' ukuran ' . $validated['size'] . ' tidak tersedia atau tidak mencukupi. Stok tersedia: ' . $stock);
                    }

                    $total = $product->price * $validated['quantity'] + $shippingCost;

                    $transactionData = [
                        'user_id' => auth()->id(),
                        'total' => $total,
                        'method' => $paymentMethod,
                        'status' => $status,
                        'shipping_method' => $shippingMethod,
                        'shipping_cost' => $shippingCost,
                    ];

                    if (Schema::hasColumn('transactions', 'payment_method')) {
                        $transactionData['payment_method'] = $paymentMethod;
                    }
                    if (Schema::hasColumn('transactions', 'shipping_method')) {
                        $transactionData['shipping_method'] = $shippingMethod;
                    }
                    if (Schema::hasColumn('transactions', 'shipping_cost')) {
                        $transactionData['shipping_cost'] = $shippingCost;
                    }
                    if (Schema::hasColumn('transactions', 'selected_address_name')) {
                        $transactionData['selected_address_name'] = $validated['selected_address_name'];
                    }
                    if (Schema::hasColumn('transactions', 'selected_address_phone')) {
                        $transactionData['selected_address_phone'] = $validated['selected_address_phone'];
                    }
                    if (Schema::hasColumn('transactions', 'selected_address_jalan')) {
                        $transactionData['selected_address_jalan'] = $validated['selected_address_jalan'];
                    }
                    if (Schema::hasColumn('transactions', 'recipient_name')) {
                        $transactionData['recipient_name'] = $validated['selected_address_name'];
                    }
                    if (Schema::hasColumn('transactions', 'phone_number')) {
                        $transactionData['phone_number'] = $validated['selected_address_phone'];
                    }
                    if (Schema::hasColumn('transactions', 'full_address')) {
                        $transactionData['full_address'] = $validated['selected_address_jalan'];
                    }

                    $transaction = Transaction::create($transactionData);

                    $transaction->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $validated['quantity'],
                        'price' => $product->price,
                    ]);

                    $stockArray[$validated['size']] -= $validated['quantity'];
                    $product->stock = $stockArray;
                    $product->save();

                    return redirect()->route('orders.show', $transaction->id)
                                     ->with('success', 'Transaksi berhasil dibuat. ' . ($paymentMethod === 'qris' ? 'Silakan upload bukti transfer setelah pesanan dibuat.' : ''));
                }

                $validated = $request->validate([
                    'payment_method' => 'nullable|string|in:qris,cod',
                    'shipping_method' => 'nullable|string|in:reguler,standar,ekspres',
                    'shipping_cost' => 'nullable|numeric|min:0',
                    'selected_address_name' => 'required|string|max:255',
                    'selected_address_phone' => 'required|string|max:20',
                    'selected_address_jalan' => 'required|string|max:500',
                ]);

                $paymentMethod = $validated['payment_method'] ?? 'qris';
                $shippingMethod = $validated['shipping_method'] ?? 'reguler';
                $shippingCost = $validated['shipping_cost'] ?? 8000;
                $status = $paymentMethod === 'cod' ? 'diproses' : 'pending';

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

                $cartItems = CartItem::with('product')
                    ->where('user_id', auth()->id())
                    ->whereIn('id', $selectedIds)
                    ->get();

                if ($cartItems->isEmpty()) {
                    return redirect()->route('cart')->with('error', 'Item yang dipilih tidak ditemukan.');
                }

                foreach ($cartItems as $cartItem) {
                    $stockArray = $cartItem->product->stock ?? [];
                    $stockArray = is_string($stockArray) ? json_decode($stockArray, true) : ($stockArray ?? []);
                    $stockArray = is_array($stockArray) ? $stockArray : [];
                    $stock = (int) ($stockArray[$cartItem->size] ?? 0);

                    if ($stock < $cartItem->quantity) {
                        return redirect()->route('cart')->with('error', 'Stok untuk ' . $cartItem->product->name . ' ukuran ' . $cartItem->size . ' tidak mencukupi. Stok tersedia: ' . $stock);
                    }
                }

                $total = $cartItems->reduce(function ($sum, $item) {
                    return $sum + ($item->product->price * $item->quantity);
                }, 0) + $shippingCost;

                $transactionData = [
                    'user_id' => auth()->id(),
                    'total' => $total,
                    'method' => $paymentMethod,
                    'status' => $status,
                    'shipping_method' => $shippingMethod,
                    'shipping_cost' => $shippingCost,
                ];

                if (Schema::hasColumn('transactions', 'payment_method')) {
                    $transactionData['payment_method'] = $paymentMethod;
                }
                if (Schema::hasColumn('transactions', 'shipping_method')) {
                    $transactionData['shipping_method'] = $shippingMethod;
                }
                if (Schema::hasColumn('transactions', 'shipping_cost')) {
                    $transactionData['shipping_cost'] = $shippingCost;
                }
                if (Schema::hasColumn('transactions', 'selected_address_name')) {
                    $transactionData['selected_address_name'] = $validated['selected_address_name'];
                }
                if (Schema::hasColumn('transactions', 'selected_address_phone')) {
                    $transactionData['selected_address_phone'] = $validated['selected_address_phone'];
                }
                if (Schema::hasColumn('transactions', 'selected_address_jalan')) {
                    $transactionData['selected_address_jalan'] = $validated['selected_address_jalan'];
                }
                if (Schema::hasColumn('transactions', 'recipient_name')) {
                    $transactionData['recipient_name'] = $validated['selected_address_name'];
                }
                if (Schema::hasColumn('transactions', 'phone_number')) {
                    $transactionData['phone_number'] = $validated['selected_address_phone'];
                }
                if (Schema::hasColumn('transactions', 'full_address')) {
                    $transactionData['full_address'] = $validated['selected_address_jalan'];
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

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    $stockArray = is_string($product->stock) ? json_decode($product->stock, true) : ($product->stock ?? []);
                    $stockArray = is_array($stockArray) ? $stockArray : [];
                    $stock = (int) ($stockArray[$cartItem->size] ?? 0);

                    if ($stock >= $cartItem->quantity) {
                        $stockArray[$cartItem->size] -= $cartItem->quantity;
                        $product->stock = $stockArray;
                        $product->save();
                    } else {
                        return redirect()->route('cart')->with('error', 'Stok untuk ukuran ' . $cartItem->size . ' tidak mencukupi.');
                    }
                }

                $sessionCart = session()->get('cart', []);
                $sessionCart = array_values(array_filter($sessionCart, function ($item) use ($selectedIds) {
                    return !in_array((int) ($item['id'] ?? 0), $selectedIds, true);
                }));
                session()->put('cart', $sessionCart);

                return redirect()->route('orders.show', $transaction->id)
                                 ->with('success', 'Transaksi berhasil dibuat. ' . ($paymentMethod === 'qris' ? 'Silakan upload bukti transfer setelah pesanan dibuat.' : ''));
            }
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'is_direct_purchase' => $isDirectPurchase,
            ]);

            if ($isDirectPurchase) {
                $productId = $request->query('product_id') ?? $request->post('product_id');
                if ($productId) {
                    return redirect()->route('product.detail', $productId)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
                return redirect()->route('products.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }

            return redirect()->route('cart')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $cartItems = [];

        if ($isDirectPurchase) {
            $productId = (int) $request->query('product_id');
            $quantity = max(1, (int) $request->query('quantity', 1));
            $size = $request->query('size');

            $product = Product::find($productId);
            if (!$product) {
                return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan.');
            }

            if (!$size || !in_array((string) $size, ['39', '40', '41', '42', '43'], true)) {
                return redirect()->route('product.detail', $productId)->with('error', 'Ukuran tidak valid.');
            }

            $stockArray = is_string($product->stock) ? json_decode($product->stock, true) : ($product->stock ?? []);
            $stockArray = is_array($stockArray) ? $stockArray : [];
            $availableStock = (int) ($stockArray[$size] ?? 0);

            Log::info('Stock check for direct purchase GET', [
                'product_id' => $productId,
                'size' => $size,
                'quantity' => $quantity,
                'stock_raw' => $product->stock,
                'stock_decoded' => $stockArray,
                'available_stock' => $availableStock,
            ]);

            if ($availableStock < $quantity) {
                return redirect()->route('product.detail', $productId)->with('error', 'Stok tidak mencukupi. Tersedia: ' . $availableStock);
            }

            $cartItems[] = [
                'id' => 'direct_' . $productId . '_' . $size,
                'product_id' => $productId,
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->images ? asset('img/product/' . $product->images[0]) : asset('images/default-product.png'),
                'size' => $size,
            ];
        } else {
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

            $dbItems = CartItem::with('product')
                ->where('user_id', auth()->id())
                ->whereIn('id', $selectedIds)
                ->get();

            foreach ($dbItems as $item) {
                $cartItems[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'image' => $item->product->images ? asset('img/product/' . $item->product->images[0]) : asset('images/default-product.png'),
                    'size' => $item->size,
                ];
            }
        }

        $user = auth()->user();
        $storedAddresses = session('checkout_addresses', []);
        $checkoutAddresses = [];

        $checkoutAddresses[0] = [
            'id' => 0,
            'name' => $user->name,
            'phone' => $user->phone ?? '',
            'provinsi' => $user->address ? 'Alamat Utama' : '',
            'jalan' => $user->address ?? '',
            'is_empty' => empty($user->address),
        ];

        for ($slot = 1; $slot <= 2; $slot++) {
            if (isset($storedAddresses[$slot]) && !empty($storedAddresses[$slot]['jalan'])) {
                $checkoutAddresses[$slot] = array_merge(['id' => $slot, 'is_empty' => false], $storedAddresses[$slot]);
            } else {
                $checkoutAddresses[$slot] = [
                    'id' => $slot,
                    'name' => '',
                    'phone' => '',
                    'provinsi' => '',
                    'jalan' => '',
                    'is_empty' => true,
                ];
            }
        }

        $selectedAddressIndex = session('checkout_selected_address', 0);
        session()->put('checkout_previous_url', url()->previous());

        return view('checkout', ['cart' => $cartItems, 'checkoutAddresses' => $checkoutAddresses, 'selectedAddressIndex' => $selectedAddressIndex]);
    }

    public function saveAddress(Request $request)
    {
        $validated = $request->validate([
            'slot_index' => 'required|integer|min:0|max:2',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'jalan' => 'required|string|max:500',
            'selected_items' => 'array',
            'selected_items.*' => 'integer',
        ]);

        $slot = (int) $validated['slot_index'];
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($slot === 0) {
            $user->name = $validated['name'];
            $user->phone = $validated['phone'];
            $user->address = $validated['jalan'];
            $user->save();
        }

        $storedAddresses = session('checkout_addresses', []);
        if ($slot !== 0) {
            $storedAddresses[$slot] = [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'jalan' => $validated['jalan'],
            ];
            session(['checkout_addresses' => $storedAddresses]);
        }

        session(['checkout_selected_address' => $slot]);

        return redirect()->route('checkout', ['selected_items' => $request->input('selected_items', [])])
                         ->with('success', 'Alamat berhasil disimpan.');
    }

    public function showOrder($id)
    {
        $order = Transaction::with('items.product')->findOrFail($id);

        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        return view('order-summary', compact('order'));
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

        $validSizes = ['39', '40', '41', '42', '43'];
        if (!$size || !in_array((string)$size, $validSizes, true)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Pilih ukuran yang valid sebelum menambahkan ke keranjang.'], 422);
            }
            return redirect()->back()->with('error', 'Pilih ukuran yang valid sebelum menambahkan ke keranjang.');
        }

        if (!$action) {
            $action = 'add_to_cart';
        }

        if ($action === 'buy_now') {
            return redirect()->route('checkout.direct', [
                'product_id' => $id,
                'quantity' => $quantity,
                'size' => $size,
            ]);
        }

        $cart = session()->get('cart', []);
        $foundIndex = null;
        $nextId = 1;

        foreach ($cart as $index => $item) {
            if (isset($item['id']) && is_numeric($item['id'])) {
                $nextId = max($nextId, (int) $item['id'] + 1);
            }
            if ((int) $item['product_id'] === (int) $id && ($item['size'] ?? null) === $size) {
                $foundIndex = $index;
                break;
            }
        }

        $selectedCartItemId = null;

        if ($foundIndex !== null) {
            $maxStock = (int) ($cart[$foundIndex]['maxStock'] ?? ($product->stock[$size] ?? 0));
            $newQuantity = (int) $cart[$foundIndex]['quantity'] + $quantity;
            if ($maxStock > 0 && $newQuantity > $maxStock) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Jumlah melebihi stok tersedia. Maksimal: ' . $maxStock], 422);
                }
                return redirect()->back()->with('error', 'Jumlah melebihi stok tersedia. Maksimal: ' . $maxStock);
            }
            $cart[$foundIndex]['quantity'] = $newQuantity;
            if (!isset($cart[$foundIndex]['maxStock']) || $cart[$foundIndex]['maxStock'] === null) {
                $cart[$foundIndex]['maxStock'] = $maxStock;
            }
            if (!isset($cart[$foundIndex]['id'])) {
                $cart[$foundIndex]['id'] = $nextId++;
            }
            $selectedCartItemId = $cart[$foundIndex]['id'];
        } else {
            $maxStock = (int) ($product->stock[$size] ?? 0);
            if ($maxStock > 0 && $quantity > $maxStock) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Jumlah melebihi stok tersedia. Maksimal: ' . $maxStock], 422);
                }
                return redirect()->back()->with('error', 'Jumlah melebihi stok tersedia. Maksimal: ' . $maxStock);
            }
            $newCartItem = [
                'id' => $nextId++,
                'product_id' => $id,
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->images ? asset('img/product/' . $product->images[0]) : 'https://via.placeholder.com/150x150?text=No+Image',
                'size' => $size,
                'maxStock' => $maxStock,
            ];
            $cart[] = $newCartItem;
            $selectedCartItemId = $newCartItem['id'];
        }

        session()->put('cart', $cart);

        if (auth()->check() && $action !== 'buy_now') {
            try {
                $userId = auth()->id();
                $cartItem = CartItem::firstOrNew([
                    'user_id' => $userId,
                    'product_id' => $id,
                    'size' => $size,
                ]);
                $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $quantity;
                $cartItem->save();

                // Update session cart with database id
                if ($foundIndex !== null) {
                    $cart[$foundIndex]['id'] = $cartItem->id;
                } else {
                    $cart[count($cart) - 1]['id'] = $cartItem->id;
                }
                session()->put('cart', $cart);
                $selectedCartItemId = $cartItem->id;
            } catch (\Exception $e) {
                // Log error but continue - session cart is primary
                Log::warning('Failed to save cart item to database', [
                    'error' => $e->getMessage(),
                    'product_id' => $id,
                    'size' => $size,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Berhasil dimasukkan keranjang',
                'count' => count(session('cart', [])),
                'cart' => session('cart', []),
            ]);
        }

        if ($action === 'buy_now') {
            return redirect()->route('checkout.direct', [
                'product_id' => $id,
                'quantity' => $quantity,
                'size' => $size
            ]);
        }

        return back()->with('success', 'Produk masuk keranjang');
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $quantity = (int) $validated['quantity'];
            $cart = session()->get('cart', []);
            $foundIndex = null;

            // Find the item in cart
            foreach ($cart as $index => $item) {
                if (isset($item['id']) && (int) $item['id'] === (int) $id) {
                    $foundIndex = $index;
                    break;
                }
            }

            if ($foundIndex === null) {
                return response()->json(['message' => 'Item keranjang tidak ditemukan.'], 404);
            }

            $cartItem = $cart[$foundIndex];
            
            // Get actual maxStock value with better validation
            $maxStock = 0;
            if (isset($cartItem['maxStock']) && $cartItem['maxStock'] !== null) {
                $maxStock = (int) $cartItem['maxStock'];
            }

            // Only validate if maxStock is greater than 0
            if ($maxStock > 0 && $quantity > $maxStock) {
                return response()->json([
                    'message' => 'Jumlah melebihi stok tersedia. Stok maksimal: ' . $maxStock,
                    'max_stock' => $maxStock,
                ], 422);
            }

            // Update session cart
            $cart[$foundIndex]['quantity'] = $quantity;
            session()->put('cart', $cart);

            // Try to update database if authenticated (silent fail if error)
            if (auth()->check()) {
                try {
                    CartItem::where('user_id', auth()->id())
                        ->where('id', $id)
                        ->update(['quantity' => $quantity]);
                } catch (\Exception $e) {
                    // Log warning but don't fail - session cart is primary
                    Log::warning('Failed to update cart quantity in database', [
                        'cart_id' => $id,
                        'quantity' => $quantity,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Calculate totals safely
            $itemPrice = 0;
            if (isset($cartItem['price'])) {
                $priceStr = (string) $cartItem['price'];
                $itemPrice = (int) preg_replace('/[^0-9]/', '', $priceStr);
                if ($itemPrice === 0 || !is_numeric($itemPrice)) {
                    $itemPrice = 0;
                }
            }
            
            $itemSubtotal = $itemPrice * $quantity;

            // Calculate total amount safely
            $totalAmount = 0;
            foreach ($cart as $item) {
                $price = 0;
                if (isset($item['price'])) {
                    $priceStr = (string) $item['price'];
                    $price = (int) preg_replace('/[^0-9]/', '', $priceStr);
                    if ($price === 0 || !is_numeric($price)) {
                        $price = 0;
                    }
                }
                $qty = isset($item['quantity']) ? (int) $item['quantity'] : 0;
                $totalAmount += ($price * $qty);
            }

            return response()->json([
                'message' => 'Quantity berhasil diperbarui.',
                'item_subtotal' => $itemSubtotal,
                'total_amount' => $totalAmount,
                'quantity' => $quantity,
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi data gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Cart update critical error', [
                'cart_id' => $id ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'message' => 'Terjadi kesalahan. Silahkan refresh halaman dan coba lagi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function confirmReceived(Request $request, $id)
    {
        $order = Transaction::findOrFail($id);

        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        if ($order->status !== 'shipping') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dikonfirmasi saat ini.');
        }

        $order->status = 'valid';
        $order->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi sebagai diterima.');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        $count = 0;

        foreach ($cart as $item) {
            if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                $count += (int) $item['quantity'];
            }
        }

        return response()->json(['count' => $count]);
    }

    public function uploadProof(Request $request, $id)
    {
        $order = Transaction::findOrFail($id);

        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        // Check if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $id)->with('error', 'Maaf, pesanan yang sudah dibatalkan tidak bisa dibayar.');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $file = $request->file('payment_proof');
            $fileName = 'proof_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('proof_payments', $fileName, 'public');

            $order->update([
                'proof_image' => $filePath,
                'status' => 'waiting',
            ]);

            Log::info('Payment proof uploaded successfully', [
                'transaction_id' => $id,
                'file_path' => $filePath,
                'user_id' => auth()->id(),
                'file_name' => $fileName,
            ]);

            return redirect()->route('orders.show', $id)->with('success', 'Bukti pembayaran berhasil diunggah dan pesanan Anda telah dikonfirmasi.');
        } catch (\Exception $e) {
            Log::error('Payment proof upload failed', [
                'transaction_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('orders.show', $id)->with('error', 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }
}