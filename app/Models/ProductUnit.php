<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProductUnit extends Model
{
    protected $fillable = [
        'full_name', 'short_name', 
    ];

     /*********DataTable Server Side Begin************/
    protected $_table_name     = 'product_units';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order  = array('id', 'full_name', 'short_name', '', '');
    var $column_search = array('full_name', 'short_name','status');
    var $order         = array('id' => 'DESC');

    private $_fullName;
    private $_shortName;
    private $_status;

    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;


    public function setFullName($fullName)
    {
        $this->_fullName = $fullName;
    }
    public function setShortName($shortName)
    {
        $this->_shortName = $shortName;
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

        $query = DB::table($this->_table_name)->select('*');

        if(!empty($this->_fullName)){
            $query->where('full_name', 'like','%'.$this->_fullName.'%');
        }

        if(!empty($this->_shortName)){
            $query->where('short_name', 'like','%'.$this->_shortName.'%');
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
        $query = $this->_get_datatables_query()->get()->count();
        return $query;
    }

    public function count_all()
    {
        $query = DB::table($this->_table_name)->select('*')->get()->count();
        return $query;
    }
    /*********DataTable Server Side End************/

    public static function units($array = NULL){
        $self = new static; //create an object to access none static property
        $query = DB::table($self->_table_name)->select('*');
        if(!empty($array)){
            $query = $query->where($array);
        }
        return $query->get();
    }
}
