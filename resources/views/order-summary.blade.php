@extends('layouts.app')

@section('title', 'Ringkasan Pesanan')

@section('content')

@php
    $createdAt = $order->created_at ? \Carbon\Carbon::parse($order->created_at) : null;
    $deadline = $createdAt ? $createdAt->copy()->addDay() : null;
    $remainingSeconds = 0;

    if ($deadline && now()->lt($deadline)) {
        $remainingSeconds = $deadline->diffInSeconds(now());
    }

    $statusLabel = match($order->status) {
        'diproses' => 'Pesanan Sedang Diproses',
        'pending' => 'Pesanan Menunggu',
        'valid' => 'Pesanan Selesai',
        default => 'Status Pesanan',
    };

    $deadlineTimestamp = $deadline ? $deadline->timestamp * 1000 : null;
@endphp

<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <div class="rounded-[32px] bg-white border border-slate-200 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.2)] p-6 sm:p-8">
                <h1 class="text-2xl font-semibold text-slate-900">{{ $statusLabel }}</h1>
                @if($order->status === 'shipping')
                    <p class="mt-2 text-sm text-blue-700 font-semibold">Paket dikirim pada {{ optional($order->updated_at)->format('d F Y H:i') }}</p>
                    <p class="mt-2 text-sm text-blue-600">Pesanan Anda sedang dalam perjalanan oleh kurir Shoegaze.</p>
                @elseif($order->status === 'valid')
                    <p class="mt-2 text-sm text-green-700 font-semibold">Pesanan diterima pada {{ optional($order->updated_at)->format('d F Y H:i') }}</p>
                @else
                    <p class="mt-2 text-sm text-slate-500">Sisa waktu pembayaran: <span id="payment-timer" class="font-semibold text-red-600">--:--</span></p>
                @endif
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.65fr_1fr]">
                <div class="space-y-6">
                    <div class="rounded-[32px] bg-white border border-slate-200 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.2)] p-6 sm:p-7">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Ringkasan Pembayaran</p>
                        <div class="mt-6 rounded-[24px] bg-slate-50 p-5 flex flex-col gap-4">
                            <div>
                                <p class="text-sm text-slate-500">Total Harga</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">Metode Pembayaran</p>
                                <p class="mt-2 text-base font-semibold text-slate-900 uppercase">{{ strtoupper($order->payment_method ?? $order->method ?? 'QRIS') }}</p>
                            </div>
                        </div>
                        <div class="mt-5 rounded-[24px] bg-white border border-slate-200 p-5">
                            @php $paymentMethod = strtolower($order->payment_method ?? $order->method ?? 'qris'); @endphp
                            @if($paymentMethod === 'qris')
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-900">Instruksi Pembayaran QRIS</p>
                                    <button type="button" data-modal-toggle="qris-modal" class="inline-flex items-center justify-center rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition duration-200">
                                        Lihat Kode QRIS
                                    </button>
                                </div>
                                <p class="mt-3 text-sm text-slate-600">
                                    Silakan lakukan pembayaran menggunakan kode QRIS di atas. Setelah transfer, unggah bukti pembayaran di bawah untuk konfirmasi pesanan.
                                </p>
                                
                                @if(!empty($order->proof_image))
                                    <div class="mt-5 rounded-[24px] border-2 border-green-200 bg-green-50 p-4">
                                        <div class="flex items-start gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-green-900">Bukti Pembayaran Dikonfirmasi</p>
                                                <p class="mt-1 text-xs text-green-700">Pesanan Anda telah dikonfirmasi dan sedang diproses.</p>
                                                <img src="{{ asset($order->proof_image) }}" alt="Bukti Pembayaran" class="mt-3 w-full max-w-xs rounded-lg object-cover border border-green-200" />
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('orders.upload', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-5" id="proof-upload-form">
                                        @csrf
                                        <div id="drop-zone" class="rounded-[24px] border-2 border-dashed border-slate-300 bg-slate-50 p-8 flex flex-col items-center justify-center cursor-pointer hover:border-red-500 hover:bg-red-50 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <p class="text-base font-semibold text-slate-600 mb-1">Seret atau Klik untuk Unggah</p>
                                            <p class="text-xs text-slate-500 mb-4">Gambar JPEG, PNG, GIF (Maks. 2MB)</p>
                                            <input type="file" name="payment_proof" accept="image/*" required class="hidden" id="file-input">
                                            <button type="button" class="inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition duration-200">
                                                Pilih File
                                            </button>
                                        </div>
                                        <div id="preview-box" class="mt-4 hidden">
                                            <p class="text-xs text-slate-500 mb-2">Preview:</p>
                                            <img id="preview-image" src="" alt="Preview" class="max-w-xs rounded-lg border border-slate-200 object-cover" />
                                        </div>
                                        <div class="mt-4 flex gap-3">
                                            <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700 transition duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Kirim Bukti Pembayaran
                                            </button>
                                        </div>
                                        @error('payment_proof')
                                            <p class="mt-3 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">✕ {{ $message }}</p>
                                        @enderror
                                    </form>
                                @endif
                            @else
                                <p class="text-sm font-semibold text-slate-900">Pembayaran Tunai (COD)</p>
                                <p class="mt-2 text-sm text-slate-600">Bayar jumlah di atas saat paket diterima. Siapkan uang pas jika memungkinkan.</p>
                                <div class="mt-4 rounded-lg bg-blue-50 border border-blue-200 p-3">
                                    <p class="text-xs text-blue-700"><strong>💡 Catatan:</strong> Pastikan Anda berada di rumah saat kurir tiba untuk melakukan pembayaran.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @php
                        $recipientName = $order->recipient_name ?? $order->selected_address_name ?? optional($order->user)->name ?? 'Nama tidak tersedia';
                        $recipientPhone = $order->phone_number ?? $order->selected_address_phone ?? optional($order->user)->phone ?? 'Nomor tidak tersedia';
                        $recipientFullAddress = $order->full_address ?? $order->selected_address_jalan ?? optional($order->user)->address ?? 'Alamat tidak tersedia';
                    @endphp
                    <div class="rounded-[32px] bg-white border border-slate-200 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.2)] p-6 sm:p-7">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Alamat Penerima</p>
                        <div class="mt-6 rounded-[24px] bg-slate-50 p-5">
                            <div class="flex items-center gap-3 text-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 4.5 6 10 6 10s6-5.5 6-10a6 6 0 00-6-6zm0 8a2 2 0 110-4 2 2 0 010 4z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-900">{{ $recipientName }}</p>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">{{ $recipientPhone }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-900">{{ $recipientFullAddress }}</p>
                        </div>
                    </div>

                    <div class="rounded-[32px] bg-white border border-slate-200 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.2)] p-6 sm:p-7">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Produk yang Dipesan</p>
                        <div class="mt-5 space-y-4">
                            @foreach($order->items ?? [] as $item)
                                <div class="flex items-center gap-4 rounded-[24px] border border-slate-200 bg-slate-50 p-4">
                                    <div class="h-20 w-20 overflow-hidden rounded-3xl bg-slate-100">
                                        <img src="{{ optional($item->product)->images ? asset('img/product/' . optional($item->product)->images[0]) : asset('images/default-product.png') }}" alt="{{ optional($item->product)->name }}" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-900">{{ optional($item->product)->name ?? 'Produk tidak diketahui' }}</p>
                                        <div class="mt-2 flex items-center gap-3 text-sm text-slate-500">
                                            <span>Qty {{ $item->quantity }}</span>
                                            <span class="font-semibold text-slate-900">Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-[32px] bg-white border border-slate-200 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.2)] p-6 sm:p-7">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Rincian Pesanan</p>
                        <div class="mt-5 space-y-4 text-slate-700">
                            <div class="rounded-[24px] bg-slate-50 p-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Nomor Pesanan</span>
                                    <span class="font-semibold text-slate-900">#{{ $order->id ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Tanggal Pemesanan</span>
                                    <span class="font-semibold text-slate-900">{{ optional($order->created_at)->format('d F Y') ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Metode Pembayaran</span>
                                    <span class="font-semibold text-slate-900 uppercase">{{ strtoupper($order->payment_method ?? $order->method ?? 'QRIS') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-[32px] bg-white border border-slate-200 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.2)] p-6 sm:p-7">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Ringkasan Biaya</p>
                        <div class="mt-5 space-y-4 text-slate-700">
                            <div class="rounded-[24px] bg-slate-50 p-4 flex items-center justify-between text-sm">
                                <span>Subtotal</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format(optional($order->items)->sum(function ($item) { return ($item->price ?? 0) * ($item->quantity ?? 0); }) , 0, ',', '.') }}</span>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4 flex items-center justify-between text-sm">
                                <span>Pengiriman</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="rounded-[24px] bg-slate-900/5 p-4 flex items-center justify-between text-sm font-semibold text-slate-900">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                @if($order->status === 'pending')
                <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-[28px] border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Batalkan Pesanan</a>
                @endif
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-[28px] bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <!-- Modal QRIS -->
    <div id="qris-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-[32px] shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 sm:px-8 py-6 flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">Kode QRIS Pembayaran</h2>
                <button type="button" data-modal-close="qris-modal" class="text-white/70 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 sm:p-8">
                <div class="bg-slate-50 rounded-[24px] p-6 flex items-center justify-center mb-4">
                    <img src="{{ asset('images/payments/qris-toko.png.jpeg') }}" alt="Kode QRIS" class="w-full max-w-xs rounded-lg object-contain" />
                </div>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-blue-900 mb-2">📱 Cara Pembayaran:</p>
                        <ol class="text-xs text-blue-800 space-y-1 list-decimal list-inside">
                            <li>Buka aplikasi dompet digital atau bank Anda</li>
                            <li>Pilih menu "Scan QRIS" atau "Bayar QR Code"</li>
                            <li>Arahkan kamera ke kode QRIS di atas</li>
                            <li>Verifikasi jumlah <strong>Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</strong></li>
                            <li>Selesaikan pembayaran</li>
                            <li>Unggah bukti transfer di bawah</li>
                        </ol>
                    </div>
                </div>
                
                <button type="button" data-modal-close="qris-modal" class="mt-6 w-full inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700 transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Modal functionality
