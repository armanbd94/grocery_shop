<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use App\Models\Product;
use App\Models\ProductHasImage;
use App\Helpers\Helper;
class WebsiteProductController extends Controller
{

    public function single_product($slug)
    {

        if(!empty($slug)){
            $product_data['data'] = DB::table('products as p')
                        ->leftjoin('brands as b','p.brand_id','=','b.id')
                        ->select('p.*','b.brand_name')
                        ->where('p.product_slug',$slug)
                        ->first();
            if(!empty($product_data['data'])){
                $product_data['weightwise_price'] = Helper::get_product_weightwise_price($product_data['data']->id);
                $product_data['primary_weight'] = $product_data['primary_price'] = '';
                $product_data['available_weight'] = [];
                if(!empty($product_data['weightwise_price'])){
                    foreach ($product_data['weightwise_price'] as $item){
                        if ($item->is_default == 1){
                            $product_data['primary_price']  = $item->price;
                            $product_data['primary_weight'] = $item->weight.$item->unitname;
                        }
                        $weight = $item->weight.$item->unitname;
                        if ($item->is_default == 2){
                            array_push($product_data['available_weight'],$weight);
                        }

                    }
                }
                $name = [$product_data['data']->brand_name,$product_data['data']->product_variation,$product_data['data']->product_name,$product_data['primary_weight']];
                $page_title = trim(join(" ",$name));
                $product_data['breadcrumb_menu'] = $this->show_breadcrumb($product_data['data']->id);
                $product_images = ProductHasImage::product_images(['product_id'=>$product_data['data']->id]);
                $product_data['image_array'] = [$product_data['data']->featured_image];
                if(!empty($product_images)){
                    foreach ($product_images as $value) {
                        array_push($product_data['image_array'],$value->image);
                    }
                }

                $category = DB::table('product_has_categories')
                ->where('product_id',$product_data['data']->id)
                ->orderBy('id','desc')
                ->first();

                $total = DB::table('product_has_categories as pc')
                ->join('products as p','pc.product_id','=','p.id')
                ->leftjoin('brands as b','p.brand_id','=','b.id')
                ->select('pc.product_id','p.*','b.brand_name')
                ->where(['p.status' => 1, 'pc.category_id' => $category->category_id])
                ->where('pc.category_id',$category->category_id)
                ->where('pc.product_id','!=',$product_data['data']->id)
                // ->orWhere('pc.category_parent_id',$category->category_id)
                ->groupBy('pc.product_id')
                ->orderBy('pc.product_id','desc')
                ->get()->count();
                $take = $total;
                if(!empty($total)){
                    if($total >= 3){
                        $take = 3;
                    }
                }
                // dd($take);
                $product_data['related_products'] = DB::table('product_has_categories as pc')
                            ->join('products as p','pc.product_id','=','p.id')
                            ->leftjoin('brands as b','p.brand_id','=','b.id')
                            ->select('pc.product_id','p.*','b.brand_name')
                            ->where(['p.status' => 1, 'pc.category_id' => $category->category_id])
                            ->where('pc.category_id',$category->category_id)
                            ->where('pc.product_id','!=',$product_data['data']->id)
                            // ->orWhere('pc.category_parent_id',$category->category_id)
                            ->groupBy('pc.product_id')
                            ->orderBy('pc.product_id','desc')
                            ->get()
                            ->random($take);
                return view('website.single-product',compact('page_title','product_data'));
            }else{
                return redirect('/');
            }
        }else{
            return redirect()->back();
        }
        
    }

    public function show_breadcrumb($product_id){

        $category = '';
        $category = '<a href="'.url("/").'"><strong><span class="mdi mdi-home"></span> Home</strong></a>';
        $category .= $this->multilevel_breadcrumb(0,$product_id);
        return $category;
    }
    function multilevel_breadcrumb($parent_id = NULL,$product_id)
    {
        $category = '';
        if($parent_id == 0){
            $categories = DB::table('product_has_categories as pp')
            ->leftjoin('categories as c','pp.category_id','=','c.id')
            ->select('pp.*','c.category_name','c.category_slug')
            ->where(['pp.product_id'=>$product_id,'pp.category_parent_id' => 0])
            ->get(); 
        }else{
            $categories =DB::table('product_has_categories as pp')
            ->leftjoin('categories as c','pp.category_id','=','c.id')
            ->select('pp.*','c.category_name','c.category_slug')
            ->where(['pp.product_id'=>$product_id,'pp.category_parent_id' => $parent_id])
            ->get();  
        }

        if(!empty($categories)){
            foreach ($categories as $value) {
                $category .= '<span class="mdi mdi-chevron-right"></span><a href="'.url('category',$value->category_slug).'">'.$value->category_name.'</a>';

                $category .= $this->multilevel_breadcrumb($value->category_id,$value->product_id);
                
            }
        }
        return $category;
    }
}
