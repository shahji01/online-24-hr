<style>
    .panel-heading {
        padding: 0px 15px;}
</style>


<?php
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payslip;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\Attendance;
use App\Models\PayrollData;
use App\Models\LoanRequest;
use App\Models\EmployeeDeposit;
use App\Models\AdvanceSalary;
use App\Models\Holidays;



foreach($subdepartment as $row){

?>
<div class="panel">
    <div class="panel-body">
        <?php $company_name =  CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <?php
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if($getEmployee == 'All'){
                $employees = Employee::where('emp_sub_department_id', '=', $row['id'])->get(['id','normal_or_per_day_salary','emp_joining_date','tax_id','eobi_id','emp_name','emp_salary','emp_id']);
            }else{
                $employees = Employee::where('emp_sub_department_id', '=', $row['id'])->where('emp_id','=',$getEmployee)->get(['normal_or_per_day_salary','emp_joining_date','emp_id','id','tax_id','eobi_id','emp_name','emp_salary']);

            }


            foreach($employees as $row1){
            $emp_name = $row1['emp_name'];
            $payroll_deduction = PayrollData::where([['emp_id', '=', $row1['emp_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            if($payroll_deduction->count()>0):$deduction_days = $payroll_deduction->value('deduction_days');


            $paySlip = Payslip::where([['emp_id', '=', $row1['emp_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            $bonus = BonusIssue::where([['emp_id', '=', $row1['emp_id']],['bonus_year', '=', $explodeMonthYear[0]],['bonus_month', '=', $explodeMonthYear[1]]]);
            $loanRequest = LoanRequest::where([['approval_status','=',2],['loan_status','=',0],['emp_id', '=', $row1['emp_id']]]);
            $advanceSalary = AdvanceSalary::where([['approval_status','=',2],['emp_id', '=', $row1['emp_id']],['deduction_year', '=', $explodeMonthYear[0]],['deduction_month', '=', $explodeMonthYear[1]]]);
            $advanceSalaryAmount = 0;

            if($advanceSalary->count() > 0):
                $advanceSalaryAmount=$advanceSalary->value("advance_salary_amount");

            endif;

            $employee_deposit_amount = 0;
            $employee_deposit_name = "-";

            $bonus_amount = 0;


            if($bonus->count() > 0):

                $bonus_issue = $bonus->first();
                $bonus_name = Bonus::select('bonus_name')->where([['id', '=', $bonus_issue->bonus_id]])->value('bonus_name');
                $bonus_amount = $bonus_issue->bonus_amount;
            endif;

            $loan_perMonthDeduction=0;
            if($loanRequest->count() > 0):

                $loanRequestData = $loanRequest->first();

                $loan_perMonthDeduction = $loanRequestData->per_month_deduction;

                $total_loan = $loanRequestData->loan_amount;


                $total_loan_paid = DB::table("payslip")
                        ->select(DB::raw("SUM(loan_amount_paid) as loan_amount_paid"))
                        ->where([['loan_id','=',$loanRequestData->id],['emp_id','=',$row1['emp_id']]])->first();

                $remaining_loan_amount = ($total_loan-$total_loan_paid->loan_amount_paid);
                if($remaining_loan_amount == 0)
                {
                    $loan_perMonthDeduction = 0 ;
                }
                //  $total_loan_paid = Payslip::where([['loan_id','=',$loanRequestData->id],['emp_id','=',$row1['emp_id']]]);


            endif;

            $allowance = Allowance::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1]]);
            $deduction = Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1]]);
            // ['status','=','1']

            CommonHelper::reconnectMasterDatabase();
            if($row1['tax_id'] != '0'):

                $tax = Tax::select('tax_percent','tax_name')->where([['id','=',$row1['tax_id']],['company_id','=',Input::get('m')],['status','=','1']])->first();

                if($tax->tax_mode == 'Percentage'):

                    $tax_deduct = (($tax->tax_percent/100)*$row1['emp_salary']);
                else:
                    $tax_deduct = ($tax->tax_percent);
                endif;

            else:
                $tax_deduct = 0;
            endif;
            if($row1['eobi_id'] != '0'):

                $eobi = Eobi::where([['id','=',$row1['eobi_id']],['company_id','=',Input::get('m')],['status','=','1']])->first();
                $eobi_deduct = $eobi->EOBI_amount;
            else:
                $eobi_deduct = 0;

            endif;
            CommonHelper::companyDatabaseConnection(Input::get('m'));

            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h4>
                                    <b class="text-center"> Emp Name: <?php echo $row1['emp_name'];?></b>
                                </h4>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                                <h2 style="text-decoration: underline;font-weight:bold;"><?= 'PAYROLL' ?></h2>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h4 style="text-decoration: underline;font-weight:bold;">Dept Name: <?=$row['department_name'] ?></h4>

                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                            <div class="row">
                                <?php
                                $count_allowance ='';
                                $allowances_total=0;
                                if($allowance->count() > 0):
                                ?>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Allowances</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $allowances_total='';
                                                            $count_allowance+=round(($allowance->sum('allowance_amount')));

                                                            $total_allowance = 0; $count=0;
                                                            foreach($allowance->get() as $value1):$count++;

                                                            $deduction_days = PayrollData::where([['emp_id', '=', $row1['emp_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]])->first();

                                                            $no_days_worked = (30)-($deduction_days->deduction_days);
                                                            $total_days_worked = ($row1['emp_salary'])*(30);
                                                            $rate_of_pay = $total_days_worked*$no_days_worked;
                                                            $basic_pay_allowance = ($rate_of_pay)/(1.5);

                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?= $value1['allowance_type']; ?></td>
                                                                <td class="text-center"><?= round($value1['allowance_amount']) ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td class="text-right"><b>Total</b></td>
                                                                <td class="text-center"><b> <?php echo round($count_allowance); ?></b></td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Allowances</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">--</td>
                                                                <td class="text-center">--</td>
                                                            </tr>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-right">
                                                                </td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php

                                $count_deduction ='';
                                if($deduction->count() > 0):
                                ?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Deductions</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php $count2=0;foreach($deduction->get() as $value2): $count2++; $count_deduction+=$value2['deduction_amount'];?>
                                                            <tr>
                                                                <td class="text-center"><?= $value2['deduction_type'];?></td>
                                                                <td class="text-center"><?= $value2['deduction_amount'];?> </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td class="text-right"><b>Total</b></td>
                                                                <td class="text-center"><b> <?php echo $count_deduction; ?></b></td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="panel-title">Deductions</div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive table-bordered table-striped table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Particular</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">--</td>
                                                                <td class="text-center">--</td>
                                                            </tr>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-right">
                                                                </td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                    <?php  if($loanRequest->count() > 0 ):?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="panel-title">Loan</div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Paid </th>
                                                                        <th class="text-center">Remaining </th>
                                                                        <th class="text-center">Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                @foreach($loanRequest->get() as $value)
                                                                    <tr>
                                                                        <td class="text-center"><?= $total_loan_paid->loan_amount_paid?></td>
                                                                        <td class="text-center"><?= $remaining_loan_amount?></td>
                                                                        <td class="text-center"><?=$value->loan_amount?></td>
                                                                    </tr>
                                                                @endforeach;

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    else:?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="panel-title">Loan</div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Paid </th>
                                                                    <th class="text-center">Remaining </th>
                                                                    <th class="text-center">Total</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>

                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($bonus->count() > 0 ):?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="panel-title">Bonus</div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Bonus</th>
                                                                    <th class="text-center">Amount</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-center"><?= $bonus_name ?></td>
                                                                    <td class="text-center"><?= $bonus_amount ?></td>

                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    else:?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="panel-title">Bonus</div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Bonus</th>
                                                                    <th class="text-center">Amount</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center">-</td>

                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php endif; ?>


                                    <?php  if($advanceSalary->count() > 0): ?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="panel-title">Advance Salary</div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Salary </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-center"><?= $advanceSalaryAmount; ?></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="panel-title">Advance Salary</div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-responsive table-bordered table-striped table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Salary</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-center">-</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                            </div>
                            <div class="row">


                                <?php
                                $deduction_days = PayrollData::where([['emp_id', '=', $row1['emp_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]])->first();
                                $perDaySalary = ($row1['emp_salary'])/(30);
                                $rate_of_pay = ($perDaySalary*$deduction_days->present_days);
                                $payroll_deduct_amount = ($deduction_days->deduction_days*$perDaySalary);
                                $payroll_ot_amount = 0;
                                $total_deduction = ($payroll_deduct_amount+$count_deduction+$tax_deduct+$eobi_deduct+$loan_perMonthDeduction+$advanceSalaryAmount);

                                $payable_salary       = ($rate_of_pay +$bonus_amount+ $count_allowance +$payroll_ot_amount);
                                $payable_wihtoutdays  = ($row1['emp_salary']+$bonus_amount+ $count_allowance +$payroll_ot_amount);
                                $netSalary            = ($payable_wihtoutdays-$total_deduction);
                                ?>
                            </div>
                            <input type="hidden" id="eobi_amount_<?php echo $row1['id'];?>" name="eobi_amount_<?php echo $row1['id'];?>" value="<?=$eobi_deduct?>" class="form-control" />
                            <input type="hidden" id="tax_amount_<?php echo $row1['id'];?>" name="tax_amount_<?php echo $row1['id'];?>" value="<?=$tax_deduct?>" class="form-control" />
                            <input type="hidden" id="bonus_amount_<?php echo $row1['id'];?>" name="bonus_amount_<?php echo $row1['id'];?>" value="<?=$bonus_amount?>" class="form-control" />
                            <input type="hidden" id="advance_salary_amount_<?php echo $row1['id'];?>" name="advance_salary_amount_<?php echo $row1['id'];?>" value="<?= $advanceSalaryAmount?>" class="form-control" readonly="" />
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <?php if($paySlip->count() > 0):?>
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Days</label>
                                                    <input type="number" id="basic_salary_<?php echo $row1['id'];?>" name="basic_salary_<?php echo $row1['id'];?>" value="<?php echo $deduction_days->total_days;?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Present</label>
                                                    <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= $deduction_days->total_present ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Absent</label>
                                                    <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $deduction_days->total_absent  ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Holidays</label>
                                                    <input type="number" id="net_salary_<?php echo $row1['id'];?>" name="net_salary_<?php echo $row1['id'];?>" value="<?= $deduction_days->total_holidays ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Late Arrival</label>
                                                    <input readonly type="number" id="deduction_days_<?php echo $row1['id'];?>" name="deduction_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->total_late_arrivals?>" class="form-control" />
                                                </div>

                                                <?php  if($loanRequest->count() > 0):?>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Loan Deduction P/M</label>
                                                    <input type="number" id="loan_amount_<?php echo $row1['id'];?>" name="loan_amount_<?php echo $row1['id'];?>" value="<?=$paySlip->first()->loan_amount_paid?>" class="form-control" />
                                                    <input type="hidden" id="loan_id_<?php echo $row1['id'];?>" name="loan_id_<?php echo $row1['id'];?>" value="<?=$loanRequestData->id?>">
                                                </div>
                                                <?php else: ?>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Loan Deduction P/M</label>
                                                    <input readonly type="number" id="loan_amount_<?php echo $row1['id'];?>" name="loan_amount_<?php echo $row1['id'];?>" value="0" class="form-control" />
                                                    <input type="hidden" id="loan_id_<?php echo $row1['id'];?>" name="loan_id_<?php echo $row1['id'];?>" value="0">
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Deduction Days</label>
                                                    <input readonly type="number" id="deduction_days_<?php echo $row1['id'];?>" name="deduction_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->deduction_days?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Deduction Days Amount</label>
                                                    <input readonly type="number" id="deduction_days_amount_<?php echo $row1['id'];?>" name="deduction_days_amount_<?php echo $row1['id'];?>" value="<?=$paySlip->first()->deduction_days_amount?>" class="form-control" />
                                                </div>

                                                <input type="hidden" id="payable_days_amount_<?php echo $row1['id'];?>" name="payable_days_amount_<?php echo $row1['id'];?>" value="<?=$paySlip->first()->payable_days_amount?>" class="form-control" />

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>O.T Days</label>
                                                    <input readonly type="number" id="ot_days_<?php echo $row1['id'];?>" name="ot_days_<?php echo $row1['id'];?>" value="<?=$paySlip->first()->total_ot?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>O.T Amount</label>
                                                    <input readonly type="number" id="ot_amount_<?php echo $row1['id'];?>" name="ot_amount_<?php echo $row1['id'];?>" value="<?=$paySlip->first()->ot_amount?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Payable Salary </label>
                                                    <input readonly type="number" id="payable_salary_<?php echo $row1['id'];?>" name="payable_salary_<?php echo $row1['id'];?>" value="<?=round($paySlip->first()->payable_salary)?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Salary</label>
                                                    <input type="number" id="total_salary_<?php echo $row1['id'];?>" name="total_salary_<?php echo $row1['id'];?>" value="<?php echo $paySlip->first()->total_salary;?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Basic</label>
                                                    <input type="number" id="basic_salary_<?php echo $row1['id'];?>" name="basic_salary_<?php echo $row1['id'];?>" value="<?php echo $row1['emp_salary'];?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Allowance</label>
                                                    <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= round($paySlip->first()->total_allowance); ?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Total Deduction</label>
                                                    <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $paySlip->first()->total_deduction?>" class="form-control" readonly="" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Net Salary</label>
                                                    <input type="number" id="net_salary_<?php echo $row1['id'];?>" name="net_salary_<?php echo $row1['id'];?>" value="<?= round($paySlip->first()->net_salary)?>" class="form-control" readonly="" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Extra Allowance</label>
                                                    <input type="number" id="extra_allowance_<?php echo $row1['id'];?>" name="extra_allowance_<?php echo $row1['id'];?>" value="<?= round($paySlip->first()->extra_allowance)?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Payment Mode</label>
                                                    <select name="payment_mode_<?php echo $row1['id'];?>" id="payment_mode_<?php echo $row1['id'];?>" class="form-control RequiredField">
                                                        <option <?php if($paySlip->first()->payment_mode == 'Cash') echo "selected"; ?> value="Cash">Cash</option>
                                                        <option <?php if($paySlip->first()->payment_mode == 'Cheque') echo "selected"; ?> value="Cheque">Cheque</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Status</label>
                                                    <select name="salary_status_<?php echo $row1['id'];?>" id="salary_status_<?php echo $row1['id'];?>" class="form-control RequiredField">
                                                        <option value="">Select</option>
                                                        <option <?php if($paySlip->first()->salary_status == '1') echo "selected"; ?> value="1">Paid</option>
                                                        <option <?php if($paySlip->first()->salary_status == '2') echo "selected"; ?> value="2">Un-Paid</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <?php else: ?>

                                               <div class="row text-center" style="background-color: gainsboro">
                                                   <h3 style="font-weight: bold;">Totals (#)</h3>
                                               </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Total Present</label>
                                                        <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= $deduction_days->total_present ?>" class="form-control" readonly="" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Total Absent</label>
                                                        <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $deduction_days->total_absent  ?>" class="form-control" readonly="" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Deduction Days</label>
                                                        <input readonly type="number" id="deduction_days_<?php echo $row1['id'];?>" name="deduction_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->deduction_days?>" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>O.T Hours</label>
                                                        <input readonly type="number" id="ot_days_<?php echo $row1['id'];?>" name="ot_days_<?php echo $row1['id'];?>" value="<?=$deduction_days->total_ot?>" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row">&nbsp;</div>
                                                <div class="row text-center" style="background-color: gainsboro">
                                                    <h3 style="font-weight: bold;">Subtractions (-) </h3>
                                                </div>
                                                    <div class="row">

                                                        <?php  if($loanRequest->count() > 0):?>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Loan Deduction P/M</label>
                                                            <input type="number" id="loan_amount_<?php echo $row1['id'];?>" name="loan_amount_<?php echo $row1['id'];?>" value="<?=$loan_perMonthDeduction?>" class="form-control" />
                                                            <input type="hidden" id="loan_id_<?php echo $row1['id'];?>" name="loan_id_<?php echo $row1['id'];?>" value="<?=$loanRequestData->id?>">
                                                        </div>
                                                        <?php else: ?>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Loan Deduction P/M</label>
                                                            <input readonly type="number" id="loan_amount_<?php echo $row1['id'];?>" name="loan_amount_<?php echo $row1['id'];?>" value="0" class="form-control" />
                                                            <input type="hidden" id="loan_id_<?php echo $row1['id'];?>" name="loan_id_<?php echo $row1['id'];?>" value="0">
                                                        </div>

                                                        <?php endif; ?>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label>Deduction Days Amount</label>
                                                                <input readonly type="number" id="deduction_days_amount_<?php echo $row1['id'];?>" name="deduction_days_amount_<?php echo $row1['id'];?>" value="<?=round($payroll_deduct_amount)?>" class="form-control" />
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label>Total Deduction</label>
                                                                <?php $total_deduction = round($total_deduction); ?>
                                                                <input type="number" id="total_deduction_<?php echo $row1['id'];?>" name="total_deduction_<?php echo $row1['id'];?>" value="<?php echo $total_deduction; ?>" class="form-control" readonly="" />
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label>Tax</label>
                                                                <input readonly class="form-control" name="tax_amount_<?php echo $row1['id'];?>" id="tax_amount_" value="<?=$tax_deduct?>">
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label>EOBI</label>
                                                                <input readonly class="form-control" name="eobi_amount_<?php echo $row1['id'];?>" id="eobi_amount_" value="<?=$eobi_deduct?>">
                                                            </div>
                                                    </div>
                                                <div class="row">&nbsp;</div>
                                                <div class="row text-center" style="background-color: gainsboro">
                                                    <h3 style="font-weight: bold;">Additions (+)</h3>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>O.T Amount</label>
                                                        <input readonly type="number" id="ot_amount_<?php echo $row1['id'];?>" name="ot_amount_<?php echo $row1['id'];?>" value="<?=round($payroll_ot_amount)?>" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Total Allowance</label>
                                                        <input type="number" id="total_allowance_<?php echo $row1['id'];?>" name="total_allowance_<?php echo $row1['id'];?>" value="<?= round($count_allowance); ?>" class="form-control" readonly="" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Bonus Amount</label>
                                                        <input type="number" id="bonus_amount_<?php echo $row1['id'];?>" name="bonus_amount_<?php echo $row1['id'];?>" value="<?= round($bonus_amount); ?>" class="form-control" readonly="" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Extra Allowance</label>
                                                        <input type="number" id="extra_allowance_<?php echo $row1['id'];?>" name="extra_allowance_<?php echo $row1['id'];?>" value="0" class="form-control"/>
                                                    </div>
                                                </div>
                                                <div class="row">&nbsp;</div>
                                                <div class="row text-center" style="background-color: gainsboro">
                                                    <h3 style="font-weight: bold;">Totals (Amount)</h3>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Total Salary</label>
                                                        <input type="number" id="total_salary_<?php echo $row1['id'];?>" name="total_salary_<?php echo $row1['id'];?>" value="<?php echo round($row1['emp_salary']+$count_allowance);?>" class="form-control" readonly="" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Basic</label>
                                                        <input type="number" id="basic_salary_<?php echo $row1['id'];?>" name="basic_salary_<?php echo $row1['id'];?>" value="<?php echo $row1['emp_salary'];?>" class="form-control" readonly="" />
                                                    </div>
                                                    <input type="hidden" id="payable_days_amount_<?php echo $row1['id'];?>" name="payable_days_amount_<?php echo $row1['id'];?>" value="<?=round($rate_of_pay)?>" class="form-control" />
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Payable Salary</label>
                                                        <input readonly type="number" id="payable_salary_<?php echo $row1['id'];?>" name="payable_salary_<?php echo $row1['id'];?>" value="<?=round($netSalary)?>" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Net Salary</label>
                                                        <input type="number" id="net_salary_<?php echo $row1['id'];?>" name="net_salary_<?php echo $row1['id'];?>" value="<?= round($netSalary)?>" class="form-control" readonly="" />
                                                    </div>
                                                 </div>
                                                <div class="row">&nbsp;</div>
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12"></div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>Payment Mode</label>
                                                    <select name="payment_mode_<?php echo $row1['id'];?>" id="payment_mode_<?php echo $row1['id'];?>" class="form-control RequiredField">
                                                        <option value="Cash">Cash</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            else:
                echo "<div class='row text-center' style='color:red'><b>Employee = $emp_name   Month=$explodeMonthYear[1] Year=$explodeMonthYear[0] Attendance Not Found !</b></div>";
            endif;
            }
            ?>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <input type="submit" name="submit" class="btn btn-success" />
            </div>
        </div>
    </div>
</div>
<?php
}
?>