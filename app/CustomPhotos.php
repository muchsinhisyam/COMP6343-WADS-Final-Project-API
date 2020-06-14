<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomPhotos extends Model
{
    protected $table = 'custom_order_photos';

    protected $fillable = [
        'order_id', 'image_name',
    ];

    public function order()
    {
        return $this->belongsTo('App\Order', 'id');
    }
}
