<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product; // Pastikan kamu punya model Product

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        // Logika mencari produk berdasarkan nama atau deskripsi
        $products = Product::where('name', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%")
                            ->get();

        return view('dashboard.search_results', compact('products', 'query'));
    }
}