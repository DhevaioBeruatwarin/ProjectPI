@extends('layouts.admin')

@section('title', 'Kelola Seniman')

@section('content')
<div class="admin-content">

    <h1 class="admin-title">Kelola Seniman</h1>

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
            @forelse($seniman as $s)
            <tr>
                <td>{{ $s->id_seniman }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->email }}</td>
                <td>
                    <form method="POST"
                          action="{{ route('admin.seniman.delete', $s->id_seniman) }}"
                          onsubmit="return confirm('Hapus seniman ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;">Tidak ada data seniman.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
