<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
//$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\PayrollData;
use App\Models\Employee;


$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
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
                        </thead>
                        <tbody>
                        <?php

                        //CommonHelper::companyDatabaseConnection(Input::get('m'));
                        $count =1;

                        if($attendanceProgress->count() > 0):

                        ?>

                        @foreach($attendanceProgress->get() as $value)
                            
                            <tr>
                                <td class="text-center">{{$count++}}</td>
                                <td class="text-center">{{ $value->emp_id }}</td>
                                <?php CommonHelper::companyDatabaseConnection(Input::get('m')); ?>
                                <td class="text-center">{{ Employee::where('id',$value->employee_id)->where('status',1)->first()->emp_name }}</td>
                                <?php CommonHelper::reconnectMasterDatabase(); ?>
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
								@if(Auth::user()->accType == 'client')                                <td class="text-center hidden-print">
                                    @if($value->approval_status_m ==2)
                                        <input type="checkbox"  class="ads_Checkbox" name="check_list" checked value="<?php echo $value->emp_id ?>_<?php echo $value->id ?>_2">
                                    @else
                                        <input type="checkbox" class="ads_Checkbox" name="check_list"  value="<?php echo $value->emp_id ?>_<?php echo $value->id ?>_2">
                                    @endif
							   @endif		
                                </td>
                            </tr>
                            <tr style="background-color: #edeff1a8;">
                                <td colspan="18"><b>Reason/Remarks :</b> @if($value->remarks){{$value->remarks }} @else - @endif</td>
                            </tr>
                            &nbsp;
                        @endforeach
                        <?php  CommonHelper::reconnectMasterDatabase();
                        else:
                            echo "<tr><td class='text-center' colspan='18' style='color:red'><b>Attendance Progress Not Found !</b></td></tr>";
                        endif;
                        ?>
                        </tbody>
                    </table>
					@if(Auth::user()->accType == 'client')
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print"><button class="btn btn-success" onclick="approveProgress()" type="submit">Approve</button> <button class="btn btn-danger" onclick="rejectProgress()" type="submit">Reject</button></div>
					@endif
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#check_all").click(function(){

            if($("#check_all").prop("checked") == true)
            {
                $(".ads_Checkbox").prop("checked",true);
            }
            else
            {
                $(".ads_Checkbox").prop("checked",false);
            }


        });
    });
</script>