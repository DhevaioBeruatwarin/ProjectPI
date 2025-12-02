@extends('layouts.admin')

@section('title', 'Monitoring Keuangan')

@section('content')

<h2 class="mb-4">Monitoring Keuangan</h2>

<div class="card p-3 mb-4">
    <h5>Total Pendapatan: <b>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</b></h5>
    <h5>Total Transaksi: <b>{{ $jumlahTransaksi }}</b></h5>
</div>

<div class="card p-3">
    <h4 class="mb-3">Daftar Transaksi</h4>

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
                        <span class="badge bg-success">Success</span>
                    @else
                        <span class="badge bg-danger">{{ $t->status }}</span>
                    @endif
                </td>
                <td>{{ $t->created_at }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection
