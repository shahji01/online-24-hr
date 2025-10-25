<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Input;
use PDF;
use Mail;
use Auth;

use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\LoanAdjustment;
use App\Models\Payslip;
use App\Models\FinalSettlement;
use App\Models\LoanTopUp;

class loanCronJob extends Command
{

    protected $signature = 'notify:loan';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        CommonHelper::companyDatabaseConnection(12);
        $loanDetails = LoanRequest::select('id', 'employee_id', 'loan_amount')->where([['loan_status', '=', 0],['status','=', 1]]);
        if($loanDetails->count() > 0):
            foreach ($loanDetails->get() as $key1 => $val1):
                $loan_request_id = $val1->id;
                $employee_id = $val1->employee_id;

                $LoanTopUp = LoanTopUp::where([['loan_id', '=', $loan_request_id],['status', '=', 1]])->sum('loan_top_up_amount');
                $total_loan = $val1->loan_amount + $LoanTopUp;

                $paid_amount = 0;
                $loan_adjusted_amount = LoanAdjustment::where([['loan_id','=',$loan_request_id],['status','=', 1]])->sum('amount');
                $payslip = Payslip::where([['employee_id' ,'=', $employee_id],['loan_id' ,'=', $loan_request_id],['status','=', 1]]);
                if($payslip->count() > 0):
                    $paid_amount += Payslip::where([['employee_id' ,'=', $employee_id],['loan_id' ,'=', $loan_request_id],['status','=', 1]])
                        ->sum('loan_amount_paid');
                endif;

                $final_settlement = FinalSettlement::where([['employee_id', '=', $employee_id],['status', '=', 1]])->orderBy('id', 'desc')->first();
                if(!empty($final_settlement)):
                    if($final_settlement->loan_id != ''):
                        $settlement_loan_id = explode(',',$final_settlement->loan_id);
                        if(in_array($loan_request_id,$settlement_loan_id)):
                            LoanRequest::where([['id', '=', $loan_request_id]])->update($update);
                        endif;
                    endif;
                endif;

                $paid_amount += $loan_adjusted_amount;
                if($paid_amount >= $total_loan):
                    LoanRequest::where([['id', '=', $loan_request_id]])->update(['loan_status' => 1]);
                elseif($paid_amount < $total_loan):
                    LoanRequest::where([['id', '=', $loan_request_id]])->update(['loan_status' => 0]);
                endif;
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
    }

}