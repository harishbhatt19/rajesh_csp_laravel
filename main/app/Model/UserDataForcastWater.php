<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserDataForcastWater extends Model
{
    protected $table = 'user_data_forcast_water';

    public $fillable = ['user_id','cat_id','pond_id','water','date'];
}
