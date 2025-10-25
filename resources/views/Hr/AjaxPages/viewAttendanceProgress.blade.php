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
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    / Firefox / input[type=number] {
                    -moz-appearance: textfield;
                }

    .panel-heading {
        padding: 0px 15px;
    }

    .field_width {
        width: 120px;
    }

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        max-height: 100px;

    }

    .tableFixHead thead th {
        position: sticky;
        top: 0px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        padding: 8px 16px;
    }

    th {
        background: #f9f9f9;
    }

    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }

    .show_data {
        display: none;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="PrintAttendanceList">
                                {{ Form::open(['url' => 'had/addAttendanceProgressDetail']) }}
                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                <div class="wrapper">
                                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead table-hover"
                                           id="AttendanceList" style="font-size: 12px">
                                        <thead>
                                        <tr>
                                            <th class="text-center">S.no</th>
                                            <th class="text-center">EMP ID.</th>
                                            <th class="text-center">Emp Name</th>
                                            <th class="text-center col-sm-1">Total Days <span
                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                            <th class="text-center col-sm-1">Holidays <span
                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                            <th class="text-center col-sm-1">Present Days <span
                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                            <th class="text-center col-sm-1">Absent Days <span
                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                            <th class="text-center col-sm-1">Deduction Days <span
                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                            <th class="text-center col-sm-1">Leaves</th>
                                            <th class="text-center col-sm-1">Overtime (In Hrs)</th>
                                            <th class="text-center col-sm-1">Off Days Overtime (In Hrs)</th>
                                            <!-- <th class="text-center col-sm-1">Gez. Overtime (In Hrs)</th> -->
                                            <th class="text-center col-sm-1">Late</th>
                                            <th class="text-center col-sm-1">Half Days</th>
                                            <th class="text-center col-sm-2">Remarks</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Refresh</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $count = 1; $holidaysOutsideLoop = $totalHoliday ;?>

                                        @if (!empty($employees))
                                            @foreach ($employees as $val)
                                                <?php
                                               
                                                $employee_id = $val->id;
                                                $datediff = strtotime($date_to) - strtotime($date_from);
                                                $totalmonthDay = $datediff / (60 * 60 * 24) + 1;
                                                $totalHolidays = $totalHoliday[$employee_id];
                                              
                                                $holiDays = $totalHolidays;
                                                $present_days = 0;
                                                $absent_days = 0;
                                                $deduction_days = 0;
                                                $leaves = 0;
                                                $overtime = 0;
                                                $cpl = 0;
                                                $Late = 0;
                                                $halfdays = 0;

                                                $status = '-';
                                                $total_count_holidays = 0;
                                                $total_holidays_count = 0;
                                                $late_deduction_days = 0;
                                                $monthly_holidays = [];
                                                $leave_dates_array = [];
                                                $Late = 0;
                                                $half_days_count = 0;
                                                $fourHourTotal = 0;
                                                $gez_overtime = 0;
                                                $remarks = '';
                                                $leavesfullday = 0 ;
                                                $leaveshalfday = 0 ;
                                                $location_name = [];

                                                if (array_key_exists($employee_id, $payroll_data_array) && $flag != 'refresh'):
                                                    $present_days = $payroll_data_array[$employee_id]->present_days == '' ? 0 : $payroll_data_array[$employee_id]->present_days;
                                                    $absent_days = $payroll_data_array[$employee_id]->absent_days == '' ? 0 : $payroll_data_array[$employee_id]->absent_days;
                                                    $deduction_days = $payroll_data_array[$employee_id]->deduction_days == '' ? 0 : $payroll_data_array[$employee_id]->deduction_days;
                                                    $overtime = $payroll_data_array[$employee_id]->overtime;
                                                    $gez_overtime = $payroll_data_array[$employee_id]->gez_overtime;
                                                    $remarks = $payroll_data_array[$employee_id]->remarks;
                                                    $Late = $payroll_data_array[$employee_id]->total_late_arrivals;
                                                    $halfdays = $payroll_data_array[$employee_id]->total_halfday_count;
                                                    $leaves = $payroll_data_array[$employee_id]->total_leaves_count;
                                                    $month = $payroll_data_array[$employee_id]->month;
                                                    $year = $payroll_data_array[$employee_id]->year;
                                                    $leavesfullday = $payroll_data_array[$employee_id]->halfdayleave;
                                                    $leaveshalfday = $payroll_data_array[$employee_id]->fulldayleave;

                                                   // $present_days = $present_days - $leaveshalfday ;// * 2) + $leavesfullday - $absent_days;

                                                    //$cpldata = DB::table(''.CONST_MASTER_DB.'.cpl')->select('cpl')->where('status', 1)->where('month', $month)->where('year', $year)->where('employee_id', $employee_id)->value('cpl');

                                                    //$cpldata ? ($cpl = $cpldata) : ($cpl = 0);
                                                    $status = 'Submitted';
                                                else:
                                                    $present_days = array_key_exists($employee_id, $totalPresentDay) ? $totalPresentDay[$employee_id] : 0;
                                                    $absent_days = array_key_exists($employee_id, $totalAbsentDays) ? $totalAbsentDays[$employee_id] : 0;
                                                    //$cpl = array_key_exists($employee_id, $totalCplLeave) ? $totalCplLeave[$employee_id] : 0;
                                                   
                                                    $gez_overtime = array_key_exists($employee_id, $gez_overtime_array) ? $gez_overtime_array[$employee_id] : 0;
                                                    $overtime = array_key_exists($employee_id, $normalday_overtime) ? $normalday_overtime[$employee_id] : 0;
                                                   
                                                    $leavesfullday = array_key_exists($employee_id, $totalFullDayLeave) ? $totalFullDayLeave[$employee_id] : 0;
                                                    $leaveshalfday = array_key_exists($employee_id, $totalHalfDayLeave) ? $totalHalfDayLeave[$employee_id] : 0;

                                                    $leaves = $leavesfullday + $leaveshalfday;
                                                    $Late = array_key_exists($employee_id, $totalLate) ? $totalLate[$employee_id] : 0;
                                                    $halfdays = array_key_exists($employee_id, $totalHalfDays) ? $totalHalfDays[$employee_id] : 0;
                                                    $deduction_days = array_key_exists($employee_id, $totalDeductionDays) ? ($totalDeductionDays[$employee_id] > 0) ? $totalDeductionDays[$employee_id] : 0 : 0;
                                                    $late_deduction_days = array_key_exists($employee_id, $totalLateDeduction) ? $totalLateDeduction[$employee_id] : 0;
                                                    
                                                    $Late = $Late - $halfdays;
                                                    $present_days = $present_days - $leaveshalfday ;
                                                    $absent_days = $absent_days - ($leavesfullday);
                                                endif;
                                                if($location_id == 10) {
                                                    $gez_overtime = 0;
                                                }
                                               
//                                                if($val->employment_status_id != 5) {
//
//                                                    $totalmonthDay = (!$totalmonthDay || $totalmonthDay < 0)? 0 : $totalmonthDay ;
//                                                    $totalHoliday = (!$holidaysOutsideLoop || $holidaysOutsideLoop < 0)? 0 : $holidaysOutsideLoop ;
//                                                    $leaves = (!$leaves || $leaves < 0)? 0 : $leaves ;
//
//                                                    if($totalmonthDay == $leaves ||  $totalmonthDay < $leaves) {
//                                                        $limit = $totalmonthDay - $leaves  ;
//                                                        $holiDays = 0;
//                                                    } else {
//                                                        $limit = $totalmonthDay - $totalHoliday - $leaves  ;
//                                                    }
//
//                                                    $limit = ($limit < 0 )? 0 : $limit ;
//                                                    $absent_days < 0 ? $absent_days = 0 : '';
//                                                } else {
//                                                    if($location_id == 3 || $location_id == 4) {
//                                                        $limit = $totalmonthDay;
//                                                        $holiDays = 0 ;
//                                                        $absent_days = $totalmonthDay - $present_days;
//                                                        $deduction_days = $Late + $absent_days + $halfdays;
//                                                        $totalHoliday = 0;
//                                                    }
//                                                    else {
//                                                        $limit = $totalmonthDay - $holiDays;
//                                                        // $holiDays = 0 ;
//                                                        $absent_days = $totalmonthDay - $present_days - $holiDays;
//                                                        $deduction_days = $Late + $absent_days;
//                                                        // $totalHoliday = 0;
//                                                    }
//                                                }

                                                $limit = $totalmonthDay;
                                                ?>

                                                <tr>
                                                    <td class="text-center">{{ $count++ }}</td>
                                                    <td class="text-center ">{{ $val->emp_id }}
                                                        <input type="hidden" name="employee_id[]"
                                                               value="{{ $employee_id }}">
                                                    </td>
                                                    <input type="hidden" name="emp_id_{{ $employee_id }}"
                                                           value="{{ $val->emp_id }}">
                                                    <td>{{ $val->emp_name }}</td>
                                                    <td class="text-center" >{{ $totalmonthDay }}
                                                        <input type="hidden" name="total_days_{{ $employee_id }}"
                                                               id="total_days_{{ $employee_id }}"
                                                               value="{{ $totalmonthDay }} ">
                                                    </td>
                                                    <td class="text-center total_holidays_{{ $employee_id }}">{{ $holiDays }}
                                                        <input type="hidden"
                                                               name="total_holidays_{{ $employee_id }}"
                                                               id="total_holidays_{{ $employee_id }}"
                                                               value="{{ $holiDays }}">

                                                    </td>
                                                    <td class="text-center"><p class="show_data present_days_{{ $employee_id }}">{{ $present_days }}</p>
                                                        <input type="number"
                                                               name="present_days_{{ $employee_id }}"
                                                               id="present_days_{{ $employee_id }}"
                                                               class="form-control" value="{{ $present_days }}"
                                                               onkeyup="calculateAbsentDays(this.value, '{{ $employee_id }}','{{ $totalHolidays }}')"
                                                               min="0" max="{{ $limit }}" step="any">
                                                    </td>
                                                    <td class="text-center"><p class="show_data absent_days_{{ $employee_id }}">{{ $absent_days }}</p>
                                                        <input readonly type="number"
                                                               name="absent_days_{{ $employee_id }}"
                                                               id="absent_days_{{ $employee_id }}"
                                                               class="form-control" min="0" value="{{ $absent_days }}" step="any">
                                                    </td>

                                                    <td class="text-center"><p class="show_data deduction_days_{{ $employee_id }}">{{ $deduction_days }}</p>
                                                        <input type="text"
                                                               name="deduction_days_{{ $employee_id }}"
                                                               id="deduction_days_{{ $employee_id }}"
                                                               class="form-control" min="0" value="{{ $deduction_days }}" step="any">
                                                    </td>
                                                    <td class="text-center total_leave_{{ $employee_id }}">{{ $leaves }}
                                                        <input type="hidden"
                                                               name="total_leave_{{ $employee_id }}"
                                                               id="total_leave_{{ $employee_id }}"
                                                               value="{{ $leaves }}">
                                                    </td>
                                                    <td class="text-center"><p class="show_data overtime_{{ $employee_id }}">{{ $overtime }}</p>
                                                        <input type="number" min="0"  name="overtime_{{ $employee_id }}"
                                                               id="overtime_{{ $employee_id }}" class="form-control"
                                                               value="{{ $overtime }}">
                                                    </td>
                                                    <td class="text-center"><p class="show_data gez_overtime_{{ $employee_id }}">{{ $gez_overtime }}</p>
                                                        <input type="number" name="gez_overtime_{{ $employee_id }}"
                                                               id="gez_overtime_{{ $employee_id }}" min="0"  class="form-control"
                                                               value="{{ $gez_overtime }}">
                                                    </td>
                                                 
                                                  
                                                    <td class="text-center"><p class="show_data Late_{{ $employee_id }}">{{ $Late }}</p>
                                                        <input type="number" name="Late_{{ $employee_id }}"
                                                               id="Late_{{ $employee_id }}"  min="0" class="form-control"
                                                               value="{{ $Late }}">
                                                        <input type="hidden" id="late_deducation_{{ $employee_id }}" value="{{ $late_deduction_days }}">
                                                    </td>
                                                    <td class="text-center"><p class="show_data half_days_">{{ $halfdays }}</p>
                                                        <input type="number" min="0"
                                                               name="half_days_{{ $employee_id }}"
                                                               id="half_days_{{ $employee_id }}"
                                                               class="form-control" value="{{ $halfdays }}">
                                                               
                                                               <input type="hidden"
                                                               name="half_days_leave_{{ $employee_id }}"
                                                               id="half_days_leave_{{ $employee_id }}"
                                                               class="form-control" value="{{ $leaveshalfday }}">

                                                               <input type="hidden"
                                                               name="full_days_leave_{{ $employee_id }}"
                                                               id="full_days_leave_{{ $employee_id }}"
                                                               class="form-control" value="{{ $leavesfullday }}">
                                                    </td>
                                                    <td class="text-center"><p class="show_data">{{ $remarks }}</p>
                                                        <input type="text" name="remarks_{{ $employee_id }}"
                                                               id="remarks{{ $employee_id }}" class="form-control"
                                                               value="{{ $remarks }}" />
                                                    </td>
                                                    <td class="text-center" style="color:green;">{{ $status }}
                                                        <input type="hidden" name="m"
                                                               value="{{ Input::get('m') }}" />
                                                        <input type="hidden" name="employee_id[]"
                                                               value="{{ $employee_id }}">
                                                        <input type="hidden"
                                                               name="attendance_type_{{ $employee_id }}"
                                                               value="1">
                                                        <input type="hidden" name="year_{{ $employee_id }}"
                                                               value="{{ $month_year[0] }}">
                                                        <input type="hidden" name="month_{{ $employee_id }}"
                                                               value="{{ $month_year[1] }}">
                                                        <input type="hidden"
                                                               name="attendance_from_{{ $employee_id }}"
                                                               value="{{ $date_from }}">
                                                        <input type="hidden"
                                                               name="attendance_to_{{ $employee_id }}"
                                                               value="{{ $date_to }}">
                                                    </td>
                                                    <td style="padding-top: 1%;"> <span onclick="refreshRow('{{$employee_id}}','{{$date_from}}','{{$date_to}}','{{$totalmonthDay}}')"><img src="{{ URL::asset('assets/images/refresh.png') }}" alt="" style="    width: 40%;"></span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class='text-center' colspan='13' style='color:red'><b>
                                                        Attendance Not Found
                                                        !</b></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="button" class="btn btn-primary" value="Refresh" onclick="viewAttendanceProgress('refresh')"/>
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

function calculateAbsentDays(present_days, emr_no, holidays) {
        var total_month_days = '{{ $total_month_days }}';

        var leave = + $('#half_days_leave_' + emr_no).val() + $('#full_days_leave_' + emr_no).val();
        var lateD = + $('#late_deducation_' + emr_no).val();
        var half_day = + $('#half_days_' + emr_no).val();
       // console.log(leave , lateD , half_day);
        half_day = (half_day/2);

        var half_days_leave = + $('#half_days_leave_' + emr_no).val();
        var positiveNumber = parseInt(total_month_days - holidays - present_days - leave);
      //  console.log(positiveNumber, lateD , half_day , half_days_leave);

        if (positiveNumber >= 0) {

            $('#absent_days_' + emr_no).val(positiveNumber);
            $('#deduction_days_' + emr_no).val(positiveNumber+lateD+half_day - half_days_leave);
            $('.absent_days_' + emr_no).text(positiveNumber);
            $('.deduction_days_' + emr_no).text(positiveNumber+lateD+half_day - half_days_leave);

        }
    }
    var $th = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    });

    function refreshRow(employee_id,from_date,to_date,totaldayofmonth)
    {
        data = {
            employee_id:employee_id,
            from_date:from_date,
            to_date:to_date,
            totaldayofmonth:totaldayofmonth,
            m:m
        };

        $.ajax({
            url: baseUrl+'/hdc/refreshAttendanceProgress',
            type: "GET",
            data: data,
            success:function(data) {
                setRefreshVal(employee_id,totaldayofmonth,data)

            },
            error: function() {
                swalError();
            }
        });
    }

    function setRefreshVal(employee_id,totaldayofmonth,data)
    {
        $("#total_holidays_"+employee_id).text(data?.totalHoliday);
        $(".total_holidays_"+employee_id).val(data?.totalHoliday);
        $("#present_days_"+employee_id).attr("max",data?.limit).val(data?.present_days);
        $(".present_days_"+employee_id).text(data?.present_days);
        $("#absent_days_"+employee_id).val(data?.totalAbsentDays);
        $(".absent_days_"+employee_id).text(data?.totalAbsentDays);

        $("#deduction_days_"+employee_id).val(data?.totalDeductionDays);
        $(".deduction_days_"+employee_id).text(data?.totalDeductionDays);
        $("#total_leave_"+employee_id).val(data?.totalLeaves);
        $(".total_leave_"+employee_id).text(data?.totalLeaves);
        

        $("#overtime_"+employee_id).val(data?.normalday_overtime);
        $(".overtime_"+employee_id).text(data?.normalday_overtime);
        $("#gez_overtime_"+employee_id).val(data?.gez_overtime_array);
        $(".gez_overtime_"+employee_id).text(data?.gez_overtime_array);

        $("#Late_"+employee_id).val(data?.totalLate);
        $(".Late_"+employee_id).text(data?.totalLate);
        $("#half_days_"+employee_id).val(data?.totalHalfDays);
        $(".half_days_"+employee_id).text(data?.totalHalfDays);
        $("#half_days_leave_"+employee_id).val(data?.totalHalfDayLeave);
        $(".half_days_leave_"+employee_id).text(data?.totalHalfDayLeave);
        $("#full_days_leave_"+employee_id).val(data?.totalFullDayLeave);
        $(".full_days_leave_"+employee_id).text(data?.totalFullDayLeave);


    }
   
</script>
