<a >
<div onclick="showDetailModelTwoParamerter('hdc/viewUpcomingBirthdaysDetail','Upcoming Birthdays') " class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i>
	Upcoming Birthdays Alerts &nbsp;(<?=$upcoming_birthday_count[0]->upcoming_birthday_count?>)
	<i class="fa fa-bell fa-fw" style="color:red"></i>
</div>
</a>

<a >
	<div onclick="showDetailModelTwoParamerter('hdc/viewPermanentEmployee','Permanent Employees') " class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">
		<i class="fa fa-tasks fa-fw" style="color:red;"></i>
		Permanent Employee Alert &nbsp;(<?=count($permanent_employee)?>)
		<i class="fa fa-bell fa-fw" style="color:red"></i>
	</div>
</a>

	<a >
<div onclick="showDetailModelTwoParamerter('hdc/viewAbsentsDetail','Absentees Alert') "  class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i>
	Absentees Alert &nbsp;(<?=$absents_detail?>)
	<i class="fa fa-bell fa-fw" style="color:red"></i>
</div>

	</a>


	
		<a >
<div onclick="showDetailModelTwoParamerter('hdc/viewEmployeeAttendanceQueriesDetail','Attendance Request Alert') "  class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i>
	Attendance Request Alert &nbsp;(<?=$attendance_queries?>)
	<i class="fa fa-bell fa-fw" style="color:red"></i>
</div>

	</a>


	<!--	<a >-->
	<!--<div onclick="showDetailModelTwoParamerter('hdc/viewEmployeeMissingImageDetail','Emp Missing Image') " class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">-->
	<!--	<i class="fa fa-tasks fa-fw" style="color:red;"></i>-->
	<!--	Emp Missing Image Alerts &nbsp;(<?=$employee_missing_images?>)-->
	<!--	<i class="fa fa-bell fa-fw" style="color:red"></i>-->
	<!--</div>-->
	<!--	</a>-->
		<a >
	<div onclick="showDetailModelTwoParamerter('hdc/viewHalfDays','Half Days Alert') " class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">
		<i class="fa fa-tasks fa-fw" style="color:red;"></i>
		Half Days Alerts &nbsp;(<?=$half_days_count?>)
		<i class="fa fa-bell fa-fw" style="color:red"></i>
	</div>
		</a>
		<a >
	<div onclick="showDetailModelTwoParamerter('hdc/viewHalfDays','Lates Alert') " class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">
		<i class="fa fa-tasks fa-fw" style="color:red;"></i>
		Lates Alerts &nbsp;(<?=$lates_count?>)
		<i class="fa fa-bell fa-fw" style="color:red"></i>
	</div>
		</a>
	




<a >
	<div id="emp_probation_expires_alert"  onclick="showDetailModelTwoParamerter('hdc/viewEmployeeProbationExpireDetail','Emp Probation/Intern Period Expire Detail') " class="modal_clickable alert alert-light bg-light text-dark border-0" role="alert">
		<i class="fa fa-tasks fa-fw" style="color:red;"></i>
		Emp Probation Intern Exp Alert&nbsp;(<?=$employeesProbationExpires?>)
		<i class="fa fa-bell fa-fw" style="color:red"></i>
	</div>
</a>


@if($employeesProbationExpires > 0)
	<script>
        $.notify({
            icon: "fa fa-exclamation-triangle",
            message: "<b onclick='test()' style='cursor:pointer;'> {!! $employeesProbationExpires  !!} Employees have completed their probationary period, Click on this or Check emp probation expire alert in Notifications Panel</b>."
        }, {
            type: 'warning',
            timer: 30000

        });


        function test(){
            $('#emp_probation_expires_alert').trigger('click');
		}

	</script>
@endif