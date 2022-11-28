<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('home.user')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>{{$product->nama_produk}}</span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body row">
                    <div class="col-md-6">
                        <figure class="imagecheck-figure text-center">
                            <img src="{{asset('storage/'.$product->foto_produk)}}" style="height: 400px;"
                                alt="{{$product->nama_produk}}" class="imagecheck-image w-100">
                        </figure>
                    </div>
                    <div class="col-md-6 pt-3">
                        <h2>{{$product->nama_produk}}</h2>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Harga</span>
                                <span>Rp. {{number_format($product->harga_produk)}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Stok</span>
                                <span>{{$product->stok_produk}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Kategori</span>
                                <span>{{$product->category->nama_kategori}}</span>
                            </li>
                        </ul>
                        <div class="mt-4">
                            @if ($product->stok_produk > 0 && $product->status_produk == 1)
                            <button class="btn btn-primary btn-sm" wire:click="addToCart">Tambah Ke Keranjang</button>
                            @else
                            <button class="btn btn-primary btn-sm" disabled>Tambah Ke Keranjang</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h1><b>Deskripsi</b></h1>
                    <p>{!! $product->deskripsi_produk !!}</p>
                </div>
            </div>
        </div>
    </div>