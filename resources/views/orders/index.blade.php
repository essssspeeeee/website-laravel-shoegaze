<div class="container mt-5">
    <h2>Riwayat Pesanan</h2>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ ucfirst($order->payment_method) }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 'success' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        @if($order->payment_method === 'qris' && $order->status === 'pending')
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#qrisModal{{ $order->id }}">
                                Upload Bukti Pembayaran
                            </button>
                        @elseif($order->payment_method === 'cod')
                            <span class="badge bg-info">Bayar di Tempat</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada pesanan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal untuk QRIS --}}
    @foreach($orders as $order)
        @if($order->payment_method === 'qris' && $order->status === 'pending')
        <div class="modal fade" id="qrisModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Bukti Pembayaran QRIS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Silakan scan QRIS ini dan upload bukti transfer di bawah ini:</strong></p>
                        @if($order->qris_image)
                            <img src="{{ asset($order->qris_image) }}" alt="QRIS" class="img-fluid mb-3">
                        @else
                            <div class="alert alert-warning">Gambar QRIS tidak tersedia</div>
                        @endif

                        <form action="{{ route('orders.upload-payment', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Upload Foto Struk/Bukti Transfer</label>
                                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                                <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                            </div>
                            <button type="submit" class="btn btn-success">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @elseif($order->payment_method === 'cod')
        <div class="modal fade" id="codModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informasi Pembayaran COD</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Harap siapkan uang tunai saat kurir datang</strong></p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>