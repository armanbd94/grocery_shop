<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use App\Models\ProductUnit;

class ProductUnitController extends Controller
{
    /** THIS ARRAY WORKING AS A VALIDATION RULES **/
    protected $rules = array();

    public function index(){

        if(in_array('Unit List',session()->get('permission'))){
            $page_title = 'Unit List';
            $page_icon = 'fas fa-weight';
            return view('dashboard.product.product-unit', compact('page_title','page_icon','categories'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request){
        if(in_array('Unit List',session()->get('permission'))){
            if($request->ajax()){

                $unit = new ProductUnit();

                if(!empty($request->fname)){
                    $unit->setFullName($request->fname);
                }
                if(!empty($request->sname)){
                    $unit->setShortName($request->sname);
                }
                if(!empty($request->status)){
                    $unit->setStatus($request->status);
                }

                $unit->setSearchValue($request->input('search.regex'));
                $unit->setOrderValue($request->input('order.0.column'));
                $unit->setDirValue($request->input('order.0.dir'));
                $unit->setLengthValue($request->input('length'));
                $unit->setStartValue($request->input('start'));

                $list = $unit->getList();
                
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
                    if(in_array('Unit Edit',session()->get('permission'))){
                        $action .= '<li><a class="edit_data" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                    }
                    if(in_array('Unit Delete',session()->get('permission'))){
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
                    $row[]  = $value->full_name;
                    $row[]  = $value->short_name;
                    if(in_array('Unit Edit',session()->get('permission'))){
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
                $output = $this->dataTableDraw($request->input('draw'), $unit->count_all(),
                                                $unit->count_filtered(),$data);


                echo json_encode($output);
            }
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function store(Request $request){
        if($request->ajax()){
            
            if(in_array('Unit Add',session()->get('permission'))){
                if(!empty($request->id)){
                    $this->rules['full_name']  = 'required|string';  
                    $this->rules['short_name']  = 'required|string';                                       
                }else{   
                    $this->rules['full_name']  = 'required|string|unique:product_units';  
                    $this->rules['short_name']  = 'required|string|unique:product_units';
                }

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {
                    
                    if(empty($request->id)){

                        $data = new ProductUnit();

                        $data->full_name  = $request->full_name;
                        $data->short_name = $request->short_name;            
                        $data->created_at   = DATE;
                        $data->updated_at   = DATE;
                        $data->save();
                        if ($data) {
                            $json['success'] = 'Data has been saved successfully';
                        }else{
                            $json['error']   = 'Data can not save';
                        }
                    }else{
                        $id       = $request->id;
                        $data     = ProductUnit::find($id);
                        $data->full_name  = $request->full_name;
                        $data->short_name = $request->short_name;            
                        $data->updated_at   = DATE;
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

        if(in_array('Unit Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['unit'] = ProductUnit::find($id);
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
        if(in_array('Unit Delete',session()->get('permission'))){

            $unit_related_products = DB::table('products')->where('brand_id',$id)->get();

            $data_delete   = ProductUnit::find($id)->delete();
        
            if ($data_delete) {
                $json['status'] = 'success';
                $json['message'] = 'Data has been deleted successfully.';
            } else {
                $json['status'] = 'error';
                $json['message'] = 'Unable to delete data.';
            }
            
            
            return Response::json($json);
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: DATA DELETE BY THIS METHOD **/

    /** BEGIN :: DELETE MULTIPLE DATA **/

    /** END  :: DELETE MULTIPLE DATA **/

    /** BEGIN:: STATUS CHANGE BY THIS METHOD **/
    public function changeStatus(Request $request){
        if($request->ajax()){
            if(in_array('Unit Edit',session()->get('permission'))){
                $id     = $request->id;
                $status = $request->status;
                if((int)$id && (int)$status){
                    
                    $data = ProductUnit::find($id);

                    $data->status      = $status;  
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
