@extends('layouts.app')

@section('title', 'Ringkasan Pesanan')

@section('content')

@php
    $createdAt = $order->created_at ? \Carbon\Carbon::parse($order->created_at) : null;
    $savedHours = $createdAt ? $createdAt->diffInHours(now()) : 0;
    $statusLabel = match($order->status) {
        'diproses' => 'Pesanan Sedang Diproses',
        'pending' => 'Pesanan Menunggu',
        'valid' => 'Pesanan Selesai',
        default => 'Status Pesanan',
    };
@endphp

<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <div class="rounded-[32px] bg-white border border-slate-200 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.2)] p-6 sm:p-8">
                <h1 class="text-2xl font-semibold text-slate-900">{{ $statusLabel }}</h1>
                <p class="mt-2 text-sm text-slate-500">Disimpan selama {{ $savedHours }} jam</p>
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
                                <p class="text-sm font-semibold text-slate-900">Instruksi Pembayaran QRIS</p>
                                <p class="mt-2 text-sm text-slate-600">
                                    Silakan lakukan pembayaran menggunakan QRIS. Setelah transfer, unggah bukti pembayaran di halaman pesanan Anda.
                                </p>
                                @if(!empty($order->proof_image))
                                    <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-sm text-slate-500">Bukti pembayaran yang telah diunggah:</p>
                                        <img src="{{ asset($order->proof_image) }}" alt="Bukti Pembayaran" class="mt-3 w-full max-w-xs rounded-3xl object-cover" />
                                    </div>
                                @else
                                    <div class="mt-4 rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">
                                        Bukti transfer belum diunggah.
                                    </div>
                                @endif
                            @else
                                <p class="text-sm font-semibold text-slate-900">Cash On Delivery</p>
                                <p class="mt-2 text-sm text-slate-600">Bayar tunai saat paket diterima. Siapkan jumlah yang sesuai.</p>
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
                                        <img src="{{ optional($item->product)->images ? asset('storage/' . optional($item->product)->images[0]) : asset('images/default-product.png') }}" alt="{{ optional($item->product)->name }}" class="h-full w-full object-cover" />
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
                <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-[28px] border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Batalkan Pesanan</a>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-[28px] bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>

@endsection
