<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    protected $fillable = [
        'role_name', 
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function menuPermission()
    {
        return $this->hasMany('App\Models\MenuPermission');
    }

    /*********DataTable Server Side Begin************/
    protected $_table_name     = 'roles';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order  = array('', 'role_name', '');
    var $column_search = array('role_name');
    var $order         = array('id' => 'DESC');

    private $_roleName;

    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;


    public function setRoleName($roleName)
    {
        $this->_roleName = $roleName;
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

        $query = DB::table('roles')->select('*');

        if (!empty($this->_roleName)) {
            $query->where('role_name', $this->_roleName);
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

    public function getRoleList()
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
        $query = DB::table('roles')->select('*')->get();
        return $query->count();
    }
    /*********DataTable Server Side End************/
}
