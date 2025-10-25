<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCardRequest extends Model{
    protected $table = 'employee_card_request';
    protected $fillable = ['emr_no','posted_at','replacement_type','card_replacement','payment','card_status', 'status', 'approval_status', 'date', 'time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

