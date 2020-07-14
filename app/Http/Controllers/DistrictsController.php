<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use Auth;
USE App\Models\District;
class DistrictsController extends Controller
{
    /** THIS ARRAY WORKING AS A VALIDATION RULES **/
    protected $rules = array();

    public function index(){

        if(in_array('District List',session()->get('permission'))){
            $page_title = 'District List';
            $page_icon = 'fas fa-list-ol';
            $categories = District::all();
            return view('dashboard.district.district', compact('page_title','page_icon','categories'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request){
        if($request->ajax()){
            if(in_array('District List',session()->get('permission'))){
                $district = new District();

                if(!empty($request->dname)){
                    $district->setDistrictName($request->dname);
                }

                $district->setSearchValue($request->input('search.regex'));
                $district->setOrderValue($request->input('order.0.column'));
                $district->setDirValue($request->input('order.0.dir'));
                $district->setLengthValue($request->input('length'));
                $district->setStartValue($request->input('start'));

                $list = $district->getList();
                
                $data = array();
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;

                    $action = '';
                    if(in_array('District Edit',session()->get('permission'))){
                        $action .= '<li><a class="edit_data" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                    }

                    if(in_array('District Delete',session()->get('permission'))){
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
                    $row[]  = $value->district_name;
                    $row[]  = $btngroup;
                    $data[] = $row;

                }
                $output = $this->dataTableDraw($request->input('draw'), $district->count_all(),
                                                $district->count_filtered(),$data);


                echo json_encode($output);
            }else{
                return redirect('/error')->with('error','You do not have permission to access this page.');
            }
        }
    }

    public function store(Request $request){
        if($request->ajax()){
            
            if(in_array('District Add',session()->get('permission'))){
                if(!empty($request->id)){
                    $this->rules['district_name']  = 'required|string';  
                }else{   
                    $this->rules['district_name']  = 'required|string|unique:districts';   
                }

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {
                    
                    if(empty($request->id)){

                        $data = new District();

                        $data->district_name   = $this->validate($request->district_name);                              
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
                        $data     = District::find($id);
                        $data->district_name   = $this->validate($request->district_name);                              
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

        if(in_array('District Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['district'] = District::find($id);
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
        if(in_array('District Delete',session()->get('permission'))){

                $data_delete   = District::find($id)->delete();
            
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

