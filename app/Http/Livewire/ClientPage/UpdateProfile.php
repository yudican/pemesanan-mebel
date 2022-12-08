<?php

namespace App\Http\Livewire\ClientPage;

use Livewire\Component;

class UpdateProfile extends Component
{
    public function render()
    {
        return view('livewire.client-page.update-profile')->layout('layouts.user');
    }
}
