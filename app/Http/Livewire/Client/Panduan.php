<?php

namespace App\Http\Livewire\Client;

use Livewire\Component;

class Panduan extends Component
{
    public function render()
    {
        return view('livewire.client.panduan')->layout('layouts.user');
    }
}
