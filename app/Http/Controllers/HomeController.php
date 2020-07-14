<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Helpers\Helper;

class HomeController extends Controller
{

    public function index()
    {
        if(in_array('Dashboard',session()->get('permission'))){
            $page_title = 'Dashboard';
            // $order_data = DB::select("SELECT * FROM orders WHERE order_status='1' AND delivery_status='2' AND (created_at BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND CURDATE())");
            // dd($order_data);
            $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  (`date` BETWEEN DATE_FORMAT(CURDATE() ,'%Y-01-01') AND CURDATE())");
            $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE (`date` BETWEEN DATE_FORMAT(CURDATE() ,'%Y-01-01') AND CURDATE())");
            $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND (`date` BETWEEN DATE_FORMAT(CURDATE() ,'%Y-01-01') AND CURDATE())");
            return view('dashboard.home.home',compact('page_title','total_new_order','total_order','total_sales'));
        }else{
           return redirect('/error')->with('error','You do not have permission to access this page.');
        }
        
    }

    public function get_dashboard_data(Request $request)
    {
        if($request->ajax()){
            $range      = $request->range;
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date   = date('Y-m-d',strtotime($request->end_date));
            
            
            // dd($range .'<br>'.$start_date.'<br>'.$end_date);
            //'Today' = 1;'Yesterday' = 2;'Last 7 Days' = 3;'Last 30 Days' = 4;'This Month' = 5;'Last Month' = 6;'Custom Range' = 7;
            if($range == 1){
                $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  `date`=CURDATE()");
                $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE `date`=CURDATE()");
                $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND `date`=CURDATE()");
            }elseif ($range == 2) {
                $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  `date` = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
                $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE `date` = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
                $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND `date` = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
                
            }elseif ($range == 3) {
                $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  (`date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE())");
                $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE (`date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE())");
                $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND (`date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE())");
            }elseif ($range == 4) {
                $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  (`date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE())");
                $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE (`date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE())");
                $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND (`date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE())");
            }elseif ($range == 5) {
                $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  `date` BETWEEN DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE()");
                $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE `date` BETWEEN DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE()");
                $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND `date` BETWEEN DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE()");
                // exit;
            }elseif ($range == 6) {

                $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  YEAR(`date`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(`date`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)");
                $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE YEAR(`date`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(`date`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)");
                $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND YEAR(`date`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(`date`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)");
                
            }elseif ($range == 7) {
                $total_new_order = DB::select("SELECT COUNT(*) AS total_new_order FROM orders WHERE order_status='1' AND delivery_status='2' AND  (`date` >= '".$start_date."' AND `date` <= '".$end_date."')");
                $total_order     = DB::select("SELECT COUNT(*) AS total_order FROM orders WHERE (`date` >= '".$start_date."' AND `date` <= '".$end_date."')");
                $total_sales     = DB::select("SELECT SUM(total_amount) AS total_sale FROM orders WHERE order_status='1' AND delivery_status='1' AND (`date` >= '".$start_date."' AND `date` <= '".$end_date."')");
                
            }
            
            return view('dashboard.home.summary',compact('total_new_order','total_order','total_sales'))->render();

        }
    }

    public function get_notification($id)
    {
        if(in_array('Message View',session()->get('permission')) || 
            in_array('Order View',session()->get('permission'))
        ){
            if(!empty($id)){
                $output = [];
                $msg_notification = DB::table('contact_messages')->where('status',2)->orderBy('id','desc')->get();
                $order_notification = DB::table('orders as o')
                ->select('o.*','c.first_name','c.last_name')
                ->leftjoin('customers as c','o.customer_id','=','c.id')
                ->where('o.seen_status',2)
                ->orderBy('o.id','desc')
                ->get();
                if(!empty($msg_notification)){
                    if(count($msg_notification) > 0){
                        $total_msg = count($msg_notification);
                    }else{
                        $total_msg = 0;
                    }
                }else {
                    $total_msg = 0;
                }
                if(!empty($order_notification)){
                    if(count($order_notification) > 0){
                        $total_order = count($order_notification);
                    }else {
                        $total_order = 0;
                    } 
                }else {
                    $total_order = 0;
                } 
                if(in_array('Message View',session()->get('permission')) &&
                    in_array('Order View',session()->get('permission'))
                ){
                    $total_notification = $total_msg + $total_order;
                }elseif (in_array('Message View',session()->get('permission')) &&
                    !in_array('Comment View',session()->get('permission'))) {
                    $total_notification = $total_msg;
                }elseif (!in_array('Message View',session()->get('permission')) &&
                    in_array('Comment View',session()->get('permission'))) {
                    $total_notification = $total_order;
                }
                $notification_list = '';
                if($id == 1){                    
                    if($total_msg > 0){                        
                        foreach ($msg_notification as $value) {
                            $notification_list .= '<div class="m-list-timeline__item">
                                                        <span class="m-list-timeline__badge m-list-timeline__badge--info"></span>
                                                        <a href="'.url("/message/view",$value->id).'" class="m-list-timeline__text">
                                                            <b>'.$value->name.'</b><br>'.Helper::readMore($value->message,50).'
                                                        </a>
                                                        <span class="m-list-timeline__time">
                                                            '.Carbon::parse($value->created_at)->diffForHumans().'
                                                        </span>
                                                    </div>';
                        }
                    }
                    
                }elseif ($id == 2) {
                    if($total_order > 0){                        
                        foreach ($order_notification as $value) {
                            $notification_list .= '<div class="m-list-timeline__item">
                                                        <span class="m-list-timeline__badge m-list-timeline__badge--info"></span>
                                                        <a href="'.url("/order/view",$value->invoice_no).'" class="m-list-timeline__text">
                                                            <b>INVOICE NO. #'.$value->invoice_no.'</b><br>'.$value->first_name.' '.$value->last_name.'
                                                        </a>
                                                        <span class="m-list-timeline__time">
                                                            '.Carbon::parse($value->created_at)->diffForHumans().'
                                                        </span>
                                                    </div>';
                        }
                    }
                }
                $output = [
                   'total_notification' => $total_notification,
                   'total_msg'          => $total_msg,
                   'total_order'        => $total_order,
                   'notification_list'  => $notification_list
                ];

                echo json_encode($output);
            }
        }else{
           return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    //composer require nesbot/carbon [to install carbon for diffForHuman]

    public function error(){
        $page_title = 'Error';
        $page_icon = 'fas fa-exclamation-triangle';
        return view('dashboard.error',compact('page_title','page_icon'));
    }

}
