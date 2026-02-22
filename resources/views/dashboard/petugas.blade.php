<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petugas Dashboard - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        /* Custom scrollbar agar lebih ramping */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
    </style>
</head>
<body class="antialiased text-gray-800">

    <div class="flex min-h-screen">
        <aside class="w-60 bg-[#2d4a99] text-white flex flex-col fixed h-full shadow-xl">
            <div class="p-5">
                <h1 class="text-lg font-bold tracking-widest uppercase">SHOEGAZE</h1>
            </div>

            <nav class="flex-1 px-3 space-y-0.5 text-[13px]">
                <a href="#" class="flex items-center py-2.5 px-4 rounded bg-[#3d5bb0] font-medium transition-all">
                    Dashboard
                </a>
                <a href="#" class="flex items-center py-2.5 px-4 rounded hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white">
                    Kelola Produk
                </a>
                <a href="#" class="flex items-center py-2.5 px-4 rounded hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white">
                    Kelola Pesanan
                </a>
                <a href="#" class="flex items-center py-2.5 px-4 rounded hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white">
                    Riwayat Pesanan
                </a>
                <a href="#" class="flex items-center py-2.5 px-4 rounded hover:bg-[#3d5bb0]/50 transition-all text-white/80 hover:text-white">
                    Laporan Penjualan
                </a>
            </nav>

            <div class="p-4">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-[#ef4444] hover:bg-red-600 text-white py-2 px-4 rounded-md text-[12px] font-bold transition-all shadow-lg uppercase tracking-wider">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 ml-60 p-6">
            
            <header class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                <div class="flex items-center gap-2">
                    <span class="text-[12px] font-medium text-gray-500">Petugas</span>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                    <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total Produk</p>
                    <h3 class="text-2xl font-black text-gray-800">01</h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                    <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total Pesanan</p>
                    <h3 class="text-2xl font-black text-gray-800">03</h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                    <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total User</p>
                    <h3 class="text-2xl font-black text-gray-800">02</h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                    <p class="text-[#2d4a99] font-bold text-[11px] mb-1 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-xl font-black text-gray-800">Rp 25.000.000</h3>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-50">
                    <h3 class="text-md font-bold text-gray-800">Pesanan Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13px]">
                        <thead class="bg-[#dfe6f1] text-[#2d4a99] text-[11px] uppercase font-bold">
                            <tr>
                                <th class="px-5 py-3 border-b">ID Pesanan</th>
                                <th class="px-5 py-3 border-b">Nama Customer</th>
                                <th class="px-5 py-3 border-b">Tanggal</th>
                                <th class="px-5 py-3 border-b">Status</th>
                                <th class="px-5 py-3 border-b">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 font-semibold text-gray-500">#001</td>
                                <td class="px-5 py-3">Budi</td>
                                <td class="px-5 py-3">01/02/2026</td>
                                <td class="px-5 py-3 italic text-gray-600">Diproses</td>
                                <td class="px-5 py-3 font-medium">Rp 500.000</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 font-semibold text-gray-500">#002</td>
                                <td class="px-5 py-3">Siti</td>
                                <td class="px-5 py-3">01/02/2026</td>
                                <td class="px-5 py-3 italic text-gray-600">Selesai</td>
                                <td class="px-5 py-3 font-medium">Rp 300.000</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 font-semibold text-gray-500">#003</td>
                                <td class="px-5 py-3">Andi</td>
                                <td class="px-5 py-3">31/01/2026</td>
                                <td class="px-5 py-3 italic text-gray-600">Dikirim</td>
                                <td class="px-5 py-3 font-medium">Rp 750.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>