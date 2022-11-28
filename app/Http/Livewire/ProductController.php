<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ProductController extends Component
{
    use WithFileUploads;
    public $tbl_products_id;
    public $nama_produk;
    public $harga_produk;
    public $foto_produk;
    public $stok_produk;
    public $deskripsi_produk;
    public $status_produk;
    public $category_id;
    public $foto_produk_path;


    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataProductById', 'getProductId'];

    public function render()
    {
        return view('livewire..tbl-products', [
            'items' => Product::all(),
            'categories' => Category::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        $foto_produk = $this->foto_produk_path->store('upload', 'public');
        $data = [
            'nama_produk'  => $this->nama_produk,
            'harga_produk'  => $this->harga_produk,
            'foto_produk'  => $foto_produk,
            'stok_produk'  => $this->stok_produk,
            'deskripsi_produk'  => $this->deskripsi_produk,
            'status_produk'  => $this->status_produk,
            'category_id'  => $this->category_id
        ];

        Product::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'nama_produk'  => $this->nama_produk,
            'harga_produk'  => $this->harga_produk,
            'foto_produk'  => $this->foto_produk,
            'stok_produk'  => $this->stok_produk,
            'deskripsi_produk'  => $this->deskripsi_produk,
            'status_produk'  => $this->status_produk,
            'category_id'  => $this->category_id
        ];
        $row = Product::find($this->tbl_products_id);


        if ($this->foto_produk_path) {
            $foto_produk = $this->foto_produk_path->store('upload', 'public');
            $data = ['foto_produk' => $foto_produk];
            if (Storage::exists('public/' . $this->foto_produk)) {
                Storage::delete('public/' . $this->foto_produk);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        Product::find($this->tbl_products_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_produk'  => 'required',
            'harga_produk'  => 'required',
            'stok_produk'  => 'required',
            'deskripsi_produk'  => 'required',
            'status_produk'  => 'required',
            'category_id'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataProductById($tbl_products_id)
    {
        $this->_reset();
        $tbl_products = Product::find($tbl_products_id);
        $this->tbl_products_id = $tbl_products->id;
        $this->nama_produk = $tbl_products->nama_produk;
        $this->harga_produk = $tbl_products->harga_produk;
        $this->foto_produk = $tbl_products->foto_produk;
        $this->stok_produk = $tbl_products->stok_produk;
        $this->deskripsi_produk = $tbl_products->deskripsi_produk;
        $this->status_produk = $tbl_products->status_produk;
        $this->category_id = $tbl_products->category_id;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getProductId($tbl_products_id)
    {
        $tbl_products = Product::find($tbl_products_id);
        $this->tbl_products_id = $tbl_products->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->tbl_products_id = null;
        $this->nama_produk = null;
        $this->harga_produk = null;
        $this->foto_produk = null;
        $this->foto_produk_path = null;
        $this->stok_produk = null;
        $this->deskripsi_produk = null;
        $this->status_produk = null;
        $this->category_id = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
