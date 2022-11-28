<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">

                    @foreach ($banners as $key => $banner)
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}"
                        class="@if ($key == 0) active @endif"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($banners as $key => $banner)
                    <div class="carousel-item @if ($key == 0) active @endif">
                        <img class="d-block w-100" src="{{asset('storage/'.$banner->banner_image)}}"
                            style="height: 500px;object-fit:cover;" alt="{{$banner->banner_title}}">
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>


        @foreach ($categories as $category)
        @if ($category->products->count() > 0)
        <div class="col-md-12 mt-4">
            <h1>{{$category->nama_kategori}}</h1>
            <div class="row">
                @foreach ($category->products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <a href="{{route('product-detail', ['product_id' => $product->id])}}"
                        style="text-decoration: none;;color:#000;">
                        <div class="card p-0">
                            <div class="card-body p-0  text-center">
                                @if ($product->status_produk > 0)
                                <label class="imagecheck mb-1">
                                    <figure class="imagecheck-figure text-center">
                                        <img src="{{asset('storage/'.$product->foto_produk)}}" style="height: 200px;"
                                            alt="title" class="imagecheck-image w-100">
                                    </figure>
                                    <span class="badge badge-success absolute">Stok {{$product->stok_produk}}</span>
                                </label>
                                @else
                                <label class="imagecheck mb-1 cursor-default">
                                    <figure class="imagecheck-figure text-center">
                                        <img src="{{asset('storage/'.$product->foto_produk)}}" style="height: 200px;"
                                            alt="title" class="imagecheck-image w-100">
                                    </figure>
                                    <span class="badge badge-danger absolute">Habis</span>
                                </label>
                                @endif
                            </div>
                            <div class="pl-3 pr-3 mb-0 pb-2">
                                <p class=" mb-0">{{$product->nama_produk}}</p>
                                <p class=" mb-0">Rp {{number_format($product->harga_produk)}}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>