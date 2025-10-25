<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\EmployeeCategory;
use App\Models\Regions;
use App\Models\EmployeePromotion;
use App\Models\IncomeTax;


$count_fuel_allow= 0;
$count_other_allow= 0;
$count_school_fee= 0;
$count_mobile= 0;
$count_deferred_salary= 0;
$count_overtime= 0;
$count_car_allow= 0;
$count_yearly_tax= 0;
$count_tax_paid= 0;
$count_balance= 0;
$count_lateness= 0;


$count_vr_other_allow = 0;
$count_medical = 0;
$count_hr_utlilty = 0;
$count_total_salary = 0;
$count_gross_salaries = 0;
$count_ot_amount = 0;
$count_deduction_day_amount = 0;
$count_deposit = 0;
$count_loan = 0;
$count_bonus = 0;
$count_tax = 0;
$count_eobi = 0;
$count_pf_amount = 0;

$count_extra_allowances = 0;
$count_deduction = 0;
$count_net_salary = 0;
$current_date = date('Y-m-d');
$count_ot_hours_amount = 0;
$count_other_deduction = 0;
$count_vehicle_addit_ = 0;
$count_taxable_salary= 0;
$count_yearly_tax= 0;
$count_monthly_tax= 0;
$count_tax_paid= 0;
$count_balance= 0;
$count_rebate= 0;
$count_advance_salary = 0;
$count_other_allowance=0;
$count_arrear=0;
$count_allowance=0;
$grossSalaryWithAllownace=0;
$totalGrossSalaryWithAllownace=0;
$count_lwp=0;
$count_penalty=0;
$count_other=0;






$grand_fuel_allow=0;
$grand_other_allow=0;
$grand_school_fee=0;
$grand_mobile=0;
$grand_deferred_salary=0;
$grand_overtime=0;
$grand_car_allow=0;
$grand_yearly_tax=0;
$grand_tax_paid=0;
$grand_balance=0;
$grand_lateness=0;

$grand_gross_salaries = 0;
$grand_gross_with_allowance = 0;
$grand_advance_salary = 0;
$grand_m_p_fund= 0;
$grand_m_i_tax = 0;
$grand_vehicle_addit = 0;
$grand_vr_other_allow = 0;
$grand_medical = 0;
$grand_hr_utlilty = 0;
$grand_loan_amount_paid  = 0;
$grand_total_salary = 0;
$grand_ot_amount = 0;
$grand_deduction_day_amount = 0;
$grand_loan = 0;
$grand_bonus = 0;
$grand_tax = 0;
$grand_eobi = 0;
$grand_pf_amount = 0;
$grand_allowance = 0;
$grand_other_amount = 0;
$grand_extra_allowances = 0;
$grand_deduction = 0;
$grand_net_salary = 0;
$current_date = date('Y-m-d');
$grand_ot_hours_amount = 0;
$grand_cheque_amount = 0;
$grand_cash_amount = 0;
$grand_other_deduction = 0;
$grand_arrears=0;
$grand_other_allowance=0;
$grand_vr_allow=0;
$grand_lwp=0;
$grand_penalty=0;
$grand_other=0;
$grandTaxableSalary=0;
$grand_annual_salary=0;
$grand_tax_amount_monthly=0;
$grand_tax_paid=0;
$grand_balance_tax_amount=0;
$grand_rebate_tax=0;



$month_year = Input::get('month_year');

?>
<style>

    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }
    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead td {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }



