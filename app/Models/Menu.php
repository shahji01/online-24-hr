<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model{
    protected $table = 'menu';
    protected $fillable = ['m_code','m_parent_code','m_type','m_main_title','name','m_controller_name','js','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
