<?php

namespace App\Http\Livewire;

use App\Models\Ongkir;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfile extends Component
{
    use WithFileUploads;
    public $user_profile_photo;
    public $user_profile_photo_path;
    public $email;
    public $nomor_hp;
    public $alamat;
    public $ongkir_id;

    public function mount()
    {
        $user = auth()->user();
        $this->user_profile_photo = $user->profile_photo_path;
        $this->user_profile_photo_path = $user->profile_photo_path;
        $this->email = $user->email;
        $this->nomor_hp = $user->profileUser->nomor_hp;
        $this->alamat = $user->profileUser->alamat;
        $this->ongkir_id = $user->profileUser->ongkir_id;
    }
    public function render()
    {
        return view('livewire.update-profile', [
            'ongkirs' => Ongkir::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        $user = auth()->user();
        $data = [
            'email'  => $this->email,
        ];

        if ($user->profile_photo_path) {
            if ($this->user_profile_photo_path) {
                $user_profile_photo = $this->user_profile_photo_path->store('upload', 'public');
                $data = ['profile_photo_path' => $user_profile_photo];
                if (Storage::exists('public/' . $this->user_profile_photo)) {
                    Storage::delete('public/' . $this->user_profile_photo);
                }
            }
        } else {
            $user_profile_photo = $this->user_profile_photo_path->store('upload', 'public');
            $data = ['profile_photo_path'  => $user_profile_photo];
        }

        $user->update($data);
        $user->profileUser()->update(['nomor_hp' => $this->nomor_hp, 'alamat' => $this->alamat, 'ongkir_id' => $this->ongkir_id]);

        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function _validate()
    {
        $rule = [
            'email'  => 'required',
            'nomor_hp'  => 'required',
            'alamat'  => 'required',
            'ongkir_id'  => 'required',
        ];

        return $this->validate($rule);
    }
}
