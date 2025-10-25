<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

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
$total_late_mins = 0;
$total_early_going_mins = 0;

$totals = [
        'total_present_days' => 0,
        'total_absent_days' => 0,
        'total_leaves' => 0,
        'total_holidays' => 0,
        'total_off_days' => 0,
        'total_late_minutes' => 0,
        'total_half_days' => 0,
];
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Report for Period: {{ HrHelper::date_format($from_date) }} - {{ HrHelper::date_format($to_date) }}</b></div>
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
                                <div class="table-responsive wrapper" id="printList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Emp ID</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Working Hour Policy</th>
                                        <th class="text-center">Day</th>
                                        <th class="text-center">Att- Status</th>
                                        <th class="text-center">Attendance Date</th>
                                        <th class="text-center">Clock In</th>
                                        <th class="text-center">Clock In Location</th>
                                        <th class="text-center">Clock Out</th>
                                        <th class="text-center">Clock Out Location</th>
                                        <th class="text-center">Duration</th>
                                        <th class="text-center">Lates</th>
                                        <th class="text-center">Early Going</th>
                                        </thead>
                                        <tbody>

                                        @if(count($attendance) > 0)
                                            @foreach($attendance as $value)

                                                <?php
                                                ($value->AttStatus == 'Holiday' || $value->AttStatus == 'Off day' )? $value->late = '--' : '' ;
                                                ?>
                                                <tr <?php echo $status_bg_color[str_replace(' ', '_', $value->AttStatus)] ?> >
                                                    <td class="text-center">{{ $count++ }}</td>
                                                    <td class="text-center">{{ $value->emp_id }}</td>
                                                    <td>{{ $value->emp_name }}</td>
                                                    <td>{{ $value->working_hours_policy }}</td>
                                                    <td class="text-center">{{ $value->day }}</td>
                                                    <td class="text-center">{{ $value->AttStatus }}</td>
                                                    <td class="text-center">{{ HrHelper::date_format($value->attendance_date) }}</td>
                                                    <td class="text-center">{{ $value->clock_in }}</td>
                                                    <td>
                                                        @if(array_key_exists($value->clock_in_location_id, $locations)){{ $locations[$value->clock_in_location_id]->location_name }} @endif
                                                    </td>
                                                    <td class="text-center">{{ $value->clock_out }}</td>
                                                    <td>
                                                        @if(array_key_exists($value->clock_out_location_id, $locations)){{ $locations[$value->clock_out_location_id]->location_name }} @endif
                                                    </td>
                                                    <td class="text-center">{{ $value->duration }}</td>
                                                    <td class="text-center">{{ $value->late }}</td>
                                                    <td class="text-center">{{ $value->early_going > 0 ? round($value->early_going) : $value->early_going }}</td>

                                                    @php
                                                    if($value->late && $value->late > 0){
                                                    $total_late_mins+=$value->late;
                                                    }
                                                    @endphp
                                                    @php
                                                    if($value->early_going && $value->early_going > 0){
                                                    $total_early_going_mins+=$value->early_going;
                                                    }
                                                    @endphp
                                                </tr>

                                                <?php
                                                switch ($value->AttStatus) {
                                                    case 'Present':
                                                        $totals['total_present_days']++;
                                                        break;
                                                    case 'Absent':
                                                        $totals['total_absent_days']++;
                                                        break;
                                                    case 'Leave':
                                                        $totals['total_leaves']++;
                                                        break;
                                                    case 'Holiday':
                                                        $totals['total_holidays']++;
                                                        break;
                                                    case 'Off day':
                                                        $totals['total_off_days']++;
                                                        break;
                                                    case 'Half day':
                                                        $totals['total_half_days']++;
                                                        break;
                                                }

                                                if ($value->late != '--') {
                                                    $totals['total_late_minutes']++;
                                                }
                                                ?>
                                            @endforeach

                                        @else
                                            <tr class="text-center">
                                                <td colspan="14" style="color:red;">
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
                        @if(count($attendance)>0)
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Present: {{$totals['total_present_days']}}</b></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Absent: {{ $totals['total_absent_days'] }}</b></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Holidays: {{ $totals['total_holidays'] }}</b></div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-right">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b> Total Late: {{ $total_late_mins }} </b></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b> Total Leave(s): {{$totals['total_leaves']}}</b></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b> Total Early Going: {{ round($total_early_going_mins) }} </b></div>
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
        var m='{{ Input::get("m") }}';

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