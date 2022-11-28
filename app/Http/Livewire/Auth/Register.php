<?php

namespace App\Http\Livewire\Auth;

use App\Models\Ongkir;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Register extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $nomor_hp;
    public $alamat;
    public $ongkir_id;
    public $password;
    public $password_confirmation;


    public function render()
    {
        return view('livewire.auth.register', [
            'ongkirs' => Ongkir::all()
        ]);
    }

    public function store()
    {
        // dd('ok');
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'ongkir_id' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password',
        ];

        $this->validate($rules);

        try {
            DB::beginTransaction();
            $role_member = Role::where('role_type', 'member')->first();
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'current_team_id' => 1
            ]);

            $user->profileUser()->create([
                'nomor_hp' => $this->nomor_hp,
                'alamat' => $this->alamat,
                'ongkir_id' => $this->ongkir_id,
            ]);

            $user->roles()->attach($role_member->id);
            $user->teams()->attach(1, ['role' => $role_member->role_type]);

            DB::commit();
            $this->_resetForm();
            event(new Registered($user));
            $this->emit('showAlert', [
                'msg' => 'Registrasi Berhasil, silahkan login.',
                'redirect' => true,
                'path' => 'login'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->emit('showAlertError', [
                'msg' => 'Registrasi Gagal, Coba lagi.',
            ]);
        }
    }

    public function _resetForm()
    {
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->password_confirmation = null;
    }
}
