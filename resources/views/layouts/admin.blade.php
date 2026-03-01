<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - SHOEGAZE')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
        }
        /* Custom scrollbar ramping agar estetik */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased text-gray-800">

    <div class="flex min-h-screen">
        <aside class="w-60 bg-[#2d4a99] text-white flex flex-col fixed h-full shadow-xl">
            <div class="p-5">
                <h1 class="text-lg font-bold tracking-widest uppercase">SHOEGAZE</h1>
            </div>

            <nav class="flex-1 px-3 space-y-0.5 text-[13px]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-[#3d5bb0] font-medium' : 'hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center py-2.5 px-4 rounded {{ request()->routeIs('admin.products.*') ? 'bg-[#3d5bb0] font-medium' : 'hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white' }}">
                    Kelola Produk
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center py-2.5 px-4 rounded {{ request()->routeIs('admin.orders.*') ? 'bg-[#3d5bb0] font-medium' : 'hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white' }}">
                    Kelola Pesanan
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center py-2.5 px-4 rounded {{ request()->routeIs('admin.users.*') ? 'bg-[#3d5bb0] font-medium' : 'hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white' }}">
                    Kelola User
                </a>
                <a href="{{ route('admin.history') }}" class="flex items-center py-2.5 px-4 rounded {{ request()->routeIs('admin.history') ? 'bg-[#3d5bb0] font-medium' : 'hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white' }}">
                    Riwayat Pesanan
                </a>
            </nav>

            <div class="p-4">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-[#ef4444] hover:bg-red-600 text-white py-2 px-4 rounded-md text-[12px] font-bold transition-all shadow-lg uppercase tracking-wider text-center">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 ml-60 p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
