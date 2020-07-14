<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Models\Product;
use App\Models\ProductPriceDetails;
use DB;
use Response;
use Validator;
use Session;
use App\Models\Coupon;

class CartController extends Controller
{
    //cart page view by this method
    public function index(){

        $page_title = 'My Cart';
        return view('website.cart',compact('page_title'));
    }

    //add cart page content data by rendering this method
    public function cart_content(Request $request)
    {
        if($request->ajax()){
            return view('website.include.cart-content')->render();
        }
    }

    //add sidebar cart content data by rendering this method
    public function sidebar_cart(Request $request) {
        if($request->ajax()){
            return view('website.include.sidebar-cart')->render();
        }
    }

    //product add to cart method
    public function add_to_cart(Request $request)
    {
        if($request->ajax()){
            $id = $request->id; //product_price_details id

            //check product already exit in cart array
            $product_exist = Cart::search(function ($cart, $key) use($id) {
                return $cart->id == $id;
            })->first();

            if($product_exist && empty($request->qty)){
                $json['warning'] = 'Product already in cart.';
            }else{
                if(!empty($request->qty)){
                    $qty =  (int)$request->qty; //requsted qty from customer
                }else{
                    $qty = 1; //by default qty is 1
                }

                //get the requsted id product data
                $product = DB::table('product_price_details as pp')
                            ->leftjoin('products as p','pp.product_id','=','p.id')
                            ->leftjoin('brands as b','p.brand_id','=','b.id')
                            ->select('pp.*','p.product_name','p.product_slug','p.product_variation','p.featured_image','b.brand_name')
                            ->where('pp.id',$id)
                            ->first();

                if(!empty($product)){
                    $data['id']                   = $product->id;
                    $data['name']                 = $product->product_name;
                    $data['price']                = $product->price;
                    $data['qty']                  = $qty;
                    $data['options']['image']     = $product->featured_image;
                    $data['options']['variation'] = $product->product_variation;
                    $data['options']['brand']     = $product->brand_name;
                    $data['options']['weight']    = $product->weight.$product->unitname;
                    $data['options']['slug']      = $product->product_slug;
                    Cart::add($data); //product data added in cart
                    $json['success'] = 'Product has been added in cart';
                }else{
                    $json['error'] = 'Somehing is wrong. Please try again!';
                }
            }
            return Response::json($json);
        }
    }

    //cart product udate method
    public function update_cart(Request $request) {
        if($request->ajax()){
            $rowId = $request->rowId;
            $qty   = $request->qty;
            Cart::update($rowId,$qty);
            $json['status'] = '300'; //status = 300 mean success
            return Response::json($json);
        }
    }
    
    //remove product from cart
    public function remove_cart(Request $request) {
        if($request->ajax()){
            $rowId = $request->rowId;
            Cart::remove($rowId);
            $json['status'] = '300'; //status = 300 mean success
            if(Cart::content()->count() == 0){
                Session::forget('coupon_data');
            }
            return Response::json($json);
        }
    }

    public function total_cart_item(Request $request)
    {
        $json['total_item'] = Cart::content()->count();
        return Response::json($json);
    }

    public function coupon_check(Request $request)
    {
        if($request->ajax()){
            $rules['coupon_code']   = 'required'; 
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            } else {
                $coupon_code = $this->validate($request->coupon_code);
                $coupon = Coupon::where(['coupon_code' => $coupon_code,'status' => 1])->first();
                if(!empty($coupon)){
                    $current_date = date('Y-m-d');
                    if(Cart::total() >= $coupon->total){

                        if($coupon->end_date >=  $current_date){
                            // if($coupon->uses_total > $coupon->uses_customer){

                                    Session::put('coupon_data',array(
                                        'coupon_code' => $coupon->coupon_code,
                                        'coupon_discount' => $coupon->discount
                                    ));
                                    $json['success'] = 'success';
                                
                            // }else{
                            //     $json['msg'] = 'Sorry! customer quota has filled up. Better luck next time.';
                            // }
                        }else{
                            $json['msg'] = 'This Coupon code has expired.';
                        }
                    }else{
                        $json['msg'] = 'To apply this coupon you have to shop at least QAR '.number_format($coupon->total,2);
                    }
                }else{
                    $json['msg'] = 'Invalid coupon code.';
                }
                

            }
            return Response::json($json);
        }
    }

    
}
