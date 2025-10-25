<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;

$accType = Auth::user()->acc_type;
$count = 1;
$total_ot_days = 0;
$leave_application_request_list = [];
$totalHoursWorked4 = 0;
$dates = [];
$absentDays = 0;
$leaves = array();
$totalLateHoursCount = 0;
$totalLateMintsCount = 0;
$diff2 = 0;
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Report for Period: {{CommonHelper::changeDateFormat($from_date)}} - {{CommonHelper::changeDateFormat($to_date)}}</b></div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Employee Code: {{ $employee['emp_id']}}</b></div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Employee Name: {{ $employee['emp_name']}}</b></div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Sub Department:
                                @if($employee['sub_department_id'] != '' && array_key_exists($employee['sub_department_id'],$sub_departments))
                                    {{ $sub_departments[$employee['sub_department_id']]->sub_department_name }}
                                @else
                                    -
                                @endif
                                </b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hidden-print" id="highlights">
                                <span class="label" style="background-color:#FFC0CB;">&nbsp;&nbsp;&nbsp;</span> = Holidays
                                <span class="label" style="background-color:#adde80;">&nbsp;&nbsp;&nbsp;</span> = Leaves
                                <span class="label" style="background-color:#e76e6ed9;;">&nbsp;&nbsp;&nbsp;</span> = Absents
                                <span class="label" style="background-color:#ffd78d;">&nbsp;&nbsp;&nbsp;</span> = Half Days
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" id="printList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Day</th>
                                        <th class="text-center">Event</th>
                                        <th class="text-center">Att- Status</th>
                                        <th class="text-center">Attendance Date</th>
                                        <th class="text-center">Clock In</th>
                                        <th class="text-center">Clock Out</th>
                                        <th class="text-center">Duration</th>
                                        <th class="text-center">Lates</th>
                                        </thead>
                                        <tbody>
                                        @if($attendance->count() > 0)
                                            @foreach($attendance as $value)
                                                <?php
                                                CommonHelper::reconnectMasterDatabase();
                                                $working_hours_policy = WorkingHoursPolicy::where('id',$value->working_hours_policy_id)->where('status',1)->orderBy('id','desc');
                                                if(!empty($working_hours_policy)){
                                                    $GraceTime = strtotime("+".$working_hours_policy->value('working_hours_grace_time')." minutes", strtotime($working_hours_policy->value('start_working_hours_time')));
                                                }

                                                if($value->working_hours_policy_id > 0){
                                                    $duty_time =  CommonHelper::getMasterTableValueById(Input::get('m'),'working_hours_policy','start_working_hours_time',$value->working_hours_policy_id);
                                                    $duty_end_time =  CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','end_working_hours_time',$value->working_hours_policy_id);
                                                }else{
                                                    $duty_time ='';
                                                    $duty_end_time ='';
                                                }

                                                $LikeDate = "'".'%'.$value->year."-".$value->month.'%'."'";
                                                $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date from leave_application
                                                    INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                                                    WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.employee_id = '.$value->employee_id.' AND leave_application.status = 1 AND leave_application.approved = 2 AND
                                                        leave_application.view = "yes"
                                                    OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.employee_id = '.$value->employee_id.'');

                                                $leaves_from_dates2 = [];
                                                if(!empty($leave_application_request_list)):
                                                    foreach($leave_application_request_list as $value3):
                                                        $leaves_from_dates = $value3->from_date;
                                                        $leaves_to_dates = $value3->to_date;
                                                        $leaves_type=$value3->leave_type;
                                                        $leaves_from_dates2[] = $value3->from_date;

                                                        $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));
                                                        foreach ($period as $date) {
                                                            $dates[] = $date->format("Y-m-d");
                                                        }
                                                    endforeach;
                                                endif;
                                                $monthly_holidays_absents = array_merge($monthly_holidays,$dates);

                                                $startTime = $working_hours_policy->value('start_working_hours_time');
                                                $finishTime = $working_hours_policy->value('end_working_hours_time');
                                                $dutyEndTime = date("H:i", strtotime($finishTime));
                                                $endTime = strtotime("+".$working_hours_policy->value('working_hours_grace_time')."minutes", strtotime($startTime));
                                                $half_day_time=strtotime("+".$working_hours_policy->value('half_day_time')."minutes", strtotime($startTime));

                                                CommonHelper::companyDatabaseConnection(Input::get('m'));

                                                $half_days_absent = date('h:i', $half_day_time);
                                                $end_day_time = date('h:i', $endTime);

                                                $lates = DB::table('attendance')->select('attendance_date')->where([['month','=',$value->month],['year','=',$value->year],
                                                        ['clock_in','>',$end_day_time],['clock_in','<',$half_days_absent],['employee_id','=',$value->employee_id]])
                                                        ->whereNotIn('attendance_date', $monthly_holidays);

                                                $half_days = DB::table('attendance')->select('attendance_date')->where([['month','=',$value->month],['year','=',$value->year],
                                                        ['clock_in','>=',$half_days_absent],['employee_id','=',$value->employee_id]])
                                                        ->whereNotIn('attendance_date', $monthly_holidays);

                                                $totalHalfDaysDates =array();
                                                if($half_days->count() > 0):
                                                    foreach($half_days->get() as $day):
                                                        $totalHalfDaysDates[] = $day->attendance_date;
                                                    endforeach;
                                                else:
                                                    $totalHalfDaysDates = array();
                                                endif;

                                                $half_2nd_days = DB::table('attendance')->select('attendance_date')->where([['month','=',$value->month],['year','=',$value->year],
                                                        ['clock_out','<',$dutyEndTime],['clock_in','!=',''],['employee_id','=',$value->employee_id]])
                                                        ->whereNotIn('attendance_date', $monthly_holidays)
                                                        ->whereNotIn('attendance_date', $totalHalfDaysDates);
                                                // $dutyEndTime

                                                $total2ndHalfDaysDates =array();
                                                if($half_2nd_days->count() > 0):

                                                    foreach($half_2nd_days->get() as $day):
                                                        $total2ndHalfDaysDates[] = $day->attendance_date;
                                                    endforeach;

                                                else:
                                                    $total2ndHalfDaysDates =array();
                                                endif;

                                                // echo "<pre>";
                                                // print_r($total2ndHalfDaysDates);
                                                // echo "</pre>";

                                                if(in_array($value->attendance_date,$dates)):
                                                    $leaves[] = $value->attendance_date;
                                                endif;
                                                $attendance_status = '';

                                                if(in_array($value->attendance_date,$totalOffDates)){
                                                    $attendance_status = 'Off Day';
                                                }
                                                else if(in_array($value->attendance_date,$public_holidays)){
                                                    $attendance_status = 'Off Day';
                                                }
                                                elseif(in_array($value->attendance_date,$leaves_from_dates2)){
                                                    $attendance_status = 'Leave';
                                                }
                                                elseif(in_array($value->attendance_date,$totalHalfDaysDates) || in_array($value->attendance_date,$total2ndHalfDaysDates)){
                                                    $attendance_status = 'Half Day';
                                                }
                                                elseif($value->clock_in != '' || $value->clock_out != ''){
                                                    $attendance_status = 'Present';
                                                }
                                                elseif(in_array($value->attendance_date,$dates)){
                                                    $attendance_status = 'Leave';
                                                }
                                                else{
                                                    $attendance_status = 'Absent';
                                                }
                                                         
                                                ?>

                                                <tr id="att_tr_{{$count}}" <?php echo $status_bg_color[$attendance_status]; ?>>
                                                    <td class="text-center">{{ $count++ }}</td>
                                                    <td class="text-center">{{date('l', strtotime($value->attendance_date))}}</td>
