<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    protected $fillable = [
        'category_name', 'category_slug', 'parent_id','creator_id','modifier_id'
    ];

    public function product()
    {
        return $this->hasMany('App\Models\Product');
    }

    /*********DataTable Server Side Begin************/
    protected $_table_name     = 'categories';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order  = array('id', 'c.category_name', 'c.parent_id','','');

    var $column_search = array('category_name','status');
    var $order         = array('id' => 'DESC');

    private $_categoryName;
    private $_parentID;
    private $_status;

    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;


    public function setCategoryName($categoryName)
    {
        $this->_categoryName = $categoryName;
    }

    public function setParentID($parentID)
    {
        $this->_parentID = $parentID;
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

        $query = DB::table($this->_table_name.' as c')
        ->join('users as creator','c.creator_id','=','creator.id')
        ->join('users as modifier','c.modifier_id','=','modifier.id')
        ->select('c.*','creator.name','modifier.name');

        if (!empty($this->_categoryName)) {
            $query->where('c.category_name', 'like','%'.$this->_categoryName.'%');
        }

        if (!empty($this->_parentID)) {
            $query->where('c.parent_id', $this->_parentID);
        }

        if (!empty($this->_status)) {
            $query->where('c.status', $this->_status);
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

    public static function categories($array = NULL){
        $self = new static; //create an object to access none static property
        $query = DB::table($self->_table_name)->select('*');
        if(!empty($array)){
            $query = $query->where($array);
        }
        return $query->get();
    }
}
