<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\LoanRequest;

$allowance_types = DB::table('allowance_types')->where([['status','=', 1]])->orderBy('id','asc')->get();

$m = '12';
$other_Allowance = 0;
$deduction_name = '';
$other_Allowance = ($payslip_data->total_allowance - $payslip_data->overtime);

$loan_amount = 0;
$loan_perMonthDeduction = 0;
$loan_closing_balance = 0;
$loan_id = 0;
$loan_balance = 0;
$loan_amount_paid = 0;

$explode = explode("-",$payslip_data->month_year);
$payslip_month_year=$explode[0]."-".$explode[1];

CommonHelper::companyDatabaseConnection($m);

$banks = DB::table('employee_bank_data')->where('emp_id',$payslip_data->emp_id);
if($banks->count() > 0){
    $banks = $banks->first();
    $bank_name = $banks->bank_name;
}
else{
    $bank_name = "";
}

if($payslip_data->loan_id != ''){
    $loanRequest = LoanRequest::select('id','loan_amount','per_month_deduction')->where([['approval_status','=',2],['status', '=', 1],['employee_id', '=', $payslip_data->employee_id],['id','=',$payslip_data->loan_id]]);
    if($loanRequest->count() > 0):
        $loan_amount = $loanRequest->value('loan_amount');
        $loan_perMonthDeduction = $payslip_data->loan_amount_paid;
        $loan_id = $loanRequest->value('id');
        $loan_paid=$loanRequest->value('pf_loan_paid');
        $loan_closing_balance=DB::table("payslip")
                ->select(DB::raw("SUM(loan_amount_paid) as loan_amount_paid"))
                ->where([['employee_id','=',$payslip_data->employee_id],['loan_id','=',$loan_id],
                        ['month_year','<=',$payslip_data->month_year] ])
                ->first();
        $loan_closing_balance=$loan_amount-($loan_closing_balance->loan_amount_paid+$loan_paid);
        $loan_paid=DB::table("payslip")
                ->select(DB::raw("SUM(loan_amount_paid) as loan_amount_paid"))
                ->where([['employee_id','=',$payslip_data->employee_id],['loan_id','=',$loan_id],
                        ['month_year','<',$payslip_data->month_year] ])
                ->first();
        $loan_amount_paid=$loan_paid->loan_amount_paid;

    endif;
}

if($payslip_data->hold_salary == 1):
    $hold_salary = $payslip_data->net_salary;
    $net_salary = 0;
else:
    $net_salary = $payslip_data->net_salary;
    $hold_salary = 0;
endif;

$other_deduction = $payslip_data->total_deduction - $payslip_data->loan_amount_paid - $payslip_data->advance_salary_amount-$payslip_data->tf_deduction;

?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>

<table style="width:100%;text-align: left;border-collapse: collapse;">
    <tr>
        <td style="border-color: #fff;">
            <span><b>{{ env('APP_NAME') }}</b></span>
        </td>
        <td style="font-size: 12px;border-color: #fff">
            <b>Employee Payslip  for the month of {{ date('F Y', strtotime($payslip_data->year.'-'.$payslip_data->month)) }}</b>
        </td>
        <td style="font-size: 12px;text-align: right;border-color: #fff">
            <b> Date {{ date('d-m-Y') }} </b>
        </td>
    </tr>
</table>
<br><br>

