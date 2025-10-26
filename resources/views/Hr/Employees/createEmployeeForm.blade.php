<?php
use App\Helpers\CommonHelper;

$m = Input::get('m');
use App\Models\Employee;
$user_roles = CommonHelper::userRoles($m);
?>
@extends('layouts.default')
@section('css')

    <style>
        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }

    </style>
@endsection

@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                                    <div class="col-sm-4">
                                <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'had/addEmployeeDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data")) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="{{ $m }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="hr-border" style=""></div>
                                    <img id="img_file_1" class="avatar img-circle img-thumbnail" style="height:210px;width:20%;" src="{{  url('storage/app/uploads/employee_images/user-dummy.png') }}">
                                </div>
                                <div class="text-center">
                                    <label class="text-dark">
                                        <input type="file" id="emp_image" name="emp_image" accept="image/*" capture style="display:none"/>
                                        <img class="avatar img-circle img-thumbnail" style="width:20%;cursor:pointer" src="<?= url('assets/images/cam.png')?>" id="upfile1"/>
                                        Change Image
                                    </label>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
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
                                        <input type="text" name="emp_id" id="emp_id" class="form-control requiredField">
                                        <span style="color:red;font-weight: bold;" id="emrExistMessage"></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label pointer">Attendance ID</label>
                                        <input type="text" name="attendance_id" id="attendance_id" class="form-control"/>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Employee Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="emp_name" id="emp_name" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Father / Husband Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="emp_father_name" id="emp_father_name" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label pointer">Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="department_id" id="department_id" onchange="getSubDepartments(this.value)">
                                            <option value="">Select Department</option>
                                            @foreach($department as $key => $val)
                                                <option value="{{ $val->id }}"> {{ $val->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Sub Department</label>
                                        <select class="form-control" name="sub_department_id" id="sub_department_id"></select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Designation','designation','designation_name','designation','{{ $m }}')"><label class="text-dark pointer sf-label">Designation</label></a>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select style="width:100%;" class="form-control requiredField" id="designation_id" name="designation_id"  onchange="getGradeByDesignation()">
                                            <option value="">Select Designation</option>
                                            @foreach($designation as $key => $val)
                                                <option value="{{ $val->id}}" data-value="{{ $val->grade_id }}">{{ $val->designation_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Select Grade</label>
                                        <select class="form-control" name="grade_id" id="grade_id">
                                            <option value="">Select Grade</option>
                                            @foreach($grade_type as $val)
                                                <option value="{{ $val->id}}">{{ $val->employee_grade_type}}</option>
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
                                                <option value="{{ $val->id}}">{{ $val->employee_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Project','employee_projects','project_name','employee_project_id','{{ $m }}')"><label class="pointer sf-label">Plant</label></a>
                                        <select class="form-control" id="project_id" name="project_id">
                                            <option value="">Select Plant</option>
                                            @foreach($projects as $key => $val)
                                                <option value="{{ $val->id}}">{{ $val->project_code.' -- '.$val->project_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label pointer">Location:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="location_id[]" id="location_id" multiple>
                                            <option value="">Select Option</option>
                                            <option value="0">All</option>
                                            @foreach($locations as $key => $y)
                                                @if(in_array($y->id, $user_roles['location_ids']))
                                                    <option value="{{ $y->id }}">{{ $y->location_code.' -- '.$y->location_name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Marital Status','marital_status','marital_status_name','marital_status','{{ $m }}')"><label class="text-dark pointer sf-label">Marital Status</label></a>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="marital_status_id" id="marital_status_id">
                                            <option value="">Select Marital</option>
                                            @foreach($marital_status as $key => $val)
                                                <option value="{{ $val->id}}">{{ $val->marital_status_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Employment Status','job_type','job_type_name','employee_status','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Employment Status</label></a>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onchange="setConfirmationDate()" class="form-control requiredField employee_status" name="employment_status_id" id="employment_status_id" >
                                            <option value="">Select Employment Status</option>
                                            @foreach($job_type as $key3 => $value)
                                                <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Cpl or Ovetime</label>
                                        <select class="form-control" name="cpl_ot" id="cpl_ot">
                                            <option value="">Select Cpl or Ovetime</option>
                                            <option value="ot">Overtime</option>
                                            <option value="cpl">Cpl</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Gender</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="gender_id" id="gender_id">
                                            <option value="">Select Gender</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Nationality</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="nationality" id="nationality_1" />
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Religion</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="religion" id="religion" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Date of Birth</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" name="date_of_birth" id="date_of_birth" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Joining Date</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input onchange="setConfirmationDate()" type="date" class="form-control requiredField" name="joining_date" id="joining_date" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Confirmation Date</label>
                                        <input type="date" class="form-control" name="confirmation_date" id="confirmation_date" />
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Resign Date</label>
                                        <input type="date" class="form-control" name="resign_date" id="resign_date" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Last Working Date</label>
                                        <input type="date" class="form-control" name="last_working_date" id="last_working_date" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">CNIC</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField cnicExistMessage" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" maxlength="15" name="cnic" id="cnic" />
                                        <span style="color:red;font-size:13px;font-weight: bold;" id="cnicExistMessage"></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="text-dark sf-label">CNIC Expiry Date</label>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                <label class="text-dark sf-label">Life Time CNIC</label>
                                                <input type="checkbox"  name="life_time_cnic" id="life_time_cnic" />
                                            </div>
                                        </div>
                                        <span class="rflabelsteric"><strong></strong></span>
                                        <input type="date" class="form-control" name="cnic_expiry_date" id="cnic_expiry_date" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Reporting Manager </label>
                                        <!--<span class="rflabelsteric"><strong>*</strong></span>-->
                                        <select class="form-control" name="reporting_manager_id" id="reporting_manager_id">
                                            <option value="">Select Reporting Manager</option>
                                            @foreach($reporting_to as $key => $val)
                                                <option value="{{ $val->id }}">{{ $val->emp_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Contact Number</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <div  class="input-group-prepend">
                                            <span class="input-group-text hidden">+92</span>
                                            <div class="input-group-area" style="width:100%;">
                                                <input type="text" id="contact_no" name="contact_no" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" class="form-control requiredField" value="" placeholder="3152401099" onkeypress="return isNumber(event)" />
                                            </div>
                                        </div>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorMobileNumberOne" ></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Emergency Number</label>
                                            <span class="input-group-prepend">
                                                <span class="input-group-text hidden">+92</span>
                                                <span class="input-group-area" style="width:100%;">
                                                    <input type="text" id="emergency_number" name="emergency_number" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" class="form-control" placeholder="3152401099" onkeypress="return isNumber(event)" />
                                                </span>
                                            </span>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorEmergencyNumber" ></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Emergency Person Name</label>
                                        <input type="text" class="form-control" name="emergency_contact_person" id="emergency_contact_person" />
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
                                        <input type="text" class="form-control" name="personal_email" id="personal_email" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Official Email</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="official_email" id="official_email" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Employee Salary</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" class="form-control requiredField" name="salary" id="salary" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="text-dark sf-label">Eobi</label>
                                        <select class="form-control" name="eobi_id" id="eobi_id">
                                            <option value="">Select EOBI</option>
                                            @foreach($eobi as $key => $val)
                                                <option value="{{ $val->id}}">
                                                    {{ $val->EOBI_name}}
                                                    ({{ $val->month_year}}),
                                                    Amount = {{ $val->EOBI_amount }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="text-dark sf-label">EOBI Number</label>
                                        <input type="text" class="form-control" name="eobi_number" id="eobi_number" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">EOBI Upload</label>
                                        <input type="file" class="form-control" name="eobi_path" id="eobi_path" multiple>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="text-dark sf-label">IP District</label>
                                        <select class="form-control" name="ip_district" id="ip_district" onchange="ipFieldEnableDisable()">
                                            <option value="1">No</option>
                                            <option value="2">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">IP Number</label>
                                        <input type="text" readonly class="form-control" name="ip_number" id="ip_number" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="text-dark sf-label">Leaves Policy</label>
                                        <select class="form-control" name="leaves_policy_id" id="leaves_policy_id">
                                            <option value="">Select</option>
                                            @foreach($leaves_policy as $key => $val)
                                                <option value="{{ $val->id}}">{{ $val->leaves_policy_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right" style="padding-top: 30px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="leaves_policy_id_1">View Policy</button>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Address</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="text-dark sf-label">Current Address</label>
                                        <textarea class="form-control requiredField" name="current_address" id="current_address"></textarea>
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="text-dark sf-label">Permanent Address</label>
                                        <textarea class="form-control requiredField" name="permanent_address" id="permanent_address"></textarea>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Bank Account Details</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input type="checkbox" name="bank_account_check" id="bank_account_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="bank_account_area"></div>

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
                                        <input type="checkbox" name="family_data_check" id="family_data_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="family_data_area"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Educational Background</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Start from Recent</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="education_check" id="education_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="education_area"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Work Experience</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">Most recent first</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="work_experience_check" id="work_experience_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="work_experience_area"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Language Proficiency</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input type="checkbox" name="language_check" id="language_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="language_area"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Health Information</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline"> Any disorder regarding</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="health_type_check" id="health_type_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="health_type_area"></div>

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
                                        <input type="checkbox" name="activity_check" id="activity_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="activity_area"></div>

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
                                        <input type="checkbox" name="reference_check" id="reference_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="reference_area"></div>


                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Next Of Kin Details</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="text-decoration: underline;">In Case of Employee Death</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="kins_check" id="kins_check">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="kins_area"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">
                                            Do you have any relatives in this company ?
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input checked type="radio" value="No" name="relative_check" id="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input type="radio" value="Yes" name="relative_check" id="relative_check">
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="relative_area"></div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Have you ever been convicted of a crime ?</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input checked type="radio" value="No" name="crime_check" id="crime_check" class="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input type="radio" value="Yes" name="crime_check" id="crime_check" class="relative_check">
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="crime_check_input"></div>
                                <div class="row">&nbsp;</div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Any Additional Information you wish to provide ?</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>
                                            No : <input checked type="radio" value="No" name="additional_info_check" id="additional_info_check" class="relative_check">
                                            &nbsp;&nbsp;
                                            Yes : <input type="radio" value="Yes" name="additional_info_check" id="additional_info_check" class="relative_check">
                                        </h4>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="additional_info_input"></div>
                                <div class="row">&nbsp;</div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Employee Items</h4>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input type="checkbox" name="employee_items_check" id="employee_items_check" />
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="employee_items_area" style="display: none;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
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
                                                        <td class="text-center"><input type="checkbox" name="equipment_id[]" value="{{ $value->id }} " /></td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
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
                                        <input type="checkbox" name="documents_upload_check" id="documents_upload_check" value="1">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div id="file_upload_area" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: #f5f3ff">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Login Credentials</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="can_login" id="can_login" value="yes">
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" id="credential_area" style="display: none;">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Account type</label>
                                        <select style="width: 100%;" class="form-control" name="account_type" id="account_type">
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Password</label>
                                        <input type="text" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <button type="button" style="margin-top:37px;color:white;" class="icon btn btn-sm btn-warning" onclick="password_generator()" >Generate</button>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Roles</label>
                                        <select style="width: 100%;" class="form-control" name="role_id" id="role_id">
                                            <option value="">Select</option>
                                            @foreach($menu_privileges as $key => $val)
                                                <option value="{{$val->id}}">{{$val->role_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <span id="emp_warning" style="color:red;font-weight:bold;"></span>
                                {{ Form::submit('Submit', ['class' => 'btn btn-success btn-sm btn_disable','id'=>'btn_add']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
    function ipFieldEnableDisable(){
        var ipDistrict = $('#ip_district').val();
        if(ipDistrict == 1){
            $('#ip_number').val('');
            $('#ip_number').attr('readonly', true);
        }else{
            $('#ip_number').attr('readonly', false);
        }
    }
    function setConfirmationDate()
    {
        var employment_status_id = $('#employment_status_id').val();
        var joining_date = $('#joining_date').val();

        if(employment_status_id && joining_date)
        {

            if(employment_status_id == 4)
            {
                $('#confirmation_date').val(joining_date)
            }
            else
            {
            var afterThreeMonth =  new Date(joining_date);
            afterThreeMonth.toLocaleDateString();
            afterThreeMonth.setMonth(afterThreeMonth.getMonth() + 3);
            $('#confirmation_date').val(formatDate(afterThreeMonth.toLocaleDateString()));

            }


        }



    }
    
     $('#can_login').click(function (e)
        {
            if($("#can_login").prop('checked') == true)
            {
                $('#credential_area').fadeIn();
                $("#password").addClass('requiredField');
                $("#role").addClass('requiredField');
            }
            else
            {
                $('#credential_area').fadeOut();
                $("#password").removeClass('requiredField');
                $("#role").removeClass('requiredField');
            }
        });

        function insuranceCheck()
        {
            if ($('.insurance').is( ":checked" )) {
                $('#insurance_number').prop("disabled", false);
                $('#insurance_path').prop("disabled", false);
            }
            else {
                $('#insurance_number').prop("disabled", true);
                $('#insurance_path').prop("disabled", true);
            }

        }

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
            
            $('#location_id').select2();
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

        $('#education_check').click(function(){
            if($(this).is(":checked") == true)
            {
                $("#education_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                        '<input type="hidden" name="education_data[]" value="1"><table class="table table-bordered table-hover"><thead><th class="text-center">S.No</th>' +
                        '<th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>' +
                        '<th class="text-center">Degree / Diploma</th>' +
                        '<th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
                        '</thead><tbody id="insert_clone"><tr class="get_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                        '<td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>' +
                        '<td><input type="hidden" name="qualificationSection[]">' +
                        '<select style="width: 100%" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1"><option value="">Select</option>'+
                        '@foreach($degree_type as $key => $val)<option value="{{ $val->id }}">{{ $val->degree_type_name }}</option>@endforeach<option value="other">Other</option></select><span id="other_option_1"></span></td>'+
                        '<td class="text-center">-</td></tr></tbody></table></div>');


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
                $('#degree_type_1').select2();
            }
            else
            {
                $("#education_area").html('');
            }
        });
        $('#bank_account_check').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#bank_account_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Select Bank</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<select class="form-control requiredField" name="bank_name" id="bank_name"><option value="">Select Bank</option>' +
                        '@foreach($banks as $bank)<option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>@endforeach</select>' +
                        '</div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Account No</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" name="account_no" id="account_no" value="" /></div>'+
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Account Title</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" name="account_title" id="account_title" value="" />' +
                        '</div>');

                $('#bank_name').select2();
            }
            else
            {
                $("#bank_account_area").html('');
            }

        });

        $("#allow_app_attendance").change(function(){
            if($('#allow_app_attendance').is(':checked')){
                $('#allow_app_attendance').val('1');
            }
            else{
                $('#allow_app_attendance').val('0');
            }
        });
        $("#annual_leaves").change(function(){
            if($('#annual_leaves').is(':checked')){
                $('#annual_leaves').val('1');
            }
            else{
                $('#annual_leaves').val('0');
            }
        });

        $("#health_insuarance").change(function(){
            if($('#health_insuarance').is(':checked')){
                $('#health_insuarance').val('1');
            }
            else{
                $('#health_insuarance').val('0');
            }
        });
        $("#group_insuarance").change(function(){
            if($('#group_insuarance').is(':checked')){
                $('#group_insuarance').val('1');
            }
            else{
                $('#group_insuarance').val('0');
            }
        });
        $("#work_remotely").change(function(){
            if($('#work_remotely').is(':checked')){
                $('#work_remotely').val('1');
                $('#salary_percent_div').html('<br><label class="text-dark sf-label">Enter Salary %</label>'
                        +'<span class="rflabelsteric"><strong>*</strong></span>'
                        +'<span class="input-group-prepend">'
                        +'<span class="input-group-text">%</span>'
                        +'<span class="input-group-area" style="">'
                        +'<input type="text" id="salary_percent" name="salary_percent" class="form-control requiredField" maxlength="3" min="1" max="100" />'
                        +'</span> </span>')


            }
            else{
                $('#work_remotely').val('0');
                $('#salary_percent_div').html('');
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
@endsection