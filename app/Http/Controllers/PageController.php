<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Page;
use App\Models\MenuPermission;
use App\Models\PagePermission;
use Validator;
use Response;
use Auth;
use Illuminate\Support\Facades\Input;
use App\Helpers\Helper;
use DB;


class PageController extends Controller
{
    protected $_table_name = 'pages';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "id desc";

    var $column_order = array('', 'pages.page_name', 'menus.menu_name', '');
    var $column_search = array('page_name', 'menu_id');
    var $order = array('pages.id' => 'DESC');

    protected $rules = array(
        'page_name'      => 'required|unique:pages',
        'menu_id'        => 'required|integer',
    );

    protected $update_rules = array(
        'page_name'      => 'required',
        'menu_id'        => 'required|integer',
    );

    public function index()
    {

        if(in_array('Page List',session()->get('permission'))){
            $page_title = 'Page List';
            $page_icon = 'fa-file';
            $menuList = Menu::select('*')->orderBy('id', 'desc')->get();
            return view('dashboard.page.page-list', compact('page_title','page_icon', 'menuList'));
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }

    public function pageList(Request $request){
        $page = new Page();
        if(!empty($request->page_name)){
            $page->setPageName($request->page_name);
        }
        if(!empty($request->menu_name)){
            $page->setMenuName($request->menu_name);
        }
        
        $page->setSearchValue($request->input('search.regex'));
        $page->setOrderValue($request->input('order.0.column'));
        $page->setDirValue($request->input('order.0.dir'));
        $page->setStartValue($request->input('start'));
        $page->setLengthValue($request->input('length'));     
           
        $list = $page->getPageList();
        // $this->dp($list);
        $edit = $del = $view = $action = $active = '';
        $data = array();
        $no = $request->input('start');
        foreach ($list as $value) {
            $no++;
            $action = '';
            if(in_array('Page Edit',session()->get('permission'))){
                $action .= '<li><a class="edit_page" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
            }
            if(in_array('Page Delete',session()->get('permission'))){
                $action .= '<li><a class="delete_page" data-id="' . $value->id . '" >'.$this->delete_icon.'</a></li>';
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
            $row[]  = $value->menu_name;
            $row[]  = $btngroup;
            $data[] = $row;

        }
        $output = array(
            "draw" => $request->input('draw'),
            "recordsTotal" => $page->count_all(),
            "recordsFiltered" => $page->count_filtered(),
            "data" => $data
        );


        echo json_encode($output);
    }


    public function store(Request $request)
    {
        if(in_array('Page Add',session()->get('permission'))){
            if ($request->ajax()) {

                $validator = Validator::make($request->all(), $this->rules);

                if ($validator->fails()) {

                    $json = array(
                        'errors' => $validator->errors()
                    );

                } else {

                    $data = new Page();
                    $data->page_name       = $request->page_name;
                    $data->menu_id         = $request->menu_id;
                    $data->created_at      = date('Y-m-d H:i:s');
                    $data->updated_at      = date('Y-m-d H:i:s');

                    if ($data->save()) {
                        if(in_array('Permission Add',session()->get('permission'))){
                            session()->push('permission', $request->page_name);
                        }
                        
                        $json['success'] = 'Data is successfully added';
                    }

                }
                return Response::json($json);
            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        if(in_array('Page Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['page'] = Page::find($id);
                return Response::json($json);
            } else {
                return redirect()->back();
            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }


    public function update(Request $request)
    {
        if(in_array('Page Edit',session()->get('permission'))){
            if ($request->ajax()) {

                $validator = Validator::make($request->all(), $this->update_rules);
                if ($validator->fails()) {

                    $json = array(
                        'errors' => $validator->errors()
                    );

                } else {
                    $id = $request->id;
                    $data = Page::find($id);
                    $old_name             = $data->page_name;
                    $data->page_name      = $request->page_name;
                    $data->menu_id        = $request->menu_id;
                    $data->updated_at     = date('Y-m-d H:i:s');
                    $data->update();
                    if ($data) {
                        if(in_array('Permission Add',session()->get('permission'))){
                            // session()->pull('permission', $old_name);
                            $permission = session()->pull('permission', []); // Second argument is a default value
                            if(($value = array_search($old_name, $permission)) !== false) {
                                unset($permission[$value]);
                            }
                            session()->put('permission', $permission);
                            session()->push('permission', $request->page_name);
                        }
                        $json['success'] = 'Data updated successfully';
                    }
                }
                return Response::json($json);
            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }


    public function destroy($id)
    {
        if(in_array('Page Delete',session()->get('permission'))){
            $page_permission_exist_check = PagePermission::where('page_id', $id)->get();

            if (count($page_permission_exist_check) > 0) {
                $json['status'] = 'error';
                $json['message'] = 'This page related with permission. Remove permission at first.';
            } else {
                $page_delete = Page::find($id)->delete();
                if ($page_delete) {
                    $json['status'] = 'success';
                    $json['message'] = 'Page Deleted Successfully ...';
                } else {
                    $json['status'] = 'error';
                    $json['message'] = 'Unable to delete page ...';
                }

            }
            return Response::json($json);
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }


    

}
