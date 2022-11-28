<?php

namespace App\Http\Livewire\Client;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;

class ProductDetail extends Component
{
    public $product;
    public function mount($product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            return abort(404);
        }

        $this->product = $product;
    }
    public function render()
    {
        return view('livewire.client.product-detail')->layout('layouts.user');
    }

    public function addToCart()
    {
        $user = auth()->user();
        if (!$user) {
            return $this->emit('showAlertError', [
                'msg' => 'Silahkan Login Terlebih Dahulu.',
                'redirect' => true,
                'path' => 'login'
            ]);
        }

        $whereData = [
            'user_id' => $user->id,
            'product_id' => $this->product->id,
        ];

        $cart = Cart::where($whereData)->first();
        $qty = $cart ? $cart->qty : 0;

        if ($qty >= $this->product->stok_produk) {
            return $this->emit('showAlertError', [
                'msg' => 'Stok Produk Tidak Cukup.',
            ]);
        }

        Cart::updateOrCreate($whereData, [
            'user_id' => $user->id,
            'product_id' => $this->product->id,
            'qty' => $qty + 1
        ]);

        return $this->emit('showAlert', [
            'msg' => 'Product Berhasil Ditambah Kekeranjang.',
        ]);
    }
}
