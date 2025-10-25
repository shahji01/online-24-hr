<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
//$diff = strtotime($date_to) - strtotime($date_from);
$total_month_days = abs(round((strtotime($date_to) - strtotime($date_from)) / 86400)) + 1;
$m = Input::get('m');
?>

<style>
   .panel-heading {
        padding: 0px 15px;}
    .field_width {width: 120px;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
        
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }
    .show_data {
        display: none;
    }


</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="PrintAttendanceList">
                {{ Form::open(array('url' => 'had/addAttendanceProgressDetail')) }}
                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" id="AttendanceList" style="font-size: 12px">
                        <thead>
                        <tr>
                            <th class="text-center">S.no</th>
                            <th class="text-center">Emr No.</th>
                            <th class="text-center">Emp Name</th>
                            <th class="text-center">Project</th>
                            <th class="text-center">Locations</th>
                            <th class="text-center">CNIC</th>
                            <th class="text-center col-sm-1">Present Days <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center col-sm-1">Absent Days <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center col-sm-1">Deduction Days <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center col-sm-1">Leaves</th>
                            <th class="text-center col-sm-1">Overtime (In Hrs)</th>
                            <th class="text-center col-sm-1">Gez. Overtime (In Hrs)</th>
                            <th class="text-center col-sm-2">Remarks</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; ?>
                        @if(!empty($employees))
                            @foreach($employees as $val)
                                <?php
                                $employee_id = $val->id;
                                $present_days = 0;
                                $absent_days = 0;
                                $deduction_days = 0;
                                $overtime = 0;
                                $gez_overtime = 0;
                                $remarks = '';
                                $status = '-';
                                $total_count_holidays = 0;
                                $monthly_holidays = [];
                                $leave_dates_array = [];

                                $location_name = [];
                                if(array_key_exists($val->id, $emp_locations_array)):
                                    if($emp_locations_array[$val->id]->location_id == 0):
                                        $location_name[] = 'All';
                                    else:
                                        if(array_key_exists($emp_locations_array[$val->id]->location_id, $location_data)):
                                            $location_name[] = $location_data[$emp_locations_array[$val->id]->location_id];
                                        endif;
                                    endif;
                                endif;

                                if(array_key_exists($val->id, $payroll_data_array)):

                                    $present_days = $payroll_data_array[$val->id]->present_days == '' ? 0 : $payroll_data_array[$val->id]->present_days;
                                    $absent_days = $payroll_data_array[$val->id]->absent_days == '' ? 0 : $payroll_data_array[$val->id]->absent_days;
                                    $deduction_days = $payroll_data_array[$val->id]->deduction_days == '' ? 0 : $payroll_data_array[$val->id]->deduction_days;
                                    $overtime = $payroll_data_array[$val->id]->overtime;
                                    $gez_overtime = $payroll_data_array[$val->id]->gez_overtime;
                                    $remarks = $payroll_data_array[$val->id]->remarks;
                                    $status = 'Submitted';
                                else:

                                    // get all sundays
                                    $startDate = new DateTime($date_from);
                                    $endDate = new DateTime($date_to);
                                    $sundays = array();
                                    while ($startDate <= $endDate):
                                        if ($startDate->format('w') == 0):
                                            $sundays[] = $startDate->format('Y-m-d');
                                        endif;
                                        $startDate->modify('+1 day');
                                    endwhile;
                                    $total_sundays_count = count($sundays);

                                    if($get_holidays->count() > 0):
                                        foreach($get_holidays->get() as $value2):
                                            $monthly_holidays[] = $value2['holiday_date'];
                                        endforeach;
                                    else:
                                        $monthly_holidays = array();
                                    endif;
                                    
                                    $leave_from = date('Y',strtotime($date_from)).'-'.date('m',strtotime($date_from)).'-01';
                                     
                                     
                                    $leave_application_request_list = DB::table("leave_application_data")
                                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                            ->select('leave_application_data.from_date','leave_application_data.to_date',
                                                    'leave_application_data.first_second_half_date','leave_application_data.no_of_days')
                                            ->where([['leave_application.employee_id', '=', $val->id], ['leave_application.status', '=', '1'],
                                                    ['leave_application.approved', '=', '2']])
                                            ->whereBetween('from_date',[$date_from, $date_to])
                                            ->get();

                                    if(!empty($leave_application_request_list)):
                                        foreach($leave_application_request_list as $value3):
                                            $leaves_from_dates = $value3->from_date;
                                            $leaves_to_dates = $value3->to_date;
                                            $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));

                                            foreach ($period as $date):
                                                if($date->format("Y-m-d") >= $date_from && $date->format("Y-m-d") <=  $date_to):
                                                    $leave_dates_array[] = $date->format("Y-m-d");
                                                endif;
                                            endforeach;
                                        endforeach;
                                    else:
                                        $leave_dates_array = [];
                                    endif;

                                    $monthly_holidays = array_unique( array_merge($monthly_holidays, $leave_dates_array) );
                                    $total_count_holidays = count($monthly_holidays);

                                    $attendance = array_filter($attendance_array, function ($value) use ($employee_id) {
                                        return ($value["employee_id"] == $employee_id);
                                    });
                                    foreach($attendance as $key => $att):

                                        $gez_hours = 0;
                                        $gez_min = 0;
                                        $total_working_time = 0;
                                        $total_hours_worked = 0;
                                        $min = 0;
                                        $hours = 0;
                                        $ot_min = 0;
                                        $ot_hours = 0;

                                        $clock_in = strtotime($att['clock_in']);
                                        $clock_out = strtotime($att['clock_out']);

                                        if($clock_in != '' && $clock_out != ''):
                                            $total_hours_worked += abs($clock_out - $clock_in);

                                            if(in_array($att['attendance_date'],$sundays)):
                                                $total_sundays_count--;
                                                if($att['overtime_approval_status'] == 2):
                                                    $min = $total_hours_worked/60;
                                                    $hours = floor($min/60);
                                                    $overtime += $hours;
                                                endif;
                                            else:
                                                if($att['overtime_approval_status'] == 2):
                                                    $total_hours_worked = abs($clock_out - $clock_in);
                                                    $min = $total_hours_worked/60;
                                                    $hours = floor($min/60);
                                                    $min = $min%60;

                                                    $timestamp1 = strtotime($roster_data[$val->roster_id]->clock_in);
                                                    $timestamp2 = strtotime($roster_data[$val->roster_id]->clock_out);
                                                    $total_working_time = abs($timestamp2 - $timestamp1)/(60*60);

                                                    $ot = abs(strtotime($hours.":".$min) - strtotime($total_working_time.':00'));
                                                    $ot_min = $ot/60.;
                                                    $ot_hours = floor($ot_min/60);

                                                    $overtime += $ot_hours;
                                                endif;
                                            endif;

                                            if(in_array($att['attendance_date'],$monthly_holidays)):
                                                $total_count_holidays--;
                                                //gez overtime
                                                $gez_min = $total_hours_worked/60;
                                                $gez_hours = floor($gez_min/60);
                                                $gez_overtime += $gez_hours;
                                            endif;
                                        endif;
                                    endforeach;


                                    if(count($attendance) > 0):
                                        $present_days = count($attendance) + $total_sundays_count + $total_count_holidays;
                                        $absent_days = $total_month_days - $present_days;
                                    else:
                                        $present_days = 0;
                                        $absent_days = 0;
                                    endif;
                                endif;

                                ?>

                                <tr>
                                    <td class="text-center">{{ $count++ }}</td>
                                    <td class="text-center">{{ $val->emp_id }}
                                        <input type="hidden" name="employee_id[]" value="{{$val->id}}">
                                    </td>
                                    <td>{{ $val->emp_name }}</td>
                                    <td>@if(array_key_exists($val->project_id, $project_data)) {{ $project_data[$val->project_id] }} @endif</td>
                                    <td>{{ implode(', ',$location_name) }}</td>
                                    <td class="text-center">{{ $val->cnic }}</td>
                                    <td class="text-center"><p class="show_data">{{ $present_days }}</p>
                                        <input type="number" name="present_days{{ $val->id }}" id="present_days{{ $val->id }}" class="form-control" value="{{ $present_days }}" onkeyup="calculateAbsentDays(this.value, '{{$val->id}}')">
                                    </td>
                                    <td class="text-center"><p class="show_data">{{ $absent_days }}</p>
                                        <input readonly type="number" name="absent_days{{ $val->id }}" id="absent_days{{ $val->id }}" class="form-control" value="{{ $absent_days }}">
                                    </td>
                                    <td class="text-center"><p class="show_data">{{ $deduction_days }}</p>
                                        <input type="number" name="deduction_days{{ $val->id }}" id="deduction_days{{ $val->id }}" class="form-control" value="{{ $deduction_days }}">
                                    </td>
                                    <td class="text-center">
                                        <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/createLeaveApplicationAttendanceForm','{{ $val->id }}','Add Leave Detail','{{ $m }}')">
                                            Click To Add Leave
                                        </a>
                                    </td>
                                    <td class="text-center"><p class="show_data">{{ $overtime }}</p>
                                        <input type="number" name="overtime{{ $val->id }}" id="overtime{{ $val->id }}" class="form-control" value="{{ $overtime }}">
                                    </td>
                                    <td class="text-center"><p class="show_data">{{ $gez_overtime }}</p>
                                        <input type="number" name="gez_overtime{{ $val->id }}" id="gez_overtime{{ $val->id }}" class="form-control" value="{{ $gez_overtime }}">
                                    </td>
                                    <td class="text-center"><p class="show_data">{{ $remarks }}</p>
                                        <input type="text" name="remarks{{ $val->id }}" id="remarks{{ $val->id }}" class="form-control" value="{{ $remarks }}" />
                                    </td>
                                    <td class="text-center" style="color:green;">{{ $status }}
                                        <input type="hidden" name="m" value="{{ Input::get('m') }}" />
                                        <input type="hidden" name="employee_id[]" value="{{ $val->id }}">
                                        <input type="hidden" name="attendance_type_{{ $val->id }}" value="1">
                                        <input type="hidden" name="year_{{ $val->id }}" value="{{ $month_year[0] }}">
                                        <input type="hidden" name="month_{{ $val->id }}" value="{{ $month_year[1] }}">
                                        <input type="hidden" name="attendance_from_{{ $val->id }}" value="{{ $date_from }}">
                                        <input type="hidden" name="attendance_to_{{ $val->id }}" value="{{ $date_to }}">
                                    </td>
                                </tr>

                            @endforeach
                        @else
                            <tr><td class='text-center' colspan='13' style='color:red'><b> Attendance Not Found !</b></td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script>

    var $th = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
        $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    });

    function calculateAbsentDays(present_days, emr_no) {
        var total_month_days = '{{ $total_month_days }}';
        $('#absent_days_'+emr_no).val(parseInt(total_month_days - present_days))
    }

</script>