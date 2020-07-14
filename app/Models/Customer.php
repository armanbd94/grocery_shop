<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Customer extends Authenticatable {

    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'password', 'status',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function socialAccount() {
        return $this->hasMany('App\Models\SocialAccount');
     }


    /*     * *******DataTable Server Side Begin*********** */
    protected $_table_name = 'customers';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "id desc";
    var $column_order = array('id', 'first_name','last_name','email', '');
    var $column_search = array('first_name','last_name','email');
    var $order = array('id' => 'desc');
    private $_customerName;
    private $_customerEmail;
    private $_customerMobile;
    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;

    public function setCustomerName($customerName) {
        $this->_customerName = $customerName;
    }
    public function setCustomerEmail($customerEmail) {
        $this->_customerEmail = $customerEmail;
    }
    public function setCustomerMobile($customerMobile) {
        $this->_customerMobile = $customerMobile;
    }

    //set datatable eliments
    public function setSearchValue($searchValue) {
        $this->_searchValue = $searchValue;
    }

    public function setOrderValue($orderValue) {
        $this->_orderValue = $orderValue;
    }

    public function setDirValue($dirValue) {
        $this->_dirValue = $dirValue;
    }

    public function setLengthValue($lengthValue) {
        $this->_lengthValue = $lengthValue;
    }

    public function setStartValue($startValue) {
        $this->_startValue = $startValue;
    }

    private function _get_datatables_query() {

        $query = DB::table($this->_table_name)->select('*');

        if (!empty($this->_customerName)) {
            $query->where('first_name','like',$this->_customerName.'%');
            $query->orWhere('last_name', 'like', $this->_customerName.'%');
        }
        if (!empty($this->_customerEmail)) {
            $query->where('email','like',$this->_customerEmail.'%');
        }
        if (!empty($this->_customerMobile)) {
            $query->where('mobile','like',$this->_customerMobile.'%');
        }

        if (isset($this->_orderValue) && isset($this->_dirValue)) { // here order processing
            $query->orderBy($this->column_order[$this->_orderValue], $this->_dirValue);
        } else if (isset($this->order)) {

            $order = $this->order;
            $query->orderBy(key($order), $order[key($order)]);
        }

        return $query;
    }

    public function getCustomerList() {
        $query = $this->_get_datatables_query();
        if ($this->_lengthValue != -1)
            $query->offset($this->_startValue)->limit($this->_lengthValue);
        return $query = $query->get();
    }

    public function count_filtered() {
        $query = $this->_get_datatables_query();
        $query = $query->get();
        return $query->count();
    }

    public function count_all() {
        $query = DB::table($this->_table_name)->select('*')->get();
        return $query->count();
    }

    /*     * *******DataTable Server Side End*********** */
}
