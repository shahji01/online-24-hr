<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\EmployeePromotion;
use App\Models\IncomeTax;

$grand_salary = 0;
$grand_gross_salaries = 0;
$grand_basic_salary = 0;
$grand_hr_utility_allowance = 0;
$grand_fix_medical = 0;
$grand_gez_overtime = 0;
$grand_food_allowance = 0;
$grand_holiday_allowance = 0;
$grand_reimbursement_allowance = 0;
$grand_leave_encash_allowance = 0;
$grand_expense_allowance = 0;
$grand_commission_allowance = 0;
$grand_station_visit_allowance = 0;
$grand_outstation_visit_allowance = 0;
$grand_installment_allowance = 0;
$grand_fuel_allowance = 0;
$grand_other_allowance = 0;
$grand_school_fees = 0;
$grand_mobile = 0;
$grand_maintenance = 0;
$grand_bonus_amount = 0;
$grand_deferred_salary = 0;
$grand_overtime = 0;
$grand_car_allowance = 0;
$grand_arrears_amount = 0;
$grand_gross_with_allowances = 0;
$grand_gross_with_allowances = 0;
$grand_annual_salary = 0;
$grand_tax_amount_yearly = 0;
$grand_rebate_tax = 0;
$grand_tax_paid = 0;
$grand_balance_tax_amount = 0;
$grand_tax_amount_monthly = 0;
$grand_advance_salary_amount = 0;
$grand_loan_amount_paid = 0;
$grand_lwp_deduction = 0;
$grand_lateness = 0;
$grand_penalty = 0;
$grand_other_deduct = 0;
$grand_total_deduction = 0;
$grand_hold_salary = 0;
$grand_net_salary = 0;
$grand_cash_amount = 0;
$grand_cheque_amount = 0;
$count = 1;

?>
<style>
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

