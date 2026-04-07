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
            'images'      => 'sometimes|array|max:5',
            'images.*'    => 'sometimes|file|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('img/product'), $filename);
                    $paths[] = $filename;
                }
            }
            $validated['images'] = $paths;
        } else {
            $validated['images'] = [];
        }

        // Ensure each size has a valid integer value and compile as JSON
        $stockData = [];
        foreach (['39', '40', '41', '42', '43'] as $size) {
            $value = isset($validated['stock'][$size]) ? $validated['stock'][$size] : 0;
            $stockData[$size] = is_numeric($value) ? max(0, intval($value)) : 0;
        }
        $validated['stock'] = json_encode($stockData);

        // Set status based on total stock
        $totalStock = array_sum($stockData);
        $validated['status'] = $totalStock > 0 ? 'Tersedia' : 'Habis';

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
            'images'      => 'sometimes|array|max:5',
            'images.*'    => 'sometimes|file|image|mimes:jpeg,png,jpg|max:2048',
            'removed_images' => 'sometimes|json',
            'description' => 'nullable|string',
        ]);

        $currentImages = $product->images ?? [];

        // Handle removed images
        if ($request->filled('removed_images')) {
            $removed = json_decode($request->input('removed_images'), true);
            if (is_array($removed)) {
                foreach ($removed as $removePath) {
                    if (in_array($removePath, $currentImages)) {
                        $filePath = public_path('img/product/' . $removePath);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $currentImages = array_diff($currentImages, [$removePath]);
                    }
                }
            }
        }

        // Handle new images
        $newPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('img/product'), $filename);
                    $newPaths[] = $filename;
                }
            }
        }
        $currentImages = array_merge($currentImages, $newPaths);

        // Ensure max 5 images
        if (count($currentImages) > 5) {
            // If more than 5, keep only first 5
            $toDelete = array_slice($currentImages, 5);
            foreach ($toDelete as $del) {
                Storage::disk('public')->delete($del);
            }
            $currentImages = array_slice($currentImages, 0, 5);
        }

        $validated['images'] = $currentImages;

        // Ensure each size has a valid integer value and compile as JSON
        $stockData = [];
        foreach (['39', '40', '41', '42', '43'] as $size) {
            $value = isset($validated['stock'][$size]) ? $validated['stock'][$size] : 0;
            $stockData[$size] = is_numeric($value) ? max(0, intval($value)) : 0;
        }
        $validated['stock'] = json_encode($stockData);

        // Set status based on total stock
        $totalStock = array_sum($stockData);
        $validated['status'] = $totalStock > 0 ? 'Tersedia' : 'Habis';

        $product->update($validated);

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.products.index' : 'admin.products.index';
        return redirect()->route($route)->with('success', 'Perubahan produk berhasil disimpan.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if (is_array($product->images)) {
            foreach ($product->images as $image) {
                $filePath = public_path('img/product/' . $image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        $product->delete();

        $route = auth()->user() && auth()->user()->role === 'petugas' ? 'staff.products.index' : 'admin.products.index';
        return redirect()->route($route)->with('success', 'Produk berhasil dihapus.');
    }
}
