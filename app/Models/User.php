<?php

namespace App\Models;

// use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    // use Notifiable;

    protected $fillable = [
        'role_id','name', 'email', 'password', 'mobile_no', 'gender','photo','address','additioanl_mobile_no'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }
    public function contactMessage()
    {
        return $this->hasMany('App\Models\ContactMessage');
    }

    /*********DataTable Server Side Begin************/
    protected $_table_name     = 'users';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order  = array('', '', 'users.name', 'users.email','users.mobile_no','users.gender','roles.role_name','users.is_active','');
    var $column_search = array('name','email','mobile_no','gender','role_name','is_active');
    var $order         = array('users.id' => 'DESC');

    private $_name;
    private $_email;
    private $_mobile;
    private $_role_id;
    private $_gender;
    private $_is_active;

    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;


    public function setName($name)
    {
        $this->_name = $name;
    }
    public function setEmail($email)
    {
        $this->_email = $email;
    }
    public function setMobile($mobile)
    {
        $this->_mobile = $mobile;
    }
    public function setRole($role_id)
    {
        $this->_role_id = $role_id;
    }
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }
    public function setStatus($is_active)
    {
        $this->_is_active = $is_active;
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

        $query = DB::table('users')->join('roles','users.role_id','=','roles.id')
        ->select('users.*','roles.role_name')->where('users.id','!=',1);


        if (!empty($this->_name)) {
            $query->where('users.name', 'like','%'.$this->_name.'%');
        }
        if (!empty($this->_email)) {
            $query->where('users.email', 'like','%'.$this->_email.'%');
        }
        if (!empty($this->_mobile)) {
            $query->where('users.mobile_no', $this->_mobile)
                ->orWhere('users.additioanl_mobile_no',$this->_mobile);
        }
        if (!empty($this->_role_id)) {
            $query->where('users.role_id', $this->_role_id);
        }
        if (!empty($this->_gender)) {
            $query->where('users.gender', $this->_gender);
        }
        if (!empty($this->_is_active)) {
            $query->where('users.is_active', $this->_is_active);
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

    public function getUserList()
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
        $query = DB::table('users')->select('*')->where('id','!=',1)->get();
        return $query->count();
    }
    /*********DataTable Server Side End************/

    
}
