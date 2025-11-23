@extends('layouts.admin')

@section('title', 'Kelola Karya Seni')

@section('content')
<div class="admin-content">

    <h1 class="admin-title">Kelola Karya Seni</h1>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Kode Seni</th>
                <th>Nama Karya</th>
                <th>Seniman</th>
                <th>ID Seniman</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($karya as $k)
            <tr>
                <td>{{ $k->kode_seni }}</td>
                <td>{{ $k->nama_karya }}</td>
                <td>{{ $k->seniman->nama ?? '-' }}</td>
                <td>{{ $k->id_seniman }}</td>
                <td>
                    @if($k->gambar)
                        <img src="{{ asset('storage/karya_seni/' . $k->gambar) }}" class="karya-img">
                    @else
                        -
                    @endif
                </td>

                <td>
                    <form method="POST" 
                          action="{{ route('admin.karya.delete', $k->kode_seni) }}"
                          onsubmit="return confirm('Hapus karya ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>

            @empty
            <tr><td colspan="6" style="text-align:center;">Tidak ada karya.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
