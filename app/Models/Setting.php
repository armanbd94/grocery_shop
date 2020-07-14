<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_title','footer_text','site_logo','site_white_logo','favicon_icon','social_media',
        'mail_info','featured_video','terms_conditions','time_duration','start_time','end_time','time_slot'
    ];
}
