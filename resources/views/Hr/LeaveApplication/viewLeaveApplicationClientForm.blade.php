<?php
$m = Input::get('m');
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

                @include('Hr.LeaveApplication.viewLeaveBalanceTable')

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-sm btn-success">Submit</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#reason').select2();

    var csrf_token = '<%= csrf_token %>';
    $("body").bind("ajaxSend", function(elm, xhr, s) {
        if (s.type == "POST") {
            xhr.setRequestHeader('X-CSRF-Token', csrf_token);
        }
    });

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

    function check_days() {
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

                var from_date = $('#from_date').val();
                var to_date = $("#to_date").val();
                var full_day_deduction_rate = '{{ $leaves_policy[0]->fullday_deduction_rate }}';
                var no_of_days = ($("#no_of_days").val() * full_day_deduction_rate);

                var data = {
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
                        swalAdd();
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

        } else {
            swalAlert('Alert','Please fill all required fields !')
        }
    }
</script>
