<?php
$m = Input::get('m');
$countRemainingLeaves = 0;
$countUsedLeavess = 0;
$transferedleaveTotal = 0;
$count = 1;
$count_leaves = 0;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\LeaveApplicationData;
use App\Models\Employee;
use App\Models\TransferedLeaves;

?>

<style>
    input[type="radio"],
    input[type="checkbox"] {
        width: 30px;
        height: 20px;
    }

    tr td {
        padding: 2px !important;
    }

    tr th {
        padding: 2px !important;
    }

    .btn span.glyphicon {
        opacity: 0;
    }

    .btn.active span.glyphicon {
        opacity: 1;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Emp ID</th>
                                            <th class="text-center">Emp Name</th>
                                            <th class="text-center">Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{ $employee->emp_id }}</td>
                                            <td>{{ $employee->emp_name }}</td>
                                            <td>
                                                @if (array_key_exists($employee->department_id, $departments))
                                                    {{ $departments[$employee->department_id]->department_name }}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="5">Leave Balances</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="text-center">S No</th>
                                            <th class="text-center">Leaves Name</th>
                                            <th class="text-center">No of leaves</th>
                                            <th class="text-center">Leaves Availed</th>
                                            <th class="text-center">Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaves_policy as $val)
                                            <?php
                                            $count_leaves += $val->no_of_leaves;
                                            
                                            if ($val->leave_type_id == 1):
                                                $transferedleaveTotal += $transferred_leave->value('annual_leaves');
                                            elseif ($val->leave_type_id == 3):
                                                $transferedleaveTotal += $transferred_leave->value('casual_leaves');
                                            endif;
                                            ?>
                                            <tr>
                                                <td class="text-center">{{ $count++ }}</td>
                                                <td>
                                                    @if (array_key_exists($val->leave_type_id, $leave_type))
                                                        {{ $leave_type[$val->leave_type_id]->leave_type_name }}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $val->no_of_leaves }}</td>
                                                <td class="text-center">
                                                    <?php
                                                    if ($val->leave_type_id == 1):
                                                        $getUsedAnnualLeaves = DB::table('leave_application_data')
                                                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                            ->where([['leave_application_data.leave_policy_id', '=', $employee->leaves_policy_id], ['leave_application.employee_id', '=', $employee->id], ['leave_application.leave_type', '=', $val->leave_type_id], ['leave_application.status', '=', '1'], ['leave_application.approval_status', '=', '2']])
                                                            ->sum('no_of_days');
                                                        echo $getUsedAnnualLeaves;
                                                        $countUsedLeavess += $getUsedAnnualLeaves;
                                                    elseif ($val->leave_type_id == 3):
                                                        $getUsedCasualLeaves = DB::table('leave_application_data')
                                                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                            ->where([['leave_application_data.leave_policy_id', '=', $employee->leaves_policy_id], ['leave_application.employee_id', '=', $employee->id], ['leave_application.leave_type', '=', $val->leave_type_id], ['leave_application.status', '=', '1'], ['leave_application.approval_status', '=', '2']])
                                                            ->sum('no_of_days');
                                                        echo $getUsedCasualLeaves;
                                                        $countUsedLeavess += $getUsedCasualLeaves;
                                                    else:
                                                        echo $getUsedSickLeaves = DB::table('leave_application_data')
                                                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                            ->where([['leave_application_data.leave_policy_id', '=', $employee->leaves_policy_id], ['leave_application.employee_id', '=', $employee->id], ['leave_application.leave_type', '=', $val->leave_type_id], ['leave_application.status', '=', '1'], ['leave_application.approval_status', '=', '2']])
                                                            ->sum('no_of_days');
                                                        $countUsedLeavess += $getUsedSickLeaves;
                                                    endif;
                                                    
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if ($val->leave_type_id == 1):
                                                        $countRemainingLeaves += $val->no_of_leaves - $getUsedAnnualLeaves;
                                                        echo $val->no_of_leaves - $getUsedAnnualLeaves;
                                                        $abc = $val->no_of_leaves - $getUsedAnnualLeaves;
                                                    elseif ($val->leave_type_id == 3):
                                                        $countRemainingLeaves += $val->no_of_leaves - $getUsedCasualLeaves;
                                                        echo $val->no_of_leaves - $getUsedCasualLeaves;
                                                        $abcs = $val->no_of_leaves - $getUsedCasualLeaves;
                                                    else:
                                                        $countRemainingLeaves += $val->no_of_leaves - $getUsedSickLeaves;
                                                        echo $val->no_of_leaves - $getUsedSickLeaves;
                                                    
                                                        $abcd = $val->no_of_leaves - $getUsedSickLeaves;
                                                    endif;
                                                    ?>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-right" colspan="2">Total</th>
                                            <th class="text-center">{{ $count_leaves + $transferedleaveTotal }}</th>
                                            <th class="text-center"><?php print_r($countUsedLeavess); ?></th>
                                            <th class="text-center">{{ $countRemainingLeaves }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <div style="color: #fff;background-color: #6a75e9; ">
                                    <b>SELECT LEAVE TYPE</b>
                                    &ensp;
                                    <span class="glyphicon glyphicon-arrow-down"></span>
                                </div>
                                <div class="btn-group" style="padding: 4px;">
                                    @foreach ($leaves_policy as $val)
                                        @if (array_key_exists($val->leave_type_id, $leave_type))
                                            <?php $leaveName = $leave_type[$val->leave_type_id]->leave_type_name; ?>
                                        @endif

                                        <label style="border:1px solid #fff;" class="btn btn-success"
                                            onclick="viewEmployeeLeavesDetail('{{ $val->id }}','{{ $val->no_of_leaves }}','{{ $val->leave_type_id }}')">
                                            <input required="required" autocomplete="off" type="radio"
                                                name="leave_type" id="leave_type" class="requiredField"
                                                value="{{ $val->leave_type_id }}" />
                                            {{ $leaveName }}
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row"></div>
                        <div id="leavesData"></div>
                        <div id="leave_days_area"></div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Reason For Leave</label>
                                <select class="form-control reasonsList requiredField" name="reason" id="reason"
                                    onchange="check_reason(this.value)">
                                    <option value="">Select</option>
                                    @foreach ($reasons as $r)
                                        <option value="{{ $r->id }}">{{ $r->reason }}</option>
                                    @endforeach
                                </select>
                                <span id="reason_area"></span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Address While on Leaave</label>
                                <textarea id="leave_address" class="form-control requiredField">-</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="leaveAppLoader"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <span id="errorMesg" style="color:red"></span>
                        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                        <button type="button" id="submitBtn" onclick="check_days()"
                            class="btn btn-sm btn-success">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var csrf_token = '<%= csrf_token %>';
    $("body").bind("ajaxSend", function(elm, xhr, s) {
        if (s.type == "POST") {
            xhr.setRequestHeader('X-CSRF-Token', csrf_token);
        }
    });



    var abc = '<?php echo $abc; ?>';
    var abcs = '<?php echo $abcs; ?>';
    var abcd = '<?php echo $abcd; ?>';


    console.log('annaul', abc, 'cas', abcs, 'sick', abcd);



    function leaves_day_type(type) {

        var current_date = '<?= date('Y-m-d') ?>';
        var leave_type = $("input[id='leave_type']:checked").val();

        if (leave_type == 2) {
            if (type == 'full_day_leave') {

                $("#leave_days_area").html('<div class="row">' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave from </label><input type="date" class="form-control requiredField"   name="from_date" id="from_date" onchange="calculateNumberOfDates(this.value,1)"> </div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave Till </label><input type="date" class="form-control requiredField"  name="to_date" id="to_date" onchange="calculateNumberOfDates(this.value,2)"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> No. of Days</label><input type="number" readonly class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                    '<span id="warning_message" style="color:red"></span></div></div>');

            } else if (type == 'half_day_leave') {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>'
                );
            } else if (type == 'short_leave') {
                $("#leave_days_area").html('');
                $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>'
                );

            }
        } else if (leave_type == 3) {
            if (type == 'full_day_leave') {

                $("#leave_days_area").html('<div class="row">' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave from </label><input type="date" class="form-control requiredField"  name="from_date" id="from_date" onchange="calculateNumberOfDates(this.value,1)"> </div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave Till </label><input type="date" class="form-control requiredField" name="to_date"   id="to_date" onchange="calculateNumberOfDates(this.value,2)"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> No. of Days</label><input type="number" readonly onclick="checkCasualLeave()" onkeyup="checkCasualLeave()" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                    '<span id="warning_message" style="color:red"></span></div></div>');


            } else if (type == 'half_day_leave') {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>'
                );
            } else if (type == 'short_leave') {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>'
                );

            }
        }
        else
        {
            if (type == 'full_day_leave') {

                $("#leave_days_area").html('<div class="row">' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave from </label><input type="date" class="form-control requiredField"   name="from_date" id="from_date" onchange="calculateNumberOfDates(this.value,1)"> </div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave Till </label><input type="date" class="form-control requiredField"  name="to_date" id="to_date" onchange="calculateNumberOfDates(this.value,2)"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> No. of Days</label><input type="number" readonly class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                    '<span id="warning_message" style="color:red"></span></div></div>');

                } else if (type == 'half_day_leave') {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>'
                );
                } else if (type == 'short_leave') {
                $("#leave_days_area").html('');
                $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>'
                );

                }
        }
    }

    function check_days() {
        var leave_type = $("input[id='leave_type']:checked").val();
        var leaves_day_type = $("input[id='leave_day_type']:checked").val();
        var leave_policy_id = '<?= $leaves_policy[0]->leaves_policy_id ?>';
        var Otherreason = '';

        jqueryValidationCustom();
        if (validate == 0) {
            if (leave_type == 4) {
                var employee_id = '{{ $employee->id }}';
                var company_id = '<?= Input::get('m') ?>';
                var no_of_days = $("#no_of_days").val();
                var from_date = $("#from_date").val();
                var to_date = $("#to_date").val();
                var leave_type = $("input[id='leave_type']:checked").val();
                var leave_day_type = 1;
                var reason = $("#reason").val();
                if (reason >= 1 && reason <= 6) {
                    reason = $("#reason option:selected").text();
                } else {
                    reason = $("#reason option:selected").text();
                    Otherreason = $("#Otherreason").val();


                }
                var leave_address = $("#leave_address").val();
                var data = {
                    employee_id: employee_id,
                    leave_policy_id: leave_policy_id,
                    company_id: company_id,
                    leave_type: leave_type,
                    leave_day_type: leave_day_type,
                    no_of_days: no_of_days,
                    from_date: from_date,
                    to_date: to_date,
                    reason: reason,
                    Otherreason: Otherreason,
                    leave_address: leave_address
                };

                var from_date = $('#from_date').val();
                var to_date = $("#to_date").val();
                var date1 = new Date(from_date);
                var date2 = new Date(to_date);
                var no_of_days = $("#no_of_days").val();
                var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            } else if (leave_type == 1) {
                if (leaves_day_type == 'full_day_leave') {

                    var inform_days_two = '29';
                    var from_date = $('#from_date').val();
                    var to_date = $("#to_date").val();
                    var no_of_days = $('#no_of_days').val();
                    var current_date = '<?= date('Y-m-d') ?>';
                    var date1 = current_date;
                    var date2 = from_date;
                    date1 = date1.split('-');
                    date2 = date2.split('-');
                    date1 = new Date(date1[0], date1[1], date1[2]);
                    date2 = new Date(date2[0], date2[1], date2[2]);
                    date1_unixtime = parseInt(date1.getTime() / 1000);
                    date2_unixtime = parseInt(date2.getTime() / 1000);
                    var timeDifference = date2_unixtime - date1_unixtime;
                    var timeDifferenceInHours = timeDifference / 60 / 60;
                    var timeDifferenceInDays = timeDifferenceInHours / 24;

                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate = '<?= $leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val() * full_day_deduction_rate);
                    var from_date = $("#from_date").val();
                    var to_date = $("#to_date").val();
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var leave_day_type = 1
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var data = {
                        employee_id: employee_id,
                        leave_policy_id: leave_policy_id,
                        company_id: company_id,
                        full_day_deduction_rate: full_day_deduction_rate,
                        leave_type: leave_type,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        from_date: from_date,
                        to_date: to_date,
                        reason: reason,
                        leave_address: leave_address
                    };
                }

                else if (leaves_day_type == 'half_day_leave') {

                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var half_day_deduction_rate = '<?= $leaves_policy[0]->halfday_deduction_rate ?>';
                    var first_second_half = $("input[id='first_second_half']:checked").val();
                    var no_of_days = (1 * half_day_deduction_rate);
                    var first_second_half_date = $("#first_second_half_date").val();
                    var leave_day_type = 2
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var data = {
                        leave_type: leave_type,
                        company_id: company_id,
                        employee_id: employee_id,
                        leave_policy_id: leave_policy_id,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        first_second_half: first_second_half,
                        first_second_half_date: first_second_half_date,
                        leave_address: leave_address,
                        reason: reason,
                        first_second_half_date: first_second_half_date
                    };
                    }

            } else if (leave_type == 2) {
                if (leaves_day_type == 'full_day_leave') {


                    var from_date = $('#from_date').val();
                    var no_of_days = $('#no_of_days').val();
                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate = '<?= $leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val() * full_day_deduction_rate);
                    var from_date = $("#from_date").val();
                    var to_date = $("#to_date").val();
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var leave_day_type = 1
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var data = {

                        leave_type: leave_type,
                        employee_id: employee_id,
                        leave_policy_id: leave_policy_id,
                        company_id: company_id,
                        full_day_deduction_rate: full_day_deduction_rate,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        from_date: from_date,
                        to_date: to_date,
                        reason: reason,
                        leave_address: leave_address,
                    };



                } else if (leaves_day_type == 'half_day_leave') {

                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var half_day_deduction_rate = '<?= $leaves_policy[0]->halfday_deduction_rate ?>';
                    var first_second_half = $("input[id='first_second_half']:checked").val();
                    var no_of_days = (1 * half_day_deduction_rate);
                    var first_second_half_date = $("#first_second_half_date").val();
                    var leave_day_type = 2
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var data = {
                        leave_type: leave_type,
                        company_id: company_id,
                        employee_id: employee_id,
                        leave_policy_id: leave_policy_id,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        first_second_half: first_second_half,
                        first_second_half_date: first_second_half_date,
                        leave_address: leave_address,
                        reason: reason,
                        first_second_half_date: first_second_half_date
                    };
                } else if (leaves_day_type == 'short_leave') {

                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var per_hour_deduction_rate = '<?= $leaves_policy[0]->per_hour_deduction_rate ?>';
                    var short_leave_time_from = $("#short_leave_time_from").val();
                    var short_leave_time_to = $("#short_leave_time_to").val();
                    var short_leave_date = $("#short_leave_date").val();
                    var no_of_days = (1 * per_hour_deduction_rate);
                    var first_second_half_date = $("#first_second_half_date").val();
                    var leave_day_type = 3;
                    var leave_type = $("input[id='leave_type']:checked").val();

                    var data = {
                        leave_type: leave_type,
                        company_id: company_id,
                        employee_id: empemployee_id_id,
                        leave_policy_id: leave_policy_id,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        short_leave_time_from: short_leave_time_from,
                        short_leave_time_to: short_leave_time_to,
                        short_leave_date: short_leave_date,
                        leave_address: leave_address,
                        reason: reason
                    };
                } else {
                    alert('Error ! Select Full/Half/Short Leave Type !');
                    return false;
                }
            } else if (leave_type == 3) {
                if (leaves_day_type == 'full_day_leave') {
                    var from_date = $('#from_date').val();
                    var no_of_days = $('#no_of_days').val();
                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate = '<?= $leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val() * full_day_deduction_rate);
                    var from_date = $("#from_date").val();
                    var to_date = $("#to_date").val();
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var leave_day_type = 1
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var data = {
                        leave_type: leave_type,
                        employee_id: employee_id,
                        leave_policy_id: leave_policy_id,
                        company_id: company_id,
                        full_day_deduction_rate: full_day_deduction_rate,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        from_date: from_date,
                        to_date: to_date,
                        reason: reason,
                        leave_address: leave_address
                    };


                } else if (leaves_day_type == 'half_day_leave') {

                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var half_day_deduction_rate = '<?= $leaves_policy[0]->halfday_deduction_rate ?>';
                    var first_second_half = $("input[id='first_second_half']:checked").val();
                    var no_of_days = (1 * half_day_deduction_rate);
                    var first_second_half_date = $("#first_second_half_date").val();
                    var leave_day_type = 2
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var data = {
                        leave_type: leave_type,
                        company_id: company_id,
                        employee_id: employee_id,
                        leave_policy_id: leave_policy_id,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        first_second_half: first_second_half,
                        first_second_half_date: first_second_half_date,
                        leave_address: leave_address,
                        reason: reason,
                        first_second_half_date: first_second_half_date,
                    };

                } else if (leaves_day_type == 'short_leave') {

                    var employee_id = '{{ $employee->id }}';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason = $("#reason").val();
                    if (reason >= 1 && reason <= 6) {
                        reason = $("#reason option:selected").text();
                    } else {
                        reason = $("#Otherreason").val();


                    }
                    var leave_address = $("#leave_address").val();
                    var per_hour_deduction_rate = '<?= $leaves_policy[0]->per_hour_deduction_rate ?>';
                    var short_leave_time_from = $("#short_leave_time_from").val();
                    var short_leave_time_to = $("#short_leave_time_to").val();
                    var short_leave_date = $("#short_leave_date").val();
                    var no_of_days = (1 * per_hour_deduction_rate);
                    var first_second_half_date = $("#first_second_half_date").val();
                    var leave_day_type = 3;
                    var leave_type = $("input[id='leave_type']:checked").val();

                    var data = {
                        leave_type: leave_type,
                        company_id: company_id,
                        employee_id: employee_id,
                        leave_policy_id: leave_policy_id,
                        leave_day_type: leave_day_type,
                        no_of_days: no_of_days,
                        short_leave_time_from: short_leave_time_from,
                        short_leave_time_to: short_leave_time_to,
                        short_leave_date: short_leave_date,
                        leave_address: leave_address,
                        reason: reason
                    };

                } else {
                    alert('Error ! Select Full/Half/Short Leave Type !');
                    return false;
                }
            } else {
                alert('Please Select Leaves Type !')
            }
            var company_id = '<?= Input::get('m') ?>';


            $.ajax({
                url: '{{ url('/') }}/hadbac/addLeaveApplicationDetail',
                type: "GET",
                data: data,
                success: function(data) {

              
                    if (data == 1) {
                        $.notify({
                            icon: "fa fa-check-circle",
                            message: "<b>Successfully Saved</b>."
                        }, {
                            type: 'success',
                            timer: 3000
                        });
                        setTimeout(location.reload(), 1000);
                    }
                    else {
                        $.notify({
                            icon: "fa fa-check-circle",
                            message: "<b>"+data+"</b>."
                        }, {
                            type: 'info',
                            timer: 3000
                        });
                    }
                },
                error: function(error) {
                    swalError();
                }
            });
        }
    }

    function viewEmployeeLeavesDetail(id, leavesCount, leaveType) {

        $('#leavesData').append(
            '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>'
        );
        $("#leave_days_area").html('');
        $.ajax({
            type: 'GET',
            url: '{{ url('/') }}/hdc/viewEmployeeLeaveDetail',
            data: {
                employee_id: '{{ $employee->id }}',
                company_id: '{{ Input::get('m') }}',
                leave_id: id,
                leavesCount: leavesCount,
                leaveType: leaveType
            },
            success: function(res) {
                $('#leavesData').html(res);
            }
        });
    }

    function checkAllowedLeaveDays(remainingLeaves, check) {
        var no_of_days = $("#no_of_days").val();
        if (check == 1) {
            if (no_of_days > remainingLeaves) {
                $("#no_of_days").val('');
                $("#warning_message").html('You cannot carry Leaves , More Then ' + remainingLeaves +
                    ' In 2nd segment !');
            } else if (no_of_days < remainingLeaves) {
                $("#no_of_days").val('');
                $("#warning_message").html('You cannot carry Less Leaves , You Have to Carry ' + remainingLeaves +
                    ' Leaves In 2nd segment !');
            } else {
                $("#warning_message").html('');
            }
        } else if (check == 0) {
            if (no_of_days > remainingLeaves) {
                $("#no_of_days").val('');
                $("#warning_message").html('You cannot carry Leaves , More Then ' + remainingLeaves +
                    ' In 1st segment !');

            } else {
                $("#warning_message").html('');
            }
        }
    }

    function checkCasualLeave() {
        var no_of_days = $("#no_of_days").val();
        if (no_of_days > 3) {
            $("#no_of_days").val('');
            $("#warning_message").html('You cannot carry More then 3 Emergency Leaves !');
            $("#submitBtn").attr('disabled', 'disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
        } else {
            $("#warning_message").html('');
            $("#errorMesg").html('');
            $("#submitBtn").removeAttr('disabled');
        }
    }

    function checkCasualLeavesDifference(leavesCount) {
        var from_date = $('#from_date').val();
        var to_date = $("#to_date").val();
        var date1 = new Date(from_date);
        var date2 = new Date(to_date);
        var no_of_days = $("#no_of_days").val();
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

        if (no_of_days == (diffDays + 1)) {
            $("#warning_message").html('');
            $("#errorMesg").html('');
            $("#submitBtn").removeAttr('disabled');
            return true;
        } else {
            $("#warning_message").html('Please Correct Date Difference !');
            $("#submitBtn").attr('disabled', 'disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
        }
    }

    function checkAnnualLeaveDays(leavesCount) {

        var no_of_days = $("#no_of_days").val();
        if (no_of_days < 4) {
            $("#warning_message").html('You cannot take less then 4 and More Then ' + leavesCount + ' Annual Leaves !');
            $("#submitBtn").attr('disabled', 'disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
        } else if (no_of_days > leavesCount) {
            $("#warning_message").html('You cannot take less then 4 and More Then ' + leavesCount + ' Annual Leaves !');
            $("#submitBtn").attr('disabled', 'disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
        } else {
            $("#warning_message").html('');
            $("#errorMesg").html('');
            $("#submitBtn").removeAttr('disabled');
            return true;
        }
    }
</script>
