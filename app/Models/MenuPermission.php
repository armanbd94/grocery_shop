<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $table = 'menu_permissions';

    protected $fillable = ['menu_id','role_id'];

    public function menu()
    {
      return $this->belongsTo('App\Models\Menu');
    }
    public function role()
    {
      return $this->belongsTo('App\Models\Role');
    }
}
