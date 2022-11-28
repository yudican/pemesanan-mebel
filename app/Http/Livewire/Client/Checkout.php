<?php

namespace App\Http\Livewire\Client;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkout extends Component
{
    public $user_id;
    public $total_order;
    public $total_order_temp;
    public $total_ongkir;
    public $total_bayar;
    public $kode_order;
    public $metode_pembayaran;
    public $tanggal_order;
    public $kode_unik;
    public $payment_method_id;
    public $status;
    public $catatan;
    public $carts;

    public function mount($order_id = null)
    {
        $this->kode_order = 'INV-' . date('d-mHi') . '-' . rand(123, 999) . '-' . date('Y');
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        $this->carts = $carts;
        $this->total_order_temp = 'Rp. ' . number_format($this->_getTotal($carts));
        $this->total_order = $this->_getTotal($carts);
        $this->total_ongkir = 'Rp. ' . number_format(auth()->user()->profileUser->ongkir->ongkir);
        $this->total_bayar = 'Rp. ' . number_format($this->total_order + auth()->user()->profileUser->ongkir->ongkir);
        if ($order_id) {
            $order = Order::find($order_id);
            if ($order) {
                $this->user_id = $order->user_id;
                $this->total_order_temp = 'Rp. ' . number_format($order->total_order);
                $this->total_order = $order->total_order;
                $this->kode_order = $order->kode_order;
                $this->metode_pembayaran = $order->metode_pembayaran;
                $this->tanggal_order = $order->tanggal_order;
                $this->kode_unik = $order->kode_unik;
                $this->payment_method_id = $order->payment_method_id;
                $this->status = $order->status;
                $this->catatan = $order->catatan;
                $this->carts = $order->orderDetails;
            }
        }
    }

    public function render()
    {

        return view(
            'livewire.client.checkout',
            [
                // 'total_price' => $this->_getTotal($carts),
                'payment_methods' => PaymentMethod::all()
            ]
        )->layout('layouts.user');
    }

    public function _validate()
    {
        $rule = [
            'metode_pembayaran'  => 'required',
        ];

        if ($this->metode_pembayaran == 'transfer') {
            $rule['payment_method_id'] = 'required';
        }

        return $this->validate($rule);
    }

    public function prosesCheckout()
    {
        $this->_validate();
        try {
            DB::beginTransaction();
            $kode_unik = rand(100, 999);
            $total_bayar = $this->total_order + auth()->user()->profileUser->ongkir->ongkir + $kode_unik;
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'ongkir_id' => auth()->user()->profileUser->ongkir_id,
                'total_order' => $total_bayar,
                'kode_order' => $this->kode_order,
                'metode_pembayaran' => $this->metode_pembayaran,
                'tanggal_order' => date('Y-m-d'),
                'kode_unik' => substr($total_bayar, -3),
                'payment_method_id' => $this->payment_method_id,
                'status' => $this->metode_pembayaran == 'cash' ? 'Diverivikasi' : 'Belum Bayar'
            ]);

            foreach ($this->carts as $cart) {
                $order->orderDetails()->create([
                    'product_id' => $cart->product_id,
                    'qty' => $cart->qty,
                    'subtotal' => $cart->qty * $cart->product->harga_produk,
                ]);
                $cart->delete();
            }


            DB::commit();
            if ($this->metode_pembayaran == 'transfer') {
                return $this->emit('showAlert', [
                    'msg' => 'Transaksi Berhasil, Silahkan Lakukan Pembayaran.',
                    'redirect' => true,
                    'path' => 'selesaikan-pesanan/' . $order->id
                ]);
            }
            return $this->emit('showAlert', [
                'msg' => 'Transaksi Berhasil, Silahkan Lakukan Pembayaran.',
                'redirect' => true,
                'path' => 'order'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->emit('showAlertError', [
                'msg' => 'Transaksi Gagal, silahkan coba lagi.',
            ]);
        }
    }

    public function _getTotal($carts = [])
    {
        $total = 0;
        foreach ($carts as $cart) {
            $total += $cart->total_price;
        }

        return $total;
    }
}
