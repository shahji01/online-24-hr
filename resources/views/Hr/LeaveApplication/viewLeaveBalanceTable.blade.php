<?php
$counter = 1;
?>
<style>
    /* Hide the radio button */
    .custom-radio {
        display: none;
    }

    /* Style the label to look like a button */
    .custom-radio + label {
        display: inline-block;
        padding: 8px 16px;
        border: 1px solid #999;
        border-radius: 4px;
        background-color: #f0f0f0;
        color: #333;
        cursor: pointer;
    }

    /* Style the label when the radio button is checked */
    .custom-radio:checked + label {
        background-color: #999;
        color: #fff;
    }
</style>
<div class="row">&nbsp;</div>
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
@if($leaves['flag'] == 1)
    <?php
    $id = '';
    $leave_type_value = '';
    $leave_day_type_value = '';
    $half_day_type_value = '';
    $from_date = '';
    $to_date = '';
    $first_second_half_date = '';
    $reason = '';
    $leave_address = '--';
    if(isset($leaveApplicationData)) {
        $id = $leaveApplicationData->id;
        $leave_type_value = $leaveApplicationData->leave_type;
        $leave_day_type_value = $leaveApplicationData->leave_day_type;
        $half_day_type_value = $leaveApplicationData->first_second_half;
        $from_date = $leaveApplicationData->from_date;
        $to_date = $leaveApplicationData->to_date;
        $first_second_half_date = $leaveApplicationData->first_second_half_date;
        $reason = $leaveApplicationData->reason;
        $leave_address = $leaveApplicationData->leave_address;
    }
    ?>

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
                        <label style="border:1px solid #fff;" class="btn btn-success" onclick="viewEmployeeLeavesDetail('{{ $val->id }}','{{ $val->no_of_leaves }}','{{ $val->leave_type_id }}')">
                            <input @if($leave_type_value == $val->leave_type_id) checked @endif required="required" autocomplete="off" type="radio" name="leave_type" id="leave_type" class="requiredField custom-radio" value="{{ $val->leave_type_id }}" />
                            {{ $leaveName }}
                            <span class="glyphicon glyphicon-ok"></span>
                        </label>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div id="leavesData"></div>
    <div id="leave_days_area"></div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label>Reason For Leave</label>
            <select class="form-control reasonsList requiredField" name="reason" id="reason" onchange="check_reason(this.value)">
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
    <script>
        function abc() {
            document.querySelectorAll('input[type="radio"]:checked').forEach(function(radioButton) {
                radioButton.click();
            });

            var leave_day_type = '{{ $leave_day_type_value }}';
            var check_day_type = (leave_day_type == 1) ? 'full_day_leave' : 'half_day_leave';

            // Clicking the appropriate radio button based on leave_day_type value
            document.querySelectorAll('#leave_day_type').forEach(function(dayTypeRadioButton) {
                if (dayTypeRadioButton.value == check_day_type) {
                    dayTypeRadioButton.click();

                    if (leave_day_type == 2) {
                        var half_day_type = '{{ $half_day_type_value }}';

                        // Checking the appropriate half_day_type radio button
                        document.querySelectorAll('#first_second_half').forEach(function(halfDayTypeRadioButton) {
                            if (halfDayTypeRadioButton.value == half_day_type) {
                                halfDayTypeRadioButton.checked = true;
                            }
                        });
                    }
                }
            });

            $('#id').val('{{ $id }}');
            $('#showDetailModelTwoParamerter #from_date').val('{{ $from_date }}').trigger('change');
            $('#showDetailModelTwoParamerter #to_date').val('{{ $to_date }}').trigger('change');
            $('#first_second_half_date').val('{{ $first_second_half_date }}').trigger('change');
            $('#reason option').filter(function() {
                return $(this).text() === '{{ $reason }}';
            }).prop('selected', true);
            $('#leave_address').val('{{ $leave_address }}');
        }


        $(document).ready(function() {
            // Checking radio buttons that are already checked
            setTimeout(abc, 300);
        });

    </script>

@endif