@extends('layouts.admin')

@section('title', 'Kelola Pembeli')

@section('content')
<div class="admin-content">

    <h1 class="admin-title">Kelola Pembeli</h1>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pembeli as $p)
            <tr>
                <td>{{ $p->id_pembeli }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->email }}</td>
                <td>
                    <form method="POST"
                          action="{{ route('admin.pembeli.delete', $p->id_pembeli) }}"
                          onsubmit="return confirm('Hapus pembeli ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;">Tidak ada data pembeli.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
