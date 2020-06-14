<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    protected $table = 'customer_info';

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'phone', 'city', 'zip_code', 'address',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function custom_orders()
    {
        return $this->hasMany('CustomOrders');
    }
}
