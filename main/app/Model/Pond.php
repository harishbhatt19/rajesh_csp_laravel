<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pond extends Model
{
    public function category() {
        $this->belongsTo('App\Model\Category');
    }
    
    
}
