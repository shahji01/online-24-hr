<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model{
    protected $table = 'tax';
    protected $fillable = ['tax_name','salary_range_from','salary_range_to','tax_percent','tax_month_year','status','company_id','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
