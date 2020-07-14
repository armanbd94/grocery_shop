<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Page extends Model
{
    protected $fillable = [
        'menu_id', 'page_name', 
    ];

    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }

    public function page_permission()
    {
        return $this->hasMany('App\Models\PagePermission');
    }

    /*********************/
    protected $_table_name     = 'pages';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order  = array('', 'pages.page_name', 'menus.menu_name', '');
    var $column_search = array('page_name', 'menu_id');
    var $order         = array('pages.id' => 'DESC');

    private $_pageName;
    private $_menuName;

    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;


    public function setPageName($pageName)
    {
        $this->_pageName = $pageName;
    }
    public function setMenuName($menuName)
    {
        $this->_menuName = $menuName;
    }

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

        $query = DB::table('pages')
            ->join('menus', 'pages.menu_id', '=', 'menus.id')
            ->select('pages.*', 'menus.menu_name');

        if (!empty($this->_pageName)) {
            // $query->where('pages.page_name', $this->_pageName);
            $query->where('pages.page_name','like','%'.$this->_pageName.'%');
        }

        if (!empty($this->_menuName)) {
            $query->where('menus.menu_name', $this->_menuName);
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

    public function getPageList()
    {
        $query = $this->_get_datatables_query();
        if ($this->_lengthValue != -1){
            $query->offset($this->_startValue)->limit($this->_lengthValue);
        }
        return $query->get();  

    }

    public function count_filtered()
    {
        $query = $this->_get_datatables_query();
        $query = $query->get();
        return $query->count();
    }

    public function count_all()
    {
        $query = DB::table('pages')->get();
        return $query->count();
    }

}
