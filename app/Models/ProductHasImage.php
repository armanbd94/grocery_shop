<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProductHasImage extends Model
{
    protected $fillable = ['product_id','image'];

    protected $_table_name = 'product_has_images';

    public static function product_images($array = NULL){
        $self = new static; //create an object to access none static property
        $query = DB::table($self->_table_name)->select('*');
        if(!empty($array)){
            $query = $query->where($array);
        }
        return $query->get();
    }
}
