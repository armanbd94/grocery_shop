<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use App\Models\Setting;
use DateTime;

class TimeSlotController extends Controller
{
    private $id = 1;
    protected $rules = array();

    public function index(){
        // $d = 60;
        // $s = '10:00AM';
        // $e = '10:00PM';
        // $time = $this->getDeliverTimeSlots($d, $s, $e);
        // foreach ($time as $key => $value) {
        //     echo $value['slot'].'<br>';
        // }
        // exit;
        if(in_array('Time Slot List',session()->get('permission'))){
            $page_title = 'Delivery Time Slot';
            $page_icon  = 'far fa-clock';
            $data = Setting::select('time_duration','start_time','end_time','time_slot')->where('id',$this->id)->first();
            $time_slot_list = json_decode($data->time_slot);
            $time_slot = view('dashboard.time-slot.time-slot-list', compact('time_slot_list'))->render();
            return view('dashboard.time-slot.time-slot', compact('page_title','page_icon','data','time_slot'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function store(Request $request){
        if($request->ajax()){

            $this->rules['time_duration']  = 'required';
            $this->rules['start_time']     = 'required';
            $this->rules['end_time']       = 'required';

            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {

                $json = array(
                    'errors' => $validator->errors()
                );

            } else {
                
                if(Setting::find($this->id)){
                    $data = Setting::find($this->id);
                }else{
                    $data = new Setting();
                }
                
                $data->time_duration = $request->time_duration;
                $data->start_time    = $request->start_time;
                $data->end_time      = $request->end_time;
                // if($data->end_time == '11:00PM'){
                //     echo 'ok';exit;
                // }
                $time = $this->getDeliverTimeSlots($data->time_duration, $data->start_time, $data->end_time);
                
                // dd($time);
                $time_slot = [];
                if(!empty($time)){
                    foreach ($time as $key => $value) {
                        //  dd($value['slot']);
                        array_push($time_slot,$value['slot']);
                    }
                }
                $data->time_slot     = json_encode($time_slot);
                if(Setting::find($this->id)){
                    $data->updated_at       = DATE;
                    if ($data->update()) {
                        $json['success'] = 'Data has been saved successfully';
                    }else{
                        $json['error'] = 'Data can not save';
                    }
                }else{
                    $data->created_at       = DATE;
                    $data->updated_at       = DATE;
                    if ($data->save()) {
                        $json['success'] = 'Data has been saved successfully';
                    }else{
                        $json['error'] = 'Data can not save';
                    }
                }

            }
            return Response::json($json);
        }
    }

    public function time_slot_list(Request $request){
        if($request->ajax()){
            $data = Setting::select('time_slot')->where('id',$this->id)->first();
            $time_slot_list = json_decode($data->time_slot);
            return view('dashboard.time-slot.time-slot-list', compact('time_slot_list'))->render();
        }
    }

    function getDeliverTimeSlots($duration, $start,$end)
    {
        
        $start      = new DateTime($start);
        $end        = new DateTime($end);
        $starttime  = $start->format('H:i');  //Start time
        $endtime    = $end->format('H:i');  // End time

        $array_of_time = array ();
        $start_time    = strtotime ($starttime); //change to strtotime
        $end_time      = strtotime ($endtime); //change to strtotime

        $add_mins  = $duration * 60;
        $time = [];
        $i=0;
        while ($start_time <= $end_time) // loop between time
        {
            $i++;
        // $array_of_time[] = date ("H:i", $start_time);
            $startTime = date ("H:i", $start_time);//store start time in a variable
            $start_time += $add_mins; // to check endtime
            $endTime = date ("H:i", $start_time); //store end time in a variable
            if($start_time <= $end_time){
                $time[$i]['start'] = date('H:iA', strtotime($starttime));
                $time[$i]['end'] =  date('H:iA', strtotime($endtime));
                $time[$i]['slot']  = date('h:iA',strtotime($startTime)).' - '.date('h:iA',strtotime($endTime));
            }
        }
        // dd($time);
        // dd($array_of_time);
        return $time;

    }
}
