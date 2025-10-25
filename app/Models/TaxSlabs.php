<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSlabs extends Model{
    protected $table = 'tax_slabs';
    protected $fillable = ['tax_id','slab_name','salary_range_from','salary_range_to','tax_mode','tax_percent',
        'tax_amount','greater_amount','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
