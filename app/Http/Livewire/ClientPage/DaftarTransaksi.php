<?php

namespace App\Http\Livewire\ClientPage;

use Livewire\Component;

class DaftarTransaksi extends Component
{
    public function render()
    {
        return view('livewire.client-page.daftar-transaksi')->layout('layouts.user');
    }
}
