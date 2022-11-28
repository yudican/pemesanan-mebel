<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileUser extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the ongkir that owns the ProfileUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ongkir()
    {
        return $this->belongsTo(Ongkir::class, 'ongkir_id');
    }

    /**
     * Get the user that owns the ProfileUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
