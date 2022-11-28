<?php

namespace App\Http\Livewire\Master;

use App\Models\Ongkir;
use Livewire\Component;


class OngkirController extends Component
{

    public $tbl_ongkir_id;
    public $nama;
    public $ongkir;



    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataOngkirById', 'getOngkirId'];

    public function render()
    {
        return view('livewire.master.tbl-ongkir', [
            'items' => Ongkir::all()
        ]);
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'nama'  => $this->nama,
            'ongkir'  => $this->ongkir
        ];

        Ongkir::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'nama'  => $this->nama,
            'ongkir'  => $this->ongkir
        ];
        $row = Ongkir::find($this->tbl_ongkir_id);



        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        Ongkir::find($this->tbl_ongkir_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama'  => 'required',
            'ongkir'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataOngkirById($tbl_ongkir_id)
    {
        $this->_reset();
        $tbl_ongkir = Ongkir::find($tbl_ongkir_id);
        $this->tbl_ongkir_id = $tbl_ongkir->id;
        $this->nama = $tbl_ongkir->nama;
        $this->ongkir = $tbl_ongkir->ongkir;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getOngkirId($tbl_ongkir_id)
    {
        $tbl_ongkir = Ongkir::find($tbl_ongkir_id);
        $this->tbl_ongkir_id = $tbl_ongkir->id;
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
        $this->tbl_ongkir_id = null;
        $this->nama = null;
        $this->ongkir = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
