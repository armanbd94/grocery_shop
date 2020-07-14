<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Cart;
use Session;
use Validator;
use Response;
use DB;
use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderHasProducts;
use App\Models\ProductPriceDetail;
use Mail;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if(Cart::content()->count() > 0){
            $page_title = 'Checkout';
            return view('website.checkout',compact('page_title'));
        }else{
            return redirect('cart');
        }
    }

    public function user_checkout_type(Request $request)
    {
        if($request->ajax()){
            if($request->user_account == 'register'){
                $group_type = 1;
            }elseif ($request->user_account == 'guest') {
                $group_type = 2;
            }
            return view('website.checkout-views.checkout-account-type',compact('group_type'))->render();
        }
    }

    public function checkout_option_view(Request $request)
    {
        if($request->ajax()){
            return view('website.checkout-views.checkout-option')->render();
        }
    }

    public function delivery_slot_view(Request $request)
    {
        if($request->ajax()){
            $customer_id   = Session::get('customer_id');
            $billing_id    = Session::get('billing_id');
            $shipping_id   = Session::get('shipping_id');
            $delivery_slot = Session::get('delivery_slot');
            $data = Setting::select('time_slot')->where('id',1)->first();
            $time_slot = json_decode($data->time_slot);
            return view('website.checkout-views.delivery-slot',compact('customer_id','billing_id','shipping_id','delivery_slot','time_slot'))->render();
        }
    }

    public function payment_method_view(Request $request)
    {
        if($request->ajax()){
            $customer_id   = Session::get('customer_id');
            $billing_id    = Session::get('billing_id');
            $shipping_id   = Session::get('shipping_id');
            $delivery_slot = Session::get('delivery_slot');
            return view('website.checkout-views.payment-method',compact('customer_id','billing_id','shipping_id','delivery_slot'))->render();
        }
    }
    public function confirm_option_view(Request $request)
    {
        if($request->ajax()){
            $customer_id   = Session::get('customer_id');
            $billing_id    = Session::get('billing_id');
            $shipping_id   = Session::get('shipping_id');
            $delivery_slot = Session::get('delivery_slot');
            $order_count =  Order::where('customer_id', '=', $customer_id)->count();
            $min_order = Helper::get_site_data()->min_order;
            if($order_count > 0)
            {
                $shipping_fee = Helper::get_site_data()->shipping_fee;
            }else{
                $shipping_fee = 0;
            }

            return view('website.checkout-views.confirm-option',compact('customer_id','billing_id','shipping_id','delivery_slot','shipping_fee','min_order'))->render();
        }
    }

    public function store_delivery_slot(Request $request)
    {
       if($request->ajax()){

            $rules = array();
            $rules['delivery_date']  = 'required';
            $rules['delivery_time']  = 'required';            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            } else {
                $slot = [
                    'delivery_date' => date('Y-m-d',strtotime($request->delivery_date)),
                    'delivery_time' => $request->delivery_time,
                ];
                
                Session::put('delivery_slot',$slot);
                $json['success'] = 'success';
            }
            return Response::json($json);
        }
    }

    public function confirm_order(Request $request)
    {
        if($request->ajax()){

            $order = new Order();

            $customer_id   = Session::get('customer_id');
            $billing_id    = Session::get('billing_id');
            $shipping_id   = Session::get('shipping_id');
            $delivery_slot = Session::get('delivery_slot');
            $coupon        = Session::get('coupon_data');
            $discount_amount  = 0;
            $coupon_discount = 0;
            $code = '';
            if(Session::get('coupon_data')){
                $code = Session::get('coupon_data')['coupon_code'];
                $coupon_discount = Session::get('coupon_data')['coupon_discount'];
                $discount_amount = Cart::total() * ( $coupon_discount/100);
            }
            $min_order = Helper::get_site_data()->min_order;

            $order_count =  Order::where('customer_id', '=', $customer_id)->count();
            if($order_count > 0)
            {
                $shipping_fee = Helper::get_site_data()->shipping_fee;
            }else{
                $shipping_fee = 0;
            }
            $pre_total =  Cart::total() - $discount_amount;
            $total = $pre_total+ $shipping_fee;

            if($pre_total < $min_order )
            {
                $json['error'] = 'Minimum order balance is QAR '.$min_order;
                return Response::json($json);
            }

            $settings_data = Setting::find(1);
            $order_status = 1;//1=confirmed
            $delivery_status = 2;//2=prending
           
           
            $order->customer_id         =  $customer_id;
            $order->billing_address_id  = $billing_id;
            $order->shipping_address_id = $shipping_id;
            $order->total_amount        = number_format($total,2);
            $order->shipping_fee        = $shipping_fee;
            $order->coupon_code         = $code;
            $order->coupon_discount     = $coupon_discount;
            $order->coupon_discount_amount = number_format($discount_amount,2);

            $order->order_status        = $order_status;
            $order->delivery_status     = $delivery_status;
            $order->delivery_date       = $delivery_slot['delivery_date'];
            $order->delivery_time       = $delivery_slot['delivery_time'];
            $order->date                = date('Y-m-d');
            $order->created_at          = DATE;
            $order->updated_at          = DATE;


            
            if($order->save()){
                $invoice_no = $settings_data->invoice_prefix + $order->id;
                $order_update = Order::find($order->id);
                $order_update->invoice_no = $invoice_no;
                $order_update->update();

                $data = [];
                foreach (Cart::content() as $row){
                    $product = ProductPriceDetail::find($row->id);
                    
                    $pname = $this->formatUrl($row->options->slug);//get product name from slug
                    $pid = PRODUCT_SLUG_PREFIX + $product->product_id; //get product id
                    $explode_name = explode($pid,$pname); //explode product id
                    $pname = trim($explode_name[1]); //take only name
                    $data[] = [
                        'order_id' => $order->id,
                        'product_id' => $product->product_id,
                        'product_price_id' => $row->id,
                        'name' => ucwords($pname),
                        'qty' => $row->qty,
                        'price' => number_format($row->price,2),
                        'total' => number_format($row->total,2),
                    ];
                }
                
                OrderHasProducts::insert($data);
                
                $admin_mail_data = ['email' => 'sam.bd89@gmail.com',
                        'receiver' => 'Admin',
                        'subject' => "New Order Placed",
                        'invoice_no' => $invoice_no,
                        ];

                Mail::send('website.email-template.admin', $admin_mail_data, function($message) use ($admin_mail_data){
                    $message->from(config('mail.username'),config('app.name'));
                    $message->to($admin_mail_data['email']);
                    $message->subject($admin_mail_data['subject']);
                });
                $customer_mail_data = ['email' => Auth::guard('customer')->user()->email,
                        'subject' => "Order Confirmed",
                        'invoice_no' => $invoice_no,
                        'subtotal' => Cart::total(),
                        'discount' => $discount_amount,
                        'shipping_fee' =>$shipping_fee,
                        'products' => Cart::content(),
                        'billing_address' => CustomerAddress::find($billing_id),
                        'shipping_address' => CustomerAddress::find($shipping_id),
                        'delivery_date' => $delivery_slot['delivery_date'],
                        'delivery_time' => $delivery_slot['delivery_time'],
                        ];

                Mail::send('website.email-template.customer', $customer_mail_data, function($message) use ($customer_mail_data){
                    $message->from(config('mail.username'),config('app.name'));
                    $message->to($customer_mail_data['email']);
                    $message->subject($customer_mail_data['subject']);
                });
                Cart::destroy();
                Session::forget(['billing_id','shipping_id','delivery_slot','coupon_data']);
                
                return view('website.checkout-views.order-placed',compact('invoice_no'))->render();
            }
            
            
        }
    }

    private function formatUrl($str, $sep=' ')
    {

            $res = strtolower($str);
            $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
            $res = preg_replace('/[[:space:]]+/', $sep, $res);
            return trim($res, $sep);
    }
    

}
