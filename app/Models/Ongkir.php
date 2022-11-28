<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ongkir extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'ongkir';
    //public $incrementing = false;

    protected $fillable = ['nama', 'ongkir'];

    protected $dates = [];

    /**
     * Get the profileUser associated with the Ongkir
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profileUser()
    {
        return $this->hasOne(ProfileUser::class, 'ongkir_id');
    }
}
