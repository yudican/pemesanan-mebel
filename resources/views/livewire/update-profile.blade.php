<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>products</span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <x-text-field type="text" name="email" label="Email" />
                    <x-text-field type="text" name="nomor_hp" label="Nomor Hp" />
                    <x-text-field type="text" name="alamat" label="Alamat" />
                    <x-select name="ongkir_id" label="Kota/Kabupaten">
                        <option value="">Select Kota/Kabupaten</option>
                        @foreach ($ongkirs as $item)
                        <option value="{{$item->id}}">{{$item->nama}}</option>
                        @endforeach
                    </x-select>
                    <x-input-photo foto="{{$user_profile_photo}}"
                        path="{{optional($user_profile_photo_path)->temporaryUrl()}}" name="user_profile_photo_path"
                        label="Foto Produk" />

                    <div class="form-group">
                        <button class="btn btn-primary pull-right" wire:click="store">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>