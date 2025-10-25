<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\EmployeePromotion;

$m = Input::get('m');
$location_name = [];

if ($employee_detail->promoted_designation != ''):
    $designation_id = $employee_detail->promoted_designation;
else:
    $designation_id = $employee_detail->designation_id;
endif;
if ($employee_detail->promoted_salary != ''):
    $salary = $employee_detail->promoted_salary;
else:
    $salary = $employee_detail->salary;
endif;

?>
@extends('layouts.default')
@section('css')
    <style>
        input[type="radio"],
        input[type="checkbox"] {
            width: 30px;
            height: 20px;
        }

        hr {
            border-top: 1px solid cadetblue
        }
    </style>
@endsection
@section('content')

    <div class="page-wrapper">
        <div class="row" id="accordion">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title">Employee Detail</h4>
                            </div>
                            <div class="col-sm-8 text-right">
                                @if (in_array('print', $operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList', '', '1') }}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="hr-border" style=""></div>
                                    <img id="img_file_1" class="avatar img-circle img-thumbnail" style="height:210px;width:20%;"
                                        src="{{ url($employee_detail->img_path != '' ? 'storage/' . $employee_detail->img_path : 'storage/app/uploads/employee_images/user-dummy.png') }} ">
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <h4 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne" style="font-weight: bold;margin-top: 10px;">Basic
                                            Information</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse show" id="collapseOne" 
                                    aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="row">
                                        <table class="table table-sm mb-0 table-bordered">
                                            <thead>
                                                <th>Emp ID </th>
                                                <td class="text-center">{{ $employee_detail->emp_id }}></td>
                                                <th>Attendance ID</th>
                                                <td class="text-center">{{ $employee_detail->attendance_id }}</td>
                                                <th>Employee Name</th>
                                                <td class="text-center">{{ $employee_detail->emp_name }}</td>
                                            </thead>

                                            <thead>
                                                <th>Father/Husband Name</th>
                                                <td class="text-center">{{ $employee_detail->emp_father_name }}</td>
                                                <th>Department</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->department_id, $departments))
                                                        {{ $departments[$employee_detail->department_id]->department_name }}
                                                    @endif
                                                </td>
                                                <th>Sub Department</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->sub_department_id, $sub_departments))
                                                        {{ $sub_departments[$employee_detail->sub_department_id]->sub_department_name }}
                                                    @endif
                                                </td>
                                            </thead>

                                            <thead>
                                                <th>Designation</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($designation_id, $designations))
                                                        {{ $designations[$designation_id]->designation_name }} @endif
                                                </td>
                                                <th>Category</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->category_id, $category))
                                                        {{ $category[$employee_detail->category_id]->employee_category_name }}
                                                    @endif
                                                </td>
                                                <th>Project</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_location->value('project_id'), $projects))
                                                        {{ $projects[$employee_location->value('project_id')]->project_name }}
                                                    @endif
                                                </td>
                                            </thead>

                                            <thead>
                                                <th>Location</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_location['location_id'], $locations))
                                                        {{ $locations[$employee_location['location_id']]->location_name }}
                                                    @endif
                                                </td>
                                                <th>Grade</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->grade_id, $grade_type))
                                                        {{ $grade_type[$employee_detail->grade_id]->employee_grade_type }}
                                                    @endif
                                                </td>
                                                <th>Marital Status</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->marital_status_id, $marital_status))
                                                        {{ $marital_status[$employee_detail->marital_status_id]->marital_status_name }}
                                                    @endif
                                                </td>
                                            </thead>

                                            <thead>
                                                <th>Employment Status</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->employment_status_id, $job_type))
                                                        {{ $job_type[$employee_detail->employment_status_id]->job_type_name }}
                                                    @endif
                                                </td>
                                                <th>Gender</th>
                                                <td class="text-center">
                                                    @if ($employee_detail->gender_id == 1)
                                                        {{ 'Male' }} @endif
                                                    @if ($employee_detail->gender_id == 2)
                                                        {{ 'Female' }} @endif
                                                </td>
                                                <th> Nationality </th>
                                                <td class="text-center">{{ $employee_detail->nationality }}</td>
                                            </thead>

                                            <thead>
                                                <th> Religion </th>
                                                <td class="text-center">{{ $employee_detail->religion }}</td>
                                                <th>Birth Date</th>
                                                <td class="text-center">
                                                    {{ HrHelper::date_format($employee_detail->date_of_birth) }}</td>
                                                <th>Join Date</th>
                                                <td class="text-center">
                                                    {{ HrHelper::date_format($employee_detail->joining_date) }}</td>
                                            </thead>

                                            <thead>
                                                <th>Confirmation Date</th>
                                                <td class="text-center">
                                                    {{ HrHelper::date_format($employee_detail->confirmation_date) }}</td>
                                                <th>Resign Date</th>
                                                <td class="text-center">
                                                    {{ HrHelper::date_format($employee_detail->resign_date) }}</td>
                                                <th>Last Working Date</th>
                                                <td class="text-center">
                                                    {{ HrHelper::date_format($employee_detail->last_working_date) }}</td>
                                            </thead>

                                            <thead>
                                                <th>CNIC</th>
                                                <td class="text-center">{{ $employee_detail->cnic }}</td>
                                                <th>CNIC Expiry Date</th>
                                                <td class="text-center">
                                                    @if ($employee_detail->life_time_cnic == 1)
                                                        Life Time Validity
                                                    @else
                                                        {{ HrHelper::date_format($employee_detail->cnic_expiry_date) }}
                                                    @endif
                                                </td>
                                                <th>Reporting Manager</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->reporting_manager_id, $employee))
                                                        {{ $employee[$employee_detail->reporting_manager_id]->emp_name }}
                                                    @endif
                                                </td>
                                            </thead>

                                            <thead>

                                                <th>Contact Number</th>
                                                <td class="text-center">{{ $employee_detail->contact_no }}</td>
                                                <th>Emergency Number</th>
                                                <td class="text-center">{{ $employee_detail->emergency_number }}</td>
                                                <th>Emergency Person Name</th>
                                                <td class="text-center">{{ $employee_detail->emergency_contact_person }}
                                                </td>
                                            </thead>

                                            <thead>
                                                <th>Office Number</th>
                                                <td class="text-center">{{ $employee_detail->office_no }}</td>
                                                <th>Personal Email</th>
                                                <td class="text-center">{{ $employee_detail->personal_email }}</td>
                                                <th>Official Email</th>
                                                <td class="text-center">{{ $employee_detail->official_email }}</td>
                                            </thead>

                                            <thead>
                                                <th>Salary</th>
                                                <td class="text-center">
                                                    <!-- Button to trigger the password modal -->
                                                    <button class="btn btn-warning btn-sm" onclick="showSalaryModal('{{ $employee_detail->id }}')">View Salary</button>

                                                    <!-- Modal for password input -->
                                                    <div id="salaryModal_{{ $employee_detail->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="salaryModalLabel_{{ $employee_detail->id }}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="salaryModalLabel_{{ $employee_detail->id }}">Enter Password to View Salary</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Password input field -->
                                                                    <input type="password" id="salaryPassword_{{ $employee_detail->id }}" class="form-control" placeholder="Enter Password">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary" onclick="verifySalaryPassword('{{ $employee_detail->id }}')">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Hidden salary field that will be revealed if password is correct -->
                                                    <span id="salary_{{ $employee_detail->id }}" style="display: none;">
                                                        {{ number_format($salary) }}
                                                    </span>
                                                </td>
                                                <th>Eobi</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->eobi_id, $eobi))
                                                        {{ $eobi[$employee_detail->eobi_id]->eobi_name }} @endif
                                                </td>
                                                <th>Eobi Number</th>
                                                <td class="text-center">{{ $employee_detail->eobi_number }}</td>
                                            </thead>

                                            <thead>
                                                <th>Leave Policy</th>
                                                <td class="text-center">
                                                    @if (array_key_exists($employee_detail->leaves_policy_id, $leaves_policy))
                                                        {{ $leaves_policy[$employee_detail->leaves_policy_id]->leaves_policy_name }}
                                                    @endif
                                                </td>
                                                <th></th>
                                                <td></td>
                                                <th></th>
                                                <td></td>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true"
                                        aria-controls="collapsetwo" style="font-weight: bold;margin-top: 10px;">Address</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" >
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse"  id="collapsetwo" class="" aria-labelledby="headingOne" data-parent="#accordion">
                                        <table class="table table-sm mb-0 table-bordered">
                                            <thead>
                                                <th> Current Address </th>
                                                <td class="text-center">{{ $employee_detail->current_address }}</td>
                                            </thead>
                                            <thead>
                                                <th> Permanent Address </th>
                                                <td class="text-center">{{ $employee_detail->permanent_address }}</td>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 data-toggle="collapse" data-target="#collapse3" aria-expanded="true"
                                        aria-controls="collapse3" style="font-weight: bold;margin-top: 10px;">Bank Account Details</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="bank_account_area">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse"  id="collapse3" class="" aria-labelledby="headingOne" data-parent="#accordion">
                                        <table class="table table-sm mb-0 table-bordered">
                                            <thead>
                                                <th class="text-center">Bank Name</th>
                                                <th class="text-center">Account Title</th>
                                                <th class="text-center">Account No</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $employee_bank_detail->value('bank_name') }}</td>
                                                    <td>{{ $employee_bank_detail->value('account_title') }}</td>
                                                    <td class="text-center">
                                                        {{ $employee_bank_detail->value('account_no') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row main" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 data-toggle="collapse" data-target="#family_data_area" aria-expanded="true"
                                        aria-controls="family_data_area"  style="font-weight: bold;margin-top: 10px;">Immediate Family Data</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">
                                        <h4 style="text-decoration: underline;">
                                            Spouse, Children, Parents, Brothers, Sisters
                                        </h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion" id="family_data_area" >
                                    @if ($employee_family_detail->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Relation</th>
                                                    <th class="text-center">Add Emergency Contact</th>
                                                </thead>
                                                <tbody>
                                                    <?php $counter = 1; ?>
                                                    @foreach ($employee_family_detail->get() as $employeeFamilyData)
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td>{{ $employeeFamilyData->family_name }}</td>
                                                            <td>{{ $employeeFamilyData->family_relation }} </td>
                                                            <td class="text-center">
                                                                {{ $employeeFamilyData->family_emergency_contact }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" data-toggle="collapse" data-target="#education_area" aria-expanded="true"
                                aria-controls="education_area"  style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Educational / Technical Background
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Start from Recent</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion" id="education_area">
                                    @if ($employee_educational_detail->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Name Of Institution</th>
                                                    <th class="text-center">From</th>
                                                    <th class="text-center">To</th>
                                                    <th class="text-center">Degree / Diploma</th>
                                                </thead>
                                                <tbody>
                                                    <?php $counter = 1; ?>
                                                    @foreach ($employee_educational_detail->get() as $employee_educational_value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td>{{ $employee_educational_value->institute_name }}</td>
                                                            <td class="text-center">
                                                                {{ $employee_educational_value->year_of_admission }}</td>
                                                            <td class="text-center">
                                                                {{ $employee_educational_value->year_of_passing }}</td>
                                                            <td>
                                                                @foreach ($degree_type as $DegreeTypeValue)
                                                                    @if ($employee_educational_value->degree_type == $DegreeTypeValue->id)
                                                                        {{ $DegreeTypeValue->degree_type_name }}
                                                                    @else
                                                                        Other
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row main" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 data-toggle="collapse" data-target="#language_area" aria-expanded="true"
                                        aria-controls="language_area"  style="font-weight: bold;margin-top: 10px;">Language Proficiency</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion"  id="language_area">
                                    @if ($employee_language_proficiency->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Language</th>
                                                        <th class="text-center">Read</th>
                                                        <th class="text-center">Write</th>
                                                        <th class="text-center">Speak</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $counter2 = 1; ?>
                                                    @foreach ($employee_language_proficiency->get() as $language_proficiency_detail)
                                                        <tr>
                                                            <td class="text-center">{{ $counter2++ }}</td>
                                                            <td>{{ $language_proficiency_detail->language_name }}</td>
                                                            <td class="text-center">
                                                                <b>Good : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Good') checked @endif
                                                                        type="radio"
                                                                        name="reading_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Good"></b>
                                                                <b>Fair : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Fair') checked @endif
                                                                        type="radio"
                                                                        name="reading_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Fair"></b>
                                                                <b>Poor : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Poor') checked @endif
                                                                        type="radio"
                                                                        name="reading_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Poor"></b>
                                                            </td>
                                                            <td class="text-center">
                                                                <b>Good : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Good') checked @endif
                                                                        type="radio"
                                                                        name="writing_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Good"></b>
                                                                <b>Fair : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Fair') checked @endif
                                                                        type="radio"
                                                                        name="writing_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Fair"></b>
                                                                <b>Poor : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Poor') checked @endif
                                                                        type="radio"
                                                                        name="writing_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Poor"></b>
                                                            </td>
                                                            <td class="text-center">
                                                                <b>Good : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Good') checked @endif
                                                                        type="radio"
                                                                        name="speaking_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Good"></b>
                                                                <b>Fair : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Fair') checked @endif
                                                                        type="radio"
                                                                        name="speaking_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Fair"></b>
                                                                <b>Poor : <input
                                                                        @if ($language_proficiency_detail->reading_skills == 'Poor') checked @endif
                                                                        type="radio"
                                                                        name="speaking_skills_<?= $language_proficiency_detail->id ?>"
                                                                        value="Poor"></b>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" data-toggle="collapse" data-target="#health_type_area" aria-expanded="true"
                                aria-controls="health_type_area" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Health Information</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline"> Any disorder regarding</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion"  id="health_type_area">
                                    @if ($employee_health_data->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Health Type</th>
                                                        <th class="text-center">Yes/No</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $counter3 = 1; ?>
                                                    @foreach ($employee_health_data->get() as $employee_health_value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter3++ }}</td>
                                                            <td>{{ $employee_health_value->health_type }}</td>
                                                            <td>{{ $employee_health_value->health_check }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Any Physical Handicap</th>
                                                        <th class="text-center">Height</th>
                                                        <th class="text-center">Weight</th>
                                                        <th class="text-center">Blood Group</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $employee_health_value->physical_handicap }}</td>
                                                        <td class="text-center">{{ $employee_health_value->height }}</td>
                                                        <td class="text-center">{{ $employee_health_value->weight }}</td>
                                                        <td class="text-center">{{ $employee_health_value->blood_group }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row main"  data-toggle="collapse" data-target="#activity_area" aria-expanded="true"
                                aria-controls="activity_area" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Activities</h4>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <h4 style="text-decoration: underline;">
                                            Associations, societies, clubs you were / are member of
                                        </h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion"  id="activity_area">
                                    @if ($employee_activity_data->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-sm mb-0 table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Name Of Institution</th>
                                                        <th class="text-center">Position Held</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $counter3 = 1; ?>
                                                    @foreach ($employee_activity_data->get() as $employee_activity_detail)
                                                        <tr>
                                                            <td class="text-center">{{ $counter3++ }}</td>
                                                            <td>{{ $employee_activity_detail->institution_name }}</td>
                                                            <td class="text-center">
                                                                {{ $employee_activity_detail->position_held }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row main" data-toggle="collapse" data-target="#work_experience_area" aria-expanded="true"
                                aria-controls="work_experience_area"  style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Work Experience</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Most recent first</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion" id="work_experience_area">
                                    @if ($employee_work_experience->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-sm mb-0 table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Organization Name</th>
                                                        <th class="text-center">From</th>
                                                        <th class="text-center">Till</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $counter4 = 1; ?>
                                                    @foreach ($employee_work_experience->get() as $employee_work_experience_value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter4++ }}</td>
                                                            <td>{{ $employee_work_experience_value->employeer_name }}</td>
                                                            <td class="text-center">
                                                                {{ $employee_work_experience_value->started }}</td>
                                                            <td class="text-center">
                                                                {{ $employee_work_experience_value->ended }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <br><label class="sf-label">Have you ever been dismissed / suspended from
                                                service ?</label>
                                            <input @if ($employee_work_experience_value->suspend_check == 'no') checked @endif type="radio"
                                                name="suspend_check_1" id="suspend_check_1" value="no" />&nbsp; No
                                            <input @if ($employee_work_experience_value->suspend_check == 'yes') checked @endif type="radio"
                                                name="suspend_check_1" id="suspend_check_1" value="yes" />&nbsp; Yes
                                            &nbsp;'
                                        </div>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row main" data-toggle="collapse" data-target="#reference_area" aria-expanded="true"
                                aria-controls="reference_area"   style="background-color: #f5f3ff">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Reference</h4>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <h4 style="text-decoration: underline;">
                                            Professional/Business references only ,
                                            Please include reference from your past employment
                                        </h4>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion"  id="reference_area">
                                    @if ($employee_reference_data->count() > 0)
                                        <?php $counter = 1; ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-sm mb-0 table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Designation</th>
                                                        <th class="text-center">Organization</th>
                                                        <th class="text-center">Contact Number</th>
                                                        <th class="text-center">Relationship</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($employee_reference_data->get() as $employee_reference_value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td>{{ $employee_reference_value->reference_name }}</td>
                                                            <td>{{ $employee_reference_value->reference_designation }}</td>
                                                            <td>{{ $employee_reference_value->reference_organization }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $employee_reference_value->reference_contact }}</td>
                                                            <td>{{ $employee_reference_value->reference_relationship }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" data-toggle="collapse" data-target="#kins_area" aria-expanded="true"
                                aria-controls="kins_area" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Next Of Kin Details</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">In Case of Employee Death</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion"  id="kins_area">
                                    @if ($employee_kins_data->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-sm mb-0 table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Relation</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $counter5 = 1; ?>
                                                    @foreach ($employee_kins_data->get() as $employee_kins_value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter5++ }}</td>
                                                            <td>{{ $employee_kins_value->next_kin_name }}</td>
                                                            <td>{{ $employee_kins_value->next_kin_relation }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" data-toggle="collapse" data-target="#relative_area" aria-expanded="true"
                                aria-controls="relative_area" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            Do you have any relatives in this company ?
                                        </h4>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>
                                            No : <input @if ($employee_relatives_data->count() == 0) checked @endif type="radio"
                                                value="No" name="relative_check_1" id="relative_check_1">
                                            &nbsp;&nbsp;
                                            Yes : <input @if ($employee_relatives_data->count() > 0) checked @endif type="radio"
                                                value="Yes" name="relative_check_1" id="relative_check_1">
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                        </h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse"  aria-labelledby="headingOne" data-parent="#accordion"  id="relative_area">
                                    @if ($employee_relatives_data->count() > 0)
                                        <?php $counter = 1; ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-sm mb-0 table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Name<< /th>
                                                        <th class="text-center">Position</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($employee_relatives_data->get() as $employee_relatives_value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td>{{ $employee_relatives_value->relative_name }}</td>
                                                            <td>{{ $employee_relatives_value->relative_position }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4  data-toggle="collapse" data-target="#crime" aria-expanded="true"
                                aria-controls="crime"  style="font-weight: bold;margin-top: 10px;">Have you ever been convicted of a
                                            crime ?</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>
                                            No : <input
                                                @if ($employee_other_details->count() > 0) @if ($employee_other_details->value('crime_check') == 'No') checked @endif
                                                @endif
                                            type="radio" value="No" name="crime_check" id="crime_check"
                                            class="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input
                                                @if ($employee_other_details->count() > 0) @if ($employee_other_details->value('crime_check') == 'Yes') checked @endif
                                                @endif
                                            type="radio" value="Yes" name="crime_check" id="crime_check"
                                            class="relative_check">
                                        </h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse"  aria-labelledby="headingOne" data-parent="#accordion" id="crime">
                                    @if ($employee_other_details->count() > 0)
                                        @if ($employee_other_details->value('crime_check') == 'Yes')
                                            <table class="table table-sm mb-0 table-bordered table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $employee_other_details->value('crime_detail') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" data-toggle="collapse" data-target="#provide" aria-expanded="true"
                                aria-controls="provide"  style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Any Additional Information you wish
                                            to provide ?</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>
                                            No : <input
                                                @if ($employee_other_details->count() > 0) @if ($employee_other_details->value('additional_info_check') == 'No') checked @endif
                                                @endif
                                            type="radio" value="No" name="additional_info_check"
                                            id="additional_info_check" class="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input
                                                @if ($employee_other_details->count() > 0) @if ($employee_other_details->value('additional_info_check') == 'Yes') checked @endif
                                                @endif
                                            type="radio" value="Yes" name="additional_info_check"
                                            id="additional_info_check" class="relative_check">
                                        </h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse"  aria-labelledby="headingOne" data-parent="#accordion" id="provide">
                                    @if ($employee_other_details->count() > 0)
                                        @if ($employee_other_details->value('additional_info_check') == 'Yes')
                                            <table class="table table-sm mb-0 table-bordered table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $employee_other_details->value('additional_info_detail') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-toggle="collapse" data-target="#employee_items_area" aria-expanded="true" aria-controls="employee_items_area">
                                        <h4  style="font-weight: bold;margin-top: 10px;">Employee Items</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse" aria-labelledby="headingOne" data-parent="#accordion" id="employee_items_area">
                                    <div class="col-sm-12">
                                        <table class="table table-sm mb-0 table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Equipment</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($employee_equipment as $value)
                                                    <tr>
                                                        <td class="text-center">{{ $value->equipment_name }}</td>
                                                        <td class="text-center"><input type="checkbox"
                                                                @foreach ($employee_equipment_assigned->get() as $ass)
                                                                                   @if ($ass->equipment_id == $value->id) checked @endif @endforeach
                                                                name="equipment_id[]" value="{{ $value->id }} " />
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4  data-toggle="collapse" data-target="#employee_documents_area" aria-expanded="true" aria-controls="employee_documents_area" style="font-weight: bold;margin-top: 10px;">Employee Documents</h4>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row collapse" aria-labelledby="headingOne" data-parent="#accordion"  id="employee_documents_area">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-sm mb-0 table-bordered table-hover">
                                            <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">File Name</th>
                                                <th class="text-center">File Type</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Download</th>
                                            </thead>
                                            <tbody>
                                                <?php $counter = 1; ?>
                                                @if ($employee_documents->count() > 0)
                                                    @foreach ($employee_documents->get() as $value)
                                                        <?php $url = url('/') . Storage::url($value->file_path); ?>
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td class="text-center">{{ $value->file_name }}</td>
                                                            <td class="text-center">{{ $value->file_type }}</td>
                                                            <td class="text-center">{{ $value->type }}</td>
                                                            <td class="text-center">
                                                                <a style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;"
                                                                    target="_blank" href="<?= $url ?>"><i
                                                                        class="badge badge-info">Download</i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center" colspan="6"
                                                            style="color:red;font-weight: bold;">Record Not Found !</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    $(document).ready(function() {
        document.getElementsByTagName("checkbox").disabled = true;
    });
    function showSalaryModal(employeeId) {
        // Show the modal for the specific employee
        $('#salaryModal_' + employeeId).modal('show');
    }

    function verifySalaryPassword(employeeId) {
        // Hardcoded password for demonstration (you can replace this with dynamic verification logic)
        var correctPassword = 'your-secure-password'; // Replace this with real password logic

        var enteredPassword = $('#salaryPassword_' + employeeId).val();

        if (enteredPassword === correctPassword) {
            // Show salary and hide the modal
            $('#salary_' + employeeId).show();
            $('#salaryModal_' + employeeId).modal('hide');
        } else {
            alert('Incorrect password, please try again.');
        }
    }
</script>