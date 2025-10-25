<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrContractConclusionLetter extends Model{
    protected $table = 'hr_contract_conclusion_letter';
    protected $fillable = ['emr_no','conclude_date','settlement_date', 'approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
