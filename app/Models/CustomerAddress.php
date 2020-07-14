<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = ['customer_id','first_name','last_name','address','city','postcode'];
}
