<?php
$counter = 1;
?>
<style>
    input[type="radio"],
    input[type="checkbox"] {
        width: 30px;
        height: 20px;
    }
</style>
<script src="{{ URL::asset('assets/custom/js/leaveApplications.js') }}"></script>

@if( count($leaves_policy) <= 0 )
    <div class="tab-pane fade in" id="Leaves">
        <span style="color:red"><b>leave policy is not assigned please contact to hr department </b></span>
    </div>
@else

    @if($WithoutLeavePolicy[0] == 'Select Leave Policy')
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    </button>
                    <span class="glyphicon glyphicon-record"></span> <strong>Warning Message</strong>
                    <hr class="message-inner-separator">
                    <p>Please Select Leave Policy.</p>
                </div>
            </div>
        </div>
    @else

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}">
        <input type="hidden" name="emp_id" id="emp_id" value="{{ $emp_data->emp_id }}">
        <input type="hidden" name="employee_id" id="employee_id" value="{{ $emp_data->id }}">

        @include('Hr.LeaveApplication.viewLeaveBalanceTable')

        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-sm btn-success">Submit</button>
            </div>
        </div>
    @endif
    {{--<div class="tab-pane fade in" id="Leaves"></div>--}}

    <script>
        function check_days() {
            var employee_id = $("#employee_id").val();
            var company_id = $("#company_id").val();
            var leave_type = $("input[id='leave_type']:checked").val();
            var leave_day_type = $("input[id='leave_day_type']:checked").val();
            var leave_policy_id = '{{ (count($leaves_policy) > 0 ) ? $leaves_policy[0]->leaves_policy_id: 0 }}';
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
                    var full_day_deduction_rate = '{{ (count($leaves_policy) > 0 ) ? $leaves_policy[0]->fullday_deduction_rate: 0 }}';
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

                    var half_day_deduction_rate = '{{ (count($leaves_policy) > 0 ) ? $leaves_policy[0]->halfday_deduction_rate:0 }}';
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

            } else {
                swalAlert('Alert','Please Select Leaves Type !')
            }
        }
    </script>
@endif
