<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Product extends Model
{
    protected $fillable = [
        'brand_id','product_name','product_slug','product_variation','featured_image','description',
    ];

    public function product_price_details()
    {
        return $this->hasMany('App\Models\ProductPriceDetail');
    }
    public function product_has_categoies()
    {
        return $this->hasMany('App\Models\ProductHasCategory');
    }

    /*********************/
    protected $_table_name     = 'products';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id desc";

    var $column_order;
    var $column_search = array('', '');
    var $order         = array('p.id' => 'DESC');

    private $_productName;
    private $_brandID;
    private $_status;

    /* Start :: Fixed */
    private $_searchValue;
    private $_orderValue;
    private $_dirValue;
    private $_startValue;
    private $_lengthValue;
    /* End :: Fixed */


    public function setProductName($productName)
    {
        $this->_productName = $productName;
    }
    public function setBrandID($brandID)
    {
        $this->_brandID = $brandID;
    }
    public function setStatus($status)
    {
        $this->_status = $status;
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
        if(in_array('Product Edit',session()->get('permission'))){
            $this->column_order = array('p.id', 'p.id','p.product_name', 'b.brand_name', '','','','');
        }else{
            $this->column_order = array('p.id', 'p.id','p.product_name', 'b.brand_name', '','','');
        }

        $query = DB::table($this->_table_name.' as p')
        ->select('p.*','b.brand_name')
        ->leftjoin('brands as b','p.brand_id','=','b.id');

        if (!empty($this->_productName)) {

            $query->where('p.product_name','like','%'.$this->_productName.'%');
        }

        if (!empty($this->_brandID)) {
            $query->where('p.brand_id', $this->_brandID);
        }

        if (!empty($this->_status)) {
            $query->where('p.status', $this->_status);
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
