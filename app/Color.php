<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'id ', 'color_name',
    ];

    public function product()
    {
        return $this->hasOne('App\Product');
    }
}
