<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gratuity extends Model{
    protected $table = 'gratuity';
    protected $fillable = ['emr_no','gratuity','date_from','date_to','year_month','status','date','username','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
