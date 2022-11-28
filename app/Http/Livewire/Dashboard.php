<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $data = [
            'total' => Order::count(),
            'dibayar' => Order::whereStatus('Diverivikasi')->count(),
            'dikirim' => Order::whereStatus('Dikirim')->count(),
            'selesai' => Order::whereStatus('Selesai')->count(),
        ];
        $role = auth()->user()->role;
        if ($role->role_type == 'member') {
            $data = [
                'total' => Order::whereUserId(auth()->user()->id)->count(),
                'dibayar' => Order::whereUserId(auth()->user()->id)->whereStatus('Diverivikasi')->count(),
                'dikirim' => Order::whereUserId(auth()->user()->id)->whereStatus('Dikirim')->count(),
                'selesai' => Order::whereUserId(auth()->user()->id)->whereStatus('Selesai')->count(),
            ];
        }

        return view('livewire.dashboard', $data);
    }
}
