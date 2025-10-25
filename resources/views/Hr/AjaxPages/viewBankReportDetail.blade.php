<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

use App\Models\PayrollData;
use App\Models\Payslip;

$count_net_salary = 0;
$current_date = date('Y-m-d');
$month_year = Input::get('month_year');
$count =1;

$timestamp = strtotime($month_year);
$month = date('F', $timestamp);
$year = date('Y', $timestamp);


$timestamp = strtotime($cheque_date);
$cheque_day = date('d', $timestamp);
$cheque_month = date('F', $timestamp);
$cheque_year = date('Y', $timestamp);


?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body" id="printList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4>
                                    <b>
                                        Date: <?php echo $cheque_month." ".$cheque_day.", ".$cheque_year ?><br><br>
                                        The Manager <br>
                                        <?php
                                        echo $bank->bank_name." Limited";
                                        ?><br>
                                        <?php
                                        if($bank->id == 3){
                                            echo "Khayban-e-Jami Branch";
                                        }
                                        if($bank->id == 4){
                                            echo "Khayban-e-Ittehad Branch";
                                        }
                                        ?>
                                        <br>
                                        Karachi
                                    </b>
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <h3 style="text-decoration: underline;">Subject: Transfer Salary</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4>Dear Sir,</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4>You are authorized to transfer the salary for the Month of <?php echo $month." ",$year ?> into the following accounts:
                                </h4>
                            </div>
                        </div>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered table-condensed" id="regionWisePayrollReport">
                                <thead>
                                <tr>
                                    <th class="text-center">S. NO.</th>
                                    <th >NAME </th>
                                    <th >ACCOUNT NO. </th>
                                    <th class="text-right">AMOUNT RS. </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($payslip as $value)
                                    <?php
                                    //  $empData = Employee::select('emp_name','account_title','bank_account')->where('emp_id',$value->emp_id);
                                    ?>

                                    <tr>
                                        <td class="text-center">{{ $count++ }}</td>
                                        <td><?php echo $value->gender_id=='1'?"Mr. ": "Ms. "?>
                                            {{$value->account_title }}</td>
                                        <td >{{ $value->account_no}}</td>
                                        <td class="text-right">{{ number_format($value->net_salary,0) }}</td>
                                    </tr>

                                @endforeach
                                <?php $count=1;?>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <strong>TOTAL : </strong>
                                    </td>
                                    <td class="text-right" style="text-decoration: underline;text-decoration-style: double;" >
                                        <b style="text-decoration-line: overline;"><?php echo number_format($payslip->sum('net_salary')+$payslip->sum('pf_company_fund'),0);?></b>
                                    </td>
                                </tr>
                                <?php CommonHelper::reconnectMasterDatabase(); ?>
                                </tbody>
                            </table>
                        </div>
                        <br><br><br><br>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" >
                                <hr style="border: 1px solid rgba(0,0,0,0.76);" />
                                <b >
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorized Signatory</p>
                                </b>

                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" >
                                <hr style="border: 1px solid rgba(0,0,0,0.76);" />
                                <b>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorized Signatory</p>
                                </b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>