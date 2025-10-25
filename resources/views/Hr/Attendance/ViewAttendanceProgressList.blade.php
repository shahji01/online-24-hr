<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\PayrollData;
use App\Models\Employee;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
@extends('layouts.default')
@section('content')
<style>
    .card-body{
        min-height:150px;
    }
</style>

    <div class="page-wrapper">


                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h4 class="card-title">View Employee Attendance Progress List</h4>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                                        <?php echo CommonHelper::displayExportButton('PrintEmployeeAttendanceList','','1')?>
                                    </div>

                                </div>
                                <hr>
                                <br>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <input type="hidden" name="accType" id="accType" value="<?php echo $accType ?>"	>
                                            <input type="hidden" name="acc_emp_id" id="acc_emp_id" value="<?php echo Auth()->user()->employee_id ?>"	>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" style="">
                                                <!-- <label class="sf-label">Search By Month-Year:</label> -->
                                                <input type="month" class="form-control requiredField" name="month_year" id="month_year">
                                                <label style="font-size:12px;" >Search by month/year</label>
                                            </div>
                                            <input type="hidden" id="company_id" value="<?= Input::get('m')?>">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="
margin-top: 5px;">
                                                <button class="btn btn-sm btn-primary btn_search" onclick="attendanceProgressFilteredList()"><i id="load" class="fas fa fa-search"> </i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>        
                        </div>

                    </div>
                </div>

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <span id="employee-list">
                            

                                        <input type="hidden" name="m" value="{{ Input::get('m') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="employeeAttendenceReportSection" id="PrintEmployeeAttendanceList">
                                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0 table-bordered table-striped">
                                                 
                                                    <thead>
                                                        <th>S.no</th>
                                                        <th class="text-center">Emp Id</th>
                                                        <th class="text-center">Employee Name</th>
                                                        <th class="text-center">Month-Year</th>
                                                        <th class="text-center">Total Days</th>
                                                        <th class="text-center">Total Present</th>
                                                        <th class="text-center">Total Absent</th>
                                                        <th class="text-center">Total Half Days</th>
                                                        <th class="text-center">Total Lates</th>
                                                        <th class="text-center">Deduction Days</th>
                                                        
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Created At</th>
														@if(Auth::user()->accType == 'client') 
                                                        <th class="text-center hidden-print">Select All<br><input id="check_all" type="checkbox"></th>
													    @endif
                                                    </thead>
                                                    <tbody>
                                                    <?php

                                                    //CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                    $count =1;

                                                    if($payrollData->count()>0):

                                                    ?>

                                                    @foreach($payrollData->get() as $value)
                                                        <?php
                                                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                          $emp= Employee::where('id',$value->employee_id);
                                                        CommonHelper::reconnectMasterDatabase();
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><span style="color: white;" class="badge badge-pill badge-secondary">{{$count++}}</span></td>
                                                            <td class="text-center">{{ $value->emp_id }}</td>

                                                            <td class="text-center">{{ $emp->value('emp_name')  }}</td>

                                                            <td class="text-center"> <?php
                                                                $dateObj   = DateTime::createFromFormat('!m', $value->month);
                                                                $monthName = $dateObj->format('F');
                                                                echo $monthName."-".$value->year ?></td>
                                                            <td class="text-center">{{cal_days_in_month(CAL_GREGORIAN,$value->month,$value->year) }}</td>
                                                            <td class="text-center">{{$value->present_days }}</td>
                                                            <td class="text-center">{{$value->absent_days }}
                                                            

                                                            </td>
                                                            
                                                            <td class="text-center">{{$value->total_halfday_count }}</td>
                                                            <td class="text-center">{{$value->total_late_arrivals }}</td>
                                                            <td class="text-center">{{$value->deduction_days }}</td>

                                                            
                                                           <td class="text-center">{{HrHelper::getApprovalStatusLabel($value->approval_status_m) }}</td>
                                                            <td class="text-center">{{HrHelper::date_format($value->date)}}</td>
															@if(Auth::user()->accType == 'client')
															 <td class="text-center hidden-print">
                                                                @if($value->approval_status_m ==2)
                                                                    <input type="checkbox"  class="ads_Checkbox" name="check_list" checked value="<?php echo $value->emp_id ?>_<?php echo $value->id ?>_2">
                                                                @else
                                                                    <input type="checkbox" class="ads_Checkbox" name="check_list"  value="<?php echo $value->emp_id ?>_<?php echo $value->id ?>_2">
                                                                @endif
                                                            </td>
															@endif
                                                           
                                                        </tr>
                                                        <tr style="background-color: #edeff1a8;">
                                                            <td class="tg-0lax" colspan="18"><b>Reason/Remarks :</b> @if($value->remarks){{$value->remarks }} @else - @endif</td>
                                                        </tr>
                                                        &nbsp;
                                                    @endforeach
                                                    <?php  CommonHelper::reconnectMasterDatabase();
                                                    else:
                                                        echo "<tr><td colspan='18' style='color:red'><b>Attendance Progress Not Found !</b></td></tr>";
                                                    endif;
                                                    ?>
                                                    </tbody>
                                                </table>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
												@if(Auth::user()->accType == 'client')
												<button class="btn btn-success" onclick="approveProgress()" type="submit">Approve</button> 
												<button class="btn btn-danger" onclick="rejectProgress()" type="submit">Reject</button>
												@endif
												</div>

                                            </div>
                                        </div>
                                 
                                <div id="loader"></div>
 
                            
                      </span>
                        </div>
                    </div>
                            </div>
                
                        </div>
                    </div>
                </div>


    </div>




@endsection

