<?php
use App\Helpers\DashboardHelper;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;

$birthday_anniversary_data = DashboardHelper::dashboardHomeData();
?>
@if(Auth::user()->acc_type != 'user')
    <?php $notification = DashboardHelper::viewExpiryAndUpcomingAlerts(); ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell" title="Notifications & Requests"></i>
            @if($notification['total_count'] > 0)
                <div class="badge-message">
                    <div class="message-count">{{ $notification['total_count'] }}</div>
                </div>
            @endif
        </a>

        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY div-overflow" style="width: 30rem !important;">
            <h4 style="padding: 10px;"><strong>Notifications</strong></h4>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" target="_blank" tabindex="-1" href="{{ url('/hr/viewEmployeeProbationExpireDetail?m=12') }}">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count">{{ count($notification['employeesProbationExpires']) }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Employees Probation Period Over</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" target="_blank" tabindex="-1" href="{{ url('/hr/viewEmployeeList?m=12') }}">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count">{{ $notification['inactive_employees'] }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">InActive Employees</span>
                </span>
            </a>

            <!--<div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" tabindex="-1" href="#">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count">{{ $notification['absents_detail'] }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Absent Employees</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" tabindex="-1" href="#">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count">{{ count($notification['lates_count']) }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Late Check In</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" tabindex="-1" href="#">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count">{{ count($notification['half_days_count']) }}</span>&nbsp;&nbsp;&nbsp;
                   <span class="text-dark">Half Day Check In</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" tabindex="-1" href="#">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count">{{ $notification['attendance_queries'] }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Attendance Requests</span>
                </span>
            </a>-->


            <div class="dropdown-divider"></div>
            <h4 style="padding: 10px;"><strong>Pending Requests</strong></h4>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" target="_blank" tabindex="-1" href="{{ url('/hr/viewLeaveApplicationRequestList?m=12') }}">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count2">{{ $notification['leave_application'] }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Leave Requests</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" target="_blank"  tabindex="-1" href="{{ url('/hr/viewLoanRequestList?m=12') }}">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count2">{{ $notification['loan_request'] }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Loan Requests</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" target="_blank"  tabindex="-1" href="{{ url('/hr/viewAdvanceSalaryList?m=12') }}">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count2">{{ $notification['advance_salary'] }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Advance Requests</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item drop-down2" target="_blank"  tabindex="-1" href="{{ url('/hr/viewEmployeePromotionsList?m=12') }}">
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="notification-count2">{{ $notification['employee_promotion'] }}</span>&nbsp;&nbsp;&nbsp;
                    <span class="text-dark">Increment Requests</span>
                </span>
            </a>
        </div>
    </li>
@endif

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-birthday-cake" aria-hidden="true" title="Birthdays"></i>
        @if(count($birthday_anniversary_data['upcoming_birthdays_detail']) > 0)
            <div class="badge-message">
                <div class="message-count">{{ count($birthday_anniversary_data['upcoming_birthdays_detail']) }}</div>
            </div>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY div-overflow">
        <h4 style="padding: 10px;"><strong>Birthdays</strong></h4>
        <div class="dropdown-divider"></div>
        @foreach($birthday_anniversary_data['upcoming_birthdays_detail'] as $key => $val)
       
            <a class="dropdown-item" href="#" style="padding: 2px !important;">
                <img id="emp_image" src="{{ asset($val["img_path"]!=''?'storage/'.$val["img_path"]:'storage/app/uploads/employee_images/user-dummy.png') }}" alt="user" class="cursor:pointercursor:pointercursor:pointercursor:pointercursor:pointer"
                     style="margin-top: -30px !important; width: 18%; height: 55px; border-radius: 50%"/>
                    <span class="ml-2 d-none d-lg-inline-block">
                        <span class="text-dark">{{ $val["emp_name"].' - '.$val["emp_id"] }}</span><br>
                        <span style="font-size: 13px;"> Date: {{ HrHelper::date_format($val["date_of_birth"]).' | '.($val["birth_years"] + 1) .' Years' }}</span>
                    </span>
            </a>
            <div class="dropdown-divider"></div>
        @endforeach
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-gift" aria-hidden="true" title="Work Anniversary"></i>
        @if(count($birthday_anniversary_data['work_anniversary']) > 0)
            <div class="badge-message">
                <div class="message-count">{{ count($birthday_anniversary_data['work_anniversary']) }}</div>
            </div>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY div-overflow">
        <h4 style="padding: 10px;"><strong>Work Anniversary</strong></h4>
        <div class="dropdown-divider"></div>
        @foreach($birthday_anniversary_data['work_anniversary'] as $key => $val)
            <a class="dropdown-item" href="#" style="padding: 2px !important;">
                <img id="emp_image" src="{{ asset($val["img_path"]!=''?'storage/'.$val["img_path"]:'storage/app/uploads/employee_images/user-dummy.png') }}" alt="user" class="cursor:pointercursor:pointercursor:pointercursor:pointercursor:pointer"
                     style="margin-top: -30px !important; width: 18%; height: 55px; border-radius: 50%"/>
                <span class="ml-2 d-none d-lg-inline-block">
                    <span class="text-dark">{{ $val["emp_name"].' - '.$val["emp_id"] }}</span><br>
                    <span style="font-size: 13px;"> Date: {{ HrHelper::date_format($val["joining_date"]).' | '.($val["years_of_work"] + 1) .' Years' }}</span>
                </span>
            </a>
            <div class="dropdown-divider"></div>
        @endforeach
    </div>
</li>