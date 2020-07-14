<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use App\Helpers\Helper;
use DB;
use App\Models\User;
use App\Models\MenuPermission;
use App\Models\PagePermission;

class RoleController extends Controller
{

    protected $rules = array(
        'role_name' => 'required|unique:roles'
    );

    public function index()
    {
        if(in_array('Role List',session()->get('permission'))){
            $page_title = 'Role List';
            $page_icon = 'fa-user-cog';
            return view('dashboard.role.role-list', compact('page_title','page_icon'));
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getRoleList(Request $request)
    {
        if($request->ajax()){
            $role = new Role();
        
            $role_name = $request->input('role_name');
            if(!empty($role_name)){
                $role->setRoleName($role_name);
            }

            $role->setSearchValue($request->input('search.regex'));
            $role->setOrderValue($request->input('order.0.column'));
            $role->setDirValue($request->input('order.0.dir'));
            $role->setLengthValue($request->input('length'));
            $role->setStartValue($request->input('start'));

            $list = $role->getRoleList();

            
            $data = array();
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;

                $action = '';
                if(in_array('Role Edit',session()->get('permission'))){
                    $action .= '<li><a class="edit_role" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
                }
                if(in_array('Role Delete',session()->get('permission'))){
                    $action .= '<li><a class="delete_role" data-id="'.$value->id.'" >'.$this->delete_icon.'</a></li>';
                }
                $btngroup = '<div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-outline btn-primary dropdown-toggle"><i class="fas fa-th-list"></i></button>
                                <ul class="dropdown-menu  pull-right">
                                    ' . $action . '
                                </ul>
                            </div>';

                $row    = array();
                $row[]  = $no;
                $row[]  = $value->role_name;
                $row[]  = $btngroup;
                $data[] = $row;

            }
            $output = array(
                "draw" => $request->input('draw'),
                "recordsTotal" => $role->count_all(),
                "recordsFiltered" => $role->count_filtered(),
                "data" => $data
            );


            echo json_encode($output);
        }
        
    }

    public function store(Request $request)
    {
        if(in_array('Role Add',session()->get('permission'))){
            if ($request->ajax()) {

                $validator = Validator::make($request->all(), $this->rules);

                if ($validator->fails()) {

                    $json = array(
                        'errors' => $validator->errors()
                    );

                } else {

                    $data = new Role();
                    $data->role_name = $request->role_name;
                    $data->created_at = date('Y-m-d H:i:s');
                    $data->updated_at = date('Y-m-d H:i:s');
                    if ($data->save()) {
                        $json['success'] = 'Data is successfully added';
                        // return response()->json(['success'=>'Menu is successfully added']);
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
        if(in_array('Role Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['role'] = Role::find($id);
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
        if(in_array('Role Edit',session()->get('permission'))){
            if ($request->ajax()) {

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {

                    $json = array(
                        'errors' => $validator->errors()
                    );

                } else {
                    $id = $request->id;
                    $data = Role::find($id);
                    $data->role_name = $request->role_name;
                    $data->updated_at = date('Y-m-d H:i:s');
                    $data->update();
                    if ($data) {
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
        if(in_array('Role Delete',session()->get('permission'))){
            $user_exist_check = User::where('role_id', $id)->get();
            $menu_permission_exist_check = MenuPermission::where('role_id', $id)->get();
            $page_permission_exist_check = PagePermission::where('role_id', $id)->get();

            if (count($user_exist_check) > 0 || count($menu_permission_exist_check) > 0 || count($page_permission_exist_check) > 0) {
                $json['status'] = 'error';
                $json['message'] = 'Role can\'t delete. It is related with other data. At first delete those data. ';
            } else {
                $role_delete = Role::find($id)->delete();
                if ($role_delete) {
                    $json['status'] = 'success';
                    $json['message'] = 'Role Deleted Successfully ...';
                } else {
                    $json['status'] = 'error';
                    $json['message'] = 'Unable to delete role ...';
                }

            }
            return Response::json($json);
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }


    
}
