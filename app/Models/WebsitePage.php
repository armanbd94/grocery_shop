<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class WebsitePage extends Model
{
    protected $fillable = [
        'page_name','page_url','page_content','creator_id','modifier_id'
    ];

     /*********DataTable Server Side Begin************/
     protected $_table_name     = 'website_pages';
     protected $_primary_key    = 'id';
     protected $_primary_filter = 'intval';
     protected $_order_by       = "id desc";
 
     var $column_order  = array('id', 'page_name', 'page_url','');
     var $column_search = array('page_name');
     var $order         = array('id' => 'DESC');
 
     private $_pageName;
 
     private $_searchValue;
     private $_orderValue;
     private $_dirValue;
     private $_startValue;
     private $_lengthValue;
 
 
     public function setPageName($pageName)
     {
         $this->_pageName = $pageName;
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
 
         if (!empty($this->_pageName)) {
             $query->where('page_name', 'like','%'.$this->_pageName.'%');
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
         $query = DB::table($this->_table_name)->get();
         return $query->count();
     }
     /*********DataTable Server Side End************/
 
}
