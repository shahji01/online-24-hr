<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusIssue extends Model{
    protected $table = 'bonus_issue';
    protected $fillable = ['bonus_id','emp_id','bonus_amount','bonus_month','bonus_year','username','bonus_status','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
