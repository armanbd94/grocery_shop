<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class District extends Model
{
    protected $fillable = ['district_name'];


     /*********DataTable Server Side Begin************/
     protected $_table_name     = 'districts';
     protected $_primary_key    = 'id';
     protected $_primary_filter = 'intval';
     protected $_order_by       = "id desc";
 
     var $column_order  = array('id', 'b.district_name', '','');
 
     var $column_search = array('district_name','status');
     var $order         = array('id' => 'DESC');
 
     private $_districtName;
     
 
     private $_searchValue;
     private $_orderValue;
     private $_dirValue;
     private $_startValue;
     private $_lengthValue;
 
 
     public function setDistrictName($districtName)
     {
         $this->_districtName = $districtName;
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
 
         $query = DB::table($this->_table_name.' as b')
         ->select('b.*');
 
         if (!empty($this->_brandName)) {
             $query->where('b.district_name', 'like','%'.$this->_districtName.'%');
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



}
