@extends('layouts.admin')

@section('title', 'Kelola User - SHOEGAZE')

@section('content')
<div x-data="userManager()">
    <!-- Toast -->
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
             class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    <header class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800">Kelola User</h2>
        <button @click="showAddModal = true" class="bg-[#2d4a99] hover:bg-[#3d5bb0] text-white px-4 py-2 rounded">+ Tambah User</button>
    </header>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 flex flex-col md:flex-row md:items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
               class="w-full md:w-1/2 px-3 py-2 border rounded" />
        <select name="role" class="w-full md:w-1/4 px-3 py-2 border rounded">
            <option value="Semua" {{ request('role')=='Semua' ? 'selected' : '' }}>Semua</option>
            @foreach($roles as $label => $val)
                <option value="{{ $val }}" {{ request('role')==$val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-200 rounded">Filter</button>
    </form>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
        <table class="w-full text-left text-[13px]">
            <thead class="bg-[#dfe6f1] text-[#2d4a99] text-[11px] uppercase font-bold">
                <tr>
                    <th class="px-5 py-3 border-b">No</th>
                    <th class="px-5 py-3 border-b">Nama</th>
                    <th class="px-5 py-3 border-b">Email</th>
                    <th class="px-5 py-3 border-b">Role</th>
                    <th class="px-5 py-3 border-b">No HP</th>
                    <th class="px-5 py-3 border-b">Status</th>
                    <th class="px-5 py-3 border-b">Tanggal Daftar</th>
                    <th class="px-5 py-3 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">{{ $users->firstItem() + $index }}</td>
                    <td class="px-5 py-3">{{ $user->name }}</td>
                    <td class="px-5 py-3">{{ $user->email }}</td>
                    <td class="px-5 py-3">{{ $user->role }}</td>
                    <td class="px-5 py-3">{{ $user->phone ?? '-' }}</td>
                    <td class="px-5 py-3">{{ $user->status }}</td>
                    <td class="px-5 py-3">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 space-x-2">
                        <button type="button" @click="openDetail({{ json_encode($user) }})" class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-[12px]">Detail</button>
                        <button type="button" @click="openEdit({{ json_encode($user) }})" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-[12px]">Edit</button>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-[12px]">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-3 text-center text-gray-500">Tidak ada user ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->withQueryString()->links() }}

    <!-- modal form add/edit -->
    <div x-show="showAddModal || showEditModal" x-cloak class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div @click.away="cancel()" class="bg-white w-full max-w-lg p-6 rounded-lg relative">
            <h3 class="text-lg font-bold mb-4" x-text="showEditModal ? 'Edit User' : 'Tambah User'"></h3>
            <form :action="showEditModal ? '/admin/users/' + editUser.id : '{{ route('admin.users.store') }}'"
                  method="POST">
                @csrf
                <template x-if="showEditModal">
                    <input type="hidden" name="_method" value="PATCH">
                </template>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="name" x-model="form.name" class="w-full border px-3 py-2 rounded" required>
                    @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" x-model="form.email" class="w-full border px-3 py-2 rounded" required>
                    @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Role</label>
                    <select name="role" x-model="form.role" class="w-full border px-3 py-2 rounded" required>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="user">User</option>
                    </select>
                    @error('role')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">No HP</label>
                    <input type="text" name="phone" x-model="form.phone" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" x-model="form.status" class="w-full border px-3 py-2 rounded" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="mb-3" x-show="!showEditModal">
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3" x-show="!showEditModal">
                    <label class="block text-sm font-medium">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded" required>
                </div>
                <!-- reset password checkbox only visible when editing -->
                <div class="mb-3" x-show="showEditModal">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="reset_password" value="1" x-model="form.resetPassword" class="form-checkbox" />
                        <span class="ml-2 text-sm">Reset ke Password Default</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="cancel()" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#2d4a99] text-white rounded"
                            x-text="showEditModal ? 'Simpan Perubahan' : 'Simpan User'"></button>
                </div>
            </form>
        </div>
    </div>

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let data = @json(old());
                let isEdit = data._method === 'PATCH';
                let mgr = document.querySelector('[x-data]').__x.$data;
                if(isEdit) {
                    mgr.showEditModal = true;
                    // populate form fields
                    mgr.form.name = data.name || '';
                    mgr.form.email = data.email || '';
                    mgr.form.role = data.role || 'user';
                    mgr.form.phone = data.phone || '';
                    mgr.form.status = data.status || 'Aktif';
                } else {
                    mgr.showAddModal = true;
                    mgr.form.name = data.name || '';
                    mgr.form.email = data.email || '';
                    mgr.form.role = data.role || 'user';
                    mgr.form.phone = data.phone || '';
                    mgr.form.status = data.status || 'Aktif';
                }
            });
        </script>
    @endif

    <!-- detail modal -->
    <div x-show="showDetailModal" x-cloak class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div @click.away="closeDetail()" class="bg-white w-full max-w-md p-6 rounded-lg relative">
            <h3 class="text-lg font-bold mb-4">Detail User</h3>
            <p><strong>Nama:</strong> <span x-text="selected.name"></span></p>
            <p><strong>Email:</strong> <span x-text="selected.email"></span></p>
            <p><strong>Role:</strong> <span x-text="selected.role"></span></p>
            <p><strong>No HP:</strong> <span x-text="selected.phone || '-'"></span></p>
            <p><strong>Status:</strong> <span x-text="selected.status"></span></p>
            <p><strong>Tanggal Daftar:</strong> <span x-text="formatDate(selected.created_at)"></span></p>
            <div class="mt-4 flex justify-end">
                <button @click="closeDetail()" class="px-4 py-2 bg-gray-200 rounded">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function userManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        showDetailModal: false,
        editUser: {},
        selected: {},
        form: { name:'', email:'', role:'user', phone:'', status:'Aktif', resetPassword:false },
        openEdit(user) {
            this.editUser = user;
            this.form = { name:user.name, email:user.email, role:user.role, phone:user.phone, status:user.status, resetPassword:false };
            this.showEditModal = true;
        },
        openDetail(user) {
            this.selected = user;
            this.showDetailModal = true;
        },
        cancel() {
            this.showAddModal = this.showEditModal = false;
            this.form = { name:'', email:'', role:'user', phone:'', status:'Aktif', resetPassword:false };
        },
        closeDetail() {
            this.showDetailModal = false;
            this.selected = {};
        },
        formatDate(dt) {
            return new Date(dt).toLocaleDateString('id-ID');
        }
    }
}
</script>
@endpush