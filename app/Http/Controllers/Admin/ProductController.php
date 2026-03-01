<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products (with optional search).
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);

        return view('dashboard.products', compact('products'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|array',
            'stock.39'    => 'required|integer|min:0',
            'stock.40'    => 'required|integer|min:0',
            'stock.41'    => 'required|integer|min:0',
            'stock.42'    => 'required|integer|min:0',
            'stock.43'    => 'required|integer|min:0',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'description' => 'nullable|string',
        ]);

        // handle image uploads
        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file) {
                    $paths[] = $file->store('products', 'public');
                }
            }
        }
        $validated['images'] = $paths;

        // stock is already array
        Product::create($validated);

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.products.index' : 'admin.products.index';
        return redirect()->route($route)->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|array',
            'stock.39'    => 'required|integer|min:0',
            'stock.40'    => 'required|integer|min:0',
            'stock.41'    => 'required|integer|min:0',
            'stock.42'    => 'required|integer|min:0',
            'stock.43'    => 'required|integer|min:0',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'description' => 'nullable|string',
        ]);

        // handle replacing images
        $paths = $product->images ?? [];
        if ($request->hasFile('images')) {
            // delete old images
            foreach ($paths as $old) {
                Storage::disk('public')->delete($old);
            }
            $paths = [];
            foreach ($request->file('images') as $file) {
                if ($file) {
                    $paths[] = $file->store('products', 'public');
                }
            }
        }
        $validated['images'] = $paths;

        $product->update($validated);

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.products.index' : 'admin.products.index';
        return redirect()->route($route)->with('success', 'Perubahan produk berhasil disimpan.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.products.index' : 'admin.products.index';
        return redirect()->route($route)->with('success', 'Produk berhasil dihapus.');
    }
}
