<?php

use App\Models\Employee;
use App\User;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Storage;
$m = Input::get('m');
$user_roles = CommonHelper::userRoles($m);
?>
@extends('layouts.default')
@section('content')

    <style>
        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }

    </style>

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="card-title">Edit Employee Form</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'had/editEmployeeDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data", "files" => true)) }}
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $employee_detail->id }}">
                        <input type="hidden" name="emp_id" value="{{ $employee_detail->emp_id }}">
                        <input type="hidden" name="login_check" value="{{ $userData->count() }}">
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="hr-border"></div>
                                    <img id="img_file_1" class="avatar img-circle img-thumbnail" style="height:210px;width:20%;" src="{{ url($employee_detail->img_path != '' ? 'storage/'.$employee_detail->img_path  : 'storage/app/uploads/employee_images/user-dummy.png') }} ">
                                </div>
                                <div class="form-group text-center">
                                    <label class="text-dark">
                                        <input type="file" id="file_1" name="fileToUpload_1" accept="image/*" capture style="display:none"/>
                                        <img class="avatar img-circle img-thumbnail" style="width:20%;cursor:pointer" src="<?= url('assets/images/cam.png')?>" id="upfile1"/>
                                        Change Image
                                    </label>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff;">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4  style="font-weight: bold;margin-top: 10px;">Basic Information</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Employee ID</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="emp_id" id="emp_id" value="{{ $employee_detail->emp_id }}" class="form-control requiredField">
                                        <span style="color:red;font-weight: bold;" id="emrExistMessage"></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label pointer">Attendance ID</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="attendance_id" class="form-control"  value="{{ $employee_detail->attendance_id }} " />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Employee Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="emp_name" id="emp_name" value="{{ $employee_detail->emp_name }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Father / Husband Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="emp_father_name" id="emp_father_name" value="{{ $employee_detail->emp_father_name }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label pointer">Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="department_id" id="department_id" onchange="getSubDepartments(this.value)">
                                            <option value="">Select Department</option>
                                            @foreach($department as $key => $val)
                                                <option @if($employee_detail->department_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label pointer">Sub Department</label>
                                        <select class="form-control" name="sub_department_id" id="sub_department_id">
                                            <option value="">Select Sub Department</option>
                                            @foreach($sub_department as $key => $val)
                                                <option @if($employee_detail->sub_department_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->sub_department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Designation','designation','designation_name','designation','{{ $m }}')"><label class="text-dark pointer sf-label">Designation</label></a>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" id="designation_id" name="designation_id"  onchange="getGradeByDesignation()">
                                            <option value="">Select</option>
                                            @foreach($designation as $key => $val)
                                                <option @if($employee_detail->designation_id == $val->id) selected @endif value="{{ $val->id }}"  data-value="{{ $val->grade_id }}">{{ $val->designation_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Select Grade</label>
                                        <select class="form-control" name="grade_id" id="grade_id">
                                            <option value="">Select Grade</option>
                                            @foreach($grade_type as $key => $val)
                                                <option @if($employee_detail->grade_id == $val->id) selected @endif value="{{ $val->id}}">{{ $val->employee_grade_type}}</option>
                                            @endforeach
                                        </select>
                                        <span id="grade_loader"></span>
                                        
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Category','employee_category','employee_category_name','employee_category_id','{{ $m }}')"><label class="pointer sf-label">Category</label></a>
                                        <select class="form-control" id="category_id" name="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($category as $key => $val)
                                                <option @if($employee_detail->category_id == $val->id) selected @endif value="{{ $val->id}}">{{ $val->employee_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Project','employee_projects','project_name','employee_project_id','{{ $m }}')"><label class="pointer sf-label">Project</label></a>
                                        <select class="form-control" id="project_id" name="project_id">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $key => $val)
                                                <option @if(in_array($y->id,$project_array)) selected @endif value="{{ $val->id}}">{{ $val->project_code.' -- '.$val->project_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label pointer">Location:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="location_id[]" id="location_id" multiple>
                                            <option value="">Select Option</option>
                                            <option @if(in_array(0,$location_array)) selected @endif value="0">All</option>
                                            @foreach($locations as $key => $y)
                                                @if(in_array($y->id, $user_roles['location_ids']))
                                                    <option @if(in_array($y->id,$location_array)) selected @endif value="{{ $y->id }}">{{ $y->location_code.' -- '.$y->location_name}}</option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Marital Status','marital_status','marital_status_name','marital_status','{{ $m }}')"><label class="text-dark pointer sf-label">Marital Status</label></a>
                                        <select class="form-control" name="marital_status_id" id="marital_status_id">
                                            <option value="">Select Marital</option>
                                            @foreach($marital_status as $key => $val)
                                                <option @if($employee_detail->marital_status_id == $val->id) selected @endif value="{{ $val->id}}">{{ $val->marital_status_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Employment Status','job_type','job_type_name','employee_status','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Employment Status</label></a>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onchange="setConfirmationDate()" class="form-control requiredField" name="employment_status_id" id="employment_status_id" onchange="employeestatus(this.value)">
                                            <option value="">Select Employment Status</option>
                                            @foreach($job_type as $key => $val)
                                                <option @if($employee_detail->employment_status_id == $val->id) selected @endif value="{{ $val->id}}">{{ $val->job_type_name}}</option>
                                            @endforeach
                                        </select>

                                    <input type="hidden" name="oldstatus" value="{{$employee_detail->employment_status_id}}" >

                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Cpl or Ovetime</label>
                                        <select class="form-control" name="cpl_ot" id="cpl_ot">
                                            <option value="">Select Cpl or Ovetime</option>
                                            <option @if($employee_detail->cpl_or_ot == "ot") selected @endif value="ot">Overtime</option>
                                            <option @if($employee_detail->cpl_or_ot == "cpl") selected @endif value="cpl">Cpl</option>
                                        </select>
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Gender</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="gender_id" id="gender_id">
                                            <option value="">Select Gender</option>
                                            <option @if($employee_detail->gender_id == 1) selected @endif value="1">Male</option>
                                            <option @if($employee_detail->gender_id == 2) selected @endif value="2">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Nationality</label>
                                        <input type="text" class="form-control" name="nationality" id="nationality" value="{{ $employee_detail->nationality }} " />
                                    </div>
                                  
                                </div>

                                <div class="row">

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Religion</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="religion" id="religion" value="{{ $employee_detail->religion }} " />
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Date of Birth</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" name="date_of_birth" id="date_of_birth" value="{{ $employee_detail->date_of_birth }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Joining Date</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input onchange="setConfirmationDate()" type="date" class="form-control requiredField" placeholder="Joining Date" name="joining_date" id="joining_date" value="{{ $employee_detail->joining_date }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Confirmation Date</label>
                                        <input type="date" class="form-control" name="confirmation_date" id="confirmation_date" value="{{ $employee_detail->confirmation_date }}" />
                                    </div>
                                    
                                </div>

                                <div class="row">

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Resign Date</label>
                                        <input type="date" class="form-control" name="resign_date" id="resign_date" value="{{ $employee_detail->resign_date }}" />
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Last Working Date</label>
                                        <input type="date" class="form-control" name="last_working_date" id="last_working_date" value="{{ $employee_detail->last_working_date }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">CNIC</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField cnicExistMessage" onkeydown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" maxlength="15" name="cnic" id="cnic" value="{{ $employee_detail->cnic }}" />
                                        <span style="color:red;font-weight: bold;" id="cnicExistMessage"></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="text-dark sf-label">CNIC Expiry Date</label>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                <label class="text-dark sf-label">Life Time CNIC</label>
                                                <input type="checkbox" @if($employee_detail->life_time_cnic == 1) checked @endif value="{{$employee_detail->life_time_cnic}}"  name="life_time_cnic" id="life_time_cnic" />
                                            </div>
                                        </div>
                                        <input type="date" class="form-control" @if($employee_detail->life_time_cnic == 1) disabled @endif name="cnic_expiry_date" id="cnic_expiry_date" value="{{ $employee_detail->cnic_expiry_date }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Reporting Manager</label>
                                        <select class="form-control" name="reporting_manager_id" id="reporting_manager_id">
                                            <option value="">Select Reporting Manager</option>
                                            @foreach($reporting_to as $key => $val)
                                                <option @if($employee_detail->reporting_manager_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->emp_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Contact Number</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                <span class="input-group-prepend">
                                                    <span class="input-group-text hidden">+92</span>
                                                    <span class="input-group-area" style="width:100%;">
                                                        <input type="text" id="contact_no" name="contact_no"  value="{{ $employee_detail->contact_no }}" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" class="form-control requiredField" placeholder="3xxxxxxxxx" onkeypress="return isNumber(event)" />
                                                    </span>
                                                </span>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorMobileNumberOne" ></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Emergency Number</label>
                                                <span class="input-group-prepend">
                                                    <span class="input-group-text hidden">+92</span>
                                                    <span class="input-group-area" style="width:100%;">
                                                        <input type="text" name="emergency_number" id="emergency_number" value="{{ $employee_detail->emergency_number }}" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" class="form-control" placeholder="3xxxxxxxxx" onkeypress="return isNumber(event)" />
                                                    </span>
                                                </span>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorlandlineNumberOne" ></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Emergency Person Name</label>
                                        <input type="text" class="form-control" name="emergency_contact_person" id="emergency_contact_person"  value="{{ $employee_detail->emergency_contact_person }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Office Number</label>
                                        <div  class="input-group-prepend">
                                            <span class="input-group-text hidden">+92</span>
                                            <div class="input-group-area" style="width:100%;">
                                                <input type="text" id="office_no" name="office_no" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" class="form-control" placeholder="3152401099" onkeypress="return isNumber(event)" />
                                            </div>
                                        </div>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorOfficeNumber" ></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Personal Email</label>
                                        <input type="text" class="form-control" name="personal_email" id="personal_email" value="{{ $employee_detail->personal_email }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Official Email</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="official_email" id="official_email" value="{{ $employee_detail->official_email }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Employee Salary</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" class="form-control requiredField" name="salary" id="salary" value="{{ $employee_detail->salary }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="text-dark sf-label">Eobi</label>
                                        <select class="form-control" name="eobi_id" id="eobi_id">
                                            <option value="">Select EOBI</option>
                                            @foreach($eobi as $key => $val)
                                                <option @if($employee_detail->eobi_id == $val->id) selected @endif value="{{ $val->id}}">
                                                    {{ $val->EOBI_name}} ({{ $val->month_year}}) Amount=({{ $val->EOBI_amount}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="text-dark sf-label">EOBI Number</label>
                                        <input type="text" class="form-control" name="eobi_number" id="eobi_number" value="{{ $employee_detail->eobi_number }}" />
                                    </div>
                                    @if($employee_eobi_copy->count() > 0)
                                        <div id="eobi_div_hide" class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <label class="pointer" id="eobi_check" style="color: green">Change / Edit EOBI copy:</label>
                                                </div>
                                                <div class="col-sm-2 text-right">
                                                    <span style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeEobiCopy','{{ $employee_detail->id.'|'.$employee_detail->emp_id }}','View Employee EOBI Copy','{{ $m }}')"><i class="badge badge-info">View</i></span>
                                                </div>
                                            </div>
                                            <input type="file" class="form-control" name="eobi_path" id="eobi_path" multiple>
                                        </div>
                                    @else
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="text-dark sf-label">EOBI Upload</label>
                                            <input type="file" class="form-control" name="eobi_path" id="eobi_path" multiple>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="text-dark sf-label">Leaves Policy</label>
                                        <select class="form-control" name="leaves_policy_id" id="leaves_policy_id">
                                            <option value="">Select</option>
                                            @foreach($leaves_policy as $key => $val)
                                                <option @if($employee_detail->leaves_policy_id == $val->id) selected @endif  value="{{ $val->id}}">{{ $val->leaves_policy_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 30px;text-align:right;">
                                        <button type="button" class="btn btn-sm btn-primary" id="leaves_policy_id_1">View Policy</button>
                                    </div>
                                </div>

                                {{--new employee details--}}
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Address</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="text-dark sf-label">Current Address</label>
                                        <textarea class="form-control" name="current_address">{{ $employee_detail->current_address }}</textarea>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="text-dark sf-label">Permanent Address</label>
                                        <textarea class="form-control" name="permanent_address">{{ $employee_detail->permanent_address }}</textarea>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Bank Account Details</h4>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input @if(!empty($employee_bank_detail)) checked @endif type="checkbox" name="bank_account_check" id="bank_account_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="bank_account_area">
                                    @if(!empty($employee_bank_detail))
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Select Bank</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="bank_name" id="bank_name">
                                                @foreach($banks as $bank)
                                                    <option value="{{ $bank->bank_name }}" @if($employee_bank_detail->bank_name == $bank->bank_name) selected @endif >{{ $bank->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Account No</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField" placeholder="Account No" name="account_no" id="account_no" value="{{ $employee_bank_detail->account_no }}" />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <input type="hidden" name="bank_account_data" value="1">
                                            <label class="sf-label">Account Title</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField" placeholder="Title Of Account" name="account_title" id="account_title" value="{{ $employee_bank_detail->account_title }}" />
                                        </div>
                                    @endif
                                </div>

                                {{--family data--}}
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Immediate Family Data</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">
                                        <h4 style="text-decoration: underline;">
                                            Spouse, Children, Parents, Brothers, Sisters
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input @if($employee_family_detail->count() > 0) checked @endif type="checkbox" name="family_data_check" id="family_data_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="family_data_area">
                                    @if($employee_family_detail->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Relation<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Add Emergency Contact<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center"><button type="button" id="addMoreFamilyData" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="family_append_area">
                                                <?php $counter = 1; ?>
                                                @foreach($employee_family_detail->get() as $employeeFamilyData)
                                                    <tr class="get_rows3" id="remove_area3_{{$employeeFamilyData->id}}">
                                                        <td class="text-center">
                                                            <input type="hidden" name="family_data[]" id="get_rows3" value="{{ $employeeFamilyData->id }}">
                                                            <span class="badge badge-pill badge-secondary">{{ $counter++ }}</span>
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" name="family_name_{{$employeeFamilyData->id}}" id="family_name_{{$employeeFamilyData->id}}" value="{{ $employeeFamilyData->family_name }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" name="family_relation_{{$employeeFamilyData->id}}"  id="family_relation_{{$employeeFamilyData->id}}" value="{{ $employeeFamilyData->family_relation }}">
                                                        </td>
                                                        <td><input class="form-control requiredField" name="family_emergency_{{$employeeFamilyData->id}}" id="family_emergency_{{$employeeFamilyData->id}}" value="{{ $employeeFamilyData->family_emergency_contact }}"></td>
                                                        <td class="text-center"><button type="button" onclick="removeFamilyDataSection('{{$employeeFamilyData->id}}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <script>
                                            $("#addMoreFamilyData").click(function(e){
                                                var form_rows_count = $(".get_rows3").length;
                                                form_rows_count++;
                                                $("#family_append_area").append('<tr class="get_rows3" id="remove_area3_'+form_rows_count+'">' +
                                                        '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'<span></td>' +
                                                        '<td class="text-center"><input class="form-control requiredField" name="family_name_'+form_rows_count+'" id="family_name_'+form_rows_count+'"></td>' +
                                                        '<td><input class="form-control requiredField" name="family_relation_'+form_rows_count+'"  id="family_relation_'+form_rows_count+'"></td>' +
                                                        '<td><input class="form-control requiredField" name="family_emergency_'+form_rows_count+'" id="family_emergency_'+form_rows_count+'"></td>' +
                                                        '<td class="text-center"><button type="button" onclick="removeFamilyDataSection('+form_rows_count+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td></tr>');
                                            });
                                        </script>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Educational Background</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Start from Recent</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input @if($employee_educational_detail->count() > 0 ) checked @endif type="checkbox" name="education_check" id="education_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="education_area">
                                    @if($employee_educational_detail->count() > 0 )
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Name Of Institution</th>
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                                <th class="text-center">Degree / Diploma</th>
                                                <th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone">
                                                <?php $counter =1; ?>
                                                @foreach($employee_educational_detail->get() as $employee_educational_value)
                                                    <tr class="get_rows" id="remove_area_{{$employee_educational_value->id}}">
                                                        <td class="text-center">
                                                            <input type="hidden" name="education_data[]" value="{{$employee_educational_value->id}}">
                                                            <span class="badge badge-pill badge-secondary">{{$counter++}}</span>
                                                        </td>
                                                        <td class="text-center"><input name="institute_name_{{$employee_educational_value->id}}" type="text" class="form-control requiredField" id="institute_name_{{$employee_educational_value->id}}" value="{{ $employee_educational_value->institute_name }}"></td>
                                                        <td class="text-center"><input name="year_of_admission_{{$employee_educational_value->id}}" type="date" class="form-control requiredField" id="year_of_admission_{{$employee_educational_value->id}}" value="{{ $employee_educational_value->year_of_admission }}"></td>
                                                        <td class="text-center"><input name="year_of_passing_{{$employee_educational_value->id}}" type="date" class="form-control requiredField" id="year_of_passing_{{$employee_educational_value->id}}" value="{{ $employee_educational_value->year_of_passing }}"></td>
                                                        <td><input type="hidden" name="qualificationSection[]">
                                                            <select style="width:100%;" id="degree_type_{{$employee_educational_value->id}}" class="form-control requiredField get_clone_1" name="degree_type_{{$employee_educational_value->id}}"><option value="">Select</option>
                                                                @foreach($degree_type as $DegreeTypeValue)
                                                                    <option @if($employee_educational_value->degree_type == $DegreeTypeValue->id) selected @endif value="{{ $DegreeTypeValue->id }}">
                                                                        {{ $DegreeTypeValue->degree_type_name }}
                                                                    </option>
                                                                @endforeach
                                                                <option value="other">Other</option>
                                                            </select>
                                                            <span id="other_option_1"></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <button onclick='removeQualificationSection("{{$employee_educational_value->id}}")'  type='button' class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                            <script>
                                                                $(document).ready(function () {
                                                                    $('#degree_type_<?=$employee_educational_value->id?>').select2();
                                                                });
                                                            </script>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <script>
                                            $("#addMoreQualification").click(function(e){
                                                var clone = $(".get_clone_1").html();

                                                var form_rows_count = $(".get_rows").length;
                                                form_rows_count++;
                                                $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                                                        "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                                                        "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                                                        "<td class='text-center'><input name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                                                        "<td class='text-center'><input name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                                                        "<td><input type='hidden' name='education_data[]' value="+form_rows_count+">" +
                                                        "<select style='width: 100%' id='degree_type_"+form_rows_count+"' class='form-control requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                                                        "<span id='other_option_"+form_rows_count+"'></span></td>" +
                                                        "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                                                        "</td></tr>");
                                                $('#degree_type_'+form_rows_count+'').select2();
                                            });
                                        </script>
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Language Proficiency</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input @if($employee_language_proficiency->count() > 0) checked @endif type="checkbox" name="language_check" id="language_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="language_area">
                                    @if($employee_language_proficiency->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Language<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Read<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Write<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Speak<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center"><button type="button" id="addMoreLanguage" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone7">
                                                <?php $counter2 = 1; ?>
                                                @foreach($employee_language_proficiency->get() as $language_proficiency_detail)
                                                    <tr class="get_rows7" id="remove_area7_<?=$language_proficiency_detail->id?>">
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-secondary">{{$counter2++}}</span>
                                                            <input type="hidden" name="language_data[]" value="{{$language_proficiency_detail->id}}">
                                                        </td>
                                                        <td id="get_clone7" class="text-center">
                                                            <input class="form-control requiredField" name="language_name_<?=$language_proficiency_detail->id?>" value="<?=$language_proficiency_detail->language_name?>" id="language_name_1" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <b>Good : <input @if($language_proficiency_detail->reading_skills == 'Good') checked @endif type="radio" name="reading_skills_<?=$language_proficiency_detail->id?>" value="Good"></b>
                                                            <b>Fair : <input @if($language_proficiency_detail->reading_skills == 'Fair') checked @endif type="radio" name="reading_skills_<?=$language_proficiency_detail->id?>" value="Fair"></b>
                                                            <b>Poor : <input @if($language_proficiency_detail->reading_skills == 'Poor') checked @endif type="radio" name="reading_skills_<?=$language_proficiency_detail->id?>" value="Poor"></b>
                                                        </td>
                                                        <td class="text-center">
                                                            <b>Good : <input @if($language_proficiency_detail->reading_skills == 'Good') checked @endif type="radio" name="writing_skills_<?=$language_proficiency_detail->id?>" value="Good"></b>
                                                            <b>Fair : <input @if($language_proficiency_detail->reading_skills == 'Fair') checked @endif type="radio" name="writing_skills_<?=$language_proficiency_detail->id?>" value="Fair"></b>
                                                            <b>Poor : <input @if($language_proficiency_detail->reading_skills == 'Poor') checked @endif type="radio" name="writing_skills_<?=$language_proficiency_detail->id?>" value="Poor"></b>
                                                        </td>
                                                        <td class="text-center">
                                                            <b>Good : <input @if($language_proficiency_detail->reading_skills == 'Good') checked @endif type="radio" name="speaking_skills_<?=$language_proficiency_detail->id?>" value="Good"></b>
                                                            <b>Fair : <input @if($language_proficiency_detail->reading_skills == 'Fair') checked @endif type="radio" name="speaking_skills_<?=$language_proficiency_detail->id?>" value="Fair"></b>
                                                            <b>Poor : <input @if($language_proficiency_detail->reading_skills == 'Poor') checked @endif type="radio" name="speaking_skills_<?=$language_proficiency_detail->id?>" value="Poor"></b>
                                                        </td>
                                                        <td class="text-center">
                                                            <button onclick='removeLanguageProficiencySection("<?=$language_proficiency_detail->id?>")' type='button' class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <script>
                                            $("#addMoreLanguage").click(function(e){
                                                var form_rows_count = $(".get_rows7").length;
                                                form_rows_count++;
                                                $("#insert_clone7").append("<tr class='get_rows7' id='remove_area7_"+form_rows_count+"' ><td class='text-center'>" +
                                                        '<input type="hidden" name="language_data[]" value="'+form_rows_count+'">' +
                                                        "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                                                        "<td class='text-center'><input class='form-control requiredField' name='language_name_"+form_rows_count+"' value='' id='language_name_"+form_rows_count+"' required></td>" +
                                                        "<td class='text-center'><b>Good : <input checked type='radio' name='reading_skills_"+form_rows_count+"' value='Good'></b>" +
                                                        "<b>Fair : <input  type='radio' name='reading_skills_"+form_rows_count+"' value='Fair'></b>" +
                                                        "<b>Poor : <input type='radio' name='reading_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                                                        "<td class='text-center'><b>Good : <input checked type='radio' name='writing_skills_"+form_rows_count+"' value='Good'></b>" +
                                                        "<b>Fair : <input  type='radio' name='writing_skills_"+form_rows_count+"' value='Fair'></b>" +
                                                        "<b>Poor : <input type='radio' name='writing_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                                                        "<td class='text-center'><b>Good : <input checked type='radio' name='speaking_skills_"+form_rows_count+"' value='Good'></b>" +
                                                        "<b>Fair : <input  type='radio' name='speaking_skills_"+form_rows_count+"' value='Fair'></b>" +
                                                        "<b>Poor : <input type='radio' name='speaking_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                                                        "<td class='text-center'><button onclick='removeLanguageProficiencySection("+form_rows_count+")' type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                                                        "</td>" +
                                                        "</tr>");
                                            });
                                        </script>
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Health Information</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline"> Any disorder regarding</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input @if($employee_health_data->count() > 0) checked @endif type="checkbox" name="health_type_check" id="health_type_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="health_type_area">
                                    @if($employee_health_data->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Health Type<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Yes/No<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center"><button type="button" id="addMoreHealth" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone8">
                                                <?php $counter3 = 1; ?>
                                                @foreach($employee_health_data->get() as $employee_health_value)
                                                    <tr class="get_rows8 remove_area8_<?=$employee_health_value->id?>">
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-secondary">{{$counter3++}}</span>
                                                            <input type="hidden" name="health_data[]" value="<?=$employee_health_value->id?>">
                                                        </td>
                                                        <td>
                                                            <select style="width:100%;" class="form-control health_type_1" name="health_type_<?=$employee_health_value->id?>" id="health_type_<?=$employee_health_value->id?>">
                                                                <option value="">Select Option</option>
                                                                <option @if($employee_health_value->health_type == 'Speech') selected @endif value="Speech">Speech</option>
                                                                <option @if($employee_health_value->health_type == 'Hearing') selected @endif value="Hearing">Hearing</option>
                                                                <option @if($employee_health_value->health_type == 'Sight') selected @endif value="Sight">Sight</option>
                                                                <option @if($employee_health_value->health_type == 'AIDS') selected @endif value="AIDS">AIDS</option>
                                                                <option @if($employee_health_value->health_type == 'Hands') selected @endif value="Hands">Hands</option>
                                                                <option @if($employee_health_value->health_type == 'Feet') selected @endif value="Feet">Feet</option>
                                                                <option @if($employee_health_value->health_type == 'Skin') selected @endif value="Skin">Skin</option>
                                                                <option @if($employee_health_value->health_type == 'Cancer') selected @endif value="Cancer">Cancer</option>
                                                                <option @if($employee_health_value->health_type == 'Epilespy') selected @endif value="Epilespy">Epilespy</option>
                                                                <option @if($employee_health_value->health_type == 'Asthma') selected @endif value="Asthma">Asthma</option>
                                                                <option @if($employee_health_value->health_type == 'Tuberculosis') selected @endif value="Tuberculosis">Tuberculosis</option>
                                                                <option @if($employee_health_value->health_type == 'Hepatitis') selected @endif value="Hepatitis">Hepatitis</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select style="width:100%;" class="form-control health_check_1" id="health_check_<?=$employee_health_value->id?>" name="health_check_<?=$employee_health_value->id?>">
                                                                <option value="">Select Option</option>
                                                                <option @if($employee_health_value->health_check == 'Yes') selected @endif value="Yes">Yes</option>
                                                                <option @if($employee_health_value->health_check == 'No') selected @endif value="No">No</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" onclick="removeHealthDetailsSection('<?=$employee_health_value->id?>')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <script>
                                                        $(document).ready(function () {
                                                            $('#health_type_<?=$employee_health_value->id?>').select2();
                                                            $('#health_check_<?=$employee_health_value->id?>').select2();
                                                        });
                                                    </script>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Any Physical Handicap</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" name="physical_handicap" id="physical_handicap" value="<?=$employee_health_value->physical_handicap?>" />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Height</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" placeholder="" name="height" id="height" value="<?=$employee_health_value->height?>"/>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Weight</label>
                                                    <span class='rflabelsteric'><strong>*</strong></span>
                                                    <input type='number' class='form-control requiredField' placeholder='80kg' name='weight' id='weight'  value="<?=$employee_health_value->weight?>" />
                                                </div>
                                                <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12'>
                                                    <label class='sf-label'>Blood Group</label>
                                                    <span class='rflabelsteric'><strong>*</strong></span>
                                                    <input type='text' class='form-control requiredField' placeholder='A+' name='blood_group' id='blood_group'  value="<?=$employee_health_value->blood_group?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $("#addMoreHealth").click(function(e){
                                                var clone_health_type = $(".health_type_1").html();
                                                var clone_health_check = $(".health_check_1").html();
                                                var form_rows_count = $(".get_rows8").length;
                                                form_rows_count++;
                                                $("#insert_clone8").append('<tr class="remove_area8_'+form_rows_count+' get_rows8" id="">' +
                                                        '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                                                        '<td><select style="width:100%;" class="form-control" name="health_type_'+form_rows_count+'" id="health_type_'+form_rows_count+'">'+clone_health_type+'</select></td>' +
                                                        '<td><select style="width: 100%;" class="form-control" name="health_check_'+form_rows_count+'" id="health_check_'+form_rows_count+'">'+clone_health_check+'</select></td>' +
                                                        '<td class="text-center"><input type="hidden" name="health_data[]" value="'+form_rows_count+'">' +
                                                        '<button type="button" onclick="removeHealthDetailsSection('+form_rows_count+')" class="btn btn-sm btn-danger remove_area8_'+form_rows_count+'"><i class="fas fa-trash"></i></button></td>' +
                                                        '</tr>');
                                                $("#health_type_"+form_rows_count+"").select2();
                                                $("#health_check_"+form_rows_count+"").select2();

                                            });
                                        </script>
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Activities</h4>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <h4 style="text-decoration: underline;">
                                            Associations, societies, clubs you were / are member of
                                        </h4>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
                                        <input @if($employee_activity_data->count() > 0 ) checked @endif type="checkbox" name="activity_check" id="activity_check">
                                    </div>

                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="activity_area">
                                    @if($employee_activity_data->count() > 0 )
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name Of Institution<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Position Held<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center"><button type="button" id="addMoreActivities" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone4">
                                                <?php $counter6 = 1; ?>
                                                @foreach($employee_activity_data->get() as $employee_activity_detail )
                                                    <tr class="get_rows4 remove_area4_<?=$employee_activity_detail->id?>">
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-secondary">{{$counter6++}}</span>
                                                            <input type="hidden" name="activity_data[]" value="<?=$employee_activity_detail->id?>">
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" name="institution_name_<?=$employee_activity_detail->id?>" id="institution_name_<?=$employee_activity_detail->id?>" value="<?=$employee_activity_detail->institution_name?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control requiredField" name="position_held_<?=$employee_activity_detail->id?>" id="position_held_<?=$employee_activity_detail->id?>" value="<?=$employee_activity_detail->position_held?>" />
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" onclick="removeActivityDataSection('<?=$employee_activity_detail->id?>')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <script>
                                            $("#addMoreActivities").click(function(e){

                                                var form_rows_count = $(".get_rows4").length;
                                                form_rows_count++;
                                                $("#insert_clone4").append('<tr class="remove_area4_'+form_rows_count+' get_rows4">' +
                                                        '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                                                        '<td><input class="form-control requiredField" name="institution_name_'+form_rows_count+'" id="institution_name_'+form_rows_count+'"></td>' +
                                                        '<td><input type="text" class="form-control requiredField" name="position_held_'+form_rows_count+'" id="position_held_'+form_rows_count+'" /></td>' +
                                                        '<td class="text-center"><button type="button" onclick="removeActivityDataSection('+form_rows_count+')" class="btn btn-sm btn-danger remove_area4_'+form_rows_count+'"><i class="fas fa-trash"></i></button></td></tr>');
                                            });
                                        </script>
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Work Experience</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Most recent first</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input @if($employee_work_experience->count() > 0) checked @endif type="checkbox" name="work_experience_check" id="work_experience_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="work_experience_area">
                                    @if($employee_work_experience->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Organization Name</th>
                                                <th class="text-center">From</th>
                                                <th class="text-center">Till</th>
                                                <th class="text-center">File Upload</th>
                                                <th class="text-center"><button type="button" id="addMoreWorkExperience" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone1">
                                                <?php $counter4 = 1; ?>
                                                @foreach($employee_work_experience->get() as $employee_work_experience_value)
                                                    <tr class="get_rows1" id="remove_area1_<?=$employee_work_experience_value->id?>">
                                                        <td class="text-center">
                                                            <span class="badge badge-pill badge-secondary">{{$counter4++}}</span>
                                                            <input type="hidden" name="work_experience_data[]" value="{{$employee_work_experience_value->id}}">
                                                            <input type="hidden" name="recordType_<?=$employee_work_experience_value->id?>" value="update">
                                                        </td>

                                                        <td class="text-center">
                                                            <input type="text" name="employeer_name_<?=$employee_work_experience_value->id?>" id="employeer_name_<?=$employee_work_experience_value->id?>" value="<?=$employee_work_experience_value->employeer_name?>" class="form-control requiredField" required>
                                                        </td>
                                                        <td class="text-center"><input name="started_<?=$employee_work_experience_value->id?>" type="date" class="form-control" id="started_<?=$employee_work_experience_value->id?>" value="<?=$employee_work_experience_value->started?>"></td>
                                                        <td class="text-center"><input name="ended_<?=$employee_work_experience_value->id?>" id="ended_<?=$employee_work_experience_value->id?>"type="date" class="form-control"  value="<?=$employee_work_experience_value->ended?>"></td>
                                                        <td class="text-center">
                                                            @if($employee_work_experience_doc->count() > 0)
                                                                <p class="pointer workExpFile_<?=$employee_work_experience_value->id?>" onclick="workExpFile('<?=$employee_work_experience_value->id?>')" style="color: green;">Change / Edit </p><input style="display: none" type="file" class="form-control" name="work_exp_path_<?=$employee_work_experience_value->id?>" id="work_exp_path_<?=$employee_work_experience_value->id?>" multiple>
                                                            @else
                                                                <input type="file" class="form-control" name="work_exp_path_<?=$employee_work_experience_value->id?>" id="work_exp_path_<?=$employee_work_experience_value->id?>" multiple></td>
                                                        @endif
                                                        <td class='text-center'>
                                                            <script>
                                                                $(document).ready(function () {
                                                                    $('#career_level_<?=$employee_work_experience_value->id?>').select2();
                                                                    $('#position_held_<?=$employee_work_experience_value->id?>').select2();

                                                                });
                                                            </script>
                                                            <button onclick='removeWorkExperienceSection("<?=$employee_work_experience_value->id?>")' type='button' class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($employee_work_experience_doc->count() > 0)
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <a style="float: right;" onclick="showMasterTableEditModel('hdc/viewEmployeeExperienceDocuments','<?php echo $employee_detail->id.'|'.$employee_detail->emr_no;?>','View Employee Work Experience Documents','<?php echo $m; ?>')" class=" btn btn-info btn-sm">View</a>
                                            </div>
                                        @endif
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <br><label class="sf-label">Have you ever been dismissed / suspended from service ?</label>
                                            <input @if($employee_work_experience_value->suspend_check == 'no') checked @endif type="radio" name="suspend_check_1" id="suspend_check_1" value="no"/>&nbsp; No
                                            <input @if($employee_work_experience_value->suspend_check == 'yes') checked @endif type="radio" name="suspend_check_1" id="suspend_check_1" value="yes" />&nbsp; Yes &nbsp;
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="suspend_detail_1">
                                            @if($employee_work_experience_value->suspend_check == 'yes')
                                                <label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" class="form-control requiredField" value="<?= $employee_work_experience_value->suspend_reason ?>" name="suspend_reason_1" id="suspend_reason_1" />
                                                <script>

                                                    $("input[name='suspend_check_1']").click(function() {
                                                        if($(this).val() == 'yes')
                                                        {
                                                            $("#suspend_detail_1").html('<label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>' +
                                                                    '<input type="text" class="form-control requiredField" placeholder="Suspend Reason" name="suspend_reason_1" id="suspend_reason_1" />');
                                                        }
                                                        else
                                                        {
                                                            $("#suspend_detail_1").html('');
                                                        }
                                                    })
                                                </script>
                                            @endif
                                        </div>
                                        <script>
                                            $("#addMoreWorkExperience").click(function(e){
                                                var form_rows_count = $(".get_rows1").length;
                                                form_rows_count++;
                                                $("#insert_clone1").append("<tr class='get_rows1' id='remove_area1_"+form_rows_count+"'><td class='text-center'>" +
                                                        "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td><td>" +
                                                        '<input type="hidden" name="work_experience_data[]" value="'+form_rows_count+'">' +
                                                        "<input type='text' name='employeer_name_"+form_rows_count+"' class='form-control requiredField' required></td>" +
                                                        "<td class='text-center'><input name='started_"+form_rows_count+"' id='started_"+form_rows_count+"'  type='date' class='form-control' value=''></td>" +
                                                        "<td class='text-center'><input name='ended_"+form_rows_count+"' id='ended_"+form_rows_count+"' type='date' class='form-control' value=''></td>" +
                                                        "<td class='text-center'><input type='file' class='form-control' name='work_exp_path_"+form_rows_count+"' id='work_exp_path_"+form_rows_count+"' multiple></td>" +
                                                        "<td class='text-center'><button onclick='removeWorkExperienceSection("+form_rows_count+")' type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                                                        "</td>" +
                                                        "</tr>");

                                            });
                                        </script>
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Reference</h4>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <h4 style="text-decoration: underline;">
                                            Professional/Business references only ,
                                            Please include reference from your past employment
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                        <input @if($employee_reference_data->count() > 0 ) checked @endif type="checkbox" name="reference_check" id="reference_check">
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" id="reference_area">
                                    @if($employee_reference_data->count() > 0 )
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Designation<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Organization<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Contact Number<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Relationship<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center"><button type="button" id="addMoreReference" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone2">
                                                <?php $counter7 = 1?>
                                                @foreach($employee_reference_data->get() as $employee_reference_value)
                                                    <tr class="get_rows2" id="remove_area2_<?=$employee_reference_value->id?>">
                                                        <td class="text-center">
                                                            <input type="hidden" name="reference_data[]" value="<?=$employee_reference_value->id?>">
                                                            <span class="badge badge-pill badge-secondary">{{ $counter7++ }}</span>
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" name="reference_name_<?=$employee_reference_value->id?>" id="reference_name_<?=$employee_reference_value->id?>" value="<?=$employee_reference_value->reference_name?>">
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" name="reference_designation_<?=$employee_reference_value->id?>" id="reference_designation_<?=$employee_reference_value->id?>" value="<?=$employee_reference_value->reference_designation?>" />
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" name="reference_organization_<?=$employee_reference_value->id?>" id="reference_organization_<?=$employee_reference_value->id?>" value="<?=$employee_reference_value->reference_organization?>" />
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="reference_contact_<?=$employee_reference_value->id?>"  id="reference_contact_<?=$employee_reference_value->id?>" value="<?=$employee_reference_value->reference_contact?>" />
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" name="reference_relationship_<?=$employee_reference_value->id?>" id="reference_relationship_<?=$employee_reference_value->id?>" value="<?=$employee_reference_value->reference_relationship?>" />
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" onclick="removeReferenceSection('<?=$employee_reference_value->id?>')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <script>
                                            $("#addMoreReference").click(function(e){
                                                var form_rows_count = $(".get_rows2").length;
                                                form_rows_count++;
                                                $("#insert_clone2").append('<tr class="get_rows2" id="remove_area2_'+form_rows_count+'">' +
                                                        '<td class="text-center"><input type="hidden" name="reference_data[]" value="'+form_rows_count+'">' +
                                                        '<span class="badge badge-pill badge-secondary">'+form_rows_count+'<span></td>' +
                                                        '<td><input class="form-control requiredField" name="reference_name_'+form_rows_count+'" id="reference_name_'+form_rows_count+'"></td>' +
                                                        '<td><input class="form-control requiredField" name="reference_designation_'+form_rows_count+'" id="reference_designation_'+form_rows_count+'"></td>' +
                                                        '<td><input class="form-control requiredField" name="reference_organization_'+form_rows_count+'" id="reference_organization_'+form_rows_count+'"></td>' +
                                                        '<td><input class="form-control" type="text" name="reference_contact_'+form_rows_count+'"  id="reference_contact_'+form_rows_count+'"></td>' +
                                                        '<td><input class="form-control requiredField" name="reference_relationship_'+form_rows_count+'" id="reference_relationship_'+form_rows_count+'"></td>' +
                                                        '<td class="text-center"><button type="button" onclick="removeReferenceSection('+form_rows_count+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td></tr>')

                                            });
                                        </script>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Next Of Kin Details</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">In Case of Employee Death</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input @if($employee_kins_data->count() > 0) checked @endif type="checkbox" name="kins_check" id="kins_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="kins_area">
                                    @if($employee_kins_data->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Relation<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center"> <button type="button" id="addMoreKinDetails" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone9">
                                                <?php $counter5 = 1; ?>
                                                @foreach($employee_kins_data->get() as $employee_kins_value)
                                                    <tr class="remove_area6_<?=$employee_kins_value->id?> get_rows9">
                                                        <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter5++}}</span></td>
                                                        <td>
                                                            <input type="hidden" name="kins_data[]" value="<?=$employee_kins_value->id?>">
                                                            <input class="form-control requiredField" name="next_kin_name_<?=$employee_kins_value->id?>" id="next_kin_name_<?=$employee_kins_value->id?>" value="<?=$employee_kins_value->next_kin_name?>" required></td>
                                                        <td>
                                                            <input class="form-control requiredField" name="next_kin_relation_<?=$employee_kins_value->id?>" id="next_kin_relation_<?=$employee_kins_value->id?>" value="<?=$employee_kins_value->next_kin_relation?>" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type='button' onclick='removeKinDetailsSection("<?=$employee_kins_value->id?>")' class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <script>
                                            $("#addMoreKinDetails").click(function(e){

                                                var form_rows_count = $(".get_rows9").length;
                                                form_rows_count++;
                                                $("#insert_clone9").append('<tr class="remove_area6_'+form_rows_count+' get_rows9" id="">' +
                                                        '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                                                        "<td class='text-center'><input type='hidden' name='kins_data[]' value="+form_rows_count+">" +
                                                        "<input class='form-control requiredField' name='next_kin_name_"+form_rows_count+"' id='next_kin_name_"+form_rows_count+"' required></td>" +
                                                        "<td class='text-center'><input class='form-control requiredField' name='next_kin_relation_"+form_rows_count+"' id='next_kin_relation_"+form_rows_count+"' required></td>" +
                                                        "<td class='text-center'><button type='button' onclick='removeKinDetailsSection("+form_rows_count+")' class='btn btn-sm btn-danger remove_area9_"+form_rows_count+"'><i class='fas fa-trash'></i></button></td>" +
                                                        '</tr>');

                                            });
                                        </script>
                                    @endif
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">
                                            Do you have any relatives in this company ?
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input @if($employee_relatives_data->count() == 0) checked @endif type="radio" value="No" name="relative_check" id="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input @if($employee_relatives_data->count() > 0) checked @endif type="radio" value="Yes" name="relative_check" id="relative_check">
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="relative_area">
                                    @if($employee_relatives_data->count() > 0)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows10">
                                            <table class="table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Position<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">
                                                    <button type="button" id="addMoreRelativesDetails" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
                                                </th>
                                                </thead>
                                                <tbody id="insert_clone10">
                                                <?php $counter8 = 1; ?>
                                                @foreach($employee_relatives_data->get() as $employee_relatives_value)
                                                    <tr class="get_rows10 remove_area10_<?=$employee_relatives_value->id?>">
                                                        <td class="text-center">
                                                            <input type="hidden" name="relatives_data[]" value="<?=$employee_relatives_value->id?>">
                                                            <span class="badge badge-pill badge-secondary">{{ $counter8++ }}</span>
                                                        </td>
                                                        <td><input class="form-control requiredField" name="relative_name_<?=$employee_relatives_value->id?>" id="relative_name_<?=$employee_relatives_value->id?>" value="<?=$employee_relatives_value->relative_name?>"></td>
                                                        <td><input class="form-control requiredField" name="relative_position_<?=$employee_relatives_value->id?>"  id="relative_position_<?=$employee_relatives_value->id?>" value="<?=$employee_relatives_value->relative_position?>"></td>
                                                        <td class="text-center">
                                                            <button type="button" onclick="removeRelativesDetailsSection('<?=$employee_relatives_value->id?>')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <script>
                                            $("#addMoreRelativesDetails").click(function(e){

                                                var form_rows_count = $(".get_rows10").length;
                                                form_rows_count++;
                                                $("#insert_clone10").append('<tr class="remove_area10_'+form_rows_count+' get_rows10" id="">' +
                                                        '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                                                        '<td class="text-center"><input type="hidden" name="relatives_data[]" value='+form_rows_count+'>' +
                                                        '<input class="form-control requiredField" name="relative_name_'+form_rows_count+'" value="" id="relative_name_'+form_rows_count+'" required></td>' +
                                                        '<td class="text-center"><input class="form-control requiredField" name="relative_position_'+form_rows_count+'" value="" id="next_kin_relation_'+form_rows_count+'" required></td>' +
                                                        '<td class="text-center"><button type="button" onclick="removeRelativesDetailsSection('+form_rows_count+')" class="btn btn-sm btn-danger remove_area10_'+form_rows_count+'"><i class="fas fa-trash"></i></button></td>' +
                                                        '</tr>');

                                            });

                                        </script>
                                    @endif
                                </div>
                                
                                
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Have you ever been convicted of a crime ?</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input
                                                    @if($employee_other_details->value('crime_check') == 'No') checked @endif
                                            type="radio" value="No" name="crime_check" id="crime_check">
                                            &nbsp;&nbsp;
                                            Yes : <input
                                                    @if($employee_other_details->value('crime_check') == 'Yes') checked @endif
                                            type="radio" value="Yes" name="crime_check" id="crime_check">
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="crime_check_input">
                                    @if(!empty($employee_other_details))
                                        @if($employee_other_details->value('crime_check') == 'Yes')
                                            <label class="sf-label">Detail</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="crime_detail" id="crime_detail" class="form-control requiredField">{{ $employee_other_details->value('crime_detail') }}</textarea>
                                        @endif
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Any Additional Information you wish to provide ?</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input
                                                    @if($employee_other_details->value('additional_info_check') == 'No') checked @endif
                                            type="radio" value="No" name="additional_info_check" id="additional_info_check">
                                            &nbsp;&nbsp;
                                            Yes : <input
                                                    @if($employee_other_details->value('additional_info_check') == 'Yes') checked @endif
                                            type="radio" value="Yes" name="additional_info_check" id="additional_info_check">
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="additional_info_input">
                                    @if(!empty($employee_other_details))
                                        @if($employee_other_details->value('additional_info_check') == 'Yes')
                                            <label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="additional_info_detail" id="additional_info_detail" class="form-control requiredField">{{ $employee_other_details->value('additional_info_detail') }}</textarea>
                                        @endif
                                    @endif
                                </div>
                                <div class="row">&nbsp;</div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Employee Items</h4>
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input type="checkbox" @if($employee_equipment_assigned->count() > 0 ) checked @endif name="employee_items_check" id="employee_items_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="employee_items_area">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-sm mb-0 table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Equipment</th>
                                                <th class="text-center">Action</th>
                                                <th class="text-center">Details</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($employee_equipment as $key => $val)
                                                <tr>
                                                    <td class="text-center">{{ $val->equipment_name }}</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" @foreach($employee_equipment_assigned->get() as $assigned) @if($assigned->equipment_id == $val->id) checked @endif @endforeach name="equipment_id[]" value="{{ $val->id }} " />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Employee Document Upload</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Can Upload Multiple Files</h4>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input @if(count($employee_documents) >= 1) checked @endif type="checkbox" name="documents_upload_check" id="documents_upload_check" class="documents_upload_check" value="1" />
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div id="file_upload_area" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @if(count($employee_documents))
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Upload CV:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($cv))
                                                                <span class="text-right" style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','cv')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="media[]" id="media" multiple>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Upload Education Degrees:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($education))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','education')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="edu_degrees[]" id="edu_degrees" multiple>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Upload 2 Passport size photos:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($passport))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','passport')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="passport_photos[]" id="passport_photos" multiple>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Upload CNIC:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if($employee_detail->cnic_path!='')
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','cnic_copy')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="cnic_path_1[]" id="cnic_path_1" multiple>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Clearance Letter (If Any):</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($clearance_letter))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','clearance_letter')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="clearance_letter[]" id="clearance_letter" multiple>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Fit & Proper Criteria Form:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($fit_criteria_form))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','fit_criteria_form')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="fit_and_criteria_form[]" id="fit_and_criteria_form" multiple>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Upload Non-Disclosure Agreement:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($non_disclosure_agreement))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','non_disclosure_agreement')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="non_disclosure_agreement[]" id="non_disclosure_agreement" multiple>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Upload Information Secrecy/Confidentially Form:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($confidentially_form))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','confidentially_form')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="confidentially_form[]" id="confidentially_form" multiple>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Upload Investment Disclosure:</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($investment_disclosure))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','investment_disclosure')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="investment_disclosure[]" id="investment_disclosure" multiple>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Affirmation of having read the Company Service Rules</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($company_service_rules))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','company_service_rules')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="company_service_rules[]" id="company_service_rules" multiple>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <label for="media">Affirmation of having read the VIS Code of Conduct 2019</label>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            @if(!empty($code_of_conduct))
                                                                <span  style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','{{ $employee_detail->id }}','View Employee Documents','{{ $m }}','code_of_conduct')"><i class="badge badge-info">View</i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="file" class="form-control" name="code_of_conduct[]" id="code_of_conduct" multiple>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <br>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Login Credentials</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 ">

                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input @if($userData->count() > 0) checked @endif type="checkbox" name="can_login" id="can_login" value="yes">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                </div>
                                @if($userData->count() > 0)
                                    <br>
                                    <div class="row" id="credential_area">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Account type</label>
                                            <select class="form-control" name="account_type">
                                                <option @if($userData->value('acc_type') == 'user') selected @endif value="user">User</option>
                                                <option @if($userData->value('acc_type') == 'admin') selected @endif value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Password</label>
                                            <input type="text" class="form-control" id="password" name="password">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <button style="margin-top:37px;color:white;" class="icon btn btn-sm btn-warning" type="button" onclick="password_generator()" >Generate</button>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Roles</label>
                                            <select class="form-control" name="role_id" id="role_id">
                                                <option value="">Select</option>
                                                @foreach($MenuPrivileges as $MenuPrivilege)
                                                    <option value="{{$MenuPrivilege->id}}" @if($employee_detail->role_id == $MenuPrivilege->id) selected @endif>{{$MenuPrivilege->role_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="row" id="credential_area" style="display: none;">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Account type</label>
                                            <select  class="form-control" name="account_type">
                                                <option value="user">User</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Password</label>
                                            <input type="text" class="form-control" id="password" name="password">
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <button style="margin-top:37px;color:white;" class="icon btn btn-sm btn-warning" type="button" onclick="password_generator()" >Generate
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Roles</label>
                                            <select class="form-control" name="role_id" id="role_id">
                                                <option value="0">Select</option>
                                                @foreach($MenuPrivileges as $MenuPrivilege)
                                                    <option value="{{$MenuPrivilege->id}}" @if($employee_detail->role_id == $MenuPrivilege->id) selected @endif>{{$MenuPrivilege->role_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <span id="emp_warning" style="color:red;font-weight:bold;"></span>
                                {{ Form::submit('Update', ['class' => 'btn btn-success btn-sm','id' => 'btn_disable']) }}
                                <button type="reset" id="reset" class="btn btn-primary btn-sm">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    
     $('#can_login').click(function (e)
        {
            if($("#can_login").prop('checked') == true)
            {
                $('#credential_area').fadeIn();
                $("#role").addClass('requiredField');
            }
            else
            {
                $('#credential_area').fadeOut();
                $("#role").removeClass('requiredField');
            }
        });

        function eobiCheck()
        {
            if ($('.eobi').is( ":checked" )) {
                $('#eobi_number').prop("disabled", false);
                $('#eobi_path').prop("disabled", false);
            }
            else {
                $('#eobi_number').prop("disabled", true);
                $('#eobi_path').prop("disabled", true);
            }

        }

        function mobileCheck()
        {
            if ($('.mobile').is( ":checked" )) {
                $('#model_number').prop("disabled", false);
                $('#mobile_number').prop("disabled", false);
                $('#sim_number').prop("disabled", false);
            }
            else {
                $('#model_number').prop("disabled", true);
                $('#mobile_number').prop("disabled", true);
                $('#sim_number').prop("disabled", true);
            }

        }


        function employee_item_check(val){

//        if($('#employee_item_check').is(":checked") == true){
//            $("#employee_items_area").css({"display": "block"});
//        }
//        else{
//            $("#employee_items_area").css({"display": "none"});
//        }
        }
        $(document).ready(function() {
            let location_id = new vanillaSelectBox("#location_id",{
                search: true,
                placeHolder: "Select Location",
            });
            $('#btn-group-location_id ul').find('li').get(0).remove();
            if ($('.insurance').is( ":checked" )) {
                $('#insurance_number').prop("disabled", false);
                $('#insurance_path').prop("disabled", false);
            }
            else {
                $('#insurance_number').prop("disabled", true);
                $('#insurance_path').prop("disabled", true);
            }

            if ($('.eobi').is( ":checked" )) {
                $('#eobi_number').prop("disabled", false);
                $('#eobi_path').prop("disabled", false);
            }
            else {
                $('#eobi_number').prop("disabled", true);
                $('#eobi_path').prop("disabled", true);
            }
        });
        $('#employee_items_check').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#employee_items_area").css({"display": "block"});
            }
            else{
                $("#employee_items_area").css({"display": "none"});
            }
        });

        $('#bank_account_check').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#bank_account_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Select Bank</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<select class="form-control requiredField" placeholder="Bank Name" name="bank_name" id="bank_name">' +
                        @foreach($banks as $bank)
                            '<option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>'+
                        @endforeach
                            '</select>' +
                        '</div>'+'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Account No</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Account No" name="account_no" id="account_no" value="" />' +
                        '</div>'+
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Account Title</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" placeholder="Account Title" name="account_title" id="account_title" value="" />' +
                        '</div>');
            }
            else
            {
                $("#bank_account_area").html('');
            }

        });

        $('#education_check').click(function(){

            if($(this).is(":checked") == true)
            {

                $("#education_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="">' +
                        '<input type="hidden" name="education_data[]" value="1"><table class="table table-sm mb-0 table-bordered table-hover"><thead><th class="text-center">S.No</th>' +
                        '<th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>' +
                        '<th class="text-center">Degree / Diploma</th>' +
                        '<th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
                        '</thead><tbody id="insert_clone"><tr class="get_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>' +
                        '<td class="text-center"><input type="hidden" name="qualificationSection[]">' +
                        '<select style="width:300px;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1"><option value="">Select</option>'+
                        '@foreach($degree_type as $DegreeTypeValue)<option value="{{ $DegreeTypeValue->id }}">{{ $DegreeTypeValue->degree_type_name }}</option>@endforeach<option value="other">Other</option></select><span id="other_option_1"></span></td>'+
                        '<td class="text-center">-</td></tr></tbody></table></div></div>');


                $("#addMoreQualification").click(function(e){
                    var clone = $(".get_clone_1").html();

                    var form_rows_count = $(".get_rows").length;
                    form_rows_count++;
                    $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                            "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                            "<td class='text-center'><input name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                            "<td class='text-center'><input name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                            "<td><input type='hidden' name='education_data[]' value="+form_rows_count+">" +
                            "<select style='width:300px;' id='degree_type_"+form_rows_count+"' class='form-control requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                            "<span id='other_option_"+form_rows_count+"'></span></td>" +
                            "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                            "</td></tr>");
                    $('#degree_type_'+form_rows_count+'').select2();

                });
                $('#degree_type_1').select2();
            }
            else
            {
                $("#education_area").html('');
            }

        });

        $("#life_time_cnic").change(function(){
            if($('#life_time_cnic').is(':checked')){
                $('#life_time_cnic').val('1');
                $('#cnic_expiry_date_1').prop('disabled',true);
            }
            else{
                $('#life_time_cnic').val('0');
                $('#cnic_expiry_date_1').prop('disabled',false);
            }
        });

    </script>

    <script src="{{ URL::asset('assets/custom/js/employees.js') }}"></script>
@endsection