<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Auth;
use Validator;
use Response;
use DB;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** BEGIN:: THIS ARRAY WORKED AS A VALIDATION RULES DURING USER UPDATE THEIR OWN DATA OR PASSWORD **/
    protected $rules = array(
        'name'      => 'required|string|max:255',
        'mobile_no' => 'required|numeric',
        'gender'    => 'required|numeric',
        'password'  => 'required|string|min:6|confirmed',
    );
    /** END:: THIS ARRAY WORKED AS A VALIDATION RULES DURING USER UPDATE THEIR OWN DATA OR PASSWORD **/

    /** BEGIN:: THIS ARRAY WORKED AS A VALIDATION RULES DURING UPDATE USER DATA**/
    protected $user_update_rules = array(
        'name'      => 'required|string|max:255',
        'mobile_no' => 'required|numeric',
        'gender'    => 'required|numeric',
        'role_id'   => 'required|numeric',
    );
    /** END:: THIS ARRAY WORKED AS A VALIDATION RULES DURING UPDATE USER DATA**/

    /** BEGIN:: USER LIST PAGE SHOWED BY THIS METHOD **/
    public function index()
    {
        if(in_array('User List',session()->get('permission'))){
            $page_title = 'User List';
            $page_icon  = 'fa-users';
            $roles      = Role::all();
            return view('dashboard.user.user-list', compact('page_title','page_icon','roles'));
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: USER LIST PAGE SHOWED BY THIS METHOD **/

    /** BEGIN:: ALL USER DATA LIST FETCH BY THIS METHOD **/

    public function userList(Request $request)
    {

        $user = new User();
        if(!empty($request->name)){
            $user->setName($request->name);
        }
        if(!empty($request->email)){
            $user->setEmail($request->email);
        }
        if(!empty($request->mobile_no)){
            $user->setMobile($request->mobile_no);
        }
        if(!empty($request->role_id)){
            $user->setRole($request->role_id);
        }
        if(!empty($request->gender)){
            $user->setGender($request->gender);
        }
        if(!empty($request->is_active)){
            $user->setStatus($request->is_active);
        }
        
        $user->setSearchValue($request->input('search.regex'));
        $user->setOrderValue($request->input('order.0.column'));
        $user->setDirValue($request->input('order.0.dir'));
        $user->setStartValue($request->input('start'));
        $user->setLengthValue($request->input('length'));     
           
        $list = $user->getUserList();
        // $this->dp($list);
        $edit = $del = $view = $action = $active = '';
        $data = array();
        $no = $request->input('start');
        foreach ($list as $value) {
            $no++;
            if($value->is_active == 1  && $value->email_verified_at  != NULL){
                $status = 'checked';
            }else{
                $status = '';
            }
            if(!empty($value->photo)){
                $photo = '<img src="'.asset(USER_PROFILE_PHOTO.$value->photo).'" class="m--img-rounded m--marginless m--img-centered user-img" alt="'.$value->name.'" />';
            }else{
                $photo = "<img src='".asset(json_decode(DEFAULT_PROFILE_IMAGE)[$value->gender])."' class='m--img-rounded m--marginless m--img-centered user-img' alt='".$value->name."'/>";
            }
            if(!empty($value->additional_mobile_no)){
                $mobile_no = $value->mobile_no.', '.$value->additional_mobile_no;
            }else{
                $mobile_no = $value->mobile_no;
            }
            $action = '';

            if(in_array('User Edit',session()->get('permission'))){
                $action .= '<li><a class="edit_user" data-id="' . $value->id . '" >'.$this->edit_icon.'</a></li>';
            }
            if(in_array('User View',session()->get('permission'))){
                $action .= '<li><a class="view_user" data-id="' . $value->id . '" >'.$this->view_icon.'</a></li>';
            }
            if(in_array('User Delete',session()->get('permission'))){
                $action .= '<li><a class="delete_user" data-id="' . $value->id . '" >'.$this->delete_icon.'</a></li>';
            }
            if(in_array('User Edit',session()->get('permission'))){
                $action .= '<li><a class="change_password" data-id="' . $value->id . '" ><i class="fas fa-unlock-alt m--font-accent"></i>&nbsp;Change Password</a></li>';
            }

            $btngroup = '<div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-outline btn-primary dropdown-toggle"><i class="fas fa-th-list"></i></button>
                                <ul class="dropdown-menu" style="min-width: 180px;">
                                    ' . $action . '
                                </ul>
                            </div>';

            $row    = array();
            $row[]  = $no;
            $row[]  = $photo;
            $row[]  = $value->name;
            $row[]  = $value->email;
            $row[]  = $mobile_no;
            $row[]  = json_decode(GENDER)[$value->gender];
            $row[]  = $value->role_name;
            if(in_array('User Edit',session()->get('permission'))){
                $row[]  = '<div class="m-form__group form-group row">
                                <div class="col-3">
                                    <span class="m-switch m-switch--icon m-switch--primary">
                                        <label>
                                            <input type="checkbox" class="change_status" data-id="' . $value->id . '" name="is_active" '.$status.'>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>';
            }
            

            $row[]  = $btngroup;
            $data[] = $row;

        }
        $output = array(
            "draw" => $request->input('draw'),
            "recordsTotal" => $user->count_all(),
            "recordsFiltered" => $user->count_filtered(),
            "data" => $data
        );


        echo json_encode($output);
    }
    /** END:: ALL USER DATA LIST FETCH BY THIS METHOD **/

    /** BEGIN:: TO UPDATE SPECIFIC USER DATA FETCH BY THIS METHOD **/
    public function edit($id){
        if(in_array('User Edit',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $json['user'] = User::find($id);
                return Response::json($json);
            } else {
                return redirect()->back();
            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: TO UPDATE SPECIFIC USER DATA FETCH BY THIS METHOD **/

    /** BEGIN:: USER DATA UPDATE BY THIS METHOD **/
    public function update(Request $request){
        if(in_array('User Edit',session()->get('permission'))){
            if($request->ajax()){

                $validator = Validator::make($request->all(), $this->user_update_rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {
                    $id                           = $request->id;
                    $userdata                         = User::find($id);
                    if(!empty($request->photo)){
                        $user_photo    = $userdata->photo;
                        $target_dir    = USER_PROFILE_PHOTO;
                        $userdata->photo = $this->upload_image($request->file('photo'),$user_photo,$target_dir);
                    }
                    $userdata->name                   = $request->name;
                    $userdata->mobile_no              = $request->mobile_no;
                    $userdata->additional_mobile_no   = $request->additional_mobile_no;
                    $userdata->gender                 = $request->gender;
                    $userdata->role_id                = $request->role_id;
                    $userdata->address                = $request->address;               
                    $userdata->updated_at             = date('Y-m-d H:i:s');
                    $userdata->update();
                    if ($userdata) {
                        $json['success'] = 'User data updated successfully';
                    }else{
                        $json['error']   = 'User data can not update';
                    }
                }
                return Response::json($json);
            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: USER DATA UPDATE BY THIS METHOD **/

    /** BEGIN:: USER ACCOUNT LOGIN STATUS CHANGE BY THIS METHOD **/
    public function changeUserStatus(Request $request){
        if(in_array('User Edit',session()->get('permission'))){
            if($request->ajax()){
                $id = $request->id;
                $status = $request->status;
                if((int)$id && (int)$status){
                    $user = User::find($id);
                    if(empty($user->email_verified_at)){
                        if($status == 1){
                            $user->email_verified_at = date('Y-m-d H:i:s');
                        }
                    }
                    $user->is_active = $status;
                    if($user->update()){
                        $json['success'] = 'User status changed successfully';
                    }else{
                        $json['error'] = 'User status can not change';
                    }
                    
                }else{
                    $json['error'] = 'User status can not change';
                }
                return Response::json($json);

            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: USER ACCOUNT LOGIN STATUS CHANGE BY THIS METHOD **/

    /** BEGIN:: USER PASSWORD CHANGE BY THIS METHOD **/
    public function changeUserPassword(Request $request)
    {
        if(in_array('User Edit',session()->get('permission'))){
            if ($request->ajax()) {
                unset($this->rules['name']);
                unset($this->rules['mobile_no']);
                unset($this->rules['gender']);
                $validator = Validator::make($request->all(), $this->rules);

                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                }else {
                    $id               = $request->id;
                    $data             = User::find($id);
                    $data->password   = Hash::make($request->password);              
                    $data->updated_at = date('Y-m-d H:i:s');
                    $data->update();
                    if ($data) {
                        $json['success'] = 'Password updated successfully';
                    }
                }
                return Response::json($json);
            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: USER PASSWORD CHANGE BY THIS METHOD **/

    /** BEGIN:: USER DATA VIEW BY THIS METHOD **/
    public function show($id){
        if(in_array('User View',session()->get('permission'))){
            $json = array();
            if ((int)$id) {
                $data = User::with('role')->where('users.id',$id)->first();
                if(!empty($data->photo)){
                    $photo = '<img src="'.asset(USER_PROFILE_PHOTO.$data->photo).'" class="m--marginless m--img-centered user-img" alt="'.$data->name.'" />';
                }else{
                    $photo = "<img src='".asset(json_decode(DEFAULT_PROFILE_IMAGE)[$data->gender])."' class='m--marginless m--img-centered user-img' alt='".$data->name."'/>";
                }
                if(!empty($data->additional_mobile_no)){
                    $mobile_no = $data->mobile_no.', '.$data->additional_mobile_no;
                }else{
                    $mobile_no = $data->mobile_no;
                }
                $output = '';
                $output .= '<div class="row">
                                <div class="col-12">
                                    <div class="m-card-user m-card-user--skin-dark">
                                        <div class="m-card-user__pic">
                                            '.$photo.'
                                        </div>
                                        <div class="m-card-user__details">
                                            <span class="m-card-user__name m--font-weight-500" style="color:black;">
                                                <b>'.$data->name.'</b>
                                            </span>
                                            <a class="m-card-user__email m--font-weight-300 m-link" style="color:#666;">
                                                
                                                <b>'.$data->role->role_name.'</b>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 user-details">
                                    <b>Email: </b>'.$data->email.'<br>
                                    <b>Mobile Number: </b>'.$mobile_no.'<br>
                                    <b>Gender: </b>'.json_decode(GENDER)[$data->gender].'<br>';
                if(in_array('User Edit',session()->get('permission'))){
                    $output .=      '<b>Status: </b>'.json_decode(ACCOUNT_STATUS)[$data->is_active].'<br>';
                }
                $output .=          '<b>Created At: </b>'.date("d M Y", strtotime($data->created_at)).'<br>';
                
                if(!empty($data->address)){
                    $output .=      '<b>Address: </b>'.$data->address.'<br>';
                }
                
                
                $output .=      '</div>
                            </div>';
                $json['user'] = $output;
                return Response::json($json);
            } else {
                return redirect()->back();
            }
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: USER DATA VIEW BY THIS METHOD **/

    /** BEGIN:: USER DATA DELETE BY THIS METHOD **/
    public function delete($id){
        if(in_array('User Delete',session()->get('permission'))){
            $user_delete   = User::find($id);
            $target_dir    = USER_PROFILE_PHOTO;
            if(!empty($user_delete->photo)){
                unlink($target_dir.$user_delete->photo);
            }
            if ($user_delete->delete()) {
                $json['status'] = 'success';
                $json['message'] = 'User Deleted Successfully ...';
            } else {
                $json['status'] = 'error';
                $json['message'] = 'Unable to delete user ...';
            }
            
            return Response::json($json);
        }else{
            return redirect('error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: USER DATA DELETE BY THIS METHOD **/

    /** BEGIN:: USER PROFILE PAGE SHOWED BY THIS METHOD **/
    public function profile()
    {

        $page_title = 'My Profile';
        $page_icon  = 'fa-user-tie';
        return view('dashboard.user.profile',compact('page_title','page_icon'));
    }
    /** END:: USER PROFILE PAGE SHOWED BY THIS METHOD **/

    /** BEGIN:: USER CAN UPDATE OWN PROFILE DATA BY THIS METHOD **/
    public function updateProfile(Request $request)
    {
        if ($request->ajax()) {
            unset($this->rules['password']);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            } else {
                $id                           = auth()->user()->id;
                $data                         = User::find($id);
                $data->name                   = $request->name;
                $data->mobile_no              = $request->mobile_no;
                $data->additional_mobile_no   = $request->additional_mobile_no;
                $data->gender                 = $request->gender;
                $data->address                = $request->address;               
                $data->updated_at             = date('Y-m-d H:i:s');
                $data->update();
                if ($data) {
                    $json['success'] = 'Profile updated successfully';
                }
            }
            return Response::json($json);
        }
    }
    /** END:: USER CAN UPDATE OWN PROFILE DATA BY THIS METHOD **/

    /** BEGIN:: USER CAN CHANGE THEIR PASSWORD BY THIS METHOD **/
    public function updatePassword(Request $request)
    {
        if ($request->ajax()) {
            unset($this->rules['name']);
            unset($this->rules['mobile_no']);
            unset($this->rules['gender']);
            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            }else {
                $id               = auth()->user()->id;
                $data             = User::find($id);
                $data->password   = Hash::make($request->password);              
                $data->updated_at = date('Y-m-d H:i:s');
                $data->update();
                if ($data) {
                    $json['success'] = 'Password updated successfully';
                }
            }
            return Response::json($json);
        }
    }
    /** END:: USER CAN CHANGE THEIR PASSWORD BY THIS METHOD **/

    /** BEGIN:: USER CAN CHANGE THEIR PROFILE PHOTO BY THIS METHOD **/
    public function addProfilePhoto(Request $request){
        if ($request->ajax()) {
            if(!empty($request->image)){

                $target_dir    = USER_PROFILE_PHOTO;
                $image_array_1 = explode(";", $request->image);
                $image_array_2 = explode(",", $image_array_1[1]);
                $data          = base64_decode($image_array_2[1]);
                $imageName     = auth()->user()->id.'_'.time(). '.png';
                $target_file   = $target_dir.$imageName;  

                $id                     = auth()->user()->id;
                $user_photo             = User::find($id);
                $user_photo->photo      = $imageName;    
                $user_photo->updated_at = date('Y-m-d H:i:s');          
                $user_photo->update();
                if ($user_photo) {
                    if(!empty(auth()->user()->photo)){
                        unlink($target_dir.auth()->user()->photo);
                    }
                    file_put_contents($target_file, $data);
                    $json['success'] = 'Profile photo updated successfully';
                }else{
                    $json['error']   = 'Profile photo can not updated';
                }
            }else{
                $json['error']   = 'Please select an image';
            }
            return Response::json($json);
        }
        
    }
    /** END:: USER CAN CHANGE THEIR PROFILE PHOTO BY THIS METHOD **/
       
}