document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
    btn.addEventListener('click', function() {
        const modalId = this.getAttribute('data-modal-toggle');
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.toggle('hidden');
    });
});

document.querySelectorAll('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', function() {
        const modalId = this.getAttribute('data-modal-close');
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.add('hidden');
    });
});

// Close modal when clicking outside
document.querySelectorAll('[id$="-modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});

// Payment Timer - Real-time Countdown
function updatePaymentTimer() {
    const timerElement = document.getElementById('payment-timer');
    if (!timerElement) return;

    const deadlineTimestamp = @json($deadlineTimestamp);
    if (!deadlineTimestamp) {
        timerElement.textContent = 'Waktu habis';
        return;
    }

    const deadline = new Date(deadlineTimestamp);
    const now = new Date();
    const remainingMs = deadline - now;

    if (remainingMs <= 0) {
        timerElement.textContent = '⏰ Waktu pembayaran habis';
        timerElement.classList.add('text-red-700', 'animate-pulse');
    } else {
        const hours = Math.floor(remainingMs / (1000 * 60 * 60));
        const minutes = Math.floor((remainingMs % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remainingMs % (1000 * 60)) / 1000);
        
        const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        timerElement.textContent = formattedTime;
        
        // Remove expired styles if still valid
        timerElement.classList.remove('text-red-700', 'animate-pulse');
    }
}

// Update timer every second
updatePaymentTimer();
setInterval(updatePaymentTimer, 1000);

// File Upload with Drag & Drop
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');
const previewBox = document.getElementById('preview-box');
const previewImage = document.getElementById('preview-image');
const proofUploadForm = document.getElementById('proof-upload-form');

if (dropZone && fileInput) {
    // Click to select file
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-red-500', 'bg-red-50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-red-500', 'bg-red-50');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFileSelect();
    }, false);

    fileInput.addEventListener('change', handleFileSelect, false);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                previewBox.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
}
</script>

@endsection
