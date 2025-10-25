<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarPolicy extends Model{
    protected $table = 'car_policy';
    protected $fillable = ['designation_id','vehicle_type_id','start_salary_range','end_salary_range','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

