<?php
use App\Helpers\CommonHelper;
use App\Models\Payslip;
use App\Models\EmployeePromotion;
use App\Models\IncomeTax;

$grand_salary = 0;
$grand_gross_salaries = 0;
$grand_basic_salary = 0;
$grand_hr_utility_allowance = 0;
$grand_fix_medical = 0;
$grand_gez_overtime = 0;
$grand_overtime = 0;
$grand_total_overtime = 0;

$grand_total_arrears = 0;
$grand_total_fuel_allowance = 0;
$grand_total_mobile_allowance = 0;
$grand_total_mics_allowance = 0;
$grand_total_incentives_allowance = 0;
$grand_total_attendance_allowance = 0;
$grand_total_food_allowance = 0;
$grand_total_target_allowance = 0;

$grand_gross_with_allowances = 0;
$grand_income_tax = 0;
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

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper" id="printList">
                <table class="table table-sm mb-0 table-bordered table-striped tableFixHead table-hover" id="exportList">
                    <thead>
                    <tr>
                        <th colspan="50">
                            Salary For The Month Of {{date('F', strtotime($explodeMonthYear[0]."-".$explodeMonthYear[1]."-15")).', '. date('Y', strtotime($explodeMonthYear[0]))  }}
                            <br> @if(array_key_exists($location_id, $locations)) {{ $locations[$location_id]->location_name }} @endif
                            <br> Attendance - {{ date('d F Y', strtotime($payroll_data->value('attendance_from'))) }} - {{ date('d F Y', strtotime($payroll_data->value('attendance_to'))) }}
                        </th>
                    </tr>
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
                        <th class="text-center">Overtime</th>
                        <th class="text-center">Total Overtime</th>
                        @foreach($allowance_types as $val)
                            <th class="text-center">{{ $val->allowance_type }}</th>
                        @endforeach
                        <th class="text-center">Gross With Allowance</th>
                        <!--<th class="text-center">Taxable Salary</th>
                        <th class="text-center">Tax Amount</th>
                        <th class="text-center">Rebate Amount</th>
                        <th class="text-center">Tax Paid</th>
                        <th class="text-center">Balance</th>
                        <th class="text-center">Income Tax</th>-->
                        <th class="text-center">TF Deduction</th>
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

                            $payslip = Payslip::where([['status','=',1],['employee_id','=',$value->id],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);
                            ?>
                        @if($payslip->count() > 0)
                                <?php
                                $payslip = $payslip->first();

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
                                $tax = IncomeTax::where([['employee_id', '=',$payslip["employee_id"]],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]])->first();

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
                                $grand_overtime += $payslip["overtime"];
                                $grand_total_overtime += $payslip["gez_overtime"] + $payslip["overtime"];

                                $grand_total_arrears  += $payslip["arrears_amount"];
                                $grand_total_fuel_allowance  += $payslip["fuel_allowance"];
                                $grand_total_mobile_allowance  += $payslip["mobile"];
                                $grand_total_mics_allowance  += $payslip["allowance_mics"];
                                $grand_total_incentives_allowance  += $payslip["allowance_incentives"];
                                $grand_total_attendance_allowance  += $payslip["attendance_allowance"];
                                $grand_total_food_allowance  += $payslip["food_allowance"];
                                $grand_total_target_allowance  += $payslip["target_allowance"];

                                $grand_gross_with_allowances += $payslip['gross_with_allowances'];
                                $grand_income_tax += $tax_amount_monthly;
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
                                <td class="text-right">{{ number_format($payslip["overtime"],0) }}</td>
                                <td class="text-right">{{ number_format($payslip["gez_overtime"] + $payslip["overtime"] ,0) }}</td>

                                <td class="text-right">{{ number_format($payslip['arrears_amount'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['allowance_tf'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['fuel_allowance'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['mobile'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['allowance_mics'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['allowance_incentives'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['attendance_allowance'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['food_allowance'],0) }}</td>
                                <td class="text-right">{{ number_format($payslip['target_allowance'],0) }}</td>

                                <td class="text-right">{{ number_format($payslip['gross_with_allowances'],0) }}</td>
                                <!--<td class="text-right">{{ number_format($annual_salary,0) }}</td>
                                <td class="text-right">{{ number_format($tax_amount_yearly,0) }}</td>
                                <td class="text-right">{{ number_format($rebate_tax,0) }}</td>
                                <td class="text-right">{{ number_format($tax_paid,0) }}</td>
                                <td class="text-right">{{ number_format($balance_tax_amount,0) }}</td>
                                <td class="text-right">{{ number_format($tax_amount_monthly,0) }}</td>-->
                                <td class="text-right">{{ number_format($payslip["tf_deduction"],0) }}</td>
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
                        <th colspan="6"></th>
                        <th class="text-right">{{ number_format($grand_salary) }}</th>
                        <th class="text-right">{{ number_format($grand_gross_salaries) }}</th>
                        <th class="text-right">{{ number_format($grand_basic_salary) }}</th>
                        <th class="text-right">{{ number_format($grand_hr_utility_allowance) }}</th>
                        <th class="text-right">{{ number_format($grand_fix_medical) }}</th>
                        <th class="text-right">{{ number_format($grand_gez_overtime) }}</th>
                        <th class="text-right">{{ number_format($grand_overtime) }}</th>
                        <th class="text-right">{{ number_format($grand_total_overtime) }}</th>

                        <th class="text-right">{{ number_format($grand_total_arrears) }}</th>
                        <th class="text-right">{{ number_format($grand_total_fuel_allowance) }}</th>
                        <th class="text-right">{{ number_format($grand_total_mobile_allowance) }}</th>
                        <th class="text-right">{{ number_format($grand_total_mics_allowance) }}</th>
                        <th class="text-right">{{ number_format($grand_total_incentives_allowance) }}</th>
                        <th class="text-right">{{ number_format($grand_total_attendance_allowance) }}</th>
                        <th class="text-right">{{ number_format($grand_total_food_allowance) }}</th>
                        <th class="text-right">{{ number_format($grand_total_target_allowance) }}</th>

                        <th class="text-right" colspan="6"></th>
                        <!--<th class="text-right">{{ number_format($grand_income_tax) }}</th>-->
                        <th class="text-right">{{ number_format($grand_advance_salary_amount) }}</th>
                        <th class="text-right">{{ number_format($grand_loan_amount_paid) }}</th>
                        <th class="text-right">{{ number_format($grand_lwp_deduction) }}</th>
                        <th class="text-right">{{ number_format($grand_lateness) }}</th>
                        <th class="text-right">{{ number_format($grand_penalty) }}</th>
                        <th class="text-right">{{ number_format($grand_other_deduct) }}</th>
                        <th class="text-right">{{ number_format($grand_total_deduction) }}</th>
                        <th class="text-right">{{ number_format($grand_hold_salary) }}</th>
                        <th class="text-right">{{ number_format($grand_net_salary) }}</th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th colspan="3">Transfer Amount</th>
                        <th colspan="2">{{ number_format($grand_cash_amount) }}</th>
                        <th colspan="3">Cheque Amount</th>
                        <th colspan="2">{{ number_format($grand_cheque_amount) }}</th>
                        <th colspan="37"></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>