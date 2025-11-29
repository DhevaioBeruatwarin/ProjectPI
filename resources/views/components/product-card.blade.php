@props(['item'])

<a href="{{ route('karya.detail', $item->kode_seni) }}" class="product-card {{ $item->stok <= 0 ? 'sold-out' : '' }}">
    <div class="card-media">
        @if($item->gambar)
            <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
        @else
            <div class="no-image">No Image</div>
        @endif
    </div>

    <div class="card-body">
        <div class="meta">
            <h3 class="title">{{ $item->nama_karya }}</h3>
            <span class="artist">{{ $item->seniman->nama ?? 'Unknown' }}</span>
        </div>

        <div class="price-row">
            <div class="price">Rp {{ number_format($item->harga,0,',','.') }}</div>
            <div class="cta">
                <button class="wish" title="Tambah Wishlist"><i data-feather="heart"></i></button>
            </div>
        </div>

        <div class="card-foot">
            <span class="stock">{{ $item->stok > 0 ? $item->stok . ' unit' : 'Habis' }}</span>
            @if(isset($item->terjual) && $item->terjual > 0)
                <span class="sold">{{ $item->terjual }} terjual</span>
            @endif
        </div>
    </div>
</a>
