@extends('layouts.app')

@section('title', 'Hasil Pencarian - SHOEGAZE')

@section('content')
<main class="max-w-7xl mx-auto px-6 md:px-16 py-12">
    <h2 class="text-2xl font-bold text-slate-900 mb-8">Hasil pencarian untuk: <span class="text-red-500">"{{ $query }}"</span></h2>

    @if($products->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-slate-200 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <p class="text-slate-400 font-semibold text-lg mb-4">Duh, sepatunya belum mendarat di gudang kami.</p>
            <a href="/home" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-2 rounded-full shadow transition">Kembali Belanja</a>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition flex flex-col overflow-hidden">
                <a href="{{ route('product.detail', $product->id) }}" class="block">
                    <div class="bg-slate-100 flex items-center justify-center h-48 md:h-56 overflow-hidden">
                        <img src="{{ is_array($product->images) && count($product->images) > 0 ? asset('img/product/' . $product->images[0]) : 'https://via.placeholder.com/300x300' }}" alt="{{ $product->name }}" class="object-contain w-full h-full hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-base text-slate-900 mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-slate-400 text-xs mb-2">{{ $product->category ?? 'Sepatu' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-red-500 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
