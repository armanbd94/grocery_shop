<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Order extends Model
{
    protected $fillable = ['invoice_no','customer_id','billing_address_id','shipping_address_id','total_amount',
    'coupon_code','coupon_discount_amount','order_order_status','delivery_order_status','delivery_from_date','delivery_time'];

    /*********************/
    protected $_table_name     = 'orders as o';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order  = array('o.id', 'o.invoice_no','','full_name', 'c.mobile', 'o.total_amount','o.created_at','o.order_status','o.delivery_date','o.delivery_time','o.delivery_status','');
    var $column_search = array('', '');
    var $order         = array('o.id' => 'DESC');

    private $order_from_date;
    private $order_to_date;
    private $invoice_no;
    private $mobile_no;
    private $delivery_from_date;
    private $delivery_to_date;
    private $delivery_time;
    private $order_status;
    private $delivery_status;

    /* Start :: Fixed */
    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;
    /* End :: Fixed */


    public function setOrderFromDate($order_from_date)
    {
        $this->order_from_date = $order_from_date.' 00:00:00';
    }
    public function setOrderToDate($order_to_date)
    {
        $this->order_to_date = $order_to_date.' 23:59:59';
    }
    public function setInvoiceNo($invoice_no)
    {
        $this->invoice_no = $invoice_no;
    }
    public function setMobileNo($mobile_no)
    {
        $this->mobile_no = $mobile_no;
    }
    public function setDeliveryFromDate($delivery_from_date)
    {
        $this->delivery_from_date = $delivery_from_date;
    }
    public function setDeliveryToDate($delivery_to_date)
    {
        $this->delivery_to_date = $delivery_to_date;
    }
    public function setDeliveryDTime($delivery_time)
    {
        $this->delivery_time = $delivery_time;
    }
    public function setOrderStatus($order_status)
    {
        $this->order_status = $order_status;
    }
    public function setDeliveryStatus($delivery_status)
    {
        $this->delivery_status = $delivery_status;
    }

    /* Start :: Fixed */
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
    /* End :: Fixed */

    private function _get_datatables_query()
    {

        $query = DB::table($this->_table_name)
        ->select('o.*',DB::raw('CONCAT(c.first_name," ",c.last_name) AS full_name'),'c.mobile')
        ->leftjoin('customers as c','o.customer_id','=','c.id');

        if (!empty($this->invoice_no)) {
            $query->where('o.invoice_no',$this->invoice_no);
        }
        if (!empty($this->mobile_no)) {
            $query->where('c.mobile','like','%'.$this->mobile_no.'%');
        }

        if (!empty($this->order_from_date)) {
            $query->where('o.created_at', '>=', $this->order_from_date);
        }
        if (!empty($this->order_to_date)) {
            $query->where('o.created_at', '<=', $this->order_to_date);
        }

        if (!empty($this->delivery_from_date)) {
            $query->where('o.delivery_date', '>=', $this->delivery_from_date);
        }
        if (!empty($this->delivery_to_date)) {
            $query->where('o.delivery_date', '<=', $this->delivery_to_date);
        }

        if (!empty($this->delivery_time)) {
            $query->where('o.delivery_time', $this->delivery_time);
        }
        if (!empty($this->order_status)) {
            $query->where('o.order_status', $this->order_status);
        }
        if (!empty($this->delivery_status)) {
            $query->where('o.delivery_status', $this->delivery_status);
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
        $query = DB::table($this->_table_name)->get()->count();
        return $query;
    }

}
