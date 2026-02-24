<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;

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
                $cart[$item->product_id] = [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                ];
            }

            session()->put('cart', $cart);
        }

        return view('cart', compact('cart'));
    }

    // Fungsi untuk menampilkan halaman checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);
        $user = auth()->user();
        // example list of addresses; in real app you'd query a related model
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

    // remove item from cart
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())
                ->where('product_id', $id)
                ->delete();
        }
        return response()->json(['message' => 'Item dihapus']);
    }

    // Fungsi untuk menambah barang (Fitur pop-up ceklis)
    public function add($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $cart = session()->get('cart', []);

        // Logika menambah ke keranjang
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        // if user logged in, persist in database
        if (auth()->check()) {
            $userId = auth()->id();
            $cartItem = CartItem::firstOrNew([
                'user_id' => $userId,
                'product_id' => $id,
            ]);
            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + 1;
            $cartItem->save();
        }

        // return the current cart count so frontend can update badge
        return response()->json([
            'message' => 'Berhasil dimasukkan keranjang',
            'count' => count(session('cart', [])),
            'cart' => session('cart', []),
        ]);
    }
}