<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = ['customer_id', 'provider_name', 'provider_id'];

    public function customer() {
        return $this->belongsTo('App\Models\Customer');
    }
}
