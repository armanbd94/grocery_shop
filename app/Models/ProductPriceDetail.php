<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB; 
// use Eloquent;
class ProductPriceDetail extends Model
{
    protected $fillable = ['product_id','weight','unitname','price'];

    public function produc()
    {
        return $this->belongsTo('App\Models\Product');
    }

    protected $_table_name     = 'product_price_details';

    public static function product_price_details($array = NULL){
        $self = new static; //create an object to access none static property
        $query = DB::table($self->_table_name)->select('*');
        if(!empty($array)){
            $query = $query->where($array);
        }
        return $query->get();
    }
}
