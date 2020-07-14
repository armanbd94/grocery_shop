<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Page;
use App\Models\MenuPermission;
use App\Models\PagePermission;
use App\Models\Role;
use Auth;
use App\Helpers\Helper;
use Response;

class PermissionController extends Controller
{


    public function index()
    {
        if(in_array('Permission',session()->get('permission'))){
            $role_list  = Role::where('id','!=',1)->get();
            $page_title = 'Permission';
            $page_icon  = 'fa-user-lock';
            return view('dashboard.permission.permission', compact('page_title', 'page_icon','role_list'));
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }

    public function savePermission(Request $request)
    {
        if ($request->ajax()) {
            if(in_array('Permission Add',session()->get('permission'))){
                $json = array();
                if(!empty($request->menu_id[0])){
                    $role_id = $request->role_id;
                    $date = date('Y-m-d H:i:s');
                    $delete_menu_permission = MenuPermission::where('role_id',$role_id)->delete();
                    $delete_page_permission = PagePermission::where('role_id',$role_id)->delete();
                
                    $add_menu_permnission = array();
                    if(!empty($request->menu_id)){
                        foreach ($request->menu_id as $menuID) {
                            $add_menu_permnission[] = [
                                'menu_id'    => $menuID,
                                'role_id'    => $role_id,
                                'created_at' => $date,
                                'updated_at' => $date
                            ];

                        }
                        MenuPermission::insert($add_menu_permnission);
                    }

                    if (!empty($request->page_id)) {
                        $add_page_permnission = array();
                        foreach ($request->page_id as $pageID) {
                            $add_page_permnission[] = [
                                'page_id' => $pageID,
                                'role_id' => $role_id,
                                'created_at' => $date,
                                'updated_at' => $date
                            ];

                        }
                        PagePermission::insert($add_page_permnission);
                    }
                    $json['success'] = 'Permission Added Successfully';
                    
                }else{
                    $json['error'] = 'Please checked at least one checkbox';
                    
                }
            }else{
                $json['error'] = 'You are unauthorized to add permission';
            }
            return Response::json($json);
        }
    }


    public function permissionList($role_id)
    {
        if(in_array('Permission',session()->get('permission'))){
            $json = array();
            if(!empty($role_id)){
                $menus = Menu::all();
                $permission = array();
                $output = '';
                foreach ($menus as $menu) {
                    $menu_has_permission = MenuPermission::where(['menu_id'=> $menu->id, 'role_id' => $role_id])->first();
                    
                    if($menu_has_permission){
                        $menu_checked = 'checked';
                    }else{
                        $menu_checked = '';
                    }
                    $output .= '<div class="col-12 permission-list">
                                <ul>
                                    <li>
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-brand">
                                        <input type="checkbox" name="menu_id[]" id="menu_id' . $menu->id . '" onclick="permissionChecked('. $menu->id .')" value="' . $menu->id . '" ' . $menu_checked . ' /> ' . $menu->menu_name . '<span></span></label>';
                    $pages = Page::where('menu_id', $menu->id)->get();
                    if(!empty($pages)){
                        foreach ($pages as $page) {
                            $page_has_permission = PagePermission::where(['page_id' => $page->id, 'role_id' => $role_id])->first();
                            
                            if ($page_has_permission) {
                                $page_checked = 'checked';
                            } else {
                                $page_checked = '';
                            }
                            $output .= '<ul>
                                            <li>
                                            <label class="m-checkbox m-checkbox--solid m-checkbox--state-brand">
                                            <input type="checkbox" name="page_id[]" class="page_id menu_id_' . $menu->id . '" value="' . $page->id . '" ' . $page_checked . ' /> ' . $page->page_name . '<span></span></label>
                                            </li>
                                        </ul>';
                        }
                    }
                    

                    $output .= '</li></ul></div>';
                }
                $json['permission'] = $output;
            }else{
                $json['error'] = 'Please select a role';
            }
            
            return Response::json($json);
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }


}
