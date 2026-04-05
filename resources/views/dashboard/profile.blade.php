@extends('layouts.app')

@section('title', 'Profil Saya - SHOEGAZE')

@section('content')
<main class="min-h-[80vh] flex items-center justify-center pt-10 mb-16 px-4 bg-slate-50">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-lg p-10">
        <h2 class="text-2xl font-bold text-center mb-10">Profil Saya</h2>
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Nama</label>
                <input type="text" value="{{ auth()->user()->name }}" readonly
                    class="w-full bg-slate-100 border border-slate-200 rounded-full py-3 px-6 text-sm text-gray-700 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Email</label>
                <input type="email" value="{{ auth()->user()->email }}" readonly
                    class="w-full bg-slate-100 border border-slate-200 rounded-full py-3 px-6 text-sm text-gray-700 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Telepon</label>
                <input type="text" value="{{ auth()->user()->phone ?? '+62 851-9163-7802' }}" readonly
                    class="w-full bg-slate-100 border border-slate-200 rounded-full py-3 px-6 text-sm text-gray-700 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Alamat</label>
                <textarea readonly rows="2"
                    class="w-full bg-slate-100 border border-slate-200 rounded-full py-3 px-6 text-sm text-gray-700 resize-none outline-none">{{ auth()->user()->address ?? 'Jl. Matraman Raya No. 123, Jakarta' }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 relative z-10">
                @if(auth()->user()->role == 'admin')
                    <a href="/admin/dashboard" class="block text-center bg-slate-100 text-slate-700 rounded-full py-3 px-8 font-medium hover:bg-slate-200 transition-all text-sm">
                        Kembali
                    </a>
                @elseif(auth()->user()->role == 'petugas')
                    <a href="/staff/dashboard" class="block text-center bg-slate-100 text-slate-700 rounded-full py-3 px-8 font-medium hover:bg-slate-200 transition-all text-sm">
                        Kembali
                    </a>
                @else
                    <a href="/home" class="block text-center bg-slate-100 text-slate-700 rounded-full py-3 px-8 font-medium hover:bg-slate-200 transition-all text-sm">
                        Kembali
                    </a>
                @endif
                <button type="button" onclick="toggleModal('modal-edit-profil')"
                    class="block text-center bg-red-500 text-white rounded-full py-3 px-8 font-semibold shadow-md hover:bg-red-600 transition-all text-sm">
                    Edit Profil
                </button>
            </div>
        </div>
    </div>
</main>

<div id="modal-edit-profil" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-edit-profil')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-10 transform transition-all overflow-hidden">
            <div class="pb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800">Edit Profil Kamu</h2>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-5 text-left">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}"
                               class="w-full border border-slate-200 bg-slate-100 px-6 py-3 rounded-full text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-1">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}"
                               class="w-full border border-slate-200 bg-slate-100 px-6 py-3 rounded-full text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-1">Telepon</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone ?? '+62 851-9163-7802' }}"
                               class="w-full border border-slate-200 bg-slate-100 px-6 py-3 rounded-full text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-1">Alamat</label>
                        <textarea name="address" rows="2"
                                  class="w-full border border-slate-200 bg-slate-100 px-6 py-3 rounded-full text-sm focus:ring-1 focus:ring-[#db4444] outline-none resize-none">{{ auth()->user()->address ?? 'Jl. Matraman Raya No. 123, Jakarta' }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-1">Password</label>
                            <input type="password" name="current_password" placeholder="********"
                                   class="w-full border border-slate-200 bg-slate-100 px-6 py-3 rounded-full text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-1">Ubah Password</label>
                            <input type="password" name="new_password" placeholder="********"
                                   class="w-full border border-slate-200 bg-slate-100 px-6 py-3 rounded-full text-sm focus:ring-1 focus:ring-[#db4444] outline-none">
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    <button type="button" onclick="toggleModal('modal-edit-profil')"
                            class="flex-1 bg-slate-100 text-slate-700 rounded-full py-3 px-8 font-medium hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 bg-red-500 text-white rounded-full py-3 px-8 font-semibold shadow-md hover:bg-red-600 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            modal.classList.toggle('hidden');
            document.body.classList.toggle('modal-active');
        }
    }
</script>
@endpush
@endsection
