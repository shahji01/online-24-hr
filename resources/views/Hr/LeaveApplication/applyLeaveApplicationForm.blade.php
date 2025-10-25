<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\LeaveApplicationData;
use App\Models\Employee;
$counter = 1;
$m = Input::get('m');

$data = json_decode($data);

?>
<script src="{{ URL::asset('assets/custom/js/leaveApplications.js') }}"></script>
<style>
    input[type="radio"],
    input[type="checkbox"] {
        width: 30px;
        height: 20px;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="{{ $m }}">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-sm mb-0 table-bordered sf-table-list">
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
                                @if(array_key_exists($employee->department_id, $departments)){{ $departments[$employee->department_id]->department_name }} @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-sm mb-0 table-bordered table-striped">
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
                        @foreach($leaves_policy as $val)
                            @if(($val->leave_type_id == 5 && $leaves['totalCplLeaves'] == 0) || ($val->leave_type_id == 4 && $leaves['totalSpecialLeaves'] == 0))
                            @else
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td>@if(array_key_exists($val->leave_type_id, $leave_type)){{ $leave_type[$val->leave_type_id]->leave_type_name }}@endif</td>
                                    <td class="text-center">
                                        @if($val->leave_type_id == 4)
                                            {{ $leaves['totalSpecialLeaves'] }}

                                        @elseif($val->leave_type_id == 5)
                                            {{ $leaves['totalCplLeaves'] }}

                                        @else
                                            {{ $val->no_of_leaves }} {{ $leaves['transferred_leaves']->count() > 0 ? ' + '.$leaves['transferred_leaves_data'][$val->leave_type_id] : '' }}

                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($val->leave_type_id == 1)
                                            {{ $leaves['takenAnnualLeaves'] }}

                                        @elseif($val->leave_type_id == 2)
                                            {{ $leaves['takenSickLeaves'] }}

                                        @elseif($val->leave_type_id == 3)
                                            {{ $leaves['takenCasualLeaves'] }}

                                        @elseif($val->leave_type_id == 4)
                                            {{ $leaves['takenSpecialLeaves'] }}

                                        @elseif($val->leave_type_id == 5)
                                            {{ $leaves['takenCplLeaves'] }}

                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($val->leave_type_id == 1)
                                            {{ $leaves['remainingAnnualLeaves'] }}

                                        @elseif($val->leave_type_id == 2)
                                            {{ $leaves['remainingSickLeaves'] }}

                                        @elseif($val->leave_type_id == 3)
                                            {{ $leaves['remainingCasualLeaves'] }}

                                        @elseif($val->leave_type_id == 3)
                                            {{ $leaves['remainingSpecialLeaves'] }}

                                        @elseif($val->leave_type_id == 5)
                                            {{ $leaves['remainingCplLeaves'] }}

                                        @else
                                            0

                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-right" colspan="2">Total</th>
                            <th class="text-center">{{ $leaves['totalLeaves'] }}</th>
                            <th class="text-center">{{ $leaves['totalUsedLeaves'] }}</th>
                            <th class="text-center">{{ $leaves['totalRemainingLeaves'] }}</th>
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

                            @if($val->leave_type_id == 5 && $leaves['remainingCplLeaves'] == 0)
                            @else
                                <label style="border:1px solid #fff;" class="btn btn-success" onclick="">
                                    <input required="required" autocomplete="off" type="radio" name="leave_type" id="leave_type" class="requiredField custom-radio" value="{{ $val->leave_type_id }}" />
                                    {{ $leaveName }}
                                    <span class="glyphicon glyphicon-ok"></span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="leavesData">
                <div class="row" style="background-color: gainsboro">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <h4>Full Day Leave :
                            <input  @if($data->type == 'full_day') checked @endif  class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="full_day_leave" onclick="leaves_day_types(this.value)" />
                        </h4>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <h4>Half Day Leave :
                            <input type="radio" @if($data->type == 'Second_half' || $data->type == 'first_half') checked @endif name="leave_day_type" id="leave_day_type" value="half_day_leave" onclick="leaves_day_types(this.value)" />
                        </h4>
                    </div>
                </div>

            </div>
            <div id="leave_days_area"></div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Reason For Leave</label>
                    <select class="form-control reasonsList" name="reason" id="reason" onchange="check_reason(this.value)">
                        <option value="">Select</option>
                        @foreach ($reasons as $r)
                            <option value="{{ $r->id }}">{{ $r->reason }}</option>
                        @endforeach
                    </select>
                    <span id="reason_area"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Address While on Leave</label>
                    <textarea id="leave_address" class="form-control requiredField">-</textarea>
                </div>
            </div>

            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <span id="errorMesg" style="color:red"></span>
                    <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                    <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-sm btn-success">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

var data = JSON.parse('<?php echo json_encode($data); ?>');


function leaves_day_types() {
        var leave_type = $("input[id='leave_day_type']:checked").val();
        if (leave_type == 'full_day_leave') {
            
            $('#leave_days_area').html('<div class="row">' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> Leave from </label><input type="date" class="form-control requiredField" value="'+data?.from_date+'" name="from_date" id="from_date" onchange="calculateNumberOfDates(this.value,1)"> </div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> Leave Till </label><input type="date" class="form-control requiredField"  value="'+data?.to_date+'"  name="to_date" id="to_date" onchange="calculateNumberOfDates(this.value,2)"></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> No. of Days</label><input type="number" readonly  value="'+data?.total_days+'" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                '<span id="warning_message" style="color:red"></span></div></div>');

        } else if (leave_type == 'half_day_leave') {

            var html = '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
           '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input ';
           
            if (data?.type == 'first_half') {
                html += 'checked ';
            }

            html += 'type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 P.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;Second Half&nbsp;:&nbsp;<input ';

            if (data?.type == 'Second_half') {
                html += 'checked ';
            }

            html += 'type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" value="'+data?.from_date+'" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date" onchange="calculateNumberOfDates(this.value,1)"> </div></div>';

            $('#leave_days_area').html(html);

        }
    }
    leaves_day_types();
    var baseUrl=$('#baseUrl').val();

    var fromDate;
    var date1;
    var toDate;
    var date2;
    function calculateNumberOfDates(value,flag) {
        var remaining_annual = '{{ $leaves['remainingAnnualLeaves'] }}';
        var remaining_sick = '{{ $leaves['remainingSickLeaves'] }}';
        var remaining_casual = '{{ $leaves['remainingCasualLeaves'] }}';
        var remaining_cpl = '{{ $leaves['remainingCplLeaves'] }}';
        var leave_type = $("input[id='leave_type']:checked").val();
        var leave_day_type = $("input[id='leave_day_type']:checked").val();
        var no_of_days = 0;

        if(leave_day_type == 'full_day_leave') {
            if(flag == 1) {
                fromDate = value;
                date1 = new Date(fromDate);
                $("#to_date").attr("min",value);
                $("#no_of_days").val('');
            } else if(flag == 2) {
                fromDate = value;
                date2 = new Date(fromDate);
                var Difference_In_Time = date2.getTime() - date1.getTime();
                no_of_days = (Difference_In_Time / (1000 * 3600 * 24)) + 1;
                $("#no_of_days").val(no_of_days).css.visibility = 'visible';
            }
        } else if(leave_day_type == 'half_day_leave') {
            no_of_days = 0.5;
        }

        if(leave_type == 1) {
            if(no_of_days > remaining_annual) {
                swalAlert('','Days is greater than your remaining annual leaves');
                $('#submitBtn').attr('disabled', true);
            } else {
                $('#submitBtn').removeAttr('disabled');
            }
        } else if(leave_type == 2) {
            if(no_of_days > remaining_sick) {
                swalAlert('','Days is greater than your remaining sick leaves');
                $('#submitBtn').attr('disabled', true);
            } else {
                $('#submitBtn').removeAttr('disabled');
            }
        } else if(leave_type == 3) {
            if(no_of_days > remaining_casual) {
                swalAlert('','Days is greater than your remaining casual leaves');
                $('#submitBtn').attr('disabled', true);
            } else {
                $('#submitBtn').removeAttr('disabled');
            }
        } else if(leave_type == 5) {
            if(no_of_days > remaining_cpl) {
                swalAlert('','Days is greater than your remaining CPL leaves');
                $('#submitBtn').attr('disabled', true);
            } else {
                $('#submitBtn').removeAttr('disabled');
            }
        }
    }

    function check_days()
    {
        var leave_application_id = '{{ Input::get('id') }}';
        var employee_id = '{{ $employee->id }}';
        var company_id = '{{ $m }}';
        var leave_type = $("input[id='leave_type']:checked").val();
        var leave_day_type = $("input[id='leave_day_type']:checked").val();
        var leave_policy_id = '{{ $leaves_policy[0]->leaves_policy_id }}';
        var leave_address = $("#leave_address").val();
        var reason = $("#reason").val();
        var Otherreason = '';
        if (reason == 6) {
            reason = $("#reason option:selected").text();
            Otherreason = $("#Otherreason").val();
        } else {
            reason = $("#reason option:selected").text();
        }

        jqueryValidationCustom();
        if (validate == 0) {
            if (leave_day_type == 'full_day_leave') {
                var from_date = $('#showDetailModelTwoParamerter #from_date').val();
                var to_date = $("#showDetailModelTwoParamerter #to_date").val();
                var full_day_deduction_rate = '{{ $leaves_policy[0]->fullday_deduction_rate }}';
                var no_of_days = ($("#no_of_days").val() * full_day_deduction_rate);

                var data = {
                    leave_application_id: leave_application_id,
                    employee_id: employee_id,
                    leave_policy_id: leave_policy_id,
                    company_id: company_id,
                    full_day_deduction_rate: full_day_deduction_rate,
                    leave_type: leave_type,
                    leave_day_type: 1,
                    no_of_days: no_of_days,
                    from_date: from_date,
                    to_date: to_date,
                    reason: reason,
                    leave_address: leave_address
                };
            } else if (leave_day_type == 'half_day_leave') {

                var half_day_deduction_rate = '{{ $leaves_policy[0]->halfday_deduction_rate }}';
                var no_of_days = (1 * half_day_deduction_rate);
                var first_second_half = $("input[id='first_second_half']:checked").val();
                var first_second_half_date = $("#first_second_half_date").val();
                var data = {
                    leave_application_id: leave_application_id,
                    leave_type: leave_type,
                    company_id: company_id,
                    employee_id: employee_id,
                    leave_policy_id: leave_policy_id,
                    leave_day_type: 2,
                    no_of_days: no_of_days,
                    first_second_half: first_second_half,
                    leave_address: leave_address,
                    reason: reason,
                    first_second_half_date: first_second_half_date
                };
            } else {
                swalAlert('Error', 'Select Full/Half/Short Leave Type !');
                return false;
            }

            $.ajax({
                url: '{{ url('/') }}/hadbac/addLeaveApplicationDetail',
                type: "GET",
                data: data,
                success: function(data) {
                    if (data == 1) {
                        swalUpdate();
                        $('#showDetailModelTwoParamerter').modal('hide');
                        viewLeaveApplicationClientForm();
                    } else {
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

        } else {
            swalAlert('Alert','Please fill all required fields !')
        }
    }

</script>