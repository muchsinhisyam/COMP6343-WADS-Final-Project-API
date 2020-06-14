<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_type', 'order_status', 'description',
    ];

    const defaultCustomOrderStatus = 'Waiting for Approval';
    const defaultStockOrderStatus = 'Waiting for Payment';

    public function customer_info()
    {
        return $this->belongsTo('App\CustomerInfo');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function custom_photo()
    {
        return $this->HasMany('App\CustomPhotos', 'order_id');
    }

    public function transfer_photo()
    {
        return $this->HasMany('App\TransferPhoto', 'order_id');
    }

    public function orderdetails()
    {
        return $this->HasMany('App\OrderDetail');
    }
}