</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printList">
        <div class="table-responsive wrapper">
            <table class="table table-sm mb-0 table-bordered table-striped tableFixHead table-hover" id="exportList">
                <thead>
                <tr>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp ID </th>
                    <th class="text-center">Emp Name </th>
                    <th class="text-center">Designation</th>
                    <th class="text-center">Cost Center</th>
                    <th class="text-center">Cnic</th>
                    <th class="text-center">Standard Gross</th>
                    <th class="text-center">Gross </th>
                    <th class="text-center">Basic Salary</th>
                    <th class="text-center">House Rent</th>
                    <th class="text-center">Utility</th>
                    <th class="text-center">Gez Overtime</th>
                    <th class="text-center">Food Allow</th>
                    <th class="text-center">Holiday Allow</th>
                    <th class="text-center">Reimbursement</th>
                    <th class="text-center">Leave Encash</th>
                    <th class="text-center">Expense</th>
                    <th class="text-center">Commission</th>
                    <th class="text-center">Station Visit</th>
                    <th class="text-center">Outstation Visit</th>
                    <th class="text-center">Installment/Depreciation</th>
                    <th class="text-center">Fuel</th>
                    <th class="text-center">Others</th>
                    <th class="text-center">School Fee</th>
                    <th class="text-center">Mobile</th>
                    <th class="text-center">Maintenance</th>
                    <th class="text-center">Bonus</th>
                    <th class="text-center">Arrears of deferred salary/increment</th>
                    <th class="text-center">Overtime</th>
                    <th class="text-center">Car Allow</th>
                    <th class="text-center">Arrears</th>
                    <th class="text-center">Gross With Allowance</th>
                    <th class="text-center">Taxable Salary</th>
                    <th class="text-center">Tax Amount</th>
                    <th class="text-center">Rebate Amount</th>
                    <th class="text-center">Tax Paid</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Income Tax</th>
                    <th class="text-center">Advances</th>
                    <th class="text-center">Loan</th>
                    <th class="text-center">L.W.P</th>
                    <th class="text-center">Lateness</th>
                    <th class="text-center">Penalty</th>
                    <th class="text-center">Others</th>
                    <th class="text-center">Total Deduction</th>
                    <th class="text-center">Hold Salary</th>
                    <th class="text-center">Net Salary</th>
                    <th class="text-center">Account Title</th>
                    <th class="text-center">Account No</th>
                    <th class="text-center">Payment Mode</th>
                </tr>
                </thead>
                <tbody>
                <?php CommonHelper::companyDatabaseConnection($_GET['m']); ?>
                @foreach($employees as $value)
                    <?php

                    $salary = $value->salary;
                    $emp_name = $value->emp_name;
                    $annual_salary = 0;
                    $tax_amount_yearly = 0;
                    $tax_amount_monthly = 0;
                    $balance_tax_amount = 0;
                    $tax_paid = 0;
                    $rebate_tax = 0;
                    $has_tax = 0;

                    $payslip = Payslip::where([['employee_id','=',$value->employee_id],["month","=",$value->month],["year","=",$value->year]]);
                    ?>
                    @if($payslip->count() > 0)
                        <?php
                        $payslip = $payslip->first();

                        $getPayslipMonth = $value->year.'-'.$value->month;
                        $category_id = $payslip['category_id'];
                        $designation_id = $payslip['designation_id'];

                        $employeeCurrentPositions = EmployeePromotion::select('salary')->where([['employee_id','=',$value->id],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
                        if($employeeCurrentPositions->count() > 0):
                            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                            $salary = $employeeCurrentPositionsDetail->salary;
                        endif;

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

                        $tax_paid = Payslip::whereBetween('month_year',[$fiscal_start_date,$fiscal_end_date])->where([['month_year','<',$getPayslipMonth]])
                                ->where([['employee_id', '=', $payslip["employee_id"]],['status','=',1]])->sum('tax_amount');
                        $tax = IncomeTax::where([['employee_id', '=',$payslip["employee_id"]],['year', '=', $value->year],['month', '=', $value->month]])->first();

                        $annual_salary = $tax['annual_salary'];
                        $rebate_tax = $tax['rebate_amount'];
                        $tax_amount_yearly = $tax['tax_payable'];
                        $tax_amount_monthly = $tax['amount'];
                        $balance_tax_amount = ($tax['balance_tax_amount'] + $tax['amount']) - $rebate_tax;

                        $grand_salary += $salary;
                        $grand_gross_salaries += $payslip["gross_salaries"];
                        $grand_basic_salary += $payslip["basic_salary"];
                        $grand_hr_utility_allowance += $payslip["hr_utility_allowance"];
                        $grand_fix_medical += $payslip["fix_medical"];
                        $grand_gez_overtime += $payslip["gez_overtime"];
                        $grand_food_allowance += $payslip["food_allowance"];
                        $grand_holiday_allowance += $payslip["holiday_allowance"];
                        $grand_reimbursement_allowance += $payslip["reimbursement_allowance"];
                        $grand_leave_encash_allowance += $payslip["leave_encash_allowance"];
                        $grand_expense_allowance += $payslip["expense_allowance"];
                        $grand_commission_allowance += $payslip["commission_allowance"];
                        $grand_station_visit_allowance += $payslip["station_visit_allowance"];
                        $grand_outstation_visit_allowance += $payslip["outstation_visit_allowance"];
                        $grand_installment_allowance += $payslip["installment_allowance"];
                        $grand_fuel_allowance += $payslip["fuel_allowance"];
                        $grand_other_allowance += $payslip["other_allowance"];
                        $grand_school_fees += $payslip["school_fees"];
                        $grand_mobile += $payslip["mobile"];
                        $grand_maintenance += $payslip["maintenance"];
                        $grand_bonus_amount += $payslip["bonus_amount"];
                        $grand_deferred_salary += $payslip["deferred_salary"];
                        $grand_overtime += $payslip["overtime"];
                        $grand_car_allowance += $payslip["car_allowance"];
                        $grand_arrears_amount += $payslip["arrears_amount"];
                        $grand_gross_with_allowances += $payslip['gross_with_allowances'];
                        $grand_annual_salary += $annual_salary;
                        $grand_tax_amount_yearly += $tax_amount_yearly;
                        $grand_rebate_tax += $rebate_tax;
                        $grand_tax_paid += $tax_paid;
                        $grand_balance_tax_amount += $balance_tax_amount;
                        $grand_tax_amount_monthly += $tax_amount_monthly;
                        $grand_advance_salary_amount += $payslip['advance_salary_amount'];
                        $grand_loan_amount_paid += $payslip['loan_amount_paid'];
                        $grand_lwp_deduction += $payslip['lwp_deduction'];
                        $grand_lateness += $payslip['lateness'];
                        $grand_penalty += $payslip['penalty'];
                        $grand_other_deduct += $payslip['other_deduct'];
                        $grand_total_deduction += $payslip['total_deduction'];


                        if($payslip['hold_salary'] == 1):
                            $hold_salary = $payslip['net_salary'];
                            $grand_hold_salary += $payslip['net_salary'];
                            $net_salary = 0;
                        else:
                            $grand_net_salary += $payslip['net_salary'];
                            $net_salary = $payslip['net_salary'];
                            $hold_salary = 0;
                        endif;

                        if($payslip['payment_mode'] == 'Transfer'):
                            $grand_cash_amount += $payslip['net_salary'];
                        else:
                            $grand_cheque_amount += $payslip['net_salary'];
                        endif;
                        ?>

                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td class="text-center">{{ $payslip["emp_id"] }}</td>
                            <td>{{ $emp_name }}</td>
                            <td>
                                @if(array_key_exists($designation_id, $designations)){{ $designations[$designation_id]->designation_name }} @endif
                            </td>
                            <td>
                                @if(array_key_exists($category_id, $category)){{ $category[$category_id]->employee_category_name }} @endif
                            </td>
                            <td class="text-center">{{ $value->cnic }}</td>
                            <td class="text-right">{{ number_format($salary) }}</td>
                            <td class="text-right">{{ number_format($payslip["gross_salaries"]) }}</td>
                            <td class="text-right">{{ number_format($payslip["basic_salary"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["hr_utility_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["fix_medical"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["gez_overtime"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["food_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["holiday_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["reimbursement_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["leave_encash_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["expense_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["commission_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["station_visit_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["outstation_visit_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["installment_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["fuel_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["other_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["school_fees"],0)  }}</td>
                            <td class="text-right">{{ number_format($payslip["mobile"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["maintenance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["bonus_amount"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["deferred_salary"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["overtime"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["car_allowance"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip['arrears_amount'],0) }}</td>
                            <td class="text-right">{{ number_format($payslip['gross_with_allowances'],0) }}</td>
                            <td class="text-right">{{ number_format($annual_salary,0) }}</td>
                            <td class="text-right">{{ number_format($tax_amount_yearly,0) }}</td>
                            <td class="text-right">{{ number_format($rebate_tax,0) }}</td>
                            <td class="text-right">{{ number_format($tax_paid,0) }}</td>
                            <td class="text-right">{{ number_format($balance_tax_amount,0) }}</td>
                            <td class="text-right">{{ number_format($tax_amount_monthly,0) }}</td>
                            <td class="text-right">{{ number_format($payslip["advance_salary_amount"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["loan_amount_paid"],0)}}</td>
                            <td class="text-right">{{ number_format(($payslip["lwp_deduction"]),0) }}</td>
                            <td class="text-right">{{ number_format($payslip["lateness"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["penalty"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["other_deduct"],0) }}</td>
                            <td class="text-right">{{ number_format($payslip["total_deduction"]) }}</td>
                            <td class="text-right">{{ number_format($hold_salary) }}</td>
                            <td class="text-right">{{ number_format($net_salary) }}</td>
                            <td>{{ $payslip['account_title'] }}</td>
                            <td class="text-center">{{ $payslip['account_no'] }}</td>
                            <td class="text-center">{{ $payslip["payment_mode"] }}</td>
                        </tr>
                    @endif

                </tbody>
                @endforeach
                <?php CommonHelper::reconnectMasterDatabase(); ?>
                <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-right">{{ number_format($grand_salary) }}</th>
                    <th class="text-right">{{ number_format($grand_gross_salaries) }}</th>
                    <th class="text-right">{{ number_format($grand_basic_salary) }}</th>
                    <th class="text-right">{{ number_format($grand_hr_utility_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_fix_medical) }}</th>
                    <th class="text-right">{{ number_format($grand_gez_overtime) }}</th>
                    <th class="text-right">{{ number_format($grand_food_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_holiday_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_reimbursement_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_leave_encash_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_expense_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_commission_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_station_visit_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_outstation_visit_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_installment_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_fuel_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_other_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_school_fees) }}</th>
                    <th class="text-right">{{ number_format($grand_mobile) }}</th>
                    <th class="text-right">{{ number_format($grand_maintenance) }}</th>
                    <th class="text-right">{{ number_format($grand_bonus_amount) }}</th>
                    <th class="text-right">{{ number_format($grand_deferred_salary) }}</th>
                    <th class="text-right">{{ number_format($grand_overtime) }}</th>
                    <th class="text-right">{{ number_format($grand_car_allowance) }}</th>
                    <th class="text-right">{{ number_format($grand_arrears_amount) }}</th>
                    <th class="text-right">{{ number_format($grand_gross_with_allowances) }}</th>
                    <th class="text-right">{{ number_format($grand_annual_salary) }}</th>
                    <th class="text-right">{{ number_format($grand_tax_amount_yearly) }}</th>
                    <th class="text-right">{{ number_format($grand_rebate_tax) }}</th>
                    <th class="text-right">{{ number_format($grand_tax_paid) }}</th>
                    <th class="text-right">{{ number_format($grand_balance_tax_amount) }}</th>
                    <th class="text-right">{{ number_format($grand_tax_amount_monthly) }}</th>
                    <th class="text-right">{{ number_format($grand_advance_salary_amount) }}</th>
                    <th class="text-right">{{ number_format($grand_loan_amount_paid) }}</th>
                    <th class="text-right">{{ number_format($grand_lwp_deduction) }}</th>
                    <th class="text-right">{{ number_format($grand_lateness) }}</th>
                    <th class="text-right">{{ number_format($grand_penalty) }}</th>
                    <th class="text-right">{{ number_format($grand_other_deduct) }}</th>
                    <th class="text-right">{{ number_format($grand_total_deduction) }}</th>
                    <th class="text-right">{{ number_format($grand_hold_salary) }}</th>
                    <th class="text-right">{{ number_format($grand_net_salary) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>>