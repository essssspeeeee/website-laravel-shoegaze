@extends('layouts.app')

@section('title', 'Shoegaze - Dashboard User')

@section('content')
    <main class="max-w-7xl mx-auto px-6 md:px-12 py-8">
        
        <div class="mb-14 relative rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="relative w-full h-64 md:h-[500px] bg-gray-200">
                <img src="{{ asset('img/banners/hero-banner.jpg') }}" alt="Banner Shoegaze" class="absolute inset-0 w-full h-full object-cover object-center z-0">
            </div>
        </div>

        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-4 h-9 bg-[#db4444] rounded-sm"></div>
                <span class="text-[#db4444] font-bold text-sm">Produk Kami</span>
            </div>
            <h2 class="text-3xl font-bold tracking-tight">Jelajahi Produk Kami</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-24">
            @foreach ($products as $product)
            <div class="group bg-white rounded-xl p-4 shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-xl transition-all duration-300 border border-gray-50 flex flex-col h-full">
                <a href="{{ route('product.detail', $product->id) }}" class="block flex-1">
                    <div class="aspect-square bg-[#f5f6f8] rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                        @php
                            $firstImage = is_array($product->images) && count($product->images) ? $product->images[0] : null;
                        @endphp
                        <img src="{{ $firstImage ? asset('img/product/' . $firstImage) : asset('images/default-product.png') }}"
                             alt="{{ $product->name }}" class="w-4/5 transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="font-bold text-[11px] uppercase mb-1 h-8 line-clamp-2">{{ $product->name }}</h3>
                </a>
                
                <div class="mt-4">
                    <span class="text-[#00a651] font-bold text-sm italic">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-4 h-9 bg-[#db4444] rounded-sm"></div>
                <span class="text-[#db4444] font-bold text-sm">Unggulan</span>
            </div>
            <h2 class="text-3xl font-bold mb-8 tracking-tight">Produk Baru</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-auto md:h-[600px] mb-10">
            <div class="relative rounded-2xl overflow-hidden group bg-gray-100">
                <img src="{{ asset('images/HAZEFLOW.jpg.webp') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute top-10 left-10 bg-white p-6 rounded-lg shadow-xl max-w-[240px]">
                    <h4 class="font-black text-2xl italic uppercase mb-1 leading-none tracking-tighter">KANZAKI 2.0</h4>
                    <p class="text-xs text-gray-500 font-medium">Run Strong. Run Confident.</p>
                </div>
            </div>
            
            <div class="grid grid-rows-2 gap-6">
                <div class="relative rounded-2xl overflow-hidden group bg-gray-100">
                    <img src="{{ asset('images/HAZEFLOW.jpg.webp') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute top-6 right-6 bg-white p-5 rounded-lg shadow-lg max-w-[200px]">
                        <h4 class="font-black text-sm uppercase mb-1">AURORUM CITY</h4>
                        <p class="text-[10px] text-gray-500 leading-tight">Light. Responsive. Built to Run Everyday.</p>
                    </div>
                </div>
                <div class="relative rounded-2xl overflow-hidden group bg-gray-100">
                    <img src="{{ asset('images/HAZEFLOW.jpg.webp') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute bottom-6 right-6 bg-white p-5 rounded-lg shadow-lg max-w-[220px]">
                        <h4 class="font-black text-sm italic uppercase mb-1">Haze Flow</h4>
                        <p class="text-[10px] text-gray-500 leading-relaxed">
                            <span class="font-semibold text-gray-800">Categories:</span> Road Running<br>
                            <span class="font-semibold text-gray-800">Best for:</span> Raceday (10K-42K)
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <div x-data="{ show: false, message: '' }" 
         @add-to-cart.window="message = $event.detail.product; show = true; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-5 right-5 z-[100] bg-white border-l-4 border-green-500 shadow-2xl rounded-lg p-4 flex items-center gap-4">
        
        <div class="bg-green-100 p-2 rounded-full text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-800">Berhasil ditambahkan!</p>
            <p class="text-xs text-gray-500" x-text="message"></p>
        </div>
    </div>

@endsection