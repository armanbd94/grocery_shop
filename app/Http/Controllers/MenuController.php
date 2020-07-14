<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Page;
use App\Models\MenuPermission;
use Validator;
use Response;
use DB;
use App\Helpers\Helper;

class MenuController extends Controller
{

    protected $rules = array(
        'menu_name' => 'required',
        'icon'      => 'required',
        'sequence'  => 'required|numeric'
    );

    public function index()
    {
        if(in_array('Menu List',session()->get('permission'))){
            $page_title = 'Menu List';
            $page_icon = 'fa-bars';
            $menuList = Menu::all();
            return view('dashboard.menu.menu-list', compact('page_title','page_icon','menuList'));
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }

    public function menuList(Request $request)
    {
        if($request->ajax()){
            $menu = new Menu();
        
            $menu_name = $request->input('menu_name');
            if(!empty($menu_name)){
                $menu->setMenuName($menu_name);
            }

            $menu->setSearchValue($request->input('search.regex'));
            $menu->setOrderValue($request->input('order.0.column'));
            $menu->setDirValue($request->input('order.0.dir'));
            $menu->setLengthValue($request->input('length'));
            $menu->setStartValue($request->input('start'));

            $list = $menu->getMenuList();

            
            $data = array();
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;

                $action = '';

                if(in_array('Menu Edit',session()->get('permission'))){
                    $action .= '<li><a class="edit_menu" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                }
                if(in_array('Menu Delete',session()->get('permission'))){
                $action .= '<li><a class="delete_menu" data-id="'.$value->id.'" >'.$this->delete_icon.'</a></li>';
                }
                $btngroup = '<div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-outline btn-primary dropdown-toggle"><i class="fas fa-th-list"></i></button>
                                <ul class="dropdown-menu  pull-right">
                                    ' . $action . '
                                </ul>
                            </div>';

                if(!empty($value->parent_id)){
                    $parent = DB::table('menus')->where('id',$value->parent_id)->first();
                    $parent_name = $parent->menu_name;
                }else{
                    $parent_name = '-';
                }
                $row    = array();
                $row[]  = $no;
                $row[]  = $value->menu_name;
                $row[]  = $value->menu_url;
                $row[]  = '<i class="'.$value->icon.'"></i> '.$value->icon;
                $row[]  = $parent_name;
                $row[]  = $value->sequence;
                $row[]  = $btngroup;
                $data[] = $row;

            }
            $output = array(
                "draw" => $request->input('draw'),
                "recordsTotal" => $menu->count_all(),
                "recordsFiltered" => $menu->count_filtered(),
                "data" => $data
            );


            echo json_encode($output);
        }
        
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            if(in_array('Menu Add',session()->get('permission'))){
                $validator = Validator::make($request->all(), $this->rules);

                if ($validator->fails()) {

                    $json = array(
                        'errors' => $validator->errors()
                    );

                } else {

                    $data = new Menu();
                    $data->menu_name  = $request->menu_name;
                    $data->menu_url   = $request->menu_url;
                    $data->icon       = $request->icon;
                    if(empty($request->parent_id)){
                        $data->parent_id = NULL;
                    }else{
                        $data->parent_id  = $request->parent_id;
                    }
                    
                    $data->sequence   = $request->sequence;
                    $data->created_at = date('Y-m-d H:i:s');
                    $data->updated_at = date('Y-m-d H:i:s');
                    if ($data->save()) {
                        $json['success'] = 'Menu is successfully added';
                        // return response()->json(['success'=>'Menu is successfully added']);
                    }

                }
            }else{
                $json['error'] = 'You are unauthorized to access';
            }
            return Response::json($json);
        }

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        if(in_array('Menu Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['menu'] = Menu::find($id);
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
        if(in_array('Menu Edit',session()->get('permission'))){
            if ($request->ajax()) {

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {

                    $json = array(
                        'errors' => $validator->errors()
                    );

                } else {
                    $id = $request->id;
                    $data = Menu::find($id);
                    $data->menu_name  = $request->menu_name;
                    $data->menu_url   = $request->menu_url;
                    $data->icon       = $request->icon;
                    if(empty($request->parent_id)){
                        $data->parent_id = NULL;
                    }else{
                        $data->parent_id  = $request->parent_id;
                    }
                    $data->sequence   = $request->sequence;
                    $data->updated_at = date('Y-m-d H:i:s');
                    $data->update();
                    if ($data) {
                        $json['success'] = 'Menu data updated successfully';
                    }else{
                        $json['error'] = 'Menu data can not update';
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
        if(in_array('Menu Delete',session()->get('permission'))){
            $menu_page_exist_check       = Page::where('menu_id', $id)->get();
            $menu_permission_exist_check = MenuPermission::where('menu_id', $id)->get();
            if (count($menu_page_exist_check) > 0 || count($menu_permission_exist_check) > 0) {
                $json['status']  = 'error';
                $json['message'] = 'Menu can not delete. It is related with other data. At first delete those data. ';
            } else {
                $menu_delete = Menu::find($id)->delete();
                if ($menu_delete) {
                    $json['status']  = 'success';
                    $json['message'] = 'Menu Deleted Successfully ...';
                } else {
                    $json['status']  = 'error';
                    $json['message'] = 'Unable to delete menu ...';
                }

            }
            return Response::json($json);
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
}
