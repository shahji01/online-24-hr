<style>
    .panel-heading {
        padding: 0px 15px;}
    .space{margin:50px;}
    @media all {
        .page-break { display: none; }
    }

    @media print {
        .page-break { display: block; page-break-before: always; }
    }
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
use App\Models\Bonus;
use App\Models\BonusIssue;
use App\Models\Attendence;
use App\Models\PayrollData;
use App\Models\LoanRequest;

foreach($subdepartment as $row){
?>
<div class="panel">

    <div class="panel-body">

        <div class="row">
            <?php
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if($getEmployee == 'All'){
                $employees = Employee::where('emp_sub_department_id', '=', $row['id'])->get(['emp_sub_department_id','acc_no','id','tax_id','eobi_id','emp_name','emp_salary']);
            }else{
                $employees = Employee::where('emp_sub_department_id', '=', $row['id'])->where('acc_no','=',$getEmployee)->get(['emp_sub_department_id','acc_no','id','tax_id','eobi_id','emp_name','emp_salary']);

            }


            foreach($employees as $row1) {
            echo '  <div class="space"></div>';
            $payslips  = Payslip::where([['acc_no', '=', $row1['acc_no']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            $bonus_amount = 0;
            $bonus = BonusIssue::where([['acc_no', '=', $row1['acc_no']],['bonus_year', '=', $explodeMonthYear[0]],['bonus_month', '=', $explodeMonthYear[1]]]);
            $loanRequest = LoanRequest::where([['approval_status','=',2],['loan_status','=',0],['acc_no', '=', $row1['acc_no']]]);

            if($bonus->count() > 0):

                $bonus_issue = $bonus->first();
                $bonus_name = Bonus::select('bonus_name')->where([['id', '=', $bonus_issue->bonus_id]])->value('bonus_name');
                $bonus_amount = $bonus_issue->bonus_amount;
            endif;

            $loan_perMonthDeduction=0;
            $total_loan_paid=0;
            $total_loan=0;
            $remaining_loan_amount=0;
            $loanRequestData=0;

            if($loanRequest->count() > 0):

                $loanRequestData = $loanRequest->first();

                $loan_perMonthDeduction = $loanRequestData->per_month_deduction;

                $total_loan = $loanRequestData->loan_amount;


                $total_loan_paid = DB::table("payslip")
                    ->select(DB::raw("SUM(loan_amount_paid) as loan_amount_paid"))
                    ->where([['loan_id','=',$loanRequestData->id],['acc_no','=',$row1['acc_no']]])->value('loan_amount_paid');

                $remaining_loan_amount = ($total_loan-$total_loan_paid);

                //  $total_loan_paid = Payslip::where([['loan_id','=',$loanRequestData->id],['acc_no','=',$row1['acc_no']]]);


            endif;

            CommonHelper::reconnectMasterDatabase();
            if($row1['tax_id'] != '0'):

                $tax = Tax::select('tax_percent','tax_name')->where([['id','=',$row1['tax_id']],['company_id','=',Input::get('m')],['status','=','1']])->first();
                $tax_deduct = (($tax->tax_percent/100)*$row1['emp_salary']);

            else:
                $tax_deduct = 0;
            endif;

            if($row1['eobi_id'] != '0'):

                $eobi = Eobi::where([['id','=',$row1['eobi_id']],['company_id','=',Input::get('m')],['status','=','1']])->first();
                $eobi_deduct = $eobi->EOBI_amount;
            else:
                $eobi_deduct = 0;

            endif;
            $subdepartmentName = HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$row1['emp_sub_department_id']);
            CommonHelper::companyDatabaseConnection(Input::get('m'));

            if ($payslips->count() > 0) {

            $deduction= PayrollData::where([['acc_no','=',$row1['acc_no']],['year', '=',$explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            if($deduction->count() > 0):
            $deduction_days= $deduction->first();
            $current_date = date('Y-m-d');
            ?>

            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($current_date);?></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 30px !important; font-style: inherit;
font-family: -webkit-body; font-weight: bold;">
                            <?php
                            CommonHelper::reconnectMasterDatabase();
                            echo CommonHelper::getCompanyName(Input::get('m'));
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?php $nameOfDay = date('l', strtotime($current_date)); ?>
                    <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <?php
            ?>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <tr>
                                        <th>Emp. No#:</th>
                                        <td><?php echo $row1['acc_no']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Emp Name:</th>
                                        <td><?php echo $row1['emp_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>SubDepartment:</th>
                                        <td><?php echo $subdepartmentName?></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                            <h2 style="text-decoration: underline;font-weight:bold;"><?= 'PAYSLIP' ?></h2>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <?php foreach ($payslips->get() as $row2) {
                                    $emp_id = Employee::select('id')->where([['acc_no','=',$row2['acc_no']]])->value('id');

                                    ?>
                                    <tr>
                                        <th>Payslip Code:</th>
                                        <td><?php echo $row2['ps_no']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Salary Status:</th>
                                        <td>
                                            <?php
                                            if ($row2['salary_status'] == 1) {
                                                echo 'Paid';
                                            } else if ($row2['salary_status'] == 0) {
                                                echo 'Up-Paid';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Month - Year:</th>
                                        <td><?php echo $explodePaysilpMonth[1] . '-' . $explodePaysilpMonth[0]; ?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if($bonus->count() > 0 ):?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
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
                                                    <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-right"> </td>
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

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <?php foreach ($payslips->get() as $row6) {?>
                                    <tr>
                                        <th>Total Days:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_days ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Present:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_present ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Absent:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_absent ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total HalfDays:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_halfday_count ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Holidays:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_holidays ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Late Arrivals:</th>
                                        <th class="text-right"><?php echo $deduction_days->total_late_arrivals ?></th>
                                    </tr>
                                    <tr>
                                        <th>EOBI:</th>
                                        <th class="text-right"><?php echo $row6['eobi_amount'] ?></th>
                                    </tr>
                                    <tr>
                                        <th>Loan Deduction This Month</th>
                                        <th class="text-right"><?php echo $row6['loan_amount_paid'] ?></th>
                                    </tr>

                                    <tr>
                                        <th>Total Loan:</th>
                                        <th class="text-right"><?php echo $total_loan; ?></th>
                                    </tr>

                                    <tr>
                                        <th>Extra Allowance:</th>
                                        <th class="text-right"><?php echo number_format($row6['extra_allowance'], 0); ?></th>
                                    </tr>


                                    <?php

                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <?php foreach ($payslips->get() as $row5) {?>

                                    <tr>
                                        <th>Deduction Days:</th>
                                        <th class="text-right"><?php echo $row5['deduction_days']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Deduction Days Amount:</th>
                                        <th class="text-right"><?php echo number_format($row5['deduction_days_amount'], 0); ?></th>
                                    </tr>

                                    <tr>
                                        <th>Total HalfDays :</th>
                                        <th class="text-right"><?php echo $deduction_days->total_halfday_count  ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total HalfDays Deduction Amount:</th>
                                        <th class="text-right"><?php echo $row5['total_halfdays_deduction_amount'] ?></th>
                                    </tr>
                                    <tr>
                                        <th>O.T Days:</th>
                                        <th class="text-right"><?php echo $row5['total_ot']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>O.T Amount:</th>
                                        <th class="text-right"><?php echo number_format($row5['ot_amount'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>PF Fund:</th>
                                        <th class="text-right"><?php echo $row5['pf_amount'] ?></th>
                                    </tr>
                                    <tr>
                                        <th>Tax:</th>
                                        <th class="text-right"><?php echo $row6['tax_amount'] ?></th>
                                    </tr>
                                    <tr>
                                        <th>Remaining Loan:</th>
                                        <th class="text-right"><?php echo number_format($remaining_loan_amount, 0); ?></th>
                                    </tr>

                                    <tr>
                                        <th>Payable Salary: </th>
                                        <th class="text-right"><?php echo number_format($row5['payable_salary'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Payment Mode:</th>
                                        <th class="text-right"><?php echo ucfirst($row5['payment_mode']); ?></th>
                                    </tr>
                                   

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div> &nbsp;
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <tbody>
                                    <?php foreach ($payslips->get() as $row5) {

                                    ?>

                                    <tr>
                                        <th>Bonus:</th>
                                        <th class="text-right"><?php echo number_format($row5['bonus_amount'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Salary:</th>
                                        <th class="text-right"><?php echo number_format($row5['total_salary'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Basic Salary:</th>
                                        <th class="text-right"><?php echo number_format($row5['basic_salary'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Allowance:</th>
                                        <th class="text-right"><?php echo number_format($row5['total_allowance'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Total Deduction:</th>
                                        <th class="text-right"><?php echo number_format($row5['total_deduction'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Paid Loan</th>
                                        <th class="text-right"><?php echo number_format($total_loan_paid, 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Advance Salary</th>
                                        <th class="text-right"><?php echo number_format($row5['advance_salary_amount'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Gross Salary:</th>
                                        <th class="text-right"><?php echo number_format($row5['extra_allowance']+$row5['net_salary'], 0); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <th class="text-right"><?php echo ($row5['salary_status'] = 1 ?  "Paid" : "Un-Paid"); ?></th>
                                    </tr>
                                    <tr>
                                        <th>-</th>
                                        <th>-</th>
                                    </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div> &nbsp;
                        </div>
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><h4><b>Approved by : <span style="text-decoration: underline"><?= $row5['approved_by']?></span></b></h4></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><h4><b>Recieved by : ________________</b></h4></div>
                </div>

                <div class="page-break"></div>
            <?php endif;
            }
            else
            {?>
            <div class="row text-center" style="color:red"><b> <?= $row1['emp_name'] ?>&nbsp;Payslip Not Found !</b></div>
            <?php }
            }
            ?>

        </div>
    </div>
</div>
<?php
}?>