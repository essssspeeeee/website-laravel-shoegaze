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

            <!-- Ringkasan Pesanan -->
            <div class="space-y-6">
                <div>
                    <span class="block text-slate-400 text-xs mb-1">Nama Sepatu</span>
                    <span class="text-lg font-bold text-white">{{ $transaction->items->first()->product->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs mb-1">Total Harga</span>
                    <span class="text-lg font-bold text-white">Rp {{ number_format($transaction->total_price) }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs mb-1">Status Pembayaran</span>
                    <span class="text-lg font-bold text-white">{{ ucfirst($transaction->payment_status ?? '-') }}</span>
                </div>
            </div>
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
