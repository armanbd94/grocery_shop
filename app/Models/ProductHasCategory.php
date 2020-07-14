<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProductHasCategory extends Model
{
    protected $fillable = ['product_id','category_id','category_parent_id'];

    public function produc()
    {
        return $this->belongsTo('App\Models\Product');
    }


    public function categorywise_product($cat_id,$page='',$show_perpage='',$order_by='')
    {
        $query = DB::table('product_has_categories as pc')
                    ->join('products as p','pc.product_id','=','p.id')
                    ->leftjoin('brands as b','p.brand_id','=','b.id')
                    ->select('pc.product_id','p.*','b.brand_name')
                    ->where('p.status', 1)
                    ->where('pc.category_id',$cat_id)
                    ->orWhere('pc.category_parent_id',$cat_id)
                    ->groupBy('pc.product_id');

        if(!empty($order_by)){
            if($order_by == 1){
                $query->orderBy('p.product_name','asc');
            }elseif ($order_by == 2) {
                $query->orderBy('p.product_name','desc');
            }          
        }else{
            $query->orderBy('pc.product_id','desc');
        }
        if(!empty($show_perpage)){
            $per_page = $show_perpage;
        }else{
            $per_page = 9;
        }

        return  $query->paginate($per_page);
    }


    public function search_product($query,$page='',$show_perpage='',$order_by='')
    {
        $query = DB::table('product_has_categories as pc')
                    ->select('pc.product_id','p.*','b.brand_name','c.category_name')
                    ->join('categories as c','pc.category_id','=','c.id')
                    ->join('products as p','pc.product_id','=','p.id')
                    ->leftjoin('brands as b','p.brand_id','=','b.id')
                    ->where('p.status',1)
                    ->where('p.product_name', 'like','%'.$query.'%')
                    ->orWhere('p.product_slug', 'like','%'.$query.'%')
                    ->orWhere('c.category_name', 'like','%'.$query.'%')
                    ->orWhere('b.brand_name', 'like','%'.$query.'%')
                    ->groupBy('pc.product_id');

        if(!empty($order_by)){
            if($order_by == 1){
                $query->orderBy('p.product_name','asc');
            }elseif ($order_by == 2) {
                $query->orderBy('p.product_name','desc');
            }          
        }else{
            $query->orderBy('pc.product_id','desc');
        }
        if(!empty($show_perpage)){
            $per_page = $show_perpage;
        }else{
            $per_page = 9;
        }

        return  $query->paginate($per_page);
    }

    
}
