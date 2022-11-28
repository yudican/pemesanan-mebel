<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\Product;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;

class ProductTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_products';
    public $hide = [];


    public function builder()
    {
        return Product::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('nama_produk')->label('Nama Produk')->searchable(),
            Column::name('harga_produk')->label('Harga Produk')->searchable(),
            Column::callback(['foto_produk'], function ($image) {
                return view('livewire.components.photo', [
                    'image_url' => asset('storage/' . $image),
                ]);
            })->label(__('Foto Produk')),
            Column::name('stok_produk')->label('Stok Produk')->searchable(),
            // Column::name('deskripsi_produk')->label('Deskripsi Produk')->searchable(),
            Column::name('category.nama_kategori')->label('Kategori')->searchable(),

            Column::callback(['tbl_products.status_produk', 'tbl_products.id'], function ($status_produk, $id) {
                return view('livewire.components.status', [
                    'id' => $id,
                    'type' => $status_produk == 1 ? 'success' : 'warning',
                    'label' =>  $status_produk == 1 ? 'AKTIF' : 'NON AKTIF'
                ]);
            })->label(__('Status')),
            Column::callback(['id'], function ($id) {
                return view('livewire.components.action-button', [
                    'id' => $id,
                    'segment' => request()->segment(1)
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataProductById', $id);
    }

    public function getId($id)
    {
        $this->emit('getProductId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function toggle($index)
    {
        if ($this->sort == $index) {
            $this->initialiseSort();
        }

        $column = HideableColumn::where([
            'table_name' => $this->table_name,
            'column_name' => $this->columns[$index]['name'],
            'index' => $index,
            'user_id' => auth()->user()->id
        ])->first();

        if (!$this->columns[$index]['hidden']) {
            unset($this->activeSelectFilters[$index]);
        }

        $this->columns[$index]['hidden'] = !$this->columns[$index]['hidden'];

        if (!$column) {
            HideableColumn::updateOrCreate([
                'table_name' => $this->table_name,
                'column_name' => $this->columns[$index]['name'],
                'index' => $index,
                'user_id' => auth()->user()->id
            ]);
        } else {
            $column->delete();
        }
    }
}
