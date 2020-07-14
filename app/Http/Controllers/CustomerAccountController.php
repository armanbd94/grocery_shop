<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\CustomerAddress AS Address;
use App\Models\Order;
use App\Models\OrderHasProducts AS Orderproduct;
use Validator;
use Response;
use Illuminate\Support\Facades\Hash;
use App\Rules\StrongPassword;
use App\Rules\ValidPhone;
use DB;
use Session;

class CustomerAccountController extends Controller
{
    
    protected $rules = array(
        'first_name' => 'required',
        'last_name' => 'required',
        
    );
    
    protected $address_rules = array(
            'first_name' => 'required', 
            'last_name' => 'required', 
            'district' => 'required', 
            'address' => 'required'
        );
    
    public function index()
    {
        Session::forget(['redirect_auth']);
        $page_title      = 'Profile';
        $breadcrumb_menu = '<a href="' . url("/") . '"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span><strong>Account</strong>';
        return view('website.account.profile', compact('page_title', 'breadcrumb_menu'));
    }
    
    
    public function profileUpdate(Request $request)
    {
        if ($request->ajax()) {
            $this->rules['mobile'] = array('required', new ValidPhone);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                
                $json = array(
                    'errors' => $validator->errors()
                );
                
            } else {
                $id   = Auth::guard('customer')->user()->id;
                $data = Customer::find($id);
                
                // dd($data);
                
                $data->first_name = $this->validate($request->first_name);
                $data->last_name  = $this->validate($request->last_name);
                $data->mobile     = $this->validate($request->mobile);
                $data->update();
                if ($data) {
                    $json['success'] = 'Profile data updated successfully';
                } else {
                    $json['error'] = 'Profile data can not update';
                }
            }
            return Response::json($json);
        }
    }
    
    public function customerAddress()
    {
        $page_title      = 'Address';
        $breadcrumb_menu = '<a href="' . url("/") . '"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span><strong>Account</strong>';
        $addressview = $this->addressList();
        return view('website.account.address', compact('page_title', 'breadcrumb_menu','addressview'));
    }
    
    public function addressList()
    {
        $id              = Auth::guard('customer')->user()->id;
        $addressList     = Address::where(['customer_id'=>$id,'soft_del'=>'2'])->orderBy('is_default','ASC')->get();
        return view('website.account.addresslist', compact('addressList'))->render();
    }

    
    public function addAddress(Request $request)
    {
        if ($request->ajax()) {
            $this->address_rules['mobile_no'] = array('required', new ValidPhone);           
            $validator = Validator::make($request->all(), $this->address_rules);
            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );             
            } else {
                $customer_id = Auth::guard('customer')->user()->id;
                
                if($request->is_default=='1')
                {
                    Address::where('customer_id',$customer_id)->update(['is_default'=>'2']);
                }
                
                $data              = new Address();
                $data->first_name  = $this->validate($request->first_name);
                $data->last_name   = $this->validate($request->last_name);
                $data->customer_id = $customer_id;
                $data->mobile_no   = $this->validate($request->mobile_no);
                $data->city        = 'Doha';
                $data->address     = $this->validate($request->address);
                $data->district    = $this->validate($request->district);
                $data->is_default  = $this->validate($request->is_default);
                if ($data->save()) {
                    $json['success'] = 'Add Address successfully';
                    $json['address'] = $this->addressList();
                } else {
                    $json['error'] = 'Address can not added';
                }
            }
            return Response::json($json);
        }
    }

    public function editAddress(Request $request)
    {
        if($request->ajax())
        {
             $customer_id = Auth::guard('customer')->user()->id;
             $address = Address::where('id',$request->id)->where('customer_id', $customer_id)->first();
             if($address)
             {
                $json['address']=$address;
             }
             return Response::json($json);

        }

    }

    public function updateAddress(Request $request)
    {   
        if ($request->ajax()) {           
            $validator = Validator::make($request->all(), $this->address_rules);
            if ($validator->fails()) {
                
                $json = array(
                    'errors' => $validator->errors()
                );
                
            } else {
                
                    $customer_id = Auth::guard('customer')->user()->id;
                    $count  = Address::where(['customer_id'=>$customer_id,'soft_del'=>2])->count();
                    $data = Address::where('id',$request->id)->where('customer_id', $customer_id)->first();
                    if($request->is_default=='1' && $count > 1 )
                    {
                        Address::where('customer_id',$customer_id)->update(['is_default'=>'2']);
                        $data->is_default  = $this->validate($request->is_default);
                    }else
                    {
                        $data->is_default  = 1;
                        $json['is_default']='';
                    }

                    
                    $data->first_name  = $this->validate($request->first_name);
                    $data->last_name   = $this->validate($request->last_name);
                    $data->mobile_no   = $this->validate($request->mobile_no);
                    $data->city        = 'Doha';
                    $data->address     = $this->validate($request->address);
                    $data->district    = $this->validate($request->district);
                    
                    if ($data->update()) {
                        $json['success'] = 'Add Address successfully update';
                        $json['address'] = $this->addressList();
                    } else {
                        $json['error'] = 'Address can not update';
                    }
            }
            return Response::json($json);
        }

    }
    
    
    public function destroyAddress(Request $request)
    {
        if ($request->ajax()) {
            $customer_id    = Auth::guard('customer')->user()->id;
            $address_delete = Address::where('id',$request->id)->where('customer_id',$customer_id)->update(['soft_del'=>1]);
            if ($address_delete) {
                $json['status']  = 'success';
                $json['address'] = $this->addressList();
                $json['message'] = 'Address Deleted Successfully ...';
            } else {
                $json['status']  = 'error';
                $json['message'] = 'Unable to delete Address ...';
            }
            
        } else {
            $json['status']  = 'error';
            $json['message'] = 'Unable to delete address ...';
        }
        
        return Response::json($json);
        
    }


    public function orderHistory()
    {
        $page_title      = 'Order History';
        $breadcrumb_menu = '<a href="' . url("/") . '"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span><strong>Account</strong>';
        $customer_id    = Auth::guard('customer')->user()->id;

        $orderlist= Order::where(['customer_id'=>$customer_id])->orderBy('invoice_no','DESC')->get();
       // dd($orderlist);
       
        return view('website.account.orderlist', compact('page_title', 'breadcrumb_menu','orderlist'));
    }


    public function orderView($id)
    {
       
       if((int)$id){
        $page_title      = 'Order view #'.$id;
        $breadcrumb_menu = '<a href="' . url("/") . '"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span><strong>Account</strong><a href="' . url("/account/order-history") . '"><span class="mdi mdi-chevron-right"></span><strong>Order History</strong></a>';
        $customer_id    = Auth::guard('customer')->user()->id;
        $order = DB::table('orders as o')
                                ->select('o.*','ba.first_name as bfname','ba.last_name as blname', 'ba.mobile_no as bmobile',
                                'ba.city as bcity', 'ba.district as bdistrict', 'ba.address as baddress',
                                'sa.first_name as sfname','sa.last_name as slname', 'sa.mobile_no as smobile',
                                'sa.city as scity', 'sa.district as sdistrict', 'sa.address as saddress')
                                ->leftjoin('customer_addresses as ba','o.billing_address_id','=','ba.id')
                                ->leftjoin('customer_addresses as sa','o.shipping_address_id','=','sa.id')
                                ->where('o.customer_id',$customer_id)
                                ->where('o.invoice_no',$id)
                                ->first();

         //dd( $order);                       
        //$order= Order::where(['customer_id'=>$customer_id,'invoice_no'=>$id])->first();
        if($order){
            $discount = $order->coupon_discount;
            $shipping_fee =  $order->shipping_fee;
            $order_products = Orderproduct::where(['order_id'=>$order->id])->get();
            $product_list   = view('website.account.productlist',compact('order_products','discount','shipping_fee'))->render();
            return view('website.account.orderview', compact('page_title', 'breadcrumb_menu','order','product_list'));
        }else
        {
            return redirect('/account/order-history')->with('error','Order not exist!');
        }
        
       }
       else{
            return redirect('//account/order-history')->with('error','Order not exist!');
        }
    }


    public function passwordChange()
    {
        $page_title      = 'Password Change';
        $breadcrumb_menu = '<a href="' . url("/") . '"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span><strong>Account</strong>';
        $customer_id    = Auth::guard('customer')->user()->id;

        //$orderlist= Order::where(['customer_id'=>$customer_id])->orderBy('invoice_no','DESC')->get();
       // dd($orderlist);
       
        return view('website.account.password', compact('page_title', 'breadcrumb_menu'));
    }


    public function passwordUpdate(Request $request)
    {
        if($request->ajax()){
            $rules['password']  = ['required','confirmed', new StrongPassword];
            $validator = Validator::make($request->all(), $rules); //validation rules checking

        if ($validator->fails()) {
            $json = array(
                'errors' => $validator->errors()
            );
        } else {
            $customer_id    = Auth::guard('customer')->user()->id;
            $customer = Customer::where('id',$customer_id)->update(['password' => Hash::make($request->password)]);
            if($customer){
                $json['success'] = 'Password has been updated successfully.';
            } else{
                $json['error'] = 'Password can not update.';
               
            }    
        }
        return Response::json($json);
        }
    }
    
}