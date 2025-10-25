<?php
use App\Helpers\CommonHelper;
use App\Models\IncomeTax;
use Carbon\Carbon;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payroll;
use App\Models\Tax;
use App\Models\TaxSlabs;
use App\Models\LeaveApplicationData;
use App\Models\Eobi;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\LoanRequest;
use App\Models\AdvanceSalary;
use App\Models\EmployeePromotion;
use App\Models\EmployeeBankData;
use App\Models\Arrears;
use App\Models\EmployeeTransfer;
use App\Models\EmployeeExit;
use App\Models\Rebate;
use App\Models\LoanTopUp;
use App\Models\LoanAdjustment;
$counter = 1;
CommonHelper::companyDatabaseConnection(Input::get('m'));
?>

<style>
    .panel-heading {
        padding: 0px 15px;}
    .field_width {width: 120px;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }

    .show_data {
        display: none;
    }

</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <form action="{{ url('had/addPayrollDetail') }}" method="post">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="printList">
                                    <div class="table-responsive wrapper">
                                        <input type="hidden" name="payslip_month" id="payslip_month" value="{{ $getPayslipMonth }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="m" value="{{ $company_id }}">
                                        <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                                            <thead>
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">EMP ID</th>
                                                <th class="text-center">Employee Name</th>
                                                <th class="text-center">Designation</th>
                                                <th class="text-center">Cost Center</th>
                                                <th class="text-center">Cnic</th>
                                                <th class="text-center">Standard Gross</th>
                                                <th class="text-center">Gross</th>
                                                <th class="text-center">Basic Salary</th>
                                                <th class="text-center">House Rent</th>
                                                <th class="text-center">Medical</th>
                                                <th class="text-center">Gez Overtime</th>
                                                <th class="text-center">Overtime</th>
                                                <th class="text-center">Total Overtime</th>
                                                <th class="text-center">Attendance Allowance</th>
                                                <th class="text-center">TF Allowance</th>
                                                @foreach($allowance_types as $val)
                                                    <th class="text-center">{{ $val->allowance_type }}</th>
                                                @endforeach
                                                <th class="text-center">Bonus</th>
                                                <th class="text-center">Gross with Allowance</th>
                                                <!--<th class="text-center">Salary Paid</th>
                                                <th class="text-center">Future Salary</th>
                                                <th class="text-center">Taxable Salary</th>
                                                <th class="text-center">Annual Tax</th>
                                                <th class="text-center">Rebate Amount</th>
                                                <th class="text-center">Tax Paid</th>
                                                <th class="text-center">Balance</th>
                                                <th class="text-center">Income Tax</th>-->
                                                {{-- <th class="text-center">PF Amount</th> --}}
                                                <th class="text-center">Advances</th>
                                                <th class="text-center">EOBI</th>
                                                <th class="text-center">Loan</th>
                                                <th class="text-center">TF Deduction</th>
                                                <th class="text-center">L.W.P</th>
                                                <th class="text-center">Lateness</th>
                                                <th class="text-center">Penalty</th>
                                                <th class="text-center">Others</th>
                                                <th class="text-center">Total Deduction</th>
                                                <th class="text-center">Net Salary</th>
                                                <th class="text-center">Account No.</th>
                                                <th class="text-center">Payment Mode</th>
                                                <th class="text-center">Hold Salary</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($employees as $row1){?>
                                            <?php

                                            $overtime = 0 ;
                                            $attendance_allowance = 0 ;
                                            $eobi_deduct=0;
                                            $total_month_days = cal_days_in_month(CAL_GREGORIAN,$explodeMonthYear[1],$explodeMonthYear[0]);
                                            $days = $total_month_days;

//                                            if( $emp_location_array[$row1->id] == 4 )
//                                                {
//                                                    if($row1->eobi_id == '2'):
//                                                        $eobi = 1250 ;//Eobi::where([['id','=',$row1->eobi_id],['company_id','=',Input::get('m')],['status','=','1']])->value('EOBI_amount');
//                                                        $eobi_deduct = $eobi;
//
//                                                    else:
//                                                        $eobi_deduct = 0;
//                                                    endif;
//                                            }
//                                            else
//                                            {
//                                                $eobideduct = 0;
//                                            }
                                            $designation_id = $row1->designation_id;
                                            $grade_id = $row1->grade_id;
                                            $emp_salary =  $row1->salary;
                                            $emp_name = $row1->emp_name;
                                            $employment_status_id = $row1->employment_status_id;

                                            $overall_allowances=0;
                                            $overall_allowances_once=0;

                                            $overall_allowances = Allowance::where([ ['allowance_type_id','!=', 6] , ['employee_id', '=', $row1->id],['status', '=', 1],['once','!=',1]])->sum('allowance_amount');
                                            $overall_allowances_once = Allowance::where([ ['allowance_type_id','!=', 6] , ['employee_id', '=', $row1->id],['status', '=', 1],['once','=',1],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');

                                            $payroll_deduction = PayrollData::where([['employee_id', '=', $row1->id],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
                                            $date_to = explode('-',$payroll_deduction->value('attendance_to'));

                                            if($employment_status_id != 5 || $emp_location_array[$row1->id] != 3):
                                                $emp_salary = $row1->salary;
                                            else:
                                                $emp_salary = $row1->salary * ($payroll_deduction->value('present_days') + $payroll_deduction->value('total_holidays'));
                                            endif;
                                            $standard_gross = $emp_salary;

                                            $monthyear = date("Y-m",strtotime($explodeMonthYear[0].'-'.$explodeMonthYear[1]));
                                            $finish = date("Y-m",strtotime($row1->joining_date));

                                            if($employment_status_id != 5):

                                                if ($finish == $monthyear) {
                                                    $fdate = $explodeMonthYear[0].'-'.$explodeMonthYear[1].'-'.$total_month_days;
                                                    $tdate = $row1->joining_date;
                                                    $datetime1 = new DateTime($fdate);
                                                    $datetime2 = new DateTime($tdate);
                                                    $interval = $datetime1->diff($datetime2);
                                                    $days = $interval->format('%a');//now do whatever you like with $days
                                                    $days += 1;
                                                    $emp_salary  = $emp_salary / $total_month_days;
                                                    $emp_salary  = $emp_salary * $days;
                                                }
                                                if ($row1->last_working_date != null && $row1->last_working_date != "0000-00-00") {
                                                    $monthyear = date("Y-m",strtotime($explodeMonthYear[0].'-'.$explodeMonthYear[1]));
                                                    $finish = date("Y-m",strtotime($row1->last_working_date));
                                                    if ($finish == $monthyear)
                                                    {
                                                        $fdate = $explodeMonthYear[0].'-'.$explodeMonthYear[1].'-1';
                                                        $tdate = $row1->last_working_date;
                                                        $datetime1 = new DateTime($fdate);
                                                        $datetime2 = new DateTime($tdate);
                                                        $interval = $datetime1->diff($datetime2);
                                                        $days = $interval->format('%a');
                                                        $emp_salary  = $emp_salary / cal_days_in_month(CAL_GREGORIAN,$explodeMonthYear[1],$explodeMonthYear[0]);
                                                        $emp_salary  = $emp_salary * $days;
                                                    }
                                                }

                                                $overall_allowances = Allowance::where([ ['allowance_type_id','!=', 6] , ['employee_id', '=', $row1->id],['status', '=', 1],['once','!=',1]])->sum('allowance_amount');
                                                $overall_allowances = ($overall_allowances / $total_month_days) * $days;
                                            endif;

                                            $gross_salary = $emp_salary;
                                            $basic_salary = round($emp_salary / 100 * 64.50);
                                            $fix_medical = round($basic_salary / 100 * 10);
                                            $hr_utility_allowance = round($emp_salary - ($basic_salary + $fix_medical));
                                            
                                            $late_deduction = $payroll_deduction->value('deduction');
                                            $overtime = $payroll_deduction->value('overtime');
                                                if( $emp_location_array[$row1->id] == 10 ) {
                                                    $gez_overtime = 0;
                                                } else {
                                                    $gez_overtime = $payroll_deduction->value('gez_overtime');
                                                }
                                            //$gez_overtime = $payroll_deduction->value('gez_overtime');
                                            $totaldays = $payroll_deduction->value('total_days');
                                            
                                            $total_absent = $payroll_deduction->value('absent_days');
                                            $total_present_day = $payroll_deduction->value('present_days');
                                            $total_holidays = $payroll_deduction->value('total_holidays');

                                            if($total_absent == 0 && (($total_present_day + $total_holidays) - $totaldays ) == 0 ) {
                                                $attendance_allowance = 1000;
                                            }
                                            $overtime_total = 0;
                                            if( $totaldays > 0) {
                                                if(array_key_exists($row1->id,$totalWorkingHour)) {
                                                    if( $emp_location_array[$row1->id] == 10 ) {
                                                        $gez_overtime = 0;
                                                    } else {
                                                        $gez_overtime = ($standard_gross / $totaldays / $totalWorkingHour[$row1->id]) * 2 * $gez_overtime;
                                                    }
                                                    $overtime = ($standard_gross / $totaldays / $totalWorkingHour[ $row1->id] ) * 1.5 * $overtime;
                                                    $overtime_total = round($gez_overtime + $overtime);
                                                }
                                            }

                                            $loan_adjusted_amount = 0;
                                            $actual_loan_amount = 0;
                                            $remaining_loan_amount = 0;
                                            $loan_per_month_deduction = 0;
                                            $loan_id = 0;
                                            $total_month_days = cal_days_in_month(CAL_GREGORIAN, $explodeMonthYear[1], $explodeMonthYear[0]);
                                            $month_year= $explodeMonthYear[0].'-'.$explodeMonthYear[1].'-01';
                                            $loan_request  = LoanRequest::where([['approval_status','=',2],['loan_status','=',0],['employee_id', '=', $row1->id],['status', '=', 1]])
                                                    ->whereDate('deduction_start_date', '<=', $explodeMonthYear[0].'-'.$explodeMonthYear[1].'-'.$total_month_days)
                                                    ->select('id','per_month_deduction','loan_amount','stop_payment_year','stop_payment_month');

                                            $stop_payment = $loan_request ->value('stop_payment_year').'-'.(int)$loan_request ->value('stop_payment_month');
                                            $stop_payment_check_month = $explodeMonthYear[0].'-'.(int)$explodeMonthYear[1];
                                            // echo $loan_request ->count() ;

                                            $loan_perMonthDeduction = 0 ;
                                            if($loan_request ->count() > 0):
                                                if($stop_payment != $stop_payment_check_month):
                                                    $loan_id = $loan_request ->value('id');

                                                    $LoanTopUp = LoanTopUp::where([['loan_id', '=', $loan_id],['status', '=', 1]])->sum('loan_top_up_amount');
                                                    $total_loan = $loan_request ->value('loan_amount') + $LoanTopUp;

                                                    $loan_adjusted_amount = LoanAdjustment::where([['loan_id','=',$loan_id],['status','=', 1]])->sum('amount');
                                                    $paid_amount = 0;

                                                    $payroll_paid_amount = 0;
                                                    $payslip_loan = Payslip::where([['employee_id' ,'=', $row1->id],['loan_id' ,'=', $loan_id],['status','=', 1]])->select('id');
                                                    if($payslip_loan->count() > 0):
                                                        $payroll_paid_amount += Payslip::where([['employee_id' ,'=', $row1->id],['loan_id' ,'=', $loan_id],['status','=', 1],['month_year' ,'<', $lastDateOfMonth ]])
                                                                ->sum('loan_amount_paid');
                                                    endif;

                                                    $total_paid_amount = $payroll_paid_amount + $loan_adjusted_amount;
                                                    $total_remaining_amount = $total_loan - $total_paid_amount ;

                                                    $total_remaining_amount = ($total_remaining_amount > 0 ) ? $total_remaining_amount : 0 ;
                                                    $loan_perMonthDeduction = $loan_request ->value('per_month_deduction');
                                                    if($total_remaining_amount < $loan_perMonthDeduction):
                                                        $loan_perMonthDeduction = $total_remaining_amount;
                                                    endif;
                                                endif;
                                            endif;
                                            // exit();
                                            $advance_salary = AdvanceSalary::where([['status','=','1'],['approval_status','=',2],['employee_id', '=', $row1->id],['deduction_year', '=', $explodeMonthYear[0]],['deduction_month', '=', $explodeMonthYear[1]]]);
                                            $advance_salary_amount = 0;
                                            if($advance_salary->count() > 0):
                                                $advance_salary_amount = $advance_salary->sum('advance_salary_amount');
                                            endif;

                                            $attendance_logs=DB::table('online24_hr_company.attendance_logs')
                                            ->where('status',1)
                                            ->where('employee_id',$row1->id)
                                            ->where('type','clock_in')
                                            ->whereBetween('attendance_date',[$payroll_deduction->value('attendance_from'),$payroll_deduction->value('attendance_to')])
                                            ->get();
                                            $tfDeduct=0;
                                            $attendance_date='';
                                            $location='';
                                            foreach($attendance_logs as $att_val){
                                                if($attendance_date!=$att_val->attendance_date || $location != $att_val->location_id){
                                                    $city_id=DB::table('online24_hr_master.locations')->select('city_id')->where('id',$att_val->location_id)->value('city_id');
                                                    if($city_id != 1){
                                                        $tfDeduct++;
                                                    }
                                                }
                                                $attendance_date=$att_val->attendance_date;
                                                $location=$att_val->location_id;
                                            }
                                            // $tfDeduct = DB::table('online24_hr_company.attendance_logs AS a')
                                            // ->leftJoin('online24_hr_master.locations AS l', 'l.id', '=', 'a.location_id')
                                            // ->where('a.status', 1)
                                            // ->where('a.employee_id', $row1->id)
                                            // ->where('a.type', 'clock_in')
                                            // ->whereBetween('a.attendance_date', [$payroll_deduction->value('attendance_from'), $payroll_deduction->value('attendance_to')])
                                            // ->where('l.city_id', '<>', 1)
                                            // ->distinct()
                                            // ->selectRaw('DATE(a.attendance_date) AS distinct_date')
                                            // ->count();

                                            $total_working_days = $totaldays - $total_holidays;
                                            $tf_allowance_paid = 0;
                                            $tf_allowance = 0;
                                            if(array_key_exists($row1->id, $tf_allowance_paid_array)): $tf_allowance_paid = $tf_allowance_paid_array[$row1->id]; endif;
                                            if(array_key_exists($row1->id, $tf_allowance_array)):
                                                $tf_allowance = $tf_allowance_array[$row1->id];
                                            endif;
                                            if($total_working_days != 0):
                                                $tf_allowance = ($tf_allowance / $total_working_days) * ($total_present_day-$tfDeduct);
                                            endif;

                                            $bonus = BonusIssue::where([['status', '=', 1],['bonus_pay', '=', 1],['employee_id', '=', $row1->id],['bonus_year', '=', $explodeMonthYear[0]],['bonus_month', '=', $explodeMonthYear[1]]]);
                                            $bonus_amount = 0;
                                            if($bonus->count() > 0):
                                                $bonus_issue = $bonus->first();
                                                $bonus_amount = $bonus_issue->bonus_amount;
                                            endif;

                                            $present = $payroll_deduction->value('present_days');
                                            $lwp_days = 0;
                                            $lwp_once_days = 0;
                                            $deduction_days = 0;
                                            $penalty_once = 0;
                                            $penalty = 0;
                                            $other_deduction_once = 0;
                                            $other_deduction = 0;
                                            $total_deduction = 0;

                                            $allowance = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1]]);
                                            //$LWP = Deduction::where([['employee_id', '=', $row1->id],['status', '=', 1],['deduction_type','=','LWP']]);
                                            $lwp_days = Deduction::where([['employee_id', '=', $row1->id],['status', '=', 1],['deduction_type','=','LWP'],['once','!=','1']])->sum('deduction_amount');
                                            $lwp_once_days = Deduction::where([['employee_id', '=', $row1->id],['status', '=', 1],['deduction_type','=','LWP'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');
                                            $deduction_days = ($lwp_days + $lwp_once_days + $payroll_deduction->value('deduction_days'));

                                            $penalty_once = Deduction::where([['employee_id', '=', $row1->id],['status', '=', 1],['deduction_type', '=','Penalty'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');
                                            $penalty = Deduction::where([['employee_id', '=', $row1->id],['status', '=', 1],['deduction_type','=','Penalty'],['once','!=','1']])->sum('deduction_amount');
                                            $penalty += $penalty_once;

                                            $other_deduction_once = Deduction::where([['employee_id', '=', $row1->id],['status', '=', 1],['deduction_type', '=','Other'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');
                                            $other_deduction = Deduction::where([['employee_id', '=', $row1->id],['status', '=', 1],['deduction_type','=','Other'],['once','!=','1']])->sum('deduction_amount');;
                                            $other_deduction += $other_deduction_once;

                                            $total_deduction = $penalty + $other_deduction;

                                            $deferred_salary = 0;
                                            $rebate_amount = 0;
                                            $deferred_salary = 0;
                                            $arrears = 0;
                                            $taxable_allowances = 0;
                                            $total_allowances = 0;
                                            $total_once_allowances=0;
                                            $overall_allowances_total=0;

                                            $overall_allowances_total = $overall_allowances + $overall_allowances_once;
                                            $taxable_allowances = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1],['once','!=',1]])
                                                    ->whereIn('allowance_type_id',$allowance_types_taxable)->sum('allowance_amount');

//                                            $deferred_salary = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1],['allowance_type_id', '=', 12],['once','!=',1]])->sum('allowance_amount');
//                                            $deferred_salary_once = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1],['allowance_type_id', '=', 12],['once','=',1],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');
//                                            $deferred_salary_total = $deferred_salary + $deferred_salary_once;

//                                             $holiday_allow = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1],['allowance_type_id', '=', 22],['once','!=',1]])->sum('allowance_amount');
//                                             $holiday_allow = ($emp_salary/30)*$holiday_allow;
//                                             $holiday_allow_once = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1],['allowance_type_id', '=', 22],['once','=',1],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');
//                                             $holiday_allow_once = ($emp_salary/30)*$holiday_allow_once;
//                                             $holiday_allow_total = $holiday_allow + $holiday_allow_once;

                                            $arrears = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1],['allowance_type_id', '=', 5],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');

                                            $fixed_rebate = Rebate::where([['employee_id', '=', $row1->id],['status', '=', 1],['type', '=', 'fixed']])->sum('rebate_amount');
                                            $other_rebate = Rebate::where([['employee_id', '=', $row1->id],['status', '=', 1],['type', '=', 'other']])->select('nature')->groupBy('nature');

                                            $total_once_allowances = $overall_allowances_once;

                                            // $taxable_allowances = $overall_allowances;
                                            $total_allowances = $overall_allowances + $overall_allowances_once + $bonus_amount + $overtime_total + $attendance_allowance + $tf_allowance;
                                            $fiscal_month_start = '07';
                                            $fiscal_month_end = '06';
                                            $fiscal_year_start = '';
                                            $fiscal_year_end = '';

                                            $dt = date_create($getPayslipMonth);
                                            if($dt->format('m') >= '01' && $dt->format('m') <= '06'):
                                                $fiscal_year = $dt->format('Y');
                                                $fiscal_year_start = $fiscal_year - 1;
                                                $fiscal_year_end = $dt->format('Y');
                                            else:
                                                $fiscal_year = $dt->format('Y');
                                                $fiscal_year_start = $dt->format('Y');
                                                $fiscal_year_end = $fiscal_year_start + 1;
                                            endif;

                                            $fiscal_start_date = $fiscal_year_start.'-'.$fiscal_month_start.'-01';
                                            $fiscal_end_date = $fiscal_year_end.'-'.$fiscal_month_end.'-30';

                                            $year1 = date('Y', strtotime($explodeMonthYear[0].'-'.$explodeMonthYear[1]));
                                            $year2 = date('Y', strtotime($fiscal_year_end.'-'.$fiscal_month_end));

                                            $month1 = date('m', strtotime($explodeMonthYear[0].'-'.$explodeMonthYear[1]));
                                            $month2 = date('m', strtotime($fiscal_year_end.'-'.$fiscal_month_end));

                                            $emp_leaving_date="";
                                            $diff_in_month_leaving=0;
                                            $emp_month = Carbon::createFromFormat('Y-m-d', $row1->joining_date)->month;
                                            $emp_year = Carbon::createFromFormat('Y-m-d', $row1->joining_date)->year;
                                            $pay_month= $explodeMonthYear[1];
                                            $pay_year= $explodeMonthYear[0];

                                            $no_of_months = (($year2 - $year1) * 12) + ($month2 - $month1);

                                            $to = Carbon::createFromFormat('Y-m-d', $row1->joining_date);
                                            $from = Carbon::createFromFormat('Y-m', $getPayslipMonth);
                                            $diff_in_months = $to->diffInMonths($from);
                                            $no_of_months+=1;

                                            if($row1->last_working_date != null && $row1->last_working_date != "0000-00-00") {
                                                $emp_leaving_date=Carbon::createFromFormat('Y-m-d', $row1->last_working_date);
                                                $emp_leaving_year = Carbon::createFromFormat('Y-m-d', $row1->last_working_date)->year;
                                                $to = Carbon::createFromFormat('Y-m-d', $emp_leaving_year."-06-30");
                                                $diff_in_month_leaving=$to->diffInMonths($emp_leaving_date);
                                            }
                                            else {
                                                $diff_in_month_leaving=0;
                                            }

                                            $joining_date = $row1->joining_date;

                                            $ts1 = strtotime($joining_date);
                                            $ts2 = strtotime($fiscal_end_date);

                                            $yearJoining = date('Y', $ts1);
                                            $yearFiscalEnd = date('Y', $ts2);

                                            $joining_month = date('m', $ts1);
                                            $fiscal_end_month = date('m', $ts2);

                                            $diff = (($yearFiscalEnd - $yearJoining) * 12) + ($fiscal_end_month - $joining_month);

                                            if($diff >= 12) {
                                                $diff = 12;
                                            }
                                            else {
                                                $diff += 1;
                                            }

                                            $tax_percent = 0;
                                            $tax_amount = 0;
                                            $balance_taxable_income = 0;
                                            $tax_payable = 0;
                                            $income_tax = 0;
                                            $bonus_amount_taxable=0;
                                            $gross_paid = 0;
                                            $tax_paid = 0;

                                            $income_tax_data = IncomeTax::whereBetween('month_year',[$fiscal_start_date,$fiscal_end_date])
                                                    ->where([['employee_id', '=', $row1->id],['status','=',1],['month_year' ,'<', $lastDateOfMonth ]])
                                                    ->selectRaw("SUM(amount) tax_paid, SUM(salary_paid) salary_paid")->get();

                                            $taxable_amount_data = DB::table('extra_taxable_amount')->whereBetween('month_year',[$fiscal_start_date,$fiscal_end_date])
                                                    ->where([['employee_id', '=', $row1->id],['status','=',1]])
                                                    ->selectRaw("SUM(amount) extra_taxable_amount")->get();

                                            $tax_paid = $income_tax_data ? $income_tax_data[0]->tax_paid : 0;
                                            $gross_paid = $income_tax_data ? $income_tax_data[0]->salary_paid : 0;

                                            $bonus_taxable = BonusIssue::where([['status', '=', 1],['bonus_pay', '=', 1],['employee_id', '=', $row1->id]])
                                                    ->whereBetween('bonus_year',[$fiscal_year_start,$fiscal_year_end]);

                                            if($bonus_taxable->count() > 0):
                                                $bonus_issue_taxable = $bonus_taxable->first();

                                                if($getPayslipMonth >= ($bonus_issue_taxable->bonus_year."-".$bonus_issue_taxable->bonus_month) ){
                                                    if($bonus_issue_taxable->bonus_month > 6){
                                                        $bonus_amount_taxable = $bonus_issue_taxable->bonus_amount;
                                                    }
                                                    else{
                                                        if($bonus_issue_taxable->bonus_year == $fiscal_year_end) {
                                                            $bonus_amount_taxable = $bonus_issue_taxable->bonus_amount;
                                                        }
                                                    }
                                                }
                                            endif;
                                            $operator='';
                                            if($explodeMonthYear[1] >= "07" && $explodeMonthYear[1] <= "12"){
                                                $operator='<=';
                                            }elseif($explodeMonthYear[1] < "07"){
                                                $operator='>=';
                                            }

                                            $overall_allowances_taxable = Allowance::where([['employee_id', '=', $row1->id],['status', '=', 1],['once','=', 0]])
                                                    ->orWhere([['employee_id', '=', $row1->id],['status', '=', 1],['once','=', 1],['month','=', $explodeMonthYear[1]],['year','=', $explodeMonthYear[0]]])
                                                    ->sum('allowance_amount');

                                            $leave_deduction_amount_for_tax=0;
                                            $per_day_salary_for_tax = $emp_salary / cal_days_in_month(CAL_GREGORIAN,$explodeMonthYear[1],$explodeMonthYear[0]);
                                            $leave_deduction_amount_for_tax = ($deduction_days * $per_day_salary_for_tax);

                                            $subtract_amount = 0;

                                            if($explodeMonthYear[1] != "07"){
                                                $promotion = EmployeePromotion::select(DB::raw('sum(increment) as increment,promotion_date'))->where([['employee_id','=',$row1->id],['status','=',1],
                                                        ['promotion_date','<=',$getPayslipMonth."-01"],['promotion_date','>',$fiscal_start_date]])
                                                        ->whereBetween('promotion_date',[$fiscal_start_date,$fiscal_end_date])->orderBy('id', 'desc')->groupBy('promotion_date');
                                                if($promotion->count()>0){
                                                    $d1 = new DateTime($promotion->value('promotion_date'));
                                                    $d2 = new DateTime($fiscal_start_date);
                                                    $interval = $d1->diff($d2);

                                                    $month_diffrence= $interval->m;
                                                    $subtract_amount=($month_diffrence*$promotion->value('increment'));
                                                }
                                            }

                                            if( $emp_location_array[$row1->id] == 1 ) {
                                                $future_salary = ($overall_allowances_taxable + $gross_salary) * $no_of_months;
                                                $taxable_income = ($gross_paid + $future_salary) * 0.9 + $taxable_amount_data[0]->extra_taxable_amount;
                                            }
                                            else {
                                                $future_salary = ($overall_allowances_taxable + $gross_salary) - ($overall_allowances_taxable + $gross_salary) * 0.10;
                                                $taxable_income = ($future_salary * $no_of_months) + $gross_paid + $taxable_amount_data[0]->extra_taxable_amount;
                                            }

                                            $diff = $diff - $diff_in_month_leaving;

                                            //$annual_salary = ($taxable_income * $diff)-($leave_deduction_amount_for_tax+$subtract_amount) + ($overall_allowances_taxable+$bonus_amount_taxable+$overtime);

                                            $account_no = DB::table('employee_bank_data')->select('account_no')->where('status',1)->where('employee_id',$row1->id)->value('account_no');
                                            $account_title = DB::table('employee_bank_data')->select('account_title')->where('status',1)->where('employee_id',$row1->id)->value('account_title');

                                            foreach($tax as $value):
                                                if($taxable_income > $value->salary_range_from && $taxable_income <= $value->salary_range_to):
                                                    $tax_percent = $value->tax_percent;
                                                    $tax_amount = $value->tax_amount;
                                                    $balance_taxable_income = $taxable_income - $value->salary_range_from;
                                                    $tax_payable = (($balance_taxable_income / 100) * $tax_percent) + $tax_amount;


                                                    //rebate calculation starts
                                                    $rebate_amount = 0;
                                                    $rebate_amount += $fixed_rebate;
                                                    $actual_investment = 0;
                                                    if($other_rebate->count() > 0):

                                                        foreach ($other_rebate->get() as $val1):
                                                            //if($val1->type == 'fixed'):
                                                            //    $rebate_amount += $val1->rebate_amount;
                                                            //endif;

                                                            //if($val1->type == 'other'):
                                                            if($val1->nature == 'Charitable / Zakaat Donation us 61'):
                                                                $max_allowed_investment = round($annual_salary * 0.30);
                                                                $rate = ($tax_payable / $annual_salary) * 100;
                                                                $actual_investment = Rebate::where([['employee_id','=', $row1->id],['nature', '=', $val1->nature],['status', '=', 1]])->sum('actual_investment');

                                                                if($actual_investment < 2000000 && $actual_investment < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * $actual_investment) / 100);
                                                                    $base = 'Actual Investment';
                                                                //                                            elseif(2000000 < $actual_investment && 2000000 < $max_allowed_investment):
                                                                //                                                $rebate_amount += round(($rate * 2000000) / 100);
                                                                //                                                $base = '2 Million';
                                                                else:
                                                                    $rebate_amount += round(($rate * $max_allowed_investment) / 100);
                                                                    $base = '30% of Taxable Income';
                                                                endif;
                                                            endif;

                                                            if($val1->nature == 'Investment of Shares and Insurance us 62'):
                                                                $max_allowed_investment = round($annual_salary * 0.20);
                                                                $rate = ($tax_payable / $annual_salary) * 100;
                                                                $actual_investment = Rebate::where([['employee_id','=', $row1->id],['nature', '=', $val1->nature],['status', '=', 1]])->sum('actual_investment');

                                                                if($actual_investment < 2000000 && $actual_investment < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * $actual_investment) / 100);
                                                                    $base = 'Actual Investment';
                                                                elseif(2000000 < $actual_investment && 2000000 < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * 2000000) / 100);
                                                                    $base = '2 Million';
                                                                else:
                                                                    $rebate_amount += round(($rate * $max_allowed_investment) / 100);
                                                                    $base = '20% of Taxable Income';
                                                                endif;

                                                            endif;

                                                            if($val1->nature == 'Bank Investment'):
                                                                $max_allowed_investment = round($annual_salary * 0.20);
                                                                $rate = ($tax_payable / $annual_salary) * 100;
                                                                $actual_investment = Rebate::where([['employee_id','=', $row1->id],['nature', '=', $val1->nature],['status', '=', 1]])->sum('actual_investment');

                                                                if($actual_investment < 2000000 && $actual_investment < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * $actual_investment) / 100);
                                                                    $base = 'Actual Investment';
                                                                elseif(2000000 < $actual_investment && 2000000 < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * 2000000) / 100);
                                                                    $base = '2 Million';
                                                                else:
                                                                    $rebate_amount += round(($rate * $max_allowed_investment) / 100);
                                                                    $base = '20% of Taxable Income';
                                                                endif;
                                                            endif;

                                                            if($val1->nature == 'Health Insurance us 62A'):
                                                                $max_allowed_investment = round($annual_salary * 0.05);
                                                                $rate = ($tax_payable / $annual_salary) * 100;
                                                                $actual_investment = Rebate::where([['employee_id','=', $row1->id],['nature', '=', $val1->nature],['status', '=', 1]])->sum('actual_investment');

                                                                if($actual_investment < 2000000 && $actual_investment < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * $actual_investment) / 100);
                                                                    $base = 'Actual Investment';
                                                                elseif(2000000 < $actual_investment && 2000000 < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * 2000000) / 100);
                                                                    $base = '0.15 Million ';
                                                                else:
                                                                    $rebate_amount += round(($rate * $max_allowed_investment) / 100);
                                                                    $base = '5% of Taxable Income';
                                                                endif;
                                                            endif;

                                                            if($val1->nature == 'Pension us 63'):

                                                                $dateOfBirth = date('d-m-Y',strtotime($row1->emp_date_of_birth));
                                                                $today = date("d-m-Y");
                                                                $diff = date_diff(date_create($dateOfBirth), date_create($today));

                                                                if($diff->format('%y') == 40 ):
                                                                    $difference = 20;
                                                                elseif($diff->format('%y') > 40):

                                                                    $difference  = $diff->format('%y') - 40;
                                                                    $difference = ($difference * 2) + 20;
                                                                endif;

                                                                $max_allowed_investment = round(($annual_salary * $difference) / 100);
                                                                $rate = ($tax_payable / $annual_salary) * 100;
                                                                $actual_investment = Rebate::where([['employee_id','=', $row1->id],['nature', '=', $val1->nature],['status', '=', 1]])->sum('actual_investment');

                                                                if($actual_investment < $max_allowed_investment):
                                                                    $rebate_amount += round(($rate * $actual_investment) / 100);
                                                                    $base = 'Actual Investment';
                                                                else:
                                                                    $rebate_amount += round(($rate * $max_allowed_investment) / 100);
                                                                    $base = '20% of Taxable Income';
                                                                endif;

                                                            endif;
                                                            //endif;
                                                        endforeach;

                                                    endif;
                                                    //rebate calculation ends

                                                    $income_tax = round(($tax_payable - ($tax_paid+$rebate_amount)) / ($no_of_months));

                                                    if($income_tax < 0 || $row1->id == 89):
                                                        $income_tax = 0;
                                                    endif;
                                                endif;
                                            endforeach;

                                            $per_day_salary = $standard_gross / cal_days_in_month(CAL_GREGORIAN,$explodeMonthYear[1],$explodeMonthYear[0]);

                                            $leave_deduction_amount = 0;
                                            if($employment_status_id != 5 || $emp_location_array[$row1->id] != 3):
                                                $leave_deduction_amount = ($deduction_days * $per_day_salary);
                                            endif;

                                            $pf_amount = 0;
                                            $pf_arrears_deferred = 0;
                                            $provident_fund_check =false;
                                            $pf_company_fund = 0;
                                            $pf_employer_fund = 0;
                                            $pf_employee_fund = 0;
                                            $pf_id = 0;

                                            /*if (($row1->confirmation_date != null && $row1->confirmation_date != '0000-00-00') && $row1->confirmation_date <= $getPayslipMonth."-31") {
                                                if ($provident_fund_data->count() > 0):
                                                    $provident_fund_check = true;
                                                    $provident_fund = $provident_fund_data->first();
                                                    if ($provident_fund->id == 0):
                                                        $pf_id = 0;
                                                    else:
                                                        $pf_id = $provident_fund->id;
                                                    endif;

                                                    if ($provident_fund->pf_mode == 'percentage'):
                                                        $pf_company_fund = 0;

                                                        //$pf_company_fund = round(($provident_fund->amount_percent / 100) * (($emp_salary-$leave_deduction_amount) / 3 * 2));
                                                        $pf_employer_fund = round(($provident_fund->amount_percent / 100) * (($emp_salary)));
                                                        $pf_employee_fund = round(($provident_fund->amount_percent / 100) * (($emp_salary)));
                                                        $pf_deferred = round(($provident_fund->amount_percent / 100) * ($deferred_salary_total));
                                                        $pf_arrears = round(($provident_fund->amount_percent / 100) * ($arrears));


                                                        $pf_amount = ($pf_employee_fund + $pf_company_fund+($pf_deferred+$pf_arrears));
                                                        $pf_arrears_deferred=($pf_deferred+$pf_arrears)*2;
                                                    else:
                                                        $pf_amount = $provident_fund->amount_percent;
                                                    endif;
                                                endif;
                                            }*/
                                            $total_deduction = $late_deduction + $loan_perMonthDeduction + $leave_deduction_amount
                                                    + $advance_salary_amount + $penalty + $eobi_deduct + $tf_allowance_paid ;
                                            $net_salary = ($emp_salary + $total_allowances) - $total_deduction;

                                            $total_deduction2 = ($late_deduction + $leave_deduction_amount
                                                    + $pf_amount-$pf_company_fund + $advance_salary_amount + $penalty + $eobi_deduct + $other_deduction+$income_tax);

                                            if($account_no != ''):
                                                $payment_mode = 'Transfer';
                                            elseif($account_no == ''):
                                                $payment_mode = 'Cheque';
                                            endif;
                                            // $net_salary = $attendance_allowance + $net_salary;
                                            ?>
                                            <tr>
                                                <td class="text-center">{{  $counter++ }}</td>
                                                <td class="text-center">{{ $row1->emp_id }}
                                                    <input type="hidden" name="emp_id[]" value="{{$row1->emp_id}}">
                                                    <input type="hidden" name="employeeId[]" value="{{$row1->id}}">
                                                    <input type="hidden" name="department_id[]" value="{{$row1->department_id}}">
                                                    <input type="hidden" name="sub_department_id[]" value="{{$row1->sub_department_id}}">
                                                    <input type="hidden" name="category_id[]" value="{{$row1->category_id}}">
                                                    <input type="hidden" name="project_id[]" value="{{$row1->project_id}}">
                                                    <input type="hidden" name="location_id[]" value="{{$row1->location_id}}">
                                                    <input type="hidden" name="designation_id[]" value="{{$designation_id}}">
                                                    <input type="hidden" name="grade_id[]" value="{{$grade_id}}">
                                                    <input type="hidden" name="deduction_days[]" value="{{$deduction_days}}">
                                                    <input type="hidden" name="standard_gross[]" value="{{$standard_gross}}">
                                                </td>
                                                <td>{{ $emp_name }}</td>
                                                <td>
                                                    @if(array_key_exists($designation_id, $designations)){{ $designations[$designation_id]->designation_name }} @endif
                                                </td>
                                                <td>
                                                    @if(array_key_exists($row1->category_id, $category)){{ $category[$row1->category_id]->employee_category_name }} @endif
                                                </td>
                                                <td class="text-center">{{ $row1->cnic }}</td>
                                                <td class="text-right">{{ number_format($standard_gross, 0) }}</td>
                                                <td class="text-right">{{ number_format($gross_salary,0) }}
                                                    <input type="hidden" name="gross_salary[]" value="{{$gross_salary}}">
                                                </td>

                                                <td class="text-right">{{ number_format($basic_salary,0) }}
                                                    <input type="hidden" name="basic_salary[]" value="{{$basic_salary}}">
                                                </td>

                                                <td class="text-right">{{ number_format($hr_utility_allowance,0) }}
                                                    <input name="hr_utility_allowance[]" type="hidden" value="<?=$hr_utility_allowance?>">
                                                    <input type="hidden"
                                                           onkeyup="payrollCalculation('{{ $row1->id }}','<?php echo $net_salary ?>','<?php echo $loan_perMonthDeduction ?>')" id="total_allowance_{{ $row1->id }}" name="total_allowance[]"
                                                           value="<?= round($overall_allowances_total); ?>" class="form-control field_width" />
                                                    <input type="hidden" id="hidden_allowance_{{ $row1->id }}" name="hidden_allowance[]" value="<?= round($overall_allowances_total); ?>" class="form-control field_width" />
                                                </td>

                                                <td class="text-right">{{ number_format($fix_medical,0) }}
                                                    <input type="hidden" name="fix_medical[]" value="{{$fix_medical}}">
                                                </td>
                                                <td class="text-right">{{ number_format($gez_overtime,0) }}
                                                    <input type="hidden" name="gez_overtime[]" value="{{ $gez_overtime }}">
                                                </td>
                                                <td class="text-right">{{ number_format($overtime,0) }}
                                                    <input type="hidden" name="overtime[]" value="{{ $overtime }}">
                                                </td>
                                                <td class="text-right">{{ number_format($overtime_total,0) }}
                                                </td>
 
                                                <td class="text-right">{{ number_format($attendance_allowance,0) }}
                                                    <input type="hidden" name="attendance_allowance[]" value="{{ $attendance_allowance }}">
                                                </td>
                                                <td class="text-right">{{ number_format($tf_allowance,0) }}
                                                    <input type="hidden" name="allowance_tf[]" value="{{ $tf_allowance }}">
                                                </td>
                                                
                                                @foreach($allowance_types as $val)
                                                    <td class="text-right">
                                                        @php
                                                        $allowance_total = 0;
                                                        $allowance_once = 0;
                                                        $allowanceTotal = 0;
                                                        $allowance_total = Allowance::where([['allowance_type_id', '=', $val->id],['employee_id', '=', $row1->id],['status', '=', 1],['once','!=',1]])->sum('allowance_amount');
                                                        $allowance_total = $allowance_total;

                                                        $allowance_once = Allowance::where([['allowance_type_id', '=', $val->id],['employee_id', '=', $row1->id],['status', '=', 1],['once','=',1],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');
                                                        $allowance_once = $allowance_once;

                                                        $allowanceTotal = (($allowance_total + $allowance_once) / $total_month_days) * $days;
                                                        @endphp
                                                        {{ number_format($allowanceTotal,0) }}
                                                        <input type="hidden" name="{{ str_replace("$","",$val->name) }}[]" value="{{$allowanceTotal}}">
                                                    </td>
                                                @endforeach
                                                <input type="hidden" name="overall_allowances[]" value="{{$total_allowances}}">
                                                <td class="text-right">{{ number_format($bonus_amount,0) }}
                                                    <input type="hidden" name="bonus_amount[]" value="{{$bonus_amount}}">
                                                </td>

                                                <td class="text-right">{{ number_format($gross_salary+$total_allowances,0) }}
                                                    <input type="hidden" name="gross_with_allowance[]" value="{{$gross_salary+$total_allowances}}">
                                                </td>
                                                <!--<td class="text-right">{{ number_format($gross_paid,0) }}
                                                    <input type="hidden" name="gross_paid[]" value="{{$gross_paid+$gross_salary+$total_allowances}}">
                                                    <input type="hidden" name="salary_paid[]" value="{{$gross_salary+$total_allowances}}">
                                                </td>
                                                <td class="text-right">{{ number_format($future_salary,0) }}
                                                    <input type="hidden" name="future_salary[]" value="{{$future_salary}}">
                                                </td>
                                                <td class="text-right">{{number_format($taxable_income,0)}}
                                                    <input type="hidden" name="annual_salary[]" value="{{$taxable_income}}">
                                                </td>

                                                <td class="text-right">{{number_format($tax_payable,0)}}
                                                    <input type="hidden" name="tax_payable[]" value="{{$tax_payable}}">
                                                </td>
                                                <td class="text-right">{{ number_format($rebate_amount,0) }}
                                                    <input type="hidden" name="rebate_amount[]" value="{{$rebate_amount}}">
                                                </td>

                                                <td  class="text-right">{{number_format($tax_paid ,0)}}
                                                    <input type="hidden" name="tax_paid[]" value="{{$tax_paid}}">
                                                    <input type="hidden" name="tax_percent[]" value="{{$tax_percent}}">
                                                </td>
                                                <td class="text-right">{{number_format(($tax_payable - $tax_paid)-$rebate_amount ,0)}}
                                                    <input type="hidden" name="tax_balance[]" value="{{($tax_payable - $tax_paid)-$rebate_amount}}">
                                                </td>
                                                
                                                <?php

                                                    if($payroll_deduction->value('present_days') == 0 && $payroll_deduction->value('total_leaves_count') != $total_month_days):
                                                        $income_tax = 0;
                                                        $gross_salary = 0 ;
                                                        $total_allowances = 0 ;
                                                        $income_tax = 0 ;
                                                        $advance_salary_amount = 0 ;
                                                        $eobi_deduct = 0 ;
                                                        $leave_deduction_amount = 0 ;
                                                        $late_deduction = 0 ;
                                                        $penalty = 0 ;
                                                        $other_deduction = 0 ;
                                                        $loan_perMonthDeduction = 0 ;
                                                        $total_deduction = 0 ;
                                                        $net_salary = 0 ;
                                                    endif;

                                                ?>

                                                <td  class="text-right"><p class="show_data income_tax_{{ $row1->id }}">{{number_format($income_tax)}}</p>
                                                    <input type="number"  class="form-control field_width" onkeyup="payrollCalculation('{{ $row1->id }}','{{ $gross_salary+$total_allowances }}','{{number_format($income_tax,0)}}','{{ round($advance_salary_amount + $eobi_deduct + $leave_deduction_amount + $late_deduction + $penalty + $other_deduction) }}',event)" min="0" id="income_tax_{{ $row1->id }}" name="income_tax[]" value="{{ $income_tax }}">
                                                </td>-->

                                                <!--<td class="text-center">-->
                                                <!--    {{ number_format($pf_employee_fund,0) }}-->
                                                <!--</td>-->
                                                <!--<td class="text-center">-->
                                                <!--    {{ number_format($pf_company_fund,0) }}-->
                                                <!--</td>-->
                                                <!--<td class="text-center">-->
                                                <!--    {{ number_format($pf_arrears_deferred,0) }}-->
                                                <!--</td>
                                                <td class="text-center">
                                                    {{ number_format($pf_amount-$pf_company_fund,0) }}
                                                        <input type="hidden" name="pf_amount[]" value="{{$pf_amount-$pf_company_fund}}">
                                                </td>-->

                                                <td class="text-right">{{ number_format($advance_salary_amount,0) }}
                                                    <input type="hidden" name="advance_salary_amount[]" value="{{$advance_salary_amount}}">
                                                </td>
                                                <td class="text-right">{{ number_format($eobi_deduct,0) }}
                                                    <input type="hidden" name="eobi_deduct[]" value="{{$eobi_deduct}}">
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" id="loan_id_{{ $row1->id }}" name="loan_id[]" value="{{ $loan_id }}" class="form-control" />

                                                    @if($loan_request->count() > 0)
                                                        <p class="show_data">{{ $loan_perMonthDeduction }}</p>
                                                        <input onkeyup="payrollCalculation('{{ $row1->id }}','{{ $gross_salary+$total_allowances }}','{{ $loan_perMonthDeduction }}','{{ round($advance_salary_amount + $eobi_deduct + $leave_deduction_amount + $late_deduction + $penalty + $other_deduction) }}',event)" min="0" type="number" id="loan_amount_{{ $row1->id }}" name="loan_amount[]" max="{{ $total_remaining_amount }}" value="{{ $loan_perMonthDeduction }}" class="form-control field_width" />
                                                    @else
                                                        0
                                                        <input type="hidden" id="loan_amount_{{ $row1->id }}" name="loan_amount[]" value="{{ $loan_perMonthDeduction }}" class="form-control field_width" />
                                                    @endif
                                                </td>
                                                <td class="text-right">{{ number_format($tf_allowance_paid,0) }}
                                                    <input type="hidden" name="tf_deduction[]" value="{{$tf_allowance_paid}}">
                                                </td>
                                                <td class="text-right">{{ number_format($leave_deduction_amount,0) }}
                                                    <input type="hidden" name="leave_deduction_amount[]" value="{{$leave_deduction_amount}}">
                                                </td>
                                                <td class="text-right">{{ number_format($late_deduction,0) }}
                                                    <input type="hidden" name="late_deduction[]" value="{{$late_deduction}}">
                                                </td>
                                                <td class="text-right">{{ $penalty ?? 0 }}
                                                    <input type="hidden" name="penalty[]" value="{{$penalty}}">
                                                </td>

                                                <td class="text-right">{{ $other_deduction ?? 0 }}
                                                    <input type="hidden" name="other_deduction[]" value="{{$other_deduction}}">
                                                </td>
                                                <td class="text-right">
                                                    <span class="total_deduction_{{ $row1->id }}"><?= number_format(round($total_deduction),0)?></span>
                                                    <input class="total_deduction_{{ $row1->id }}" type="hidden" name="total_deduction[]" value="{{$total_deduction}}">
                                                </td>
                                                <input type="hidden" onkeyup="payrollCalculation('{{ $row1->id }}','<?php echo $net_salary ?>','<?php echo $loan_perMonthDeduction ?>')" id="other_amount_{{ $row1->id }}" name="other_amount[]" value="0" class="form-control field_width">
                                                <td class="text-right">
                                                    <span class="net_salary2_{{ $row1->id }}"><?= number_format(round($net_salary),0)?></span>
                                                    <input class="net_salary2_{{ $row1->id }}" type="hidden" name="net_salary[]" value="{{$net_salary}}">
                                                </td>

                                                <td class="text-center">{{ $account_no }}
                                                    <input type="hidden" name="account_no[]" value="{{ $account_no }}" />
                                                    <input type="hidden" name="account_title[]" value="{{ $account_title }}" />

                                                    <input type="hidden" name="pf_arrears_deferred[]" value="{{ $pf_arrears_deferred }}" />
                                                    <input type="hidden" name="pf_employee_fund[]" value="{{ $pf_employee_fund }}" />
                                                    <input type="hidden" name="pf_employer_fund[]" value="{{ $pf_employer_fund }}" />
                                                    <input type="hidden" name="pf_amount[]" value="{{ $pf_amount }}" />
                                                    <input type="hidden" name="provident_fund_check[]" value="{{ $provident_fund_check }}" />

                                                </td>
                                                <td class="text-center">
                                                    <select name="payment_mode[]" id="payment_mode_{{ $row1->id }}" class="form-control field_width">
                                                        <option @if($account_no != '') selected @endif value="Transfer">Transfer</option>
                                                        <option @if($account_no == '') selected @endif value="Cheque">Cheque</option>z
                                                        <option @if($account_no == '') selected @endif value="Cash">Cash</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" id="hold_salary_{{ $row1->id }}" name="hold_salary[{{ $row1->id }}]" value="1">
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <input type="submit" name="submit" class="btn btn-sm btn-success" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>