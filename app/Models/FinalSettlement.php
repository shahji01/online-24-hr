<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalSettlement extends Model{
    protected $table = 'final_settlement';
    protected $fillable = ['emr_no','salary_from','salary_to','gratuity','others','notice_pay',
        'advance','mobile_bill','accommodation_charges','hire_purchase_balance','surety','any_others',
        'approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
