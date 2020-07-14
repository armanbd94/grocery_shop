<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Coupon extends Model
{
    protected $fillable = [
        'coupon_name','coupon_code','discount','total','start_date','end_date','uses_total',
        'uses_customer','creator_id','modifier_id'
    ];

     /*********DataTable Server Side Begin************/
     protected $_table_name     = 'coupons';
     protected $_primary_key    = 'id';
     protected $_primary_filter = 'intval';
     protected $_order_by       = "id desc";
 
     var $column_order  = array('id', 'coupon_name', 'coupon_code','discount','total','start_date','end_date','','');
 
     var $column_search = array('coupon_code','status');
     var $order         = array('id' => 'DESC');
 
     private $_couponCode;
     private $_status;
 
     private $_searchValue;
     private $_orderValue;
     private $_dirValue;
     private $_startValue;
     private $_lengthValue;
 
 
     public function setCouponCode($couponCode)
     {
         $this->_couponCode = $couponCode;
     }
 
     public function setStatus($status)
     {
         $this->_status = $status;
     }
 
     //set datatable eliments
     public function setSearchValue($searchValue)
     {
         $this->_searchValue = $searchValue;
     }
     public function setOrderValue($orderValue)
     {
         $this->_orderValue = $orderValue;
     }
     public function setDirValue($dirValue)
     {
         $this->_dirValue = $dirValue;
     }
     public function setLengthValue($lengthValue)
     {
         $this->_lengthValue = $lengthValue;
     }
     public function setStartValue($startValue)
     {
         $this->_startValue = $startValue;
     }
 
 
     private function _get_datatables_query()
     {
 
         $query = DB::table($this->_table_name);
 
         if (!empty($this->_couponCode)) {
             $query->where('coupon_code', 'like','%'.$this->_couponCode.'%');
         }
 
         if (!empty($this->_status)) {
             $query->where('status', $this->_status);
         }
 
         if (isset($this->_orderValue) && isset($this->_dirValue)) // here order processing
         {
             $query->orderBy($this->column_order[$this->_orderValue], $this->_dirValue);
 
         } else if (isset($this->order)) {
 
             $order = $this->order;
             $query->orderBy(key($order), $order[key($order)]);
         }
 
         return $query;
 
     }
 
     public function getList()
     {
         $query = $this->_get_datatables_query();
         if ($this->_lengthValue != -1)
             $query->offset($this->_startValue)->limit($this->_lengthValue);
         return $query = $query->get();
 
     }
 
     public function count_filtered()
     {
         $query = $this->_get_datatables_query();
         $query = $query->get();
         return $query->count();
     }
 
     public function count_all()
     {
         $query = DB::table($this->_table_name)->select('*')->get();
         return $query->count();
     }
     /*********DataTable Server Side End************/
 
}
