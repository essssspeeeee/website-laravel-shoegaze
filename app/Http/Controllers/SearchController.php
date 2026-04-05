<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product; // Pastikan kamu punya model Product

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Handle both 'query' and 'q' parameters
        $query = $request->input('query') ?? $request->input('q');
        
        // Logika mencari produk berdasarkan nama atau deskripsi dengan LIKE
        $products = Product::where('name', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%")
                            ->get();

        return view('dashboard.search_results', compact('products', 'query'));
    }
}