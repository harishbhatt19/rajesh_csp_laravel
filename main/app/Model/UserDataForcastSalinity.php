<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserDataForcastSalinity extends Model
{
    protected $table = 'user_data_forcast_salinity';

    public $fillable = ['user_id','category_id','pond_id','salinity','date'];
}
