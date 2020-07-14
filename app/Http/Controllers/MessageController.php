<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use Auth;
use App\Models\ContactMessage;
use App\Helpers\Helper;

class MessageController extends Controller
{
    public function index(){

        if(in_array('Message List',session()->get('permission'))){
            $page_title = 'Message List';
            $page_icon  = 'far fa-envelope';
            return view('dashboard.message.message', compact('page_title','page_icon'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function contact(){
        $page_title = 'Contact';
        return view('website.contact',compact('page_title'));
    }

    public function getList(Request $request){
        if($request->ajax()){
            if(in_array('Message List',session()->get('permission'))){
                $message = new ContactMessage();

                if(!empty($request->from_date)){
                    $from_date = $request->from_date.' 00:00:00';
                    $message->setFromDate($request->from_date);
                }
                if(!empty($request->to_date)){
                    $to_date = $request->to_date.' 23:59:59';
                    $message->setToDate($to_date);
                }

                $message->setSearchValue($request->input('search.regex'));
                $message->setOrderValue($request->input('order.0.column'));
                $message->setDirValue($request->input('order.0.dir'));
                $message->setLengthValue($request->input('length'));
                $message->setStartValue($request->input('start'));

                $list = $message->getList();
                // dd($list);
                $data = array();
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;

                    $action = '';

                    if(in_array('Message View',session()->get('permission'))){
                        $action .= '<li><a href="'.url("/message/view",$value->id).'">'.$this->view_icon.'</a></li>';
                    }
                    if(in_array('Message Delete',session()->get('permission'))){
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
                    $row[]  = $value->name;
                    $row[]  = $value->subject;
                    $row[]  = $value->email;
                    $row[]  = Helper::readMore($value->message,50);
                    if(auth()->user()->role_id == 1){
                        $row[]  = '<span class="m-badge '.json_decode(STATUS_TYPE)[$value->status].'  m-badge--wide">'.json_decode(MSG_STATUS)[$value->status].'</span>';
                        $row[]  = $value->user_name;
                    }
                    $row[]  = Helper::date_time($value->created_at);
                    $row[]  = $btngroup;
                    $data[] = $row;

                }
                $output = $this->dataTableDraw($request->input('draw'), $message->count_all(),
                                                $message->count_filtered(),$data);


                echo json_encode($output);
            }else{
                return redirect('/error')->with('error','You do not have permission to access this page.');
            }
        }
    }

    public function store_contact_message(Request $request)
    {
        if($request->ajax()){
            $rules = [
                'name'    => 'required|string|max:100',
                'email'   => 'required|email',
                'subject' => 'required|string|max:150',
                'message' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            } else {
                $data          = new ContactMessage();
                $data->name    = $this->validate($request->name);
                $data->email   = $this->validate($request->email);
                $data->subject = $this->validate($request->subject);
                $data->message = $this->validate($request->message);
                $data->save();
                if ($data) {
                    $json['success'] = 'Message sent successfully';
                }else{
                    $json['error']   = 'Message can not send. Try again';
                }
            }
            return Response::json($json);
        }
    }

    public function show($id)
    {
        if(!empty($id)){
            if(in_array('Message View',session()->get('permission'))){
                $data = ContactMessage::find($id);

                if(!empty($data)){
                        $page_title       = 'Message View';
                        $page_icon        = 'far fa-envelope';
                        if($data->status == 2){
                            $data->status  = 1;
                            $data->seen_by = auth()->user()->id;
                            $data->update();
                        }
                        
                        $message = DB::table('contact_messages as cm')
                                        ->leftjoin('users as u','cm.seen_by','=','u.id')
                                        ->select('cm.*','u.name as user_name')
                                        ->where('cm.id',$id)
                                        ->first();
                        return view('dashboard.message.message-view', compact('message','page_title','page_icon'));        
                }else{
                    return redirect('error')->with('error','Unauthorized Access blocked!');
                }
            }else{
                return redirect('error')->with('error','You do not have permission to access this page.');
            } 
        }else{
            return redirect('/error')->with('error','Unauthorized Access blocked!');
        }
    }
    

     /** BEGIN:: DATA DELETE BY THIS METHOD **/
    public function destroy(Request $request){
        if(in_array('Message Delete',session()->get('permission'))){
            $id = $request->id;
            $data_delete = ContactMessage::find($id)->delete();
            if ($data_delete) {
                $json['status']  = 'success';
                $json['message'] = 'Data Deleted Successfully ...';
            } else {
                $json['status']  = 'error';
                $json['message'] = 'Unable to delete data ...';
            }
            return Response::json($json);
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: DATA DELETE BY THIS METHOD **/
}
