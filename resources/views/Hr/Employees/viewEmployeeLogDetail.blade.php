<?php

$accType = Auth::user()->acc_type;


use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\BonusIssue;
use App\Models\EmployeePromotion;



$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employee History</h4>
                            </div>
                            <div class="col-sm-4 text-right">

                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeList','','1')?>
                                @endif
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2" id="userinfo">

                            </div>
                            <div class="col-sm-8 text-center">
                                <div class="text-center">
                                    <?php if($employee->value('img_path') != ''){ ?>
                                    <img src="<?php echo url('storage/'.$employee->value('img_path')) ?>" class="avatar img-circle img-thumbnail img-responsive" style="width:20%;" alt="avatar">
                                    <?php } else{ ?>
                                    <img id="img_file_1" class="avatar img-circle img-thumbnail" style="width:20%;height:auto;" src="<?= url('storage/app/uploads/employee_images/user-dummy.png')?>">
                                    <?php } ?>

                                </div>
                            </div>
                            <div class="col-sm-2">&nbsp;

                            </div>
                        </div>
                        <br>
                        <div class="row" id="EmployeeList">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <table class="table table-sm mb-0 table-bordered table-striped" id="PrintEmployeeList">
                                    <tr>
                                        <th>Emp Id:</th>
                                        <td>{{ $employee->emp_id }}</td>
                                    </tr>
                                    <tr>
                                        <th >Emp Name:</th>
                                        <td>{{ $employee->emp_name }}</td>
                                    </tr>

                                    <tr>
                                        <th >Last Department:</th>
                                        <?php CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m')); ?>
                                        <?php $department_id=DB::table('location')->where('emp_id',$employee->emp_id)->where('status',1)->first(); ?>
                                        <td>@if(!empty($department_id))
                                            {{ HrHelper::getMasterTableValueById(Input::get('m'),' department','department_name',$department_id->emp_department_id) }}
                                        @else
                                                --
                                            @endif
                                        </td>
                                        <?php CommonHelper::reconnectMasterDatabase(); ?>
                                    </tr>
                                    <tr>
                                        <th >Last Sub Department:</th>
                                        <?php CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m')); ?>
                                        <?php $sub_department_id=DB::table('location')->where('emp_id',$employee->emp_id)->where('status',1)->first(); ?>
                                        <td>@if(!empty($sub_department_id))
                                                {{ HrHelper::getMasterTableValueById(Input::get('m'),' sub_department','sub_department_name',$sub_department_id->emp_sub_department_id) }}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <?php CommonHelper::reconnectMasterDatabase(); ?>
                                    </tr>
                                    <tr>
                                        <th >Current Department:</th>
                                        <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),' department','department_name',$employee->emp_department_id)  }}</td>
                                    </tr>

                                    <tr>
                                        <th >Current Sub Department:</th>
                                        <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),' sub_department','sub_department_name',$employee->emp_sub_department_id)  }}</td>
                                    </tr>
                                    <tr>
                                        <th >Last Location:</th>
                                        <?php CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m')); ?>
                                        <td>{{ DB::table('location')->where('emp_id',$employee->emp_id)->where('status',1)->first()->branch_name ?? "--" }}</td>
                                        <?php CommonHelper::reconnectMasterDatabase(); ?>
                                    </tr>
                                    <tr>
                                        <th >Current Location:</th>
                                        <td>{{ $employee->branch }}</td>
                                    </tr>
                                    {{--<tr>--}}
                                        {{--<th >Sub Department:</th>--}}
                                        {{--<td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$employee->emp_sub_department_id) }}</td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <th>Joining Salary:</th>
                                        <td>{{ number_format($employee->emp_salary,0) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Probation Completion Date:</th>
                                        <td>{{ HrHelper::date_format($employee->probation_expire_date) ?? "--" }}</td>
                                    </tr>
                                    <tr>
                                        <th>Salary After Completion of Probation:</th>
                                        <?php
                                        CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                        $salary=EmployeePromotion::where([['status','=',1],['type','=','permanent'],['emp_id','=',$employee->emp_id]])->first();
                                        CommonHelper::reconnectMasterDatabase();
                                        ?>
                                        <td>
                                            @if($salary)
                                            {{ number_format($salary->salary,0) ?? '--' }}
                                                @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Annual Increment:</th>
                                        <?php
                                        CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                        $promotion=EmployeePromotion::where([['status','=',1],['type','=','promotion'],['emp_id','=',$employee->emp_id]])->orderBy('id')->get();
                                        CommonHelper::reconnectMasterDatabase();
                                        ?>
                                        <?php $count=0;?>
                                        <td><?php foreach ($promotion as $val){

                                            if($count == 0){
                                                $count++;
                                                CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                                $emp_promotion_salary=EmployeePromotion::where([['status','=',1],['type','=','permanent'],['emp_id','=',$employee->emp_id]])->orderBy('id')->first();
                                                CommonHelper::reconnectMasterDatabase();
                                                if(!empty($emp_promotion_salary)){
                                                    $new_width = ($val->increment / $emp_promotion_salary->salary);
                                                }
                                                else{
                                                    $new_width = ($val->increment /  $employee->emp_salary);
                                                }

                                            }
                                            else{
                                                CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                                $promotion=EmployeePromotion::where([['status','=',1],['type','=','promotion'],['emp_id','=',$employee->emp_id]])->orderBy('id')->first();
                                                CommonHelper::reconnectMasterDatabase();

                                                $new_width = ($val->increment / $promotion->salary);


                                            }
                                                echo HrHelper::date_format($val->promotion_date)." "."(".number_format((float)$new_width, 2, '.', '')." % of salary) Rs ".number_format($val->increment,0);
                                                echo "</br>";

                                            } ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>Salary After Increment:</th>
                                        <td>
                                            <?php
                                            CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                            $promotion=EmployeePromotion::where([['status','=',1],['emp_id','=',$employee->emp_id]])->orderBy('id')->get();
                                            CommonHelper::reconnectMasterDatabase();
                                            foreach ($promotion as $val){
                                                echo HrHelper::date_format($val->promotion_date)." "."(Rs ".number_format($val->salary,0).")";
                                                echo "</br>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bonus Recieved Amount And Date:</th>
                                        <td>
                                            <?php
                                            CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                            $bonus=BonusIssue::where([['status','=',1],['emp_id','=',$employee->emp_id]])->orderBy('id');
                                            CommonHelper::reconnectMasterDatabase();
                                            if($bonus->count()>0){
                                                foreach ($bonus->get() as $b){
                                                    $dateObj   = DateTime::createFromFormat('!m', $b->bonus_month);
                                                    $monthName = $dateObj->format('F'); // March
                                                    echo $monthName."-".$b->bonus_year." (Rs ".$b->bonus_amount.")";
                                                    echo "</br>";
                                                }
                                            }
                                            else{
                                                echo "--";
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date of Change of Designation:</th>
                                        <td>
                                            <?php
                                                $a=0;
                                            CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                            $promotion=EmployeePromotion::where([['status','=',1],['emp_id','=',$employee->emp_id]])->orderBy('id');
                                            CommonHelper::reconnectMasterDatabase();
                                            if($promotion->count()>0){
                                            foreach ($promotion->get() as $val){
                                                if($employee->designation_id != $val->designation_id || $a != 0){
                                                    $a++;
                                                    echo HrHelper::date_format($val->promotion_date)." "."(".HrHelper::getMasterTableValueById(\Illuminate\Support\Facades\Input::get('m'),'designation','designation_name',$val->designation_id).")";
                                                    echo "</br>";
                                                }
                                            }
                                            if($a==0){
                                                echo "--";
                                            }
                                            }
                                            else{
                                                echo "--";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Designation:</th>
                                        <td>
                                            <?php
                                            CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                            $promotion=EmployeePromotion::where([['status','=',1],['emp_id','=',$employee->emp_id]])->orderBy('id','desc')->skip(1)->first();
                                            CommonHelper::reconnectMasterDatabase();
                                            if($promotion){
                                                echo HrHelper::getMasterTableValueById(\Illuminate\Support\Facades\Input::get('m'),'designation','designation_name',$promotion->designation_id);
                                            }
                                            else{
                                                echo HrHelper::getMasterTableValueById(\Illuminate\Support\Facades\Input::get('m'),'designation','designation_name',$employee->designation_id);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Current Designation:</th>
                                        <td>
                                            <?php
                                            CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
                                            $promotion=EmployeePromotion::where([['status','=',1],['emp_id','=',$employee->emp_id]])->orderBy('id','desc')->first();
                                            CommonHelper::reconnectMasterDatabase();
                                            if($promotion){
                                                echo HrHelper::getMasterTableValueById(\Illuminate\Support\Facades\Input::get('m'),'designation','designation_name',$promotion->designation_id);
                                            }
                                            else{
                                                echo HrHelper::getMasterTableValueById(\Illuminate\Support\Facades\Input::get('m'),'designation','designation_name',$employee->designation_id);
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>










@endsection




