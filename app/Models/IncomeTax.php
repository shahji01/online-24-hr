<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeTax extends Model{
    protected $table = 'income_tax';
    protected $fillable = ['emp_id','month','year','month_year','taxable_income','annual_salary','tax_payable',
        'balance_taxable_income','tax_percent','amount','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
