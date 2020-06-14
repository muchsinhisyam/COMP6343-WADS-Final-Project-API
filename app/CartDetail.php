<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    protected $table = 'cart_details';

    protected $fillable = [
        'cart_id', 'product_id', 'qty'
    ];

    public function cart()
    {
        return $this->belongsTo('App\Product');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