<table style="width:100%;text-align: left !important;border-collapse: collapse;font-size:13px;">
    <tr>
        <th style="border: 1px solid grey; padding: 3px;">Employee Name</th>
        <td style="border: 1px solid grey; padding: 3px;">{{ $payslip_data->emp_name }}</td>
        <th style="border: 1px solid grey; padding: 3px;" colspan="2">Father Name / Husband Name</th>
        <td style="border: 1px solid grey; padding: 3px;" colspan="2">{{ $payslip_data->emp_father_name }}</td>

    </tr>
    <tr>
        <th style="border: 1px solid grey; padding: 3px;">Date of Joining</th>
        <td style="border: 1px solid grey; padding: 3px;">{{ HrHelper::date_format($payslip_data->joining_date) }}</td>
        <th style="border: 1px solid grey; padding: 3px;" colspan="2">Emp ID</th>
        <td style="border: 1px solid grey; padding: 3px;" colspan="2">{{ $payslip_data->emp_id }}</td>
    </tr>

    <tr>
        <th style="border: 1px solid grey; padding: 3px;">Designation</th>
        <td style="border: 1px solid grey; padding: 3px;">
            @if(array_key_exists($payslip_data->designation_id, $designations)){{ $designations[$payslip_data->designation_id]->designation_name }} @endif
        </td>
        <th style="border: 1px solid grey; padding: 3px;" colspan="2">Email ID</th>
        <td style="border: 1px solid grey; padding: 3px;" colspan="2">{{ $payslip_data->official_email }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid grey; padding: 3px;">CNIC</th>
        <td style="border: 1px solid grey; padding: 3px;">{{ $payslip_data->cnic }}</td>
        <th style="border: 1px solid grey; padding: 3px;" colspan="2">Dept</th>
        <td style="border: 1px solid grey; padding: 3px;" colspan="2">
            @if(array_key_exists($payslip_data->department_id, $departments)){{ $departments[$payslip_data->department_id]->department_name }} @endif
        </td>
    </tr>
</table>

<br><br>
<!--<table style="width:100%;text-align: left;border-collapse: collapse;font-size:13px;">

    <tr style="text-align: center">
        <th style="border: 1px solid grey; padding: 3px;" colspan="2">Loan</th>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Loan Amount</td>
        <td style=" border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($loan_amount) }}</td>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Paid Amount</td>
        <td style=" border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($loan_amount_paid) }}</td>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Deducted During the Month</td>
        <td style=" border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->loan_amount_paid) }}</td>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Closing Balance</td>
        <td style=" border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($loan_closing_balance) }}</td>
    </tr>
</table>-->

<table style="width:100%;text-align: left;border-collapse: collapse;font-size:13px;">

    <tr style="text-align: center">
        <th style="border: 1px solid grey; padding: 3px;" colspan="2">Payments</th>
        <th style="border: 1px solid grey; padding: 3px;" colspan="2">Deduction</th>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Basic</td>
        <td style=" border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->basic_salary) }}</td>
        <td style="border: 1px solid grey; padding: 3px;">EOBI</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->eobi_amount) }}</td>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Utility</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->fix_medical) }}</td>
        <td style="border: 1px solid grey; padding: 3px;">Income Tax</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->tax_amount) }}</td>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">House Rent</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->hr_utility_allowance) }}</td>
        <td style="border: 1px solid grey; padding: 3px;">Loan</td>
        <td style="border: 1px solid grey; text-align: right; padding: 3px;">{{ number_format($payslip_data->loan_amount_paid) }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Overtime</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->gez_overtime) }}</td>
        <td style="border: 1px solid grey; padding: 3px;">Advance Salary</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->advance_salary_amount) }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Arrears</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->arrears_amount) }}</td>
        
        <td style="border: 1px solid grey; padding: 3px;">TF Deduction</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->tf_deduction) }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">TF Allowances</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->allowance_tf) }}</td>

        <?php
        if($payslip_data->other_deduct != 0){
            $deduction_name.=" Others ";
        }
        if($payslip_data->penalty != 0){
            $deduction_name.=" Penalty ";
        }
        if($payslip_data->lateness != 0){
            $deduction_name.=" Lateness ";
        }
        if($payslip_data->lwp_deduction != 0){
            $deduction_name.=" Lwp ";
        }
        if($deduction_name != ''){
            $parts = explode("  ", $deduction_name);
            $result = implode(', ', $parts);
            $deduction_name = $result;
        }
        ?>
        <td style="border: 1px solid grey; padding: 3px;">Other Deductions</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($other_deduction) }}</td>

      
    </tr>
  
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Attendance Allowance</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->attendance_allowance) }}</td>
        <td style="border: 1px solid grey; padding: 3px;">Hold Salary</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($hold_salary) }}</td>
        
       
    </tr>
   
    @if($payslip_data->fuel_allowance > 0)
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Fuel Allowances</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->fuel_allowance) }}</td>
        <td style="border: 1px solid grey; padding: 3px;"></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;"></td>
       
    </tr>
    @endif
    @if($payslip_data->mobile > 0)
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Mobile Allowances</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->mobile) }}</td>
        <td style="border: 1px solid grey; padding: 3px;"></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;"></td>
       
    </tr>
    @endif
    @if($payslip_data->allowance_mics > 0)
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Mics Allowance</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->allowance_mics) }}</td>
        <td style="border: 1px solid grey; padding: 3px;"></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;"></td>
       
    </tr>
    @endif
    @if($payslip_data->allowance_incentives > 0)
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">CPHOC Incentive</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->allowance_incentives) }}</td>
        <td style="border: 1px solid grey; padding: 3px;"></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;"></td>
       
    </tr>
    @endif
    
    @if($payslip_data->food_allowance > 0)
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Food Allowance</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->food_allowance) }}</td>
        <td style="border: 1px solid grey; padding: 3px;"></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;"></td>
       
    </tr>
    @endif
    @if($payslip_data->target_allowance > 0)
    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Distance+Target Incentive</td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">{{ number_format($payslip_data->target_allowance) }}</td>
        <td style="border: 1px solid grey; padding: 3px;"></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;"></td>
       
    </tr>
    @endif

    <tr>
        <td style="border: 1px solid grey; padding: 3px;"><strong>Total Payment Rs.</strong></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;">
            <strong>{{ number_format($payslip_data->total_allowance + $payslip_data->gross_salaries) }}</strong>
        </td>
        <td style="border: 1px solid grey; padding: 3px;"><strong>Total Deduction Rs.</strong></td>
        <td style="border: 1px solid grey; text-align: right; padding: 0px 3px;"><strong>{{ number_format($payslip_data->total_deduction) }}</strong></td>
    </tr>
</table>

<br><br>
<table style="width:100%;text-align: left;border-collapse: collapse;font-size:13px;">

    <tr>
        <td style="border: 1px solid grey; padding: 3px;"><strong>Net Payments Rs</strong></td>
        <td style="border: 1px solid grey;text-align: right; padding: 0px 3px;" colspan="4">
            <strong>{{ number_format($net_salary) }}</strong>
        </td>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Bank</td>
        <td style="border: 1px solid grey;" colspan="4">{{ $bank_name }}</td>
    </tr>

    <tr>
        <td style="border: 1px solid grey; padding: 3px;">Employee A/c No</td>
        <td style="border: 1px solid grey;" colspan="4">{{ $payslip_data->account_no }}</td>
    </tr>

</table>
<br><br>

<br><br><br>
<table style="width:100%;text-align: left;border-collapse: collapse;font-size:13px;">
    <tr>
        <td>
            <p style="font-size:12px;">Note : <span style="color:red;">This is a system generated document and doesn't require any signature. Thank You !</span></p>
        </td>
    </tr>
</table>

</body>
</html>