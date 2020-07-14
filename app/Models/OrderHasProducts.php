<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHasProducts extends Model
{
    protected $fillable = ['order_id','product_id','product_price_id','name','qty','price','total'];
}
