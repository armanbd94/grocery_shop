<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductHasCategory;

class CategorywiseProductController extends Controller
{
    protected $_cat = array();
    public function index($slug)
    {
        // dd($this->show_breadcrumb(15));

        if(!empty($slug)){
            $category = Category::where('category_slug',$slug)->first();
            if(!empty($category)){

                $product_data = DB::table('product_has_categories as pc')
                            ->join('products as p','pc.product_id','=','p.id')
                            ->leftjoin('brands as b','p.brand_id','=','b.id')
                            ->select('pc.product_id','p.*','b.brand_name')
                            ->where(['p.status' => 1, 'pc.category_id' => $category->id])
                            ->where('pc.category_id',$category->id)
                            ->orWhere('pc.category_parent_id',$category->id)
                            ->groupBy('pc.product_id')
                            ->orderBy('pc.product_id','desc')
                            ->paginate(9);

                
                $cat = $this->get_categoryID($category->parent_id);  //get sll psrent and grand parent id
                array_push($this->_cat,$category->id);
                $breadcrumb = $this->show_breadcrumb($this->_cat);   //get breadcrumb menu html data 

                $page_title = $category->category_name;
                return view('website.categorywise-product',compact('page_title','category','product_data','breadcrumb'));
            }else{
                return redirect('/');
            }
            
        }else{
            return redirect()->back();
        }
    }

    public function categorywise_product(Request $request)
    {
        if($request->ajax()){
            $cat_product = new ProductHasCategory();
        
            $cat_id = $request->cat_id;
            
            $page = $request->page;

            $show_perpage = $request->show_perpage;
        
            $order_by = $request->order_by;


            $product_data = $cat_product->categorywise_product($cat_id,$page,$show_perpage,$order_by);
            // dd($product_data);
            return view('website.include.categorywise-product-content', compact('product_data'))->render();
        }
    }

    public function show_breadcrumb($cat_id = array()){

        $breadcrumb = '';
        $breadcrumb .= '<a href="'.url("/").'"><strong><span class="mdi mdi-home"></span> Home</strong></a>';
        IF(!empty($cat_id)){
            if(count($cat_id) > 0){
                foreach ($cat_id as $value) {
                    $data = Category::find($value);
                    $breadcrumb .= '<span class="mdi mdi-chevron-right"></span><a href="'.url('category',$data->category_slug).'">'.$data->category_name.'</a>';
                }
            }
        }
        return $breadcrumb;
    }
    function get_categoryID($parent_id = NULL)
    {
        // $category = '';
        if($parent_id == 0){
            $categories = Category::categories(['id' => $parent_id]); 
        }else{
            $categories = Category::categories(['id' => $parent_id]); 
        }
        // dd( $categories);
        if(!empty($categories)){
            foreach ($categories as $value) {
                // $category .= '<span class="mdi mdi-chevron-right"></span><a href="'.url('category/',$value->category_slug).'">'.$value->category_name.'</a>';
                // echo $value->parent_id.'<br>';
                
                    $this->get_categoryID($value->parent_id);
                    array_push($this->_cat,$value->id);
                
                
            }
        }
        // dd($category);
        return true;
    }
}
