<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\Order;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;
use App\Models\ConfirmPayment;

class OrderTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_orders';
    public $hide = [];


    public function builder()
    {
        $role = auth()->user()->role;
        if ($role->role_type == 'member') {
            return Order::query()->where('user_id', auth()->user()->id);
        }
        return Order::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        $role = auth()->user()->role;
        if ($role->role_type == 'member') {
            return [
                Column::name('kode_order')->label('Kode Order')->searchable(),
                Column::callback('total_order', function ($total) {
                    return 'Rp. ' . number_format($total);
                })->label('Total Order')->searchable(),
                Column::name('metode_pembayaran')->label('Type Pembayaran')->searchable(),
                Column::name('kode_unik')->label('Kode Unik')->searchable(),
                Column::name('tanggal_order')->label('Tanggal Order')->searchable(),
                Column::name('paymentMethod.nama_bank')->label('Metode Pembayaran')->searchable(),
                Column::name('status')->label('Status')->searchable(),
                Column::name('catatan')->label('Catatan')->searchable(),

                Column::callback(['tbl_orders.id', 'tbl_orders.status'], function ($id, $status) {
                    if ($status == 'Ditolak') {
                        return '<a href="' . route('checkout.payment', ['order_id' => $id]) . '"><button type="button" class="btn btn-danger btn-sm">Konfirmasi Ulang Pembayaran</button></a>';
                    }
                })->label(__('Aksi')),
            ];
        }
        return [
            Column::name('kode_order')->label('Kode Order')->searchable(),
            Column::name('user.name')->label('Pelanggan')->searchable(),
            Column::callback('total_order', function ($total) {
                return 'Rp. ' . number_format($total);
            })->label('Total Order')->searchable(),
            Column::name('metode_pembayaran')->label('Type Pembayaran')->searchable(),
            Column::name('kode_unik')->label('Kode Unik')->searchable(),
            Column::name('tanggal_order')->label('Tanggal Order')->searchable(),
            Column::name('paymentMethod.nama_bank')->label('Metode Pembayaran')->searchable(),
            // Column::name('status')->label('Status')->searchable(),
            Column::name('catatan')->label('Catatan')->searchable(),

            Column::callback(['tbl_orders.id', 'tbl_orders.status', 'tbl_orders.metode_pembayaran'], function ($id, $status, $metode_pembayaran) {
                if ($metode_pembayaran == 'cash') {
                    if ($status == 'Selesai') {
                        return '<button id="btn-detail-' . $id . '" type="button" class="btn btn-success btn-sm" wire:click="getDataById(' . $id . ')">Detail Pesanan</button>';
                    }
                    return '<button id="btn-update-' . $id . '" type="button" class="btn btn-primary btn-sm" wire:click="getDataById(' . $id . ')">Update Status Pesanan</button>';
                }
                $confirmPayment = ConfirmPayment::where('order_id', $id)->first();
                if ($confirmPayment) {
                    if ($status == 'Diproses') {
                        return '<button id="btn-update-' . $id . '" type="button" class="btn btn-primary btn-sm" wire:click="getConfirmPaymentId(' . $confirmPayment->id . ')">Lihat Pembayaran</button>';
                    }
                    if ($status == 'Selesai') {
                        return '<button  id="btn-detail-' . $id . '" type="button" class="btn btn-success btn-sm" wire:click="getConfirmPaymentId(' . $confirmPayment->id . ')">Detail Pesanan</button>';
                    }
                    return '<button id="btn-update-' . $id . '" type="button" class="btn btn-primary btn-sm" wire:click="getConfirmPaymentId(' . $confirmPayment->id . ')">Update Status Pesanan</button>';
                }
                return null;
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataOrderById', $id);
    }

    public function getId($id)
    {
        $this->emit('getOrderId', $id);
    }
    public function getConfirmPaymentId($id)
    {
        $this->emit('getDataConfirmPayment', $id);
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
