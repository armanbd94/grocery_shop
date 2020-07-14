<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use Auth;
use App\Models\Category;

class CategoryController extends Controller
{
    /** THIS ARRAY WORKING AS A VALIDATION RULES **/
    protected $rules = array();

    public function index(){

        if(in_array('Category List',session()->get('permission'))){
            $page_title = 'Category List';
            $page_icon = 'fas fa-list-ol';
            
            // dd($categories);
            return view('dashboard.product.category', compact('page_title','page_icon'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request){
        if($request->ajax()){
            if(in_array('Category List',session()->get('permission'))){
                $category = new Category();

                if(!empty($request->cat_name)){
                    $category->setCategoryName($request->cat_name);
                }
                
                if(!empty($request->parent)){
                    $category->setParentID($request->parent);
                }

                if(!empty($request->status)){
                    $category->setStatus($request->status);
                }


                $category->setSearchValue($request->input('search.regex'));
                $category->setOrderValue($request->input('order.0.column'));
                $category->setDirValue($request->input('order.0.dir'));
                $category->setLengthValue($request->input('length'));
                $category->setStartValue($request->input('start'));

                $list = $category->getList();
                
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
                    if(in_array('Category Edit',session()->get('permission'))){
                        $action .= '<li><a class="edit_data" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                    }

                    if(in_array('Category Delete',session()->get('permission'))){
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
                    $row[]  = $value->category_name;
                    $row[]  = $this->parent_name($value->parent_id);

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
                $output = $this->dataTableDraw($request->input('draw'), $category->count_all(),
                                                $category->count_filtered(),$data);


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
                    $this->rules['category_name']  = 'required|string';  
                    $this->rules['category_slug']   = 'required|max:255';                                       
                }else{   
                    $this->rules['category_name']  = 'required|string|unique:categories';  
                    $this->rules['category_slug']   = 'required|max:255|unique:categories';
                }

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {
                    
                    if(empty($request->id)){

                        $data = new Category();

                        $data->category_name   = $this->validate($request->category_name);
                        $data->category_slug   = $this->validate($request->category_slug);
                        if(!empty($request->parent_id)){
                            $data->parent_id = $request->parent_id;
                        }
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
                        $data     = Category::find($id);
                        $data->category_name   = $this->validate($request->category_name);
                        $data->category_slug   = $this->validate($request->category_slug);
                        if(!empty($request->parent_id)){
                            $data->parent_id = $request->parent_id;
                        }         
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

        if(in_array('Category Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['category'] = Category::find($id);
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
        if(in_array('Category Delete',session()->get('permission'))){

            $category_related_products = DB::table('products')->where('category_id',$id)->get();
            if(count($category_related_products) > 0){
                $json['status']  = 'error';
                $json['message'] = 'This data is related with product table data.';
            }else{
                $data_delete   = Category::find($id)->delete();
            
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
            if(in_array('Category Edit',session()->get('permission'))){
                $id     = $request->id;
                $status = $request->status;
                if((int)$id && (int)$status){
                    
                    $data = Category::find($id);

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

    private function parent_name($parent_id){
        $parent_data = Category::find($parent_id);
        (!empty($parent_data)) ? $parent_name = $parent_data->category_name : $parent_name = '-';
        return $parent_name;
    }

    public function category_list(){
        $categories = Category::all();
        $output = '';
        if(!empty($categories)){
            $output .= '<option value="">Please Select</option>';
            foreach ($categories as $category) {
                $output .= '<option value="'.$category->id.'">'.$category->category_name.'</option>';
            }
        }
        return Response::json($output);
    }


    
}
