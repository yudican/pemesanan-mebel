<?php

namespace App\Http\Livewire\Client;

use App\Models\Banner;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class HomeUser extends Component
{
    public function render()
    {
        return view('livewire.client.home-user', [
            'banners' => Banner::all(),
            'categories' => Category::with('products')->get()
        ])->layout('layouts.user');
    }
}
