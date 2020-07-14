<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use Auth;
use App\Models\Coupon;

class CouponController extends Controller
{
     /** THIS ARRAY WORKING AS A VALIDATION RULES **/
     protected $rules = array();

     public function index(){
 
         if(in_array('Coupon List',session()->get('permission'))){
             $page_title = 'Coupon List';
             $page_icon = 'fas fa-list-ol';
             
             // dd($categories);
             return view('dashboard.coupon.coupon', compact('page_title','page_icon'));
         }else{
             return redirect('/error')->with('error','You do not have permission to access this page.');
         }
     }
 
     public function getList(Request $request){
         if($request->ajax()){
             if(in_array('Coupon List',session()->get('permission'))){
                 $coupon = new Coupon();
 
                 if(!empty($request->code)){
                    $coupon->setCouponCode($request->code);
                 }
                 
                 if(!empty($request->status)){
                     $coupon->setStatus($request->status);
                 }
 
 
                 $coupon->setSearchValue($request->input('search.regex'));
                 $coupon->setOrderValue($request->input('order.0.column'));
                 $coupon->setDirValue($request->input('order.0.dir'));
                 $coupon->setLengthValue($request->input('length'));
                 $coupon->setStartValue($request->input('start'));
 
                 $list = $coupon->getList();
                 
                 $data = array();
                 $no = $request->input('start');
                 foreach ($list as $value) {
                     $no++;
                     if($value->status == 1){
                         $status = 'checked';
                     }else{
                         $status = '';
                     }
 
                     $action = '';
                     if(in_array('Coupon Edit',session()->get('permission'))){
                         $action .= '<li><a class="edit_data" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                     }
 
                     if(in_array('Coupon Delete',session()->get('permission'))){
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
                     $row[]  = $value->coupon_name;
                     $row[]  = $value->coupon_code;
                     $row[]  = $value->discount.'%';
                     $row[]  = 'QAR '.number_format($value->total,2);
                     $row[]  = date('F j, Y',strtotime($value->start_date));
                     $row[]  = date('F j, Y',strtotime($value->end_date));
                    //  $row[]  = $value->uses_total;
                    //  $row[]  = $value->uses_customer;
 
                     if(in_array('Category Edit',session()->get('permission'))){
                     $row[]  = '<span class="m-switch m-switch--icon m-switch--primary">
                                     <label>
                                         <input type="checkbox" class="change_status" data-id="' . $value->id . '" name="status" '.$status.'>
                                         <span></span>
                                     </label>
                                 </span>';
                     }
                     $row[]  = $btngroup;
                     $data[] = $row;
 
                 }
                 $output = $this->dataTableDraw($request->input('draw'), $coupon->count_all(),
                                                 $coupon->count_filtered(),$data);
 
 
                 echo json_encode($output);
             }else{
                 return redirect('/error')->with('error','You do not have permission to access this page.');
             }
         }
     }
 
     public function store(Request $request){
         if($request->ajax()){
             
             if(in_array('Category Add',session()->get('permission'))){
                 if(!empty($request->id)){
                     $this->rules['coupon_name']   = 'required|string';  
                     $this->rules['coupon_code']   = 'required|max:255';                                       
                 }else{   
                     $this->rules['coupon_name']   = 'required|string|unique:coupons';  
                     $this->rules['coupon_code']   = 'required|max:255|unique:coupons';
                 }
                 $this->rules['discount']       = 'required|numeric|min:1';
                 $this->rules['total']          = 'required|numeric|min:1';
                 $this->rules['start_date']     = 'required';
                 $this->rules['end_date']       = 'required';
                //  $this->rules['uses_total']     = 'required|numeric|min:1';
 
                 $validator = Validator::make($request->all(), $this->rules);
                 if ($validator->fails()) {
                     $json = array(
                         'errors' => $validator->errors()
                     );
                 } else {
                     
                     if(empty($request->id)){
 
                         $data = new Coupon();
 
                         $data->coupon_name     = $request->coupon_name;
                         $data->coupon_code     = $request->coupon_code;
                         $data->discount        = $request->discount;
                         $data->total           = $request->total;
                         $data->start_date      = $request->start_date;
                         $data->end_date        = $request->end_date;
                        //  $data->uses_total      = $request->uses_total;
                         $data->status          = 2;
                         $data->creator_id      = auth()->user()->id;             
                         $data->modifier_id     = auth()->user()->id;             
                         $data->created_at      = DATE;
                         $data->updated_at      = DATE;
                         $data->save();
                         if ($data) {
                             $json['success'] = 'Data has been saved successfully';
                         }else{
                             $json['error']   = 'Data can not save';
                         }
                     }else{
                         $id       = $request->id;
                         $data     = Coupon::find($id);
                         $data->coupon_name     = $request->coupon_name;
                         $data->coupon_code     = $request->coupon_code;
                         $data->discount        = $request->discount;
                         $data->total           = $request->total;
                         $data->start_date      = $request->start_date;
                         $data->end_date        = $request->end_date;
                        //  $data->uses_total      = $request->uses_total;
                         $data->modifier_id     = auth()->user()->id;             
                         $data->updated_at      = DATE;
                         $data->update();
                         if ($data) {
                             $json['success'] = 'Data has been updated successfully';
                         }else{
                             $json['error']   = 'Data can not update';
                         }
                     }
                     
                 }
                 return Response::json($json);
             }else{
                 return redirect('/error')->with('error','You do not have permission to access this page.');
             }
         }
     }
     
     public function edit($id)
     {
 
         if(in_array('Coupon Edit',session()->get('permission'))){
             $json = array();
             if ((int)$id) {
                 $json['coupon'] = Coupon::find($id);
                 return Response::json($json);
             } else {
                 return redirect()->back();
             }
         }else{
             return redirect('/error')->with('error','You do not have permission to access this page.');
         }
 
     }
 
      /** BEGIN:: DATA DELETE BY THIS METHOD **/
     public function destroy($id){
         if(in_array('Coupon Delete',session()->get('permission'))){
 
            $data_delete   = Coupon::find($id)->delete();
            
            if ($data_delete) {
                    $json['status'] = 'success';
                    $json['message'] = 'Data has been deleted successfully.';
            }else{
                    $json['status'] = 'error';
                    $json['message'] = 'Unable to delete data.';
            }
            return Response::json($json);
         }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
         }
     }
     /** END:: DATA DELETE BY THIS METHOD **/
 
 
     /** BEGIN:: STATUS CHANGE BY THIS METHOD **/
     public function changeStatus(Request $request){
         if($request->ajax()){
             if(in_array('Coupon Edit',session()->get('permission'))){
                 $id     = $request->id;
                 $status = $request->status;
                 if((int)$id && (int)$status){
                     
                     $data = Coupon::find($id);
 
                     $data->status      = $status;
                     $data->modifier_id = auth()->user()->id;    
                     $data->updated_at  = DATE;
                     if($data->update()){
                         $json['success'] = 'Status has been changed successfully';
                     }else{
                         $json['error'] = 'Status can not change';
                     }
                     
                 }else{
                     $json['error'] = 'Status can not change';
                 }
                 return Response::json($json);
             }else{
                 return redirect('/error')->with('error','You do not have permission to access this page.');
             }
 
         }
     }
     /** END:: STATUS CHANGE BY THIS METHOD **/
 
}
