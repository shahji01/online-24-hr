<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainings extends Model{
    protected $table = 'trainings';
    protected $fillable = ['region_id','employee_category_id','participant_type','participants','location_id','training_date','topic_name','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
