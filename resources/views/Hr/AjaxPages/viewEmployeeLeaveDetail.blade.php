<?php
$array[1] = '<span class="label label-warning">Pending</span>';
$array[2] = '<span class="label label-success">Approved</span>';
$array[3] = '<span class="label label-danger">Rejected</span>';

?>
<div class="row" style="background-color: gainsboro">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h4>Full Day Leave :
            <input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="full_day_leave" onclick="leaves_day_type(this.value)" />
        </h4>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h4>Half Day Leave :
            <input type="radio" name="leave_day_type" id="leave_day_type" value="half_day_leave" onclick="leaves_day_type(this.value)" />
        </h4>
    </div>
</div>

<div class="row">&nbsp;</div>

<script>
    var fromDate;
    var date1;
    var toDate;
    var date2;

    function calculateNumberOfDates(value,flag) {

		var remaining_annual = '{{ $leave_balances['remainingAnnualLeaves'] }}';
		var remaining_sick = '{{ $leave_balances['remainingSickLeaves'] }}';
		var remaining_casual = '{{ $leave_balances['remainingCasualLeaves'] }}';
		var remaining_cpl = '{{ $leave_balances['remainingCplLeaves'] }}';
		var leave_type = '{{ Input::get('leaveType') }}';
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

// 	specialLeaveLimit();

// 	function specialLeaveLimit() {
		
// 		var fromDate = $('#from_date').val();
// 		var date = new Date(fromDate);
// 		date.setDate(date.getDate() + 30);
// 		var dateString = date.toISOString().split('T')[0]; 
	
		
// 		var datepickerObject = document.getElementById("to_date").ej2_instances[0]; 
//             //Clear date 
//             datepickerObject.value = null; 

// 		// $("#to_date").val('');
// 		$("#to_date").attr("min", fromDate);
// 		$("#to_date").attr("max", dateString);
// 	}

// 	function specialLeaveCalculate() {

// 		var fromDate = $('#from_date').val();
// 		var toDate = $("#to_date").val();

// 		var date1 = new Date(fromDate);
// 		var date2 = new Date(toDate);

// 		var Difference_In_Time = date2.getTime() - date1.getTime();
//         var no_of_days = Difference_In_Time / (1000 * 3600 * 24);
// 		$("#no_of_days").val(no_of_days).css.visibility = 'visible';

		
// 	}

	
</script>
