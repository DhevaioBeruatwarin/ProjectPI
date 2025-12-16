@extends('layouts.admin')

@section('title', 'Monitoring Keuangan')

@section('content')

<h2 class="mb-4">Monitoring Keuangan</h2>

<div class="card p-3 mb-4">
    <h5>Total Pendapatan: <b>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</b></h5>
    <h5>Total Transaksi: <b>{{ $jumlahTransaksi }}</b></h5>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Transaksi</h4>

        {{-- FILTER STATUS --}}
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm w-auto">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>
                    Success
                </option>
            </select>
            <button class="btn btn-sm btn-primary">Filter</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Pembeli</th>
                <th>Karya</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($transaksi as $t)
            <tr>
                <td>{{ $t->no_transaksi }}</td>
                <td>{{ $t->nama_pembeli }}</td>
                <td>{{ $t->nama_karya }}</td>
                <td>Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                <td>{{ $t->jumlah }}</td>
                <td>Rp {{ number_format($t->harga * $t->jumlah, 0, ',', '.') }}</td>
                <td>
                    @if($t->status == 'success')
                        <span class="status status-success">Success</span>
                    @else
                        <span class="status status-pending">
                            {{ ucfirst($t->status) }}
                        </span>
                    @endif
                </td>
                <td>{{ $t->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- STYLE KHUSUS STATUS (AMAN, TIDAK GANGGU TABLE) --}}
<style>
.status {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-success {
    background-color: #e6f7ee;
    color: #0f9d58;
}

.status-pending {
    background-color: #fff4e5;
    color: #f57c00;
}
</style>

@endsection
