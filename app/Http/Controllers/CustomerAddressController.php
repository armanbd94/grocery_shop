<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\Models\CustomerAddress;
use Validator;
use Response;
use App\Rules\ValidPhone;
use App\Models\Customer;

class CustomerAddressController extends Controller
{
    public function billing_option_view(Request $request)
    {
        if($request->ajax()){
            $customer_id = Session::get('customer_id');
            $billing_id  = Session::get('billing_id');
            $shipping_id  = Session::get('shipping_id');
            $existing_address = CustomerAddress::where(['customer_id'=>$customer_id,'soft_del'=>2])->get();
            return view('website.checkout-views.billing-form',compact('existing_address','billing_id','shipping_id'))->render();
        }
    }

    public function delivery_option_view(Request $request)
    {
        if($request->ajax()){
            $customer_id = Session::get('customer_id');
            $shipping_id  = Session::get('shipping_id');
            $existing_address = CustomerAddress::where(['customer_id'=>$customer_id,'soft_del'=>2])->get();
            return view('website.checkout-views.shipping-form',compact('existing_address','shipping_id'))->render();
        }
    }

    public function store_address_details(Request $request){
        if($request->ajax()){
            $customer_id = Session::get('customer_id');
            $rules = array();
            if($request->payment_address == 'existing' && $request->address_type == 'billing'){
                $rules['address_id'] = 'required|numeric';
            }elseif($request->shipping_address == 'existing' && $request->address_type == 'shipping'){
                $rules['address_id'] = 'required|numeric';
            }else{
                $rules['first_name']  = 'required|string';
                $rules['last_name']   = 'required|string';
                $rules['mobile_no']   = array('required', new ValidPhone);
                $rules['district']    = 'required|string';
                $rules['address']     = 'required|string';
            }
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            } else {
                if(($request->payment_address == 'existing') || $request->shipping_address == 'existing'){
                    $address_id = $request->address_id;
                    if((!empty($address_id)) && ($request->address_type == 'billing')){
                        Session::put('billing_id',$address_id);
                        if($request->same_address == 1){
                            Session::put('shipping_id',$address_id);
                            $json['same_address'] = 'yes';
                        }else{
                            $json['same_address'] = 'no';
                        }
                    }elseif ((!empty($address_id)) && ($request->address_type == 'shipping')) {
                        Session::put('shipping_id',$address_id);
                    }
                    
                }else{

                    $address = new CustomerAddress(); 
                    $address->customer_id   = $customer_id;
                    $address->first_name    = $this->validate($request->first_name);
                    $address->last_name     = $this->validate($request->last_name);
                    $address->mobile_no     = $this->validate($request->mobile_no);
                    $address->address       = $this->validate($request->address);
                    $address->city          = 'Doha';
                    $address->district      = $this->validate($request->district);
                    $address->created_at    = DATE;
                    $address->updated_at    = DATE;

                    
                    if($address->save()){

                        if($request->address_type == 'billing'){
                            Session::put('billing_id',$address->id);
                            if($request->same_address == 1){
                                Session::put('shipping_id',$address->id);
                                $json['same_address'] = 'yes';
                            }else{
                                $json['same_address'] = 'no';
                            }
                        }elseif ($request->address_type == 'shipping') {
                            Session::put('shipping_id',$address->id);
                        }

                        $customer_info = Customer::find($customer_id);
                        if(empty($customer_info->mobile)){
                            Customer::where('id',$customer_id)->update(['mobile'=> $request->mobile_no]);
                        }
                        
                    }
                }
                $json['success'] = 'success';
            }
            return Response::json($json);
        }
    }
}
