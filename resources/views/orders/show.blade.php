<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - SHOEGAZE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: #0f172a; color: #e2e8f0; font-family: Inter, sans-serif; }
        .container { max-width: 720px; margin: 0 auto; padding: 2rem; }
        .card { background: rgba(15, 23, 42, 0.88); border: 1px solid rgba(148, 163, 184, 0.15); box-shadow: 0 20px 50px rgba(15, 23, 42, 0.35); }
        .btn-primary { background: #ef4444; }
        .btn-secondary { background: #1f2937; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="container">
        <div class="card rounded-3xl p-8">
            <h1 class="text-2xl font-black text-white mb-6">Detail Pesanan</h1>
            <p class="text-sm text-slate-400 mb-6">Silakan pilih aksi berikut untuk melanjutkan proses pembelian.</p>

            <form action="{{ route('cart.add', $shoe->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="size" value="{{ $selectedSize }}">
                <input type="hidden" name="quantity" value="{{ $selectedQuantity }}">

                <div class="grid gap-4 md:grid-cols-2">
                    <button type="submit" name="action" value="add_to_cart" class="btn-secondary text-white px-5 py-4 rounded-3xl font-semibold hover:bg-slate-700 transition">Tambah ke Keranjang</button>
                    <button type="submit" name="action" value="buy_now" class="btn-primary text-white px-5 py-4 rounded-3xl font-semibold hover:bg-red-500 transition">Beli Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                background: '#0f172a',
                color: '#f8fafc',
                showConfirmButton: false,
                timer: 2400,
                toast: true,
                position: 'top-end',
                customClass: {
                    popup: 'rounded-3xl border border-white/10 shadow-xl'
                }
            });
        });
    </script>
    @endif
</body>
</html>