<td>@if(in_array($value->attendance_date,$totalOffDates)) Off Day @elseif(in_array($value->attendance_date,$public_holidays))Holiday @elseif(in_array($value->attendance_date,$leaves_from_dates2))Leave @else Routine @endif
                                                    </td>
<td>@if(in_array($value->attendance_date,$totalOffDates)) Off Day @elseif(in_array($value->attendance_date,$public_holidays))Off Day @elseif(in_array($value->attendance_date,$leaves_from_dates2))Leave @elseif(in_array($value->attendance_date,$totalHalfDaysDates) || in_array($value->attendance_date,$total2ndHalfDaysDates)) Half Day @elseif($value->clock_in != '' || $value->clock_out != '')@if($value->work_remotely == 1 && $accType=="client")<select style="height:30px !important;background:none !important;" name="att_status" id="select_att_status" class="form-control select_att_status"><option value="absent*{{$value->attendance_date}}*{{$value->employee_id}}*{{$count}}">Absent</option><option selected value="present*{{$value->attendance_date}}*{{$value->employee_id}}*{{$count}}">Work Remotely</option></select> @else @if($value->work_remotely == 1) Work Remotely @else    Present @endif    @endif @elseif(in_array($value->attendance_date,$dates)) Leave @else  @if($value->work_remotely == 1 && $accType=="client") <select style="height:30px !important;background:none !important;" name="att_status" id="select_att_status" class="form-control select_att_status">  <option value="absent*{{$value->attendance_date}}*{{$value->employee_id}}*{{$count}}">Absent</option> <option value="present*{{$value->attendance_date}}*{{$value->employee_id}}*{{$count}}">Work Remotely</option> </select> @else Absent @endif @endif</td>
                                                    <td class="text-center">{{HrHelper::date_format($value->attendance_date)}}</td>
                                                    <td class="text-center">
                                                        @if($value->clock_in != '')
                                                            {{date('h:i: a', strtotime($value->clock_in))}}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($value->clock_out != '')
                                                            {{date('h:i: a', strtotime($value->clock_out)) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $clock_in = $value->clock_in;

                                                        if($clock_in != '' && $value->clock_out != ''){
                                                            $time1 = strtotime($clock_in);
                                                            $time2 = strtotime($value->clock_out);


                                                            $diff = abs($time2 - $time1);
                                                            //$diff2 += abs($time1 - $GraceTime);
                                                            $tmins = $diff/60;

                                                            $hours = floor($tmins/60);

                                                            $mins = $tmins%60;

                                                            $totalHoursWorked[] = $hours.":".$mins;
                                                            $totalLateHoursCount = $hours;

                                                            if($mins > 15):
                                                                $totalLateHoursCount +=1;
                                                            endif;
                                                            // $totalLateHoursCount += $hours;
                                                            // $totalLateMintsCount += $mins;
                                                            echo  "<b>".$hours.':'. $mins."</b>";

                                                        }else{
                                                            echo "--";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php

                                                        //Late Coming
                                                        if(!in_array($value->attendance_date,$monthly_holidays)):

                                                            $clock_in = $value->clock_in;
                                                            if($clock_in == ''){$clock_in = "00:00";}
                                                            if($clock_in != ''){
                                                                $time1 = strtotime($clock_in);

                                                                if($time1 > $GraceTime):
                                                                    $diff = abs($time1 - $GraceTime);
                                                                    $diff2 += abs($time1 - $GraceTime);
                                                                    $tmins = $diff/60;

                                                                    $hours = floor($tmins/60);

                                                                    $mins = $tmins%60;

                                                                    $totalHoursWorked[] = $hours.":".$mins;

                                                                    $totalLateHoursCount += $hours;

                                                                    $totalLateMintsCount += $mins;

                                                                    if($mins > 0):
                                                                        $totalLateHoursCount +=1;
                                                                    endif;
                                                                    echo "<b>$hours : $mins</b>";
                                                                endif;
                                                            }
                                                        endif;
                                                        ?>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="text-center">
                                                <td colspan="3" style="color:red;">
                                                    Record Not Found
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        @if($attendance->count()>0)
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Present: {{$total_present}}</b></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Absent: {{ $total_absent }}</b></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Holidays: {{ count($monthly_holidays) - 1 }}</b></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Leave(s): {{$count_leave_apllication}}</b></div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-right">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings">
                                            <b>Total Lates: {{ $lates->count() }}</b>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings">
                                            <b>Total Half Days: {{ $half_days->count() }}</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.select_att_status').on('change', function() {
        var status=this.value;
        var myArr = status.split("*");
        var c=myArr[3];
        c--;
        if(myArr[0] == "absent"){
            $('#att_tr_'+c).css( "background-color", "#e76e6ed9" );
        }
        else{
            $('#att_tr_'+c).css('background-color','');
        }

        var att_status=myArr[0];
        var emp_id=myArr[2];
        var att_date=myArr[1];
        var m='<?php echo Input::get("m") ?>';

        $.ajax({
            url: baseUrl+'/had/editAttendanceDetail',
            type: "GET",
            data: {att_status:att_status,emp_id:emp_id,att_date:att_date,m:m},
            success:function(data) {
                if(data=='success'){
                    $.notify({
                        icon: "fa fa-check-circle",
                        message: "<b> Successfully Updated</b>.",
                    }, {
                        type: 'success',
                        timer: 50
                    });
                }
            }

        });
    });
</script>