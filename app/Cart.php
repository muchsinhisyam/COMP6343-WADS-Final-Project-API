<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id'
    ];

    public function cartdetail()
    {
        return $this->hasMany('App\CartDetail');
    }
}
