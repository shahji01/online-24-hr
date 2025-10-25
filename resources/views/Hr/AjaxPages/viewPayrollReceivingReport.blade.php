<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;

$count_total_salary='';
$count_basic_salary='';
$count_ot_amount='';
$count_deduction_day_amount = '';
$count_deposit = '';
$count_loan = '';
$count_bonus = '';
$count_tax='';
$count_eobi='';
$count_allowance='';
$count_extra_allowances ='';
$count_deduction='';
$count_payable_salary='';
$count_net_salary = '';
$current_date = date('Y-m-d');
$count_ot_hours_amount = '';
$countTotalNetSalary = '';

?>
<style>
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}
    tbody > tr > td
    {
        border: 1px solid black !important;
    }
    .table-bordered > thead > tr > th
    {
        border: 1px solid black !important;
    }
    .table-bordered > tfoot > tr > th
    {
        border: 1px solid black !important;
    }
    @media print {
        .page-break { display: block; page-break-before: always; }
        tbody > tr > td
        {
            border: 1px solid black !important;
        }
        .table-bordered > thead > tr > th
        {
            border: 1px solid black !important;
        }
        .table-bordered > tfoot > tr > th
        {
            border: 1px solid black !important;
        }
    }
</style>

<div class="">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @foreach($companiesList as $companyData)
                <div style="margin: 50px;"></div>
                <div class="table-responsive">

                    <?php $count =1;


                    CommonHelper::companyDatabaseConnection($companyData->id);
                    $departments = Employee::select('emp_sub_department_id')->groupBy('emp_sub_department_id')->get()->toArray();
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo HrHelper::date_format($current_date);?></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 30px !important; font-style: inherit;font-family: -webkit-body; font-weight: bold;">
                                    {{ $companyData->name}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                            <?php $nameOfDay = date('l', strtotime($current_date)); ?>
                            <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                        </div>
                        <div style="line-height:5px;">&nbsp;</div>
                        <div class="row text-center"><h4 style="text-decoration: underline;"><b>Employee Payroll Receiving Report</b></h4></div>
                        <div style="line-height:5px;">&nbsp;</div>
                    </div>
                    @foreach($departments as $value)

                        <table class="table table-responsive table-bordered table-condensed">
                            <thead>
                            <th class="text-center"><h4><b>S No.</b></h4></th>
                            <th class="text-center"><h4><b>Emp Name </b></h4></th>
                            <th class="text-center"><h4><b>CNIC</b></h4></th>
                            <th class="text-center"><h4><b>Payment Mode</b></h4></th>
                            <th class="text-center"><h4><b>Net Salary</b></h4></th>
                            <th class="text-center"><h4><b>Received By: </b></h4></th>
                            </thead>
                            <tbody>

                            <tr style="background-color: #ddd;" >
                                <td colspan="29">
                                    <div class="row text-center">
                                        <h4><b><?= HrHelper::getMasterTableValueById($companyData->id,'sub_department','sub_department_name',$value["emp_sub_department_id"])?>
                                            </b>
                                        </h4>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            CommonHelper::companyDatabaseConnection($companyData->id);
                            $all_emp = Employee::select("emp_salary","acc_no","emp_name","emp_cnic")->where([["emp_sub_department_id","=",$value["emp_sub_department_id"]]])->get()->toArray(); ?>

                            @foreach($all_emp as $value)
                                <?php

                                $emp_name=$value["emp_name"];
                                $payslip = Payslip::select('net_salary','extra_allowance','payment_mode')->where([['acc_no','=',$value["acc_no"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);
                                if($payslip->count() > 0 ):
                                $payslip =$payslip->first();
                                $countTotalNetSalary += $payslip->net_salary+$payslip->extra_allowance;
                                ?>
                                <tr class="text-center">
                                    <td><br><br><h4><b>{{$count++ }}</b></h4></td>
                                    <td><br><br><h4><b>{{$value["emp_name"] }}</b></h4></td>
                                    <td><br><br><h4><b>{{$value["emp_cnic"] }}</b></h4></td>
                                    <td><br><br><h4><b>{{$payslip->payment_mode }}</b></h4></td>
                                    <td><br><br><h4><b>{{$payslip->net_salary+$payslip->extra_allowance}}</b></h4></td>
                                    <td style="border: 2px solid black !important;"><br><br><br><br></td>
                                </tr>

                                <?php else:
                                    echo "<tr class='text-center'><td colspan='29'><b style='color:red;'> $emp_name Payroll Not Found !</b></td></tr>";
                                endif; ?>
                            @endforeach
                            <?php $count=1;?>
                            <?php CommonHelper::reconnectMasterDatabase(); ?>
                            </tbody>
                            <tfoot>
                                <th class="text-right" colspan="4"><h4><b>Total Net. Salary</b></h4></th>
                                <th class="text-center" colspan="1"><h4 style="text-decoration:underline;"><b><?php echo ($countTotalNetSalary); $countTotalNetSalary=''; ?></b></h4></th>
                                <th class="text-center" colspan="1"><h4><b>xxxxx</b></h4></th>
                            </tfoot>
                        </table>

                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="lineHeight">&nbsp;</div>