td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="PrintEmployeeAttendanceList">
                @foreach($companiesList as $companyData)
                    <?php echo CommonHelper::headerPrintSectionInPrintView($companyData->id);?>
                        <?php
                        $count =1;
                        CommonHelper::companyDatabaseConnection($companyData->id);
                        $departments = Employee::select('department_id')->groupBy('department_id')->get()->toArray();
                        ?>
                        @foreach($departments as $regionsValue)
                            <?php
                                if(array_key_exists($regionsValue["department_id"], $DepartmentArray)):
                                    $department_name = $DepartmentArray[$regionsValue["department_id"]];
                                else:
                                    $department_name = '';
                                endif;
                            ?>
                            <div class="table-responsive wrapper pagebreak">
                            <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" >
                                <thead >
                                <tr class="hide-table" id="hide-table-row">
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'colspan="10"></td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'colspan="1" class="hidden-print"></td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'colspan="9" class="text-center">Allowances</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'colspan="10" class="text-center">Deductions</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'colspan="7"></td>
                                </tr>
                                <tr>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">S No.</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Emp ID </td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Emp Name </td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Designation </td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Present</td>
    
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Gross </td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Basic</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">House Rent</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Utility</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Fuel</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Others</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">School Fee</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Mobile</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Bonus</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Arrears of deferred salary/increment</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Overtime</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Car Allow</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Gross With Allowance</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Taxable Salary</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Tax Amount (<small>Yearly</small>)</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Rebate Amount</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Tax Paid</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Balance</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center ">Tax Amount (<small>Monthly</small>)</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">RPF</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Adv Salary</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">EOBI </td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Loan</td>

                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">LWP</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Lateness</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Penalty</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Others</td>
                                    <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Deduction</td>
                                    <td style='color:black;font-weight:bold;bacground:#f9f9f9;'class="text-center">Net Salary </td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr style="background-color: #f5f3ff;" >
                                    <td class="text-center" colspan="10">
                                        <div >
                                            <h3>
                                                <b><?= $department_name ?></b>
                                            </h3>
                                        </div>
                                    </td>

                                    <td class="text-center hidden-print" colspan="2"> </td>

                                    <td class="text-center" colspan="10">
                                        <div >
                                            <h3>
                                                <b><?= $department_name ?></b>
                                            </h3>
                                        </div>
                                    </td>
                                    <td class="text-center hidden-print" colspan="2"> </td>
                                    <td class="text-center" colspan="10">
                                        <div >
                                            <h3>
                                                <b><?= $department_name ?></b>
                                            </h3>
                                        </div>
                                    </td>
                                    <td class="text-center hidden-print" colspan="3"> </td>
                                </tr>
                                <?php
                                CommonHelper::companyDatabaseConnection($companyData->id);
                                $all_emp = Employee::select("id","designation_id","cnic","emp_id","salary","eobi_id","emp_name","date_of_birth","emp_father_name")
                                    ->whereRaw("status != 2 AND DATE_FORMAT(joining_date,'%Y-%m') <= '$monthYearDay'")
                                    ->where([["department_id","=",$regionsValue["department_id"]]])->orderBy('id')
                                    ->get()->toArray();
                                ?>
                                @foreach($all_emp as $value)
                                    <?php

                                    $annual_salary=0;
                                    $tax_amount_yearly=0;
                                    $tax_amount_monthly=0;
                                    $balance_tax_amount=0;
                                    $tax_paid=0;
                                    $rebate_tax=0;
                                    $has_tax=0;

                                    $designation_id = $value['designation_id'];
                                    $employeeCurrentPositions = EmployeePromotion::select('designation_id')->where([['employee_id','=',$value['id']],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

                                    if($employeeCurrentPositions->count() > 0):
                                        $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                                        $designation_id = $employeeCurrentPositionsDetail->designation_id;
                                    endif;
                                    //CommonHelper::reconnectMasterDatabase();
                                    //  $empCategory = HrHelper::getMasterTableValueById($companyData->id,'employee_category','employee_category_name',$value["employee_category_id"]);
                                    //$emp_designation =  HrHelper::getMasterTableValueById($companyData->id,'designation','designation_name', $designation_id);
                                    //CommonHelper::companyDatabaseConnection($companyData->id);
                                    $emp_name=$value["emp_name"];

                                    $fiscal_month_start = '07';
                                    $fiscal_month_end = '06';
                                    $fiscal_year_start = '';
                                    $fiscal_year_end = '';

                                    $dt = date_create($month_year);
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

                                    $payslip = Payslip::where([['employee_id','=',$value["id"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);
                                   // $payroll = PayrollData::where([['emp_id','=',$value["emp_id"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);
                                    if(array_key_exists($designation_id, $designationArray)):
                                        $emp_designation = $designationArray[$designation_id];
                                    else:
                                        $emp_designation = '';
                                    endif;

                                    if($payslip->count() > 0):
                                    //$payroll_data =$payroll->first();
                                    $payslip =$payslip->first();

                                    $tax_paid = Payslip::whereBetween('month_year',[$fiscal_start_date,$fiscal_end_date])->where([['month_year','<',$month_year]])
                                        ->where([['employee_id', '=', $payslip->employee_id],['status','=',1]])->sum('tax_amount');
                                    $has_tax = Payslip::whereBetween('month_year',[$fiscal_start_date,$fiscal_end_date])->where([['month','=',$explodeMonthYear[1]],['year', '=', $explodeMonthYear[0]]])
                                        ->where([['employee_id', '=', $payslip->employee_id],['status','=',1]])->sum('tax_amount');
                                    if($has_tax != 0){
                                        $tax= IncomeTax::where([['employee_id', '=',$payslip->employee_id],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
                                        if($tax->count()>0){
                                            $income_tax=$tax->first();
                                            $annual_salary=$income_tax->annual_salary;
                                            $rebate_tax=$income_tax->rebate_amount;
                                            $tax_amount_yearly=$income_tax->tax_payable;
                                            $tax_amount_monthly=$income_tax->amount;
                                            $balance_tax_amount=($income_tax->balance_tax_amount+$income_tax->amount)-$rebate_tax;

                                        }else{
                                            $rebate_tax=0;
                                            $annual_salary=$payslip['taxable_salary'];
                                            $tax_amount_yearly=$payslip['tax_amount_yearly'];
                                            $tax_amount_monthly=$payslip['tax_amount'];
                                            $balance_tax_amount=$payslip['balance']-$rebate_tax;

                                        }
                                    }

                                    $count_fuel_allow+=$payslip->fuel_allowance;
                                    $count_other_allow+=$payslip->other_allowance;
                                    $count_school_fee+=$payslip->school_fees;
                                    $count_mobile+=$payslip->mobile;
                                    $count_deferred_salary+=$payslip->deferred_salary;
                                    $count_overtime+=$payslip->overtime;
                                    $count_car_allow+=$payslip->car_allowance;
                                    $count_yearly_tax+=$payslip->tax_amount_yearly;
                                    $count_tax_paid+=$payslip->tax_paid;
                                    $count_balance+=$payslip->balance;
                                    $count_lateness+=$payslip->lateness;

                                    $count_gross_salaries+=$payslip->gross_salaries;
                                    $count_total_salary+=$payslip->basic_salary;
                                    $count_ot_hours_amount+=$payslip->total_ot_hours_amount;
                                    $count_ot_amount+=$payslip->ot_amount;
                                    $count_deduction_day_amount+=$payslip->deduction_days_amount;
                                    $count_other_deduction+=$payslip->other_deduction;
                                    $count_vr_other_allow+=$payslip->vr_other_allow;
                                    $count_other_allowance+=$payslip->other_allowance;
                                    $count_arrear+=$payslip->Arrears;
                                    $count_loan+=$payslip->loan_amount_paid;

                                    $count_medical+=$payslip->fix_medical;
                                    $count_hr_utlilty+=$payslip->hr_utility_allowance;
                                    $count_tax+=$payslip->tax_amount;
                                    $count_eobi+=$payslip->eobi_amount;
                                    $count_pf_amount+=$payslip->pf_amount;
                                    //$count_allowance+=$payslip->total_allowance;
                                    $count_extra_allowances +=$payslip->extra_allowance;
                                    $count_deduction+=$payslip->total_deduction;
                                    $count_net_salary+=$payslip->net_salary;
                                    $count_vehicle_addit_+=$payslip->vehicle_addit_;
                                    $count_taxable_salary+=$annual_salary;
                                    $count_yearly_tax+= $tax_amount_yearly;
                                    $count_monthly_tax+= $tax_amount_monthly;
                                    $count_tax_paid+= $tax_paid;
                                    $count_balance+= $balance_tax_amount;
                                    $count_rebate+= $rebate_tax;
                                    $count_advance_salary +=$payslip->advance_salary_amount;
                                    $count_lwp += ($payslip->lwp_deduction+$payslip->deduction_days_amount);
                                    $count_penalty += $payslip->penalty;
                                    $count_other += $payslip->other_deduct;

                                    $grand_total_salary+=$payslip->total_salary;
                                    $grand_ot_amount+=$payslip->ot_amount;
                                    $grand_deduction_day_amount+=$payslip->deduction_days_amount;
                                    $grand_other_deduction+=$payslip->other_deduction;
                                    $grand_loan+=$payslip->loan_amount_paid;
                                    $grand_bonus+=$payslip->bonus_amount;
                                    $grand_tax+=$payslip->tax_amount;
                                    $grand_eobi+=$payslip->eobi_amount;
                                    $grand_pf_amount+=$payslip->pf_amount;
                                    $grand_allowance+=$payslip->total_allowance;
                                    $grand_other_amount+=$payslip->other_amount;
                                    $grand_extra_allowances+=$payslip->extra_allowance;
                                    $grand_deduction+=$payslip->total_deduction;
                                    $grand_net_salary+=$payslip->net_salary;
                                    $grand_ot_hours_amount+=$payslip->total_ot_hours_amount;
                                    $grand_hr_utlilty += $payslip->hr_utility_allowance;
                                    $grand_medical += $payslip->fix_medical;
                                    $grand_vr_allow += $payslip->vr_other_allow;
                                    $grand_other_allowance += $payslip->other_allowance;
                                    $grand_arrears += $payslip->Arrears;
                                    $grand_gross_salaries +=$payslip->gross_salaries;
                                    $grandTaxableSalary+=$payslip->taxable_salary;

                                    $grand_vr_other_allow+=$payslip->vr_other_allow;
                                    $grand_lwp += ($payslip->lwp_deduction+$payslip->deduction_days_amount);
                                    $grand_penalty += $payslip->penalty;
                                    $grand_other += $payslip->other_deduct;
                                    $grand_vehicle_addit +=$payslip->vehicle_addit_;
                                    $grand_m_i_tax += $payslip->tax_amount;
                                    $grand_m_p_fund += $payslip->pf_amount;
                                    $grand_advance_salary +=$payslip->advance_salary_amount;
                                    $count_bonus += $payslip->bonus_amount;
                                    $grand_loan_amount_paid += $payslip->loan_amount_paid;

                                    $grand_fuel_allow+=$payslip->fuel_allowance;
                                    $grand_other_allow+=$payslip->other_allowance;
                                    $grand_school_fee+=$payslip->school_fees;
                                    $grand_mobile+=$payslip->mobile;
                                    $grand_deferred_salary+=$payslip->deferred_salary;
                                    $grand_overtime+=$payslip->overtime;
                                    $grand_car_allow+=$payslip->car_allowance;
                                    $grand_yearly_tax+=$tax_amount_yearly;
                                    $grand_annual_salary+=$annual_salary;
                                    $grand_tax_amount_monthly+=$tax_amount_monthly;
                                    $grand_tax_paid+=$tax_paid;
                                    $grand_balance_tax_amount+=$balance_tax_amount;
                                    $grand_rebate_tax+=$rebate_tax;

                                    $grand_lateness+=$payslip->lateness;

                                    $count_allowance=($payslip->fuel_allowance+$payslip->other_allowance+$payslip->school_fees+$payslip->mobile+$payslip->deferred_salary+$payslip->overtime+$payslip->car_allowance+$payslip->bonus_amount);
                                    $grossSalaryWithAllownace=($payslip->gross_salaries+$count_allowance);

                                    $totalGrossSalaryWithAllownace+=$grossSalaryWithAllownace;
                                    $grand_gross_with_allowance+=$grossSalaryWithAllownace;

                                    if($payslip->payment_mode == 'Transfer'):
                                        $grand_cash_amount+=$payslip->net_salary;
                                    else:
                                        $grand_cheque_amount+=$payslip->net_salary;
                                    endif;
                                    ?>
                                    <tr class="text-center">
                                        <td>{{ $count++ }}</td>
                                        <td class="hidden-print">{{ $payslip->emp_id }}</td>
                                        <td>{{ $value["emp_name"] }}</td>
                                        <td>{{ $emp_designation }}</td>
                                        <td class="hidden-print">{{ $payslip->present }}</td>
                                        
                                        <td>{{ number_format($payslip->gross_salaries) }}</td>
                                        <td>{{ number_format($payslip->basic_salary,0) }}</td>
                                        <td>{{ number_format($payslip->hr_utility_allowance,0) }}</td>
                                        <td>{{ number_format($payslip->fix_medical,0) }}</td>
                                        <td>{{ number_format($payslip->fuel_allowance,0) }}</td>
                                        <td>{{ number_format($payslip->other_allowance,0) }}</td>
                                        <td>{{ number_format($payslip->school_fees,0)  }}</td>
                                        <td>{{ number_format($payslip->mobile,0) }}</td>
                                        <td>{{ number_format($payslip->bonus_amount,0) }}</td>
                                        <td>{{ number_format($payslip->deferred_salary,0) }}</td>
                                        <td>{{ number_format($payslip->overtime,0) }}</td>
                                        <td>{{ number_format($payslip->car_allowance,0) }}</td>
                                        <td>{{ number_format($grossSalaryWithAllownace,0) }}</td>
                                        <td class="hidden-print">{{ number_format($annual_salary,0)}}</td>
                                        <td class="hidden-print">{{ number_format($tax_amount_yearly,0) }}</td>
                                        <td class="hidden-print">{{ number_format($rebate_tax,0) }}</td>
                                        <td class="hidden-print">{{ number_format($tax_paid,0) }}</td>
                                        <td class="hidden-print">{{ number_format($balance_tax_amount,0) }}</td>
                                        <td>{{ number_format($tax_amount_monthly,0) }}</td>
                                        <td>{{ number_format($payslip->pf_amount,0) }}</td>
                                        <td>{{ number_format($payslip->advance_salary_amount,0) }}</td>
                                        <td>{{ number_format($payslip->eobi_amount,0) }}</td>
                                        <td>{{ number_format($payslip->loan_amount_paid,0)}}</td>

                                        <td>{{ number_format($payslip->lwp_deduction,0) }}</td> <!-- +$payslip->deduction_days_amount) -->
                                        <td>{{ number_format($payslip->lateness,0) }}</td>
                                        <td>{{ number_format($payslip->penalty,0) }}</td>
                                        <td>{{ number_format($payslip->other_deduct,0) }}</td>

                                        <td>{{ number_format($payslip->total_deduction,0) }}</td>
                                        <td>{{ number_format($payslip->net_salary,0) }}</td>
                                    </tr>
                                    <?php else:
                                        $recordNotFound[] = "<tr class='text-center'><td colspan='27'><b style='color:red;'> $emp_name Payroll Not Found !</b></td></tr>";
                                    endif; ?>
                                @endforeach
                                <?php //$count=1;?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <b>xxxx</b>
                                    </td>
                                    <td colspan="2" class="text-center hidden-print">

                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php
                                            echo number_format($count_gross_salaries);
                                            $count_gross_salaries=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php
                                            echo number_format($count_total_salary);
                                            $count_total_salary = 0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php
                                            echo number_format($count_hr_utlilty,0);
                                            $count_hr_utlilty = 0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php
                                            echo number_format($count_medical,0);
                                            $count_medical = 0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php
                                           echo number_format($count_fuel_allow,0);
                                            $count_fuel_allow = 0;
                                            ?>
                                        </b>
                                    </td>
                                    <td  class="text-center">
                                        <b><?php echo number_format($count_other_allow ,0);
                                            $count_other_allow=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td  class="text-center">
                                        <b><?php echo number_format($count_school_fee ,0);
                                            $count_school_fee=0;
                                        ?>
                                        </b>
                                    </td>
                                    <td  class="text-center">
                                        <b><?php echo number_format($count_mobile,0);
                                            $count_mobile=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_bonus,0); $count_bonus='0';?></b>
                                    </td>
                                    <td  class="text-center">
                                        <b><?php echo number_format($count_deferred_salary,0);
                                            $count_deferred_salary=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td  class="text-center">
                                        <b><?php echo number_format($count_overtime,0);
                                            $count_overtime=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td  class="text-center">
                                        <b><?php echo number_format($count_car_allow,0);
                                            $count_car_allow=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php
                                            echo number_format($totalGrossSalaryWithAllownace,0);
                                            $totalGrossSalaryWithAllownace = 0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center hidden-print">
                                      <b><?php
                                        echo number_format($count_taxable_salary,0);
                                        $count_taxable_salary = 0;
                                        ?>
                                    </b>
                                    </td>
                                    <td colspan="" class="text-center hidden-print">
                                        <b><?php
                                            echo number_format($count_yearly_tax,0);
                                            $count_yearly_tax = 0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center hidden-print">
                                        <b><?php
                                            echo number_format($count_rebate,0);
                                            $count_rebate = 0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center hidden-print">
                                        <b>
                                            <?php
                                            echo number_format($count_tax_paid,0);
                                            $count_tax_paid=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center hidden-print">
                                        <b>
                                            <?php
                                            echo number_format($count_balance,0);
                                            $count_balance=0;
                                            ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b>
                                            <?php
                                                echo number_format($count_monthly_tax,0);
                                            $count_monthly_tax=0;
                                             ?>
                                        </b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_pf_amount,0); $count_pf_amount=0;?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_advance_salary,0) ; $count_advance_salary=0;?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_eobi,0); $count_eobi='0';?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_loan,0); $count_loan='0';?></b>
                                    </td>

                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_lwp,0); $count_lwp='0';?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_lateness,0); $count_lateness='0';?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_penalty,0); $count_penalty='0';?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_other,0); $count_other='0';?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_deduction,0); $count_deduction=0;?></b>
                                    </td>
                                    <td colspan="" class="text-center">
                                        <b><?php echo number_format($count_net_salary,0); $count_net_salary='0';?></b>
                                    </td>
                                </tr>

                                <?php CommonHelper::reconnectMasterDatabase(); ?>
                                </tbody>
                            </table>
                        </div>

                        @endforeach

                @endforeach
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" >
                        <thead>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center" colspan="24"><b style="font-size:30px;text-decoration: underline">Grand Total</b></td>
                        </thead>
                        <thead>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Gross Salary </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Basic Salary</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total House Rent</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Utility</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Fuel</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Other</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total School Fee</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Mobile</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Bonus </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Arrears of deferred salary/increment</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Overtime</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Car Allow</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Gross With Allowance</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Total Taxable Salary </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Total Tax Amount (<small>Yearly</small> ) </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Total Rebate Amount</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Total Tax Paid</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">Total Balance </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Tax Amount (<small>Monthly</small>)</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total RPF </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Advances</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total EOBI </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Loan </td>

                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total LWP</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Lateness</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Penalty</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Other</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Total Deduction </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">Net Salary</td>
                        </thead>
                        <tfoot>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-right" colspan="4">Total Transfer Amount &nbsp;&nbsp;</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-right" colspan="2">&nbsp;{{ number_format($grand_cash_amount,0) }}</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-right" colspan="3">Total Cheque Amount &nbsp;&nbsp;</td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-right" colspan="2">&nbsp;{{ number_format($grand_cheque_amount,0) }}</td>
                        <td  colspan="13" style='color:black;font-weight:500;background:#f9f9f9;'></td>
                        </tfoot>
                        <tfoot>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_gross_salaries,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_total_salary,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_hr_utlilty,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_medical,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_fuel_allow,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_other_allow,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_school_fee,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_mobile,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_bonus,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_deferred_salary,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_overtime,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_car_allow,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_gross_with_allowance,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">
                            <b>{{ number_format($grand_annual_salary,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">
                            <b>{{ number_format($grand_yearly_tax,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">
                            <b>{{ number_format($grand_rebate_tax,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">
                            <b>{{ number_format($grand_tax_paid,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center hidden-print">
                            <b>{{ number_format($grand_balance_tax_amount,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_tax_amount_monthly,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_m_p_fund,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_advance_salary,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_eobi,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_loan_amount_paid,0) }}</b>
                        </td>

                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_lwp,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_lateness,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_penalty,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_other,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_deduction,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:500;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_net_salary,0) }}</b>
                        </td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lineHeight">&nbsp;</div>
