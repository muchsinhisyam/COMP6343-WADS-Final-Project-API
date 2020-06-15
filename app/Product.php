<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name', 'color_id', 'category_id', 'price', 'qty', 'description',
    ];

    public function photos()
    {
        return $this->hasMany('App\Photos', 'product_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function color()
    {
        return $this->belongsTo('App\Color');
    }

    public function orderdetail()
    {
        return $this->hasMany('App\OrderDetail');
    }
}
