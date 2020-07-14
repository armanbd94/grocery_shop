<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ContactMessage extends Model
{
    protected $fillable = [
        'name','email','subject','message','seen_by'
    ];

    /*********DataTable Server Side Begin************/
    protected $_table_name     = 'contact_messages';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order  = array('cm.id', 'cm.name','bc.subject','cm.email','cm.message','cm.status',
    'u.name','cm.created_at','');
    var $column_search = array('created_at');
    var $order         = array('cm.id' => 'DESC');

    private $_fromDate;
    private $_toDate;

    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;

    public function setFromDate($fromDate)
    {
        $this->_fromDate = $fromDate;
    }
    public function setToDate($toDate)
    {
        $this->_toDate = $toDate;
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

        $query = DB::table('contact_messages as cm')
        ->leftjoin('users as u','cm.seen_by','=','u.id')
        ->select('cm.*','u.name as user_name');

        if (!empty($this->_fromDate)) {
            $query->where('cm.created_at', '>=',$this->_fromDate);
        }
        if (!empty($this->_toDate)) {
            $query->where('cm.created_at', '<=',$this->_toDate);
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
        $query = DB::table('contact_messages')->select('*')->get();
        return $query->count();
    }
    /*********DataTable Server Side End************/
}
