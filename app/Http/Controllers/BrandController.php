<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use Auth;
USE App\Models\Brand;
class BrandController extends Controller
{
    /** THIS ARRAY WORKING AS A VALIDATION RULES **/
    protected $rules = array();

    public function index(){

        if(in_array('Brand List',session()->get('permission'))){
            $page_title = 'Brand List';
            $page_icon = 'fas fa-list-ol';
            $categories = Brand::all();
            return view('dashboard.product.brand', compact('page_title','page_icon','categories'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request){
        if($request->ajax()){
            if(in_array('Brand List',session()->get('permission'))){
                $brand = new Brand();

                if(!empty($request->bname)){
                    $brand->setBrandName($request->bname);
                }
                
                if(!empty($request->status)){
                    $brand->setStatus($request->status);
                }


                $brand->setSearchValue($request->input('search.regex'));
                $brand->setOrderValue($request->input('order.0.column'));
                $brand->setDirValue($request->input('order.0.dir'));
                $brand->setLengthValue($request->input('length'));
                $brand->setStartValue($request->input('start'));

                $list = $brand->getList();
                
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
                    if(in_array('Brand Edit',session()->get('permission'))){
                        $action .= '<li><a class="edit_data" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                    }

                    if(in_array('Brand Delete',session()->get('permission'))){
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
                    $row[]  = $value->brand_name;

                    if(in_array('Brand Edit',session()->get('permission'))){
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
                $output = $this->dataTableDraw($request->input('draw'), $brand->count_all(),
                                                $brand->count_filtered(),$data);


                echo json_encode($output);
            }else{
                return redirect('/error')->with('error','You do not have permission to access this page.');
            }
        }
    }

    public function store(Request $request){
        if($request->ajax()){
            
            if(in_array('Brand Add',session()->get('permission'))){
                if(!empty($request->id)){
                    $this->rules['brand_name']  = 'required|string';  
                    $this->rules['brand_slug']  = 'required|max:255';                                       
                }else{   
                    $this->rules['brand_name']  = 'required|string|unique:brands';  
                    $this->rules['brand_slug']  = 'required|max:255|unique:brands';
                }

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {
                    
                    if(empty($request->id)){

                        $data = new Brand();

                        $data->brand_name   = $this->validate($request->brand_name);
                        $data->brand_slug   = $this->validate($request->brand_slug);
                        $data->creator_id   = auth()->user()->id;             
                        $data->modifier_id  = auth()->user()->id;             
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
                        $data     = Brand::find($id);
                        $data->brand_name   = $this->validate($request->brand_name);
                        $data->brand_slug   = $this->validate($request->brand_slug);       
                        $data->modifier_id  = auth()->user()->id;             
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

        if(in_array('Brand Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['brand'] = Brand::find($id);
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
        if(in_array('Brand Delete',session()->get('permission'))){

            $brand_related_products = DB::table('products')->where('brand_id',$id)->get();
            if(count($brand_related_products) > 0){
                $json['status']  = 'error';
                $json['message'] = 'This data is related with product table data.';
            }else{
                $data_delete   = Brand::find($id)->delete();
            
                if ($data_delete) {
                    $json['status'] = 'success';
                    $json['message'] = 'Data has been deleted successfully.';
                } else {
                    $json['status'] = 'error';
                    $json['message'] = 'Unable to delete data.';
                }
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
            if(in_array('Brand Edit',session()->get('permission'))){
                $id     = $request->id;
                $status = $request->status;
                if((int)$id && (int)$status){
                    
                    $data = Brand::find($id);

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
