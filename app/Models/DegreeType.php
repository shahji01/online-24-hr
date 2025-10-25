<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DegreeType extends Model{
    protected $table = 'degree_type';
    protected $fillable = ['company_id','degree_type_name','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
