<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $table = 'user_data';
    
    public function user() {
        $this->belongsTo('App\User');
    }
}
