<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use App\Models\Order;
use App\Models\OrderHasProducts;

class OrderController extends Controller
{
    protected $rules = array();
    public function index()
    {

        if(in_array('Order List',session()->get('permission'))){

            $page_title = 'Order List'; // page title value
            $page_icon  = 'fab fa-opencart'; // page icon value
            
            return view('dashboard.order.order', compact('page_title','page_icon'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request)
    {
        if(in_array('Order List',session()->get('permission'))){
            if($request->ajax()){

                $order = new Order();

                if(!empty($request->order_from_date)){
                    $order->setOrderFromDate($request->order_from_date);
                }
                if(!empty($request->order_to_date)){
                    $order->setOrderToDate($request->order_to_date);
                }
                if(!empty($request->invoice_no)){
                    $order->setInvoiceNo($request->invoice_no);
                }
                if(!empty($request->mobile_no)){
                    $order->setMobileNo($request->mobile_no);
                }
                if(!empty($request->delivery_from_date)){
                    $order->setDeliveryFromDate($request->delivery_from_date);
                }
                if(!empty($request->delivery_to_date)){
                    $order->setDeliveryToDate($request->delivery_to_date);
                }
                if(!empty($request->delivery_time)){
                    $order->setDeliveryDTime($request->delivery_time);
                }
                if(!empty($request->order_status)){
                    $order->setOrderStatus($request->order_status);
                }
                if(!empty($request->delivery_status)){
                    $order->setDeliveryStatus($request->delivery_status);
                }

                $order->setSearchValue($request->input('search.regex'));
                $order->setOrderValue($request->input('order.0.column'));
                $order->setDirValue($request->input('order.0.dir'));
                $order->setLengthValue($request->input('length'));
                $order->setStartValue($request->input('start'));

                $list = $order->getList();
                
                $data = array();
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;

                    $action = $delivery_status = '';
                    if($value->order_status == 1){
                        if($value->delivery_status == 2){
                            $delivery_status = 'Pending';
                        }else{
                            $delivery_status = 'Delivered';
                        }
                        if(in_array('Order Edit',session()->get('permission'))){
                            $action .= '<li><a class="change_status" data-type="order" data-status="2" data-id="'.$value->id.'" ><i class="fas fa-ban text-warning"></i> Cancel</a></li>';
                        }
                    }else{
                        if(in_array('Order Edit',session()->get('permission'))){
                            $action .= '<li><a class="change_status" data-type="order" data-status="1" data-id="'.$value->id.'" ><i class="fas fa-check-circle text-primary"></i> Confirmed</a></li>';
                        }
                         $delivery_status = 'Canceled';
                    }

                    
                    if($value->order_status == 1){
                        if($value->delivery_status == 2){
                            if(in_array('Order Edit',session()->get('permission'))){
                                $action .= '<li><a class="change_status" data-type="delivery" data-status="1" data-id="'.$value->id.'" ><i class="fas fa-truck"></i> Delivered</a></li>';
                            }
                        }else{
                            if(in_array('Order Edit',session()->get('permission'))){
                                $action .= '<li><a class="change_status" data-type="delivery" data-status="2" data-id="'.$value->id.'" ><i class="fas fa-times"></i><i class="fas fa-truck"></i> Not Delivered</a></li>';
                            }
                        }
                    }
                    if(in_array('Order View',session()->get('permission'))){
                        $action .= '<li><a href="'.url("/order/view",$value->invoice_no).'">'.$this->view_icon.'</a></li>';
                    }
                    if(in_array('Order Delete',session()->get('permission'))){
                        $action .= '<li><a class="delete_data" data-id="'.$value->id.'" >'.$this->delete_icon.'</a></li>';
                    }
                    $btngroup = '<div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-outline btn-primary dropdown-toggle"><i class="fas fa-th-list"></i></button>
                                    <ul class="dropdown-menu  pull-right">
                                        ' . $action . '
                                    </ul>
                                </div>';

                    $row    = array();
                    $row[]  = $no;
                    $row[]  = $value->invoice_no;
                    $row[]  = '<span class="m-badge '.json_decode(STATUS_TYPE)[$value->seen_status].'  m-badge--wide">'.json_decode(MSG_STATUS)[$value->seen_status].'</span>';
                    $row[]  = $value->full_name;
                    $row[]  = '974'.$value->mobile;
                    $row[]  = 'QAR '.number_format($value->total_amount,2);
                    $row[]  = 'QAR '.number_format($value->shipping_fee ,2);
                    $row[]  = date('d M, Y',strtotime($value->created_at));
                    $row[]  = '<span class="m-badge m-badge--wide '.json_decode(STATUS_TYPE)[$value->order_status].' font-weight-bold">'.json_decode(ORDER_STATUS)[$value->order_status].'</span>';
                    $row[]  = date('d M, Y',strtotime($value->delivery_date));
                    $row[]  = $value->delivery_time;
                    $row[]  = '<span class="m-badge m-badge--wide '.json_decode(STATUS_TYPE)[$value->delivery_status].' font-weight-bold">'.$delivery_status.'</span>';
                    $row[]  = $btngroup;
                    $data[] = $row;

                }
                $output = $this->dataTableDraw($request->input('draw'), $order->count_all(),
                                                $order->count_filtered(),$data);


                echo json_encode($output);
            }
        }
    }



    public function show($id)
    {
        if(in_array('Product View',session()->get('permission'))){
            if((int)$id){
                $page_title = 'Order Details'; // page title value
                $page_icon  = 'fab fa-opencart'; // page icon value
                $product_variation = '';
                $order_data = DB::table('orders as o')
                                ->select('o.*','ba.first_name as bfname','ba.last_name as blname', 'ba.mobile_no as bmobile',
                                'ba.city as bcity', 'ba.district as bdistrict', 'ba.address as baddress',
                                'sa.first_name as sfname','sa.last_name as slname', 'sa.mobile_no as smobile',
                                'sa.city as scity', 'sa.district as sdistrict', 'sa.address as saddress')
                                ->leftjoin('customer_addresses as ba','o.billing_address_id','=','ba.id')
                                ->leftjoin('customer_addresses as sa','o.shipping_address_id','=','sa.id')
                                ->where('o.invoice_no',$id)
                                ->first();
                if($order_data){
                    $data = Order::find($order_data->id);
                    if($data->seen_status == 2){
                        $data->seen_status  = 1;
                        $data->update();
                    }
                    $code = $order_data->coupon_code;
                    $discount = $order_data->coupon_discount;
                    $shipping_fee = $order_data->shipping_fee;
                    $order_products = OrderHasProducts::where(['order_id'=>$order_data->id])->orderBy('id','asc')->get();
                    $product_list   = view('dashboard.order.product-list',compact('order_products','code','discount','shipping_fee'))->render();
                    return view('dashboard.order.order-view', compact('page_title','page_icon','order_data','product_list'));
                }else{
                    return redirect('/error')->with('error','Order not exist!');
                }
            }else{
                return redirect('/error')->with('error','Undefined value rejected.');
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
        
    }

    public function get_ordered_product_list(Request $request){
        if($request->ajax()){
            $order_id = $request->order_id;
            $code = $request->coupon_code;
            $discount = $request->discount;
            $order_products = OrderHasProducts::where(['order_id'=>$order_id])->orderBy('id','asc')->get();
            return view('dashboard.order.product-list',compact('order_products','code','discount'))->render();
        }
    }

    public function destroy(Request $request){
        if(in_array('Order Delete',session()->get('permission'))){
            if($request->ajax()){
                $id = $request->id;
                $order_has_product = OrderHasProducts::where('order_id',$id)->delete();
                if($order_has_product){
                    $order_delete = Order::find($id)->delete();
                    if ($order_delete) {
                        $json['status'] = 'success';
                        $json['message'] = 'Order data has been deleted successfully.';
                    } else {
                        $json['status'] = 'error';
                        $json['message'] = 'Unable to delete data.';
                    }
                }else{
                    $json['status'] = 'error';
                    $json['message'] = 'Unable to delete data.';
                }
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function change_status(Request $request)
    {
       if(in_array('Order Edit',session()->get('permission'))){
            if($request->ajax()){
                $id = $request->id;
                $status = $request->status;
                $type = $request->type;
                // dd($status);
                if($type == 'order'){
                    $change_status = Order::where('id',$id)->update(['order_status' => $status]);
                }elseif ($type == 'delivery') {
                    $change_status = Order::where('id',$id)->update(['delivery_status' => $status]);
                }
                
                if($change_status){
                    $json['status'] = 'success';
                    $json['message'] = 'Status has been changed successfully.';
                    
                }else{
                    $json['status'] = 'error';
                    $json['message'] = 'Unable to change status.';
                }
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
    public function change_product_availibilty_status(Request $request)
    {
        if(in_array('Order Edit',session()->get('permission'))){
            if($request->ajax()){
                $id = $request->id;
                $status = $request->status;
               
                $change_status = OrderHasProducts::find($id);
                $order_id = $change_status->order_id;
                $change_status->available = $status;
                $status_updated = $change_status->update();

                if($status_updated){
                    
                    $order_has_product = OrderHasProducts::where('order_id',$order_id)->get();
                    $product_total_price = [];
                    $unavailable_product_total_price = [];
                    if(!empty($order_has_product)){
                        foreach ($order_has_product as $item) {
                            if ($item->available == 2){
                                array_push($unavailable_product_total_price,$item->total);
                            }
                            array_push($product_total_price,$item->total);
                        }
                    }
                    $update_order_total_price = Order::find($order_id);
                    $total_amount    = array_sum($product_total_price);
                    $total_price     = $total_amount -  array_sum($unavailable_product_total_price);
                    $discount_amount = 0;
                    if($update_order_total_price->coupon_discount > 0){
                        $discount_amount = $total_price * ($update_order_total_price->coupon_discount/100);
                    }
                    $update_order_total_price->coupon_discount_amount = $discount_amount;
                    $update_order_total_price->total_amount    = number_format(($total_price - $discount_amount),2);
                    $updated = $update_order_total_price->update();
                    if($updated){
                        $json['status'] = 'success';
                        $json['message'] = 'Status has been changed successfully.';
                    }else{
                        $json['status'] = 'error';
                        $json['message'] = 'order can not update';
                    }  
                }else{
                    $json['status'] = 'error';
                    $json['message'] = 'Unable to change status.';
                }
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function update_ordered_product_qty(Request $request)
    {
        if(in_array('Order Edit',session()->get('permission'))){
            if($request->ajax()){
                $id = $request->id;
                $qty = $request->quantity;
                if(!empty($id) && !empty($qty)){
                    $ordered_product = OrderHasProducts::find($id);
                    
                    $old_total_price = $ordered_product->total;
                    $new_total_price = $request->quantity * $ordered_product->price;

                    $ordered_product->qty =  $request->quantity;
                    $ordered_product->total =  number_format($new_total_price,2);
                    if($ordered_product->update()){
                        $order_id = $ordered_product->order_id;
                        // $order_data = Order::find($order_id);
                        // $old_total_amount = $order_data->total_amount;
                        // $new_total_amount = ($old_total_amount - $old_total_price) + $new_total_price;
                        // $order_data->total_amount = number_format($new_total_amount,3);
                        $order_has_product = OrderHasProducts::where('order_id',$order_id)->get();
                        $product_total_price = [];
                        $unavailable_product_total_price = [];
                        if(!empty($order_has_product)){
                            foreach ($order_has_product as $item) {
                                if ($item->available == 2){
                                    array_push($unavailable_product_total_price,$item->total);
                                }
                                array_push($product_total_price,$item->total);
                            }
                        }
                        $update_order_total_price = Order::find($order_id);
                        $total_amount    = array_sum($product_total_price);
                        $total_price     = $total_amount -  array_sum($unavailable_product_total_price);
                        $discount_amount = 0;
                        if($update_order_total_price->coupon_discount > 0){
                            $discount_amount = $total_price * ($update_order_total_price->coupon_discount/100);
                        }
                        $update_order_total_price->coupon_discount_amount = $discount_amount;
                        $update_order_total_price->total_amount    = number_format(($total_price - $discount_amount),2);
                        $updated = $update_order_total_price->update();
                        
                        if($updated){
                            $json['success'] = 'Data has been changed successfully.';
                        }else{
                            $json['error'] = 'Data can not update';
                        }
                    }else{
                        $json['error'] = 'Data can not update';
                    }

                }else{

                    $json['error'] = 'Data can not update';
                }
                

                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
}
