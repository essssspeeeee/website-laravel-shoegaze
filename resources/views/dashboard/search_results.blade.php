<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-white text-gray-900">
    
    <nav class="bg-white border-b border-gray-100 py-4 px-6 md:px-16 flex items-center justify-between">
    <div class="flex-shrink-0">
        <h1 class="text-2xl font-black tracking-tighter uppercase italic text-gray-900 select-none">
            SHOEGAZE
        </h1>
    </div>

    <div class="hidden md:flex flex-1 max-w-md mx-10">
        <form action="{{ route('search') }}" method="GET" class="relative w-full">
            <input type="text" name="query" value="{{ $query ?? '' }}" placeholder="Mau cari apa di SHOEGAZE?" 
                class="w-full bg-[#f6f6f6] border border-transparent rounded-md py-2 px-4 pr-10 text-xs focus:bg-white transition-all outline-none">
            <button type="submit" class="absolute right-3 top-2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="flex items-center space-x-5">
        <button class="text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </button>
        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
    </div>
</nav>

    <main class="max-w-7xl mx-auto px-6 md:px-16 py-10">
        <div class="mb-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-[#db4444] transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Halaman Sebelumnya
            </a>
        </div>

        <div class="mb-10">
            <h2 class="text-3xl font-bold tracking-tight italic uppercase">Hasil Pencarian: "{{ $query }}"</h2>
            <p class="text-sm text-gray-400 mt-2 font-medium">Ditemukan {{ $products->count() }} pasang sepatu yang cocok untukmu.</p>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-24 bg-[#fbfbfb] rounded-2xl border-2 border-dashed border-gray-100">
                <div class="mb-4 flex justify-center text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <p class="text-gray-500 font-semibold italic">"{{ $query }}" tidak ditemukan di rak kami.</p>
                <a href="/home" class="text-[#db4444] text-xs font-black mt-4 inline-block uppercase tracking-widest border-b-2 border-[#db4444] pb-1">Cek Koleksi Lain</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($products as $product)
                <a href="{{ route('product.detail', $product->id) }}" class="group">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 transition-all hover:shadow-xl hover:-translate-y-1 h-full flex flex-col">
                        <div class="bg-[#f6f6f6] rounded-xl p-8 mb-5 flex items-center justify-center relative overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}" 
                                 class="w-full h-36 object-contain group-hover:scale-110 transition-transform duration-500">
                        </div>
                        
                        <div class="px-2">
                            <h3 class="text-[11px] font-black uppercase tracking-tighter mb-2 leading-tight text-gray-800">
                                {{ $product->name }}
                            </h3>
                            <div class="flex justify-between items-center mt-4">
                                <p class="text-[#22c55e] text-xs font-extrabold uppercase tracking-tighter">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <div class="w-7 h-7 bg-gray-900 rounded-full flex items-center justify-center text-white transform group-hover:bg-[#db4444] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="bg-[#2b2d2f] text-gray-500 py-10 text-center mt-24 border-t border-gray-800">
        <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">
            &copy; FAUZAAN ESPE 2026. Seluruh hak dilindungi undang-undang.
        </p>
    </footer>

</body>
</html>