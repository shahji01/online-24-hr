<?php
use App\Models\Employee;
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\TransferedLeaves;

use Carbon\Carbon;

use App\Models\Policies;


if(Input::get('filter') != 'user'):
    $emp_id = Auth::user()->emp_id;
    $employee_id = Auth::user()->employee_id;
else:
    $emp_id = Input::get('emp_id');
    $employee_id = Input::get('employee_id');
endif;

$m = Input::get('m');
?>
@if($m == '')

@else

    <div class="row">
        <div class="col-sm-12" id="userinfo">
            <div class="text-center">
                @if($emp_data->img_path != '')
                    <img src="{{ url('storage/'.$emp_data->img_path) }}" class="avatar img-circle img-thumbnail img-responsive" style="width:20%; height: 210px" alt="avatar">
                @else
                    <img id="img_file_1" class="avatar img-circle img-thumbnail" style="width:20%;height:auto;" src="{{ url('storage/app/uploads/employee_images/user-dummy.png') }}">
                @endif
            </div>
        </div>
        <div class="col-sm-12">&nbsp;
            <div class="text-center">
                <h3 id="username"><strong>{{ $emp_data->emp_name }}</strong></h3>
                <pre>{{ $emp_data->department_name }} &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; {{ $emp_data->new_designation }}</pre>
                <pre>{{ $emp_data->official_email }}</pre>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-nav-tabs card-plain">
                <div class="card-header card-header-danger" style="padding-left: 7%;">
                    <div class="nav-tabs-navigation">
                        <div class="nav-tabs-wrapper">
                            <ul class="nav nav-tabs" data-tabs="tabs" style="display: flex;
                            flex-direction: row;
                            justify-content: center;">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#info" id="info" onclick="viewDashboardComponent('info','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab">Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#leaves" onclick="viewDashboardComponent('leaves','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab">Apply Leave</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#myLeaves" onclick="viewDashboardComponent('myLeaves','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab"> My Leaves</a>
                                </li>
                                @if($team_lead_exists->exists())
                                    <li class="nav-item">
                                        <a class="nav-link" href="#teamLeaves" onclick="viewDashboardComponent('teamLeaves','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab">Team Leaves</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" href="#education" onclick="viewDashboardComponent('education','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab">Education</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#jobHistory" onclick="viewDashboardComponent('jobHistory','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab">History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#policies" onclick="viewDashboardComponent('policies','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab">Policies</a>
                                </li>
                                @if($team_lead_exists->exists())
                                    <li class="nav-item">
                                        <a class="nav-link" href="#team" onclick="viewDashboardComponent('team','{{ $employee_id }}','{{ $emp_id }}')" data-toggle="tab">Team</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="viewDashboardComponentData" style="padding: 10px;"></div>
                {{--<div class="tab-content container-fluid" >--}}

                    {{--@include('Dashboard.dashboardComponent.basicInfo')--}}
                    {{--@include('Dashboard.dashboardComponent.leaves')--}}
                    {{--@include('Dashboard.dashboardComponent.myLeaves')--}}
                    {{--@include('Dashboard.dashboardComponent.teamLeaves')--}}
                    {{--@include('Dashboard.dashboardComponent.education')--}}
                    {{--@include('Dashboard.dashboardComponent.jobHistory')--}}
                    {{--@include('Dashboard.dashboardComponent.policies')--}}
                    {{--@include('Dashboard.dashboardComponent.teams')--}}

                {{--</div>--}}
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout($('#info').click(), 200)
        });

        function viewDashboardComponent(id, employee_id, emp_id) {
            $.ajax({
                url: '{{ url('/') }}/ddc/viewDashboardComponent',
                type: "GET",
                data: {id: id, employee_id: employee_id, emp_id: emp_id, m: m},
                success: function(data) {
                    $('#viewDashboardComponentData').html(data);
                },
                error: function(error) {
                    swalError();
                }
            });
        }

        $('#btn_check').on('click', function() {
            var $this = $(this);
            $this.button('loading');
            setTimeout(function() {
                $this.button('reset');
            }, 3000);
        });

        $("#salaryPrivacy").click(function(){
            $("#pass_eye").removeClass("hidden");
            $(".checkSubmit").removeClass('hidden');
        });

        $(document).ready(function(){
            $('#add_edu span').html(" Add Education");
            $('#degree_type_1').select2();
            $(".salary_hidden").html('******');
            $("#institute_name_1").removeClass('requiredField');
            $("#year_of_admission_1").removeClass('requiredField');
            $("#year_of_passing_1").removeClass('requiredField');
            $("#degree_type_1").removeClass('requiredField');
            $("#document_1").removeClass('requiredField');
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

        $(function(){
            $('select[name="department_id"]').on('change', function() {
                $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                var department_id = $(this).val();
                if($(this).val() == '') { $('#emp_loader').html('');}
                var m = '<?= Input::get('m'); ?>';
                if(department_id) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/MachineEmployeeListDeptWise',
                        type: "GET",
                        data: { department_id:department_id,m:m},
                        success:function(data) {
                            $('#emp_loader').html('');
                            $('select[name="employee_id"]').empty();
                            $('select[name="employee_id"]').html(data);
                            // $('select[name="employee_id"]').find('option').get(0).remove();
                        }
                    });
                }else{
                    $('select[name="employee_id"]').empty();
                }
            });
        });

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

    </script>
@endif