<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferPhoto extends Model
{
    protected $table = 'transfer_photos';

    protected $fillable = [
        'order_id', 'image_name',
    ];

    public function order()
    {
        return $this->belongsTo('App\Order', 'id');
    }
}
