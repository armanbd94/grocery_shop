<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use App\Helpers\Helper;

class CustomerController extends Controller {

    public function index() {
        if (in_array('Customer List', session()->get('permission'))) {
            $page_title = 'Customer List';
            $page_icon = 'fa-bars';
            $customerList = Customer::all();

            return view('dashboard.customer.customer-list', compact('page_title', 'page_icon', 'customerList'));
        } else {
            return redirect('/error')->with('error', 'You do not have permission to access this page.');
        }
    }

    public function customerList(Request $request) {
        if ($request->ajax()) {
            $customer = new Customer();

            $customer_name = $request->input('customer_name');
            $customer_email = $request->input('email');
            $customer_mobile = $request->input('mobile');

            if (!empty($customer_name)) {
                $customer->setCustomerName($customer_name);
            }
            if (!empty($customer_email)) {
                $customer->setCustomerEmail($customer_email);
            }
            if (!empty($customer_mobile)) {
                $customer->setCustomerMobile($customer_mobile);
            }

            $customer->setSearchValue($request->input('search.regex'));
            $customer->setOrderValue($request->input('order.0.column'));
            $customer->setDirValue($request->input('order.0.dir'));
            $customer->setLengthValue($request->input('length'));
            $customer->setStartValue($request->input('start'));

            $list = $customer->getCustomerList();

            // dd($list);
            $data = array();
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;

                if ($value->status == 1) {
                    $status = 'checked';
                } else {
                    $status = '';
                }

                $action = '';
                /*
                  if(in_array('Customer Edit',session()->get('permission'))){
                  $action .= '<li><a class="edit_customer" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                  }

                
                  if(in_array('Customer Delete',session()->get('permission'))){
                  $action .= '<li><a class="delete_data" data-id="'.$value->id.'" >'.$this->delete_icon.'</a></li>';
                  }
                  */ 
                

                $status_btn = '<div class="m-form__group form-group row">
                                <div class="col-3">
                                    <span class="m-switch m-switch--icon m-switch--primary">
                                        <label>
                                            <input type="checkbox" class="change_status" data-id="' . $value->id . '" name="is_active" ' . $status . '>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>';

                if (in_array('Customer View', session()->get('permission'))) {
                    $action .= '<li><a class="view_customer" data-id="' . $value->id . '" >' . $this->view_icon . '</a></li>';
                }
                $btngroup = '<div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-outline btn-primary dropdown-toggle"><i class="fas fa-th-list"></i></button>
                                <ul class="dropdown-menu  pull-right">
                                    ' . $action . '
                                </ul>
                            </div>';
                $row = array();
                $row[]  = '<input type="checkbox" name="id[]" value="' . $value->id . '" class="form-control delete_customer">';
                $row[] = $no;
                $row[] = $value->first_name . ' ' . $value->last_name;
                $row[] = $value->email;
                $row[] = $value->mobile;          
                $row[] = $status_btn;
                $row[] = $btngroup;
                $data[] = $row;
            }
            $output = array(
                "draw" => $request->input('draw'),
                "recordsTotal" => $customer->count_all(),
                "recordsFiltered" => $customer->count_filtered(),
                "data" => $data
            );


            echo json_encode($output);
        }
    }
    
     /** BEGIN:: USER ACCOUNT LOGIN STATUS CHANGE BY THIS METHOD **/
    public function changeCustomerStatus(Request $request){
        if(in_array('Customer Edit',session()->get('permission'))){
            if($request->ajax()){
                $id = $request->id;
                $status = $request->status;
                if((int)$id && (int)$status){
                    $customer = Customer::find($id);
                    $customer->status = $status;
                    if($customer->update()){
                        $json['success'] = 'Customer status changed successfully';
                    }else{
                        $json['error'] = 'Customer status can not change';
                    }
                    
                }else{
                    $json['error'] = 'Customer status can not change';
                }
                return Response::json($json);

            }
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: CUSTOMER ACCOUNT LOGIN STATUS CHANGE BY THIS METHOD **/
    
/** BEGIN:: USER DATA VIEW BY THIS METHOD **/
public function show($id){
    if(in_array('Customer View',session()->get('permission'))){
        $json = array();
        if ((int)$id) {
            $data = Customer::find($id);
            $address =CustomerAddress::where('customer_id','=',$id)->get();
            $output = '';
            $output .= '<div class="row">
                            <div class="col-12">
                                <div class="m-card-user m-card-user--skin-dark">
                                    <div class="m-card-user__pic">
                                       
                                    </div>
                                    <div class="m-card-user__details">
                                        <span class="m-card-user__name m--font-weight-500" style="color:black;">
                                            <b>'.$data->first_name.' '.$data->last_name.'</b>
                                        </span>
                                        <a class="m-card-user__email m--font-weight-300 m-link" style="color:#666;">    
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 user-details">
                                <b>Email: </b>'.$data->email.'<br>
                                <b>Mobile Number: </b>'.$data->mobile.'<br>';
                                
            if(in_array('Customer Edit',session()->get('permission'))){
                $output .=      '<b>Status: </b>'.json_decode(ACCOUNT_STATUS)[$data->status].'<br>';
            }
            $output .=          '<b>Created At: </b>'.date("d M Y", strtotime($data->created_at)).'<br>';
            
            if(!empty($address)){
                foreach($address as $adr):

                $output .=      '<b>Address: </b>'.$adr->address_one .', '.$adr->district.', '.$adr->city.', Qatar<br>';


            endforeach;
            }
            
            
            $output .=      '</div>
                        </div>';
            $json['customer'] = $output;
            return Response::json($json);
        } else {
            return redirect()->back();
        }
    }else{
        return redirect('/error')->with('error','You do not have permission to access this page.');
    }
}
/** END:: USER DATA VIEW BY THIS METHOD **/

public function destroy($id)
{
    if(in_array('Customer Delete',session()->get('permission'))){
        $customer_exist_check       = Customer::where('id', $id)->get();
        $customer_address_exist_check = CustomerAddress::where('customer_id', $id)->get();

        $c_address = DB::table('customer_addresses')->where('customer_id', '=', $id)->delete();
        $customer_delete = Customer::find($id)->delete();
        
        if ($customer_delete) {
            $json['status']  = 'success';
            $json['message'] = 'Customer Deleted Successfully ...';
        } else {
            $json['status']  = 'error';
            $json['message'] = 'Unable to delete Customer ...';
        }

        return Response::json($json);
    }else{
        return redirect('/error')->with('error','You do not have permission to access this page.');
    }
}
public function destroy_all(Request $request)
	{
        if($request->ajax()){


        }
        else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }    
        
        
        
        
        
        
        
        
        
        
        
        
        if (!$this->input->is_ajax_request()) {
					$this->data["page"] = "error";
					$this->load->view('_layout_error', $this->data);
			}
			if(in_array('Customer Delete',session()->get('permission'))){
					$json   = array();
					$id = $this->input->post('id');
					if(!empty($id)){
							if(count($id) > 0){
									for($count = 0; $count < count($id); $count++)
									{
											$this->customer->delete_data($id[$count]);
											// echo $id[$count];
									}
									$json['success'] = "Successfully Delete!";
							}else{
									$json['error'] = "Please clicked at least one checkbox!";
							}
					}
					
			}else{
					$json['error'] = "You are not authorized to delete data!";
			}
			echo json_encode($json);
			die();
	}


    

}
