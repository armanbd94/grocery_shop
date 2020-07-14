<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Banner;
use App\Models\ProductHasCategory;

class WebsiteHomeController extends Controller
{
    private $zero = 0;
    

    public function index()
    {

        $page_title = 'Home';
        $total_category = DB::table('categories')->where('parent_id','!=',$this->zero)->get()->count();
        if(!empty($total_category)){
            if ($total_category >= 3) {
               $random_no = 3;
            }else{
                $random_no = $total_category;
            }
        }
        $baners = Banner::all();
        $categories = DB::table('categories')->where('parent_id','!=',$this->zero)->get()->random($random_no);
        return view('website.home',compact('page_title','baners','categories'));
    }

    public function active_tab_products(Request $request)
    {
        if($request->ajax()){
            // $catid = 15;
            $catid = $request->catid;
            if($catid == 0){
                $total_data = DB::table('product_has_categories as pc')
                        ->join('categories as c','pc.category_id','=','c.id')
                        ->join('products as p','pc.product_id','=','p.id')
                        ->select('pc.product_id','p.*')
                        ->where('p.status',1)
                        ->groupBy('pc.product_id')
                        ->orderBy('pc.product_id','desc')
                        ->get();
                $total_count = count($total_data);
                if($total_count >= 6){
                    $total = 6;
                }else{
                    $total = $total_count;
                }
                $data = DB::table('product_has_categories as pc')
                        ->join('categories as c','pc.category_id','=','c.id')
                        ->join('products as p','pc.product_id','=','p.id')
                        ->leftjoin('brands as b','p.brand_id','=','b.id')
                        ->select('pc.product_id','p.*','b.brand_name')
                        ->where('p.status',1)
                        ->groupBy('pc.product_id')
                        // ->orderBy('pc.product_id','desc')
                        // ->take($total)
                        ->get()
                        ->random($total);

            }else{
                $total_data = DB::table('product_has_categories as pc')
                                    ->join('categories as c','pc.category_id','=','c.id')
                                    ->join('products as p','pc.product_id','=','p.id')
                                    ->select('pc.product_id','p.*')
                                    ->where('p.status',1)
                                    ->where('pc.category_id',$catid)
                                    ->orWhere('pc.category_parent_id',$catid)
                                    ->groupBy('pc.product_id')
                                    ->get();
                $total_count = count($total_data);
                if($total_count >= 12){
                    $total = 12;
                }else{
                    $total = $total_count;
                }
                $data = DB::table('product_has_categories as pc')
                        ->join('categories as c','pc.category_id','=','c.id')
                        ->join('products as p','pc.product_id','=','p.id')
                        ->leftjoin('brands as b','p.brand_id','=','b.id')
                        ->select('pc.product_id','p.*','b.brand_name')
                        ->where('p.status',1)
                        ->where('pc.category_id',$catid)
                        ->orWhere('pc.category_parent_id',$catid)
                        ->groupBy('pc.product_id')
                        ->orderBy('pc.product_id','desc')
                        ->take($total)
                        ->get()->random($total);

            }
            return view('website.tab-product', compact('data'))->render();
        }
    }


    public function searching_data(Request $request)
    {
        $query = $this->validate($request->get('query'));
        if(!empty($query)){
            $cat_product = new ProductHasCategory();
            $page = $show_perpage = $order_by = '';
            $product_data = $cat_product->search_product($query,$page,$show_perpage,$order_by);
            $product       =  view('website.include.search-product', compact('product_data'))->render();
            $page_title   = 'Search';
            return view('website.search',compact('page_title','product','query'));
        }
    }

    public function search_product(Request $request)
    {
        if($request->ajax()){
            $cat_product  = new ProductHasCategory();     
            $query        = $request->get('query');      
            $page         = $request->get('page');
            $show_perpage = $request->get('show_perpage');
            $order_by     = $request->get('$request->order_by');
            $product_data = $cat_product->search_product($query,$page,$show_perpage,$order_by);
            return view('website.include.search-product', compact('product_data'))->render();
        }
    }

    public function page_content($page_url)
    {
        if(!empty($page_url)){
            $data = DB::table('website_pages')->where('page_url',$page_url)->first();
            return view('website.page-content',compact('data'));
        }else{
            return redirect('error/404');
        }
    }

    public function error()
    {
        $page_title = 'Error 404';
        return view('website.404',compact('page_title'));
    }
}
