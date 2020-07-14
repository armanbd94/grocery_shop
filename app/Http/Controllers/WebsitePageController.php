<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use App\Models\WebsitePage;

class WebsitePageController extends Controller
{

    protected $rules = array();

    public function index(){

        if(in_array('Website Page List',session()->get('permission'))){
            $page_title = 'Website Page List';
            $page_icon = 'far fa-file-alt';
            
            // dd($categories);
            return view('dashboard.website-page.website-page', compact('page_title','page_icon'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request){
        if($request->ajax()){
            if(in_array('Website Page List',session()->get('permission'))){
                $page = new WebsitePage();

                if(!empty($request->pg_name)){
                    $page->setPageName($request->pg_name);
                }
                
                $page->setSearchValue($request->input('search.regex'));
                $page->setOrderValue($request->input('order.0.column'));
                $page->setDirValue($request->input('order.0.dir'));
                $page->setLengthValue($request->input('length'));
                $page->setStartValue($request->input('start'));

                $list = $page->getList();
                
                $data = array();
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;

                    $action = '';
                    if(in_array('Website Page Edit',session()->get('permission'))){
                        $action .= '<li><a class="edit_data" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                    }

                    if(in_array('Website Page Delete',session()->get('permission'))){
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
                    $row[]  = $value->page_name;
                    $row[]  = $value->page_url;
                    $row[]  = $btngroup;
                    $data[] = $row;

                }
                $output = $this->dataTableDraw($request->input('draw'), $page->count_all(),
                                                $page->count_filtered(),$data);


                echo json_encode($output);
            }else{
                return redirect('/error')->with('error','You do not have permission to access this page.');
            }
        }
    }

    public function store(Request $request){
        if($request->ajax()){
            
            if(in_array('Website Page Add',session()->get('permission'))){
                if(!empty($request->id)){
                    $this->rules['page_name']      = 'required|string';  
                    $this->rules['page_url']       = 'required|max:255';                                       
                    $this->rules['page_content']   = 'required';                                       
                }else{   
                    $this->rules['page_name']     = 'required|string|unique:website_pages';  
                    $this->rules['page_url']      = 'required|max:255|unique:website_pages';
                    $this->rules['page_content']  = 'required';    
                }

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {
                    
                    if(empty($request->id)){

                        $data = new WebsitePage();

                        $data->page_name      = $this->validate($request->page_name);
                        $data->page_url       = $this->validate($request->page_url);
                        $data->page_content   = $request->page_content;
                        $data->creator_id     = auth()->user()->id;             
                        $data->modifier_id    = auth()->user()->id;             
                        $data->created_at     = DATE;
                        $data->updated_at     = DATE;
                        $data->save();
                        if ($data) {
                            $json['success'] = 'Data has been saved successfully';
                        }else{
                            $json['error']   = 'Data can not save';
                        }
                    }else{
                        $id       = $request->id;
                        $data     = WebsitePage::find($id);
                        $data->page_name      = $this->validate($request->page_name);
                        $data->page_url       = $this->validate($request->page_url);
                        $data->page_content   = $request->page_content;       
                        $data->modifier_id    = auth()->user()->id;             
                        $data->updated_at     = DATE;
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

        if(in_array('Website Page Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['page'] = WebsitePage::find($id);
                return Response::json($json);
            } else {
                return redirect()->back();
            }
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }

    }

    /** BEGIN:: DATA DELETE BY THIS METHOD **/
    public function destroy(Request $request){
        if(in_array('Website Page Delete',session()->get('permission'))){

            $id = $request->id;
            $data_delete   = WebsitePage::find($id)->delete();
        
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
    
}
