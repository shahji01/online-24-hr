<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\Attendance;

?>
<div class="panel">
    <div class="panel-heading">

    </div>
    <div class="panel-body">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="label" style="background-color:#FFC0CB;">&nbsp;&nbsp;&nbsp;</span> = Holidays
            <span class="label" style="background-color:lightcoral;">&nbsp;&nbsp;&nbsp;</span> = Late Arrivals
            @if(in_array('delete', $operation_rights2))
                <input type="button" class="btn btn-sm btn-danger" id="deleteAttendenceReport" onclick="deleteAttendanceReport()" value="Delete Attendence" style="float: right" />
            @endif
        </div>
        <br><br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12" id="PrintEmployeeAttendanceList">
                <div class="table-responsive" >
                    <table class="table table-responsive table-bordered table-condensed table-hover" id="EmployeeAttendanceList">
                        <thead>
                            <th class="text-center">S.no</th>
                            <th class="text-center">EMR No</th>
                            <th class="text-center">Present Days</th>
                            <th class="text-center">Absent Days</th>
                            <th class="text-center">Overtime</th>
                            <th class="text-center">Employee Project</th>
                            <th class="text-center">From - To</th>
                            <th class="text-center">Month</th>
                            <th class="text-center">Year</th>
                            <th class="text-center">Action</th>
                        </thead>
                        <tbody>
                        <?php $count =1;?>
                        @if($attendance->count() > 0)
                            @foreach($attendance->get() as $value)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));

                                $day_off_emp =Employee::select('day_off')->where([['emr_no','=',$value->emr_no]])->value('day_off');
                                $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['emr_no','=',$value->emr_no]]);

                                if($total_days_off->count() > 0):
                                    foreach($total_days_off->get()->toArray() as $offDates):
                                        $totalOffDates[] = $offDates['attendance_date'];
                                    endforeach;
                                else:
                                    $totalOffDates =array();
                                endif;

                                $get_holidays = Holidays::select('holiday_date')->where([['status','=',1],['month','=',$value->month],['year','=',$value->year]]);
                                if($get_holidays->count() > 0):
                                    foreach($get_holidays->get() as $value2):
                                        $monthly_holidays[]=$value2['holiday_date'];
                                    endforeach;
                                else:
                                    $monthly_holidays =array();
                                endif;

                                $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                {{--<tr @if(in_array($value->attendance_date,$monthly_holidays)) style="background-color: #FFC0CB;" @endif>--}}
                                <tr>
                                    <td class="text-center">{{$count++}}</td>
                                    <td class="text-center">{{$value->emr_no}}</td>
                                    <td class="text-center">{{ $value->present_days }}</td>
                                    <td class="text-center">{{ $value->absent_days }}</td>
                                    <td class="text-center">{{ $value->overtime }}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$value->employee_project_id)}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($value->attendance_from)."--".HrHelper::date_format($value->attendance_to) }}</td>
                                    <td class="text-center">{{ $value->month }}</td>
                                    <td class="text-center">{{ $value->year }}</td>
                                    <td class="text-center">
                                        @if(in_array('edit', $operation_rights2))
                                            <button class="btn btn-primary btn-xs" onclick="showDetailModelTwoParamerter('hr/editEmployeeAttendanceDetailForm','<?php echo $value->id ?>','Edit Employee Attendance Detail','<?php echo Input::get('m'); ?>')">Edit</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        @else
                            <tr><td class='text-center' colspan="11" style='color: red; font-weight: bold'>Attendance Not Found !</td></tr>
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>

