<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagePermission extends Model
{
    protected $table = 'page_permissions';

    protected $fillable = ['page_id','role_id'];
    
    public function page()
    {
      return $this->belongsTo('App\Models\Page');
    }
    public function role()
    {
      return $this->belongsTo('App\Models\Role');
    }
}
