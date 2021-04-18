<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function ponds() {
        return $this->hasMany('App\Model\Pond');
    }
}
