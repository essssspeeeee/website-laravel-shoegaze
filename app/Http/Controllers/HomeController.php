<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        // Get banner images from public/img/banners/
        $bannerPath = public_path('img/banners');
        $banners = [];
        if (File::exists($bannerPath)) {
            $files = File::files($bannerPath);
            foreach ($files as $file) {
                if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $banners[] = $file->getFilename();
                }
            }
        }
        
        return view('dashboard.user', compact('products', 'banners'));
    }
}