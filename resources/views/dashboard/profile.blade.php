<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SHOEGAZE</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f9fafb;
        }
        /* Mencegah scroll saat modal terbuka */
        .modal-active {
            overflow: hidden;
        }
    </style>
</head>
<body class="antialiased text-gray-900">

    <nav class="bg-white border-b border-gray-100 py-4 px-6 md:px-16 flex items-center justify-between">
        <div class="flex-shrink-0">
            <h1 class="text-2xl font-black tracking-tighter uppercase italic" style="letter-spacing: -0.05em;">SHOEGAZE</h1>
        </div>

        <div class="hidden md:flex flex-1 max-w-md mx-10">
            <div class="relative w-full">
                <input type="text" placeholder="Mau cari apa di SHOEGAZE?" 
                    class="w-full bg-[#f6f6f6] border border-transparent rounded-md py-2 px-4 pr-10 text-xs focus:bg-white transition-all outline-none">
                <div class="absolute right-3 top-2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-5">
            <button class="text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
            <div class="w-8 h-8 bg-[#db4444] rounded-full flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
    </nav>

    <main class="min-h-[80vh] flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] p-10">
            <h2 class="text-2xl font-bold text-center mb-10">Profil Saya</h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold mb-2">Nama</label>
                    <input type="text" value="{{ auth()->user()->name }}" readonly
                        class="w-full bg-[#f9f9f9] border border-gray-100 rounded-md py-3 px-4 text-sm text-gray-700 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" readonly
                        class="w-full bg-[#f9f9f9] border border-gray-100 rounded-md py-3 px-4 text-sm text-gray-700 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Telepon</label>
                    <input type="text" value="{{ auth()->user()->phone ?? '+62 851-9163-7802' }}" readonly
                        class="w-full bg-[#f9f9f9] border border-gray-100 rounded-md py-3 px-4 text-sm text-gray-700 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Alamat</label>
                    <textarea readonly rows="2" 
                        class="w-full bg-[#f9f9f9] border border-gray-100 rounded-md py-3 px-4 text-sm text-gray-700 resize-none outline-none">{{ auth()->user()->address ?? 'Jl. Matraman Raya No. 123, Jakarta' }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 relative z-10">
                    @if(auth()->user()->role == 'admin')
                        <a href="/admin/dashboard" class="block text-center bg-[#db4444] hover:bg-red-600 text-white font-semibold py-3 rounded-md transition-all text-sm">
                            Kembali
                        </a>
                    @elseif(auth()->user()->role == 'petugas')
                        <a href="/staff/dashboard" class="block text-center bg-[#db4444] hover:bg-red-600 text-white font-semibold py-3 rounded-md transition-all text-sm">
                            Kembali
                        </a>
                    @else
                        <a href="/home" class="block text-center bg-[#db4444] hover:bg-red-600 text-white font-semibold py-3 rounded-md transition-all text-sm">
                            Kembali
                        </a>
                    @endif

                    <button type="button" onclick="toggleModal('modal-edit-profil')" 
                        class="block text-center bg-[#db4444] hover:bg-red-600 text-white font-semibold py-3 rounded-md transition-all text-sm">
                        Edit Profil
                    </button>
                </div>
            </div>
        </div>
    </main>

    <div id="modal-edit-profil" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-edit-profil')"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all overflow-hidden">
                <div class="pt-8 pb-4 text-center">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Profil Kamu</h2>
                </div>
                
                <form action="{{ route('profile.update') }}" method="POST" class="px-8 pb-8">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-4 text-left">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                   class="w-full border border-gray-200 bg-[#f9f9f9] px-4 py-3 rounded-md text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                   class="w-full border border-gray-200 bg-[#f9f9f9] px-4 py-3 rounded-md text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Telepon</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone ?? '+62 851-9163-7802' }}" 
                                   class="w-full border border-gray-200 bg-[#f9f9f9] px-4 py-3 rounded-md text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                            <textarea name="address" rows="2" 
                                      class="w-full border border-gray-200 bg-[#f9f9f9] px-4 py-3 rounded-md text-sm focus:ring-1 focus:ring-[#db4444] outline-none resize-none">{{ auth()->user()->address ?? 'Jl. Matraman Raya No. 123, Jakarta' }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                                <input type="password" name="current_password" placeholder="********" 
                                       class="w-full border border-gray-200 bg-[#f3f4f6] px-4 py-3 rounded-md text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Ubah Password</label>
                                <input type="password" name="new_password" placeholder="********" 
                                       class="w-full border border-gray-200 bg-[#f9f9f9] px-4 py-3 rounded-md text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <button type="button" onclick="toggleModal('modal-edit-profil')"
                                class="flex-1 bg-[#db4444] hover:bg-red-600 text-white font-bold py-3 rounded-md shadow-md transition-all active:scale-95">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-[#db4444] hover:bg-red-600 text-white font-bold py-3 rounded-md shadow-md transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-[#2b2d2f] text-gray-400 py-6 text-center mt-10">
        <p class="text-[11px] font-medium opacity-80">
            &copy; FAUZAAN ESPE 2026. Seluruh hak dilindungi undang-undang.
        </p>
    </footer>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal) {
                modal.classList.toggle('hidden');
                document.body.classList.toggle('modal-active');
            }
        }
    </script>
</body>
</html>