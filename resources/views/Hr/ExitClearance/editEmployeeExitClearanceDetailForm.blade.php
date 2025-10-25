<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

$m 	= Input::get('m');

$supervisor_name = $exit_employee_data->supervisor_name;
$signed_by_supervisor = $exit_employee_data->signed_by_supervisor;
$last_working_date= $exit_employee_data->last_working_date;
$leaving_type=$exit_employee_data->leaving_type;

$room_key=$exit_employee_data->room_key;
$mobile_sim=$exit_employee_data->mobile_sim;
$fuel_card=$exit_employee_data->fuel_card;
$mfm_employee_card=$exit_employee_data->mfm_employee_card;
$client_access_card=$exit_employee_data->client_access_card;
$medical_insurance_card=$exit_employee_data->medical_insurance_card;
$eobi_card=$exit_employee_data->eobi_card;
$biometric_scan=$exit_employee_data->biometric_scan;
$payroll_deduction=$exit_employee_data->payroll_deduction;
$info_sent_to_client=$exit_employee_data->info_sent_to_client;
$client_exit_checklist=$exit_employee_data->client_exit_checklist;
$exit_interview=$exit_employee_data->exit_interview;

$laptop=$exit_employee_data->laptop;
$desktop_computer=$exit_employee_data->desktop_computer;
$email_account_deactivated=$exit_employee_data->email_account_deactivated;
$toolkit_ppe=$exit_employee_data->toolkit_ppe;
$uniform=$exit_employee_data->uniform;

$advance_loan=$exit_employee_data->advance_loan;
$extra_leaves=$exit_employee_data->extra_leaves;
$final_settlement=$exit_employee_data->final_settlement;

$room_key_remarks=$exit_employee_data->room_key_remarks;
$mobile_sim_remarks=$exit_employee_data->mobile_sim_remarks;
$fuel_card_remarks=$exit_employee_data->fuel_card_remarks;
$mfm_employee_card_remarks=$exit_employee_data->mfm_employee_card_remarks;
$client_access_card_remarks=$exit_employee_data->client_access_card_remarks;
$medical_insurance_card_remarks=$exit_employee_data->medical_insurance_card_remarks;
$eobi_card_remarks=$exit_employee_data->eobi_card_remarks;
$biometric_scan_remarks=$exit_employee_data->biometric_scan_remarks;
$payroll_deduction_remarks=$exit_employee_data->payroll_deduction_remarks;
$info_sent_to_client_remarks=$exit_employee_data->info_sent_to_client_remarks;
$client_exit_checklist_remarks=$exit_employee_data->client_exit_checklist_remarks;
$exit_interview_remarks=$exit_employee_data->exit_interview_remarks;

$laptop_remarks=$exit_employee_data->laptop_remarks;
$desktop_computer_remarks=$exit_employee_data->desktop_computer_remarks;
$email_account_deactivated_remarks=$exit_employee_data->email_account_deactivated_remarks;
$toolkit_ppe_remarks=$exit_employee_data->toolkit_ppe_remarks;
$uniform_remarks=$exit_employee_data->uniform_remarks;

$advance_loan_remarks=$exit_employee_data->advance_loan_remarks;
$extra_leaves_remarks=$exit_employee_data->extra_leaves_remarks;
$final_settlement_remarks=$exit_employee_data->final_settlement_remarks;
$note = $exit_employee_data->note;

$laptop_other_devices=$exit_employee_data->laptop_other_devices;
$cr_manuals=$exit_employee_data->cr_manuals;
$other=$exit_employee_data->other;
$cr_reference_books=$exit_employee_data->cr_reference_books;
$laptop_other_devices_remarks=$exit_employee_data->laptop_other_devices_remarks;
$cr_manuals_remarks=$exit_employee_data->cr_manuals_remarks;
$cr_reference_books_remarks=$exit_employee_data->cr_reference_books_remarks;
$other_remarks=$exit_employee_data->other_remarks;
$exit_from_attendance_system=$exit_employee_data->exit_from_attendance_system;
$inactivate_vis_login=$exit_employee_data->inactivate_vis_login;
$network_login=$exit_employee_data->network_login;
$inactivate_vis_ratio_login=$exit_employee_data->inactivate_vis_ratio_login;
$change_email_password=$exit_employee_data->change_email_password;
$omit_name=$exit_employee_data->omit_name;
$email_diverted_to_supervisor=$exit_employee_data->email_diverted_to_supervisor;
$remove_name_from_web=$exit_employee_data->remove_name_from_web;
$inactivate_vista_plus_login=$exit_employee_data->inactivate_vista_plus_login;
$remove_from_vis_whatsapp=$exit_employee_data->remove_from_vis_whatsapp;
$inactivate_own_cloud_login=$exit_employee_data->inactivate_own_cloud_login;
$leaving_statement=$exit_employee_data->leaving_statement;


?>

<style>


    input[type="radio"],[type="checkbox"]{ width:22px;
        height:16px;
    }

    .name-d-d ul li {
        font-size: 17px;
        margin: 10px 0px 22px 0px;
    }

    .name-d-d-input ul li {
        margin: 7px 0px 10px 0px;
    }

    .depart-row .col-lg-3 {
        background-color: #080808;
        color: #fff;
        border-left: 1px solid #fff;
    }

    .depart-row-two .col-lg-4 {
        background-color: #999;
        color: #fff;
        border-left: 1px solid #fff;
        padding: 7px 0px 2px 0px;
    }
    .table-row-heading {
        background-color: #999;
        color: #fff;
        border-left: 1px solid #fff;
    }

</style>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="panel">
                    <div class="panel-body">
                        <form method="post" action="{{url('had/editEmployeeExitClearanceDetail')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="{{ $m }}">
                            <input type="hidden" name="id" id="id" value="{{ $exit_employee_data->id }}">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Emp ID:</label>
                                    <input readonly name="emp_id" id="emp_id" type="text" value="@if(array_key_exists($exit_employee_data->employee_id, $employees)) {{ $employees[$exit_employee_data->employee_id]->emp_id }} @endif" class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Employee Name:</label>
                                    <input readonly name="emp_name" id="emp_name" type="text" value="@if(array_key_exists($exit_employee_data->designation_id, $employees)) {{ $employees[$exit_employee_data->employee_id]->emp_name }} @endif" class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Father/Husband Name:</label>
                                    <input readonly name="father_name" id="father_name" type="text" value="@if(array_key_exists($exit_employee_data->designation_id, $employees)) {{ $employees[$exit_employee_data->employee_id]->emp_father_name }} @endif" class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>CNIC:</label>
                                    <input readonly name="cnic" id="cnic" type="text" value="@if(array_key_exists($exit_employee_data->designation_id, $employees)) {{ $employees[$exit_employee_data->employee_id]->cnic }} @endif" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Department:</label>
                                    <input readonly type="text" value="@if(array_key_exists($exit_employee_data->department_id, $departments)) {{ $departments[$exit_employee_data->department_id]->department_name }} @endif" class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Designation:</label>
                                    <input readonly type="text" value="@if(array_key_exists($exit_employee_data->designation_id, $designations)) {{ $designations[$exit_employee_data->designation_id]->designation_name }} @endif" class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Leaving Reason:</label>
                                    <select id="leaving_type" name="leaving_type" class="form-control">
                                        <option value="1" @if($leaving_type==1)selected @endif>Resignation</option>
                                        <option value="2" @if($leaving_type==2)selected @endif>Retirement</option>
                                        <option value="3" @if($leaving_type==3)selected @endif>Termination</option>
                                        <option value="4" @if($leaving_type==4)selected @endif>Dismissal</option>
                                        <option value="5" @if($leaving_type==5)selected @endif>Demise</option>
                                        <option value="6" @if($leaving_type==6)selected @endif>Contract End:</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Last Working Data:</label>
                                    <input name="last_working_date" id="last_working_date" type="date" value="@if(array_key_exists($exit_employee_data->designation_id, $employees)) {{ $employees[$exit_employee_data->employee_id]->last_working_date }} @endif" class="form-control requiredField"/>
                                </div>
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                    <thead class="table-row-heading">
                                    <tr>
                                        <th scope="col" class="text-center"><h4>Department</h4></th>
                                        <th scope="col" class="text-center"><h4>Verification</h4></th>
                                        <th scope="col" class="text-center"><h4>Status</h4></th>
                                        <th scope="col" class="text-center"><h4>Remarks</h4></th>
                                    </tr>
                                    </thead>

                                    {{--hr and admin department start--}}
                                    <tbody>
                                    <tr>
                                        <td rowspan="5" class="text-center">
                                            <h4>HR & Admin <br/> Department</h4>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            Laptop/Other Devices
                                        </td>
                                        <td class="text-center">
                                            <input @if($laptop_other_devices==1) checked @endif type="radio" name="laptop_other_devices" value="1"> Yes
                                            <input @if($laptop_other_devices==2) checked @endif type="radio" name="laptop_other_devices" value="2"> No <br>
                                            <input @if($laptop_other_devices==3) checked @endif type="radio" name="laptop_other_devices" value="3"> N/A
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" name="laptop_other_devices_remarks"> @if($laptop_other_devices_remarks!='') {{trim($laptop_other_devices_remarks)}} @endif </textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            CR Manuals
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($cr_manuals==1) checked @endif type="radio" name="cr_manuals" value="1"> Yes </b>
                                            <b><input @if($cr_manuals==2) checked @endif type="radio" name="cr_manuals" value="2"> No </b><br>
                                            <b><input @if($cr_manuals==3) checked @endif type="radio" name="cr_manuals" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" name="cr_manuals_remarks"> @if($cr_manuals_remarks!='') {{trim($cr_manuals_remarks)}} @endif </textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            CR Reference Books
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($cr_reference_books==1) checked @endif type="radio" name="cr_reference_books" value="1"> Yes </b>
                                            <b><input @if($cr_reference_books==2) checked @endif type="radio" name="cr_reference_books" value="2"> No </b><br>
                                            <b><input @if($cr_reference_books==3) checked @endif type="radio" name="cr_reference_books" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" name="cr_reference_books_remarks"> @if($cr_reference_books_remarks!='') {{trim($cr_reference_books_remarks)}} @endif </textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            Other
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($other==1) checked @endif type="radio" name="other" value="1"> Yes </b>
                                            <b><input @if($other==2) checked @endif type="radio" name="other" value="2"> No </b><br>
                                            <b><input @if($other==3) checked @endif type="radio" name="other" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" name="other_remarks"> @if($other_remarks!='') {{trim($other_remarks)}} @endif </textarea>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <tbody>
                                    <tr>
                                        <td rowspan="13" class="text-center">
                                            <h4>IT & Store <br/> Department</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            Exit From Attendance System
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($exit_from_attendance_system==1) checked @endif type="radio" name="exit_from_attendance_system" value="1"> Yes </b>
                                            <b><input @if($exit_from_attendance_system==2) checked @endif type="radio" name="exit_from_attendance_system" value="2"> No </b><br>
                                            <b><input @if($exit_from_attendance_system==3) checked @endif type="radio" name="exit_from_attendance_system" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea disabled class="form-control" rows="2" > </textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            Network Login/Password
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($network_login==1) checked @endif type="radio" name="network_login" value="1"> Yes </b>
                                            <b><input @if($network_login==2) checked @endif type="radio" name="network_login" value="2"> No </b><br>
                                            <b><input @if($network_login==3) checked @endif type="radio" name="network_login" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" disabled ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            Remove from list of group Emails / WhatApp groups<br>
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($omit_name==1) checked @endif type="radio" name="omit_name" value="1"> Yes </b>
                                            <b><input @if($omit_name==2) checked @endif type="radio" name="omit_name" value="2"> No </b><br>
                                            <b><input @if($omit_name==3) checked @endif type="radio" name="omit_name" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" disabled ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            Email have been diverted to the supervisor
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($email_diverted_to_supervisor==1) checked @endif type="radio" name="email_diverted_to_supervisor" value="1"> Yes </b>
                                            <b><input @if($email_diverted_to_supervisor==2) checked @endif type="radio" name="email_diverted_to_supervisor" value="2"> No </b><br>
                                            <b><input @if($email_diverted_to_supervisor==3) checked @endif type="radio" name="email_diverted_to_supervisor" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" disabled ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            Leaving Statement
                                        </td>
                                        <td class="text-center">
                                            <b><input @if($leaving_statement==1) checked @endif type="radio" name="leaving_statement" value="1"> Yes </b>
                                            <b><input @if($leaving_statement==2) checked @endif type="radio" name="leaving_statement" value="2"> No </b><br>
                                            <b><input @if($leaving_statement==3) checked @endif type="radio" name="leaving_statement" value="3"> N/A </b>
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" disabled ></textarea>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <tbody>
                                    <tr>
                                        <td rowspan="4" class="text-center">
                                            <h4>Finance <br/> Department</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            Advance/Loan adjusted if any taken by the employee
                                        </td>
                                        <td class="text-center">
                                            <input @if($advance_loan==1) checked @endif type="radio" name="advance_loan" value="1"> Yes
                                            <input @if($advance_loan==2) checked @endif type="radio" name="advance_loan" value="2"> No
                                            <input @if($advance_loan==3) checked @endif type="radio" name="advance_loan" value="3"> N/A
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" name="advance_loan_remarks"> @if($advance_loan_remarks!='') {{trim($advance_loan_remarks)}} @endif </textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            Extra Leaves adjusted from final settlement
                                        </td>
                                        <td class="text-center">
                                            <input @if($extra_leaves==1) checked @endif type="radio" name="extra_leaves" value="1"> Yes
                                            <input @if($extra_leaves==2) checked @endif type="radio" name="extra_leaves" value="2"> No
                                            <input @if($extra_leaves==3) checked @endif type="radio" name="extra_leaves" value="3"> N/A
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" name="extra_leaves_remarks"> @if($extra_leaves_remarks!='') {{trim($extra_leaves_remarks)}} @endif </textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            Final settlement processed
                                        </td>
                                        <td class="text-center">
                                            <input @if($final_settlement==1) checked @endif type="radio" name="final_settlement" value="1"> Yes
                                            <input @if($final_settlement==2) checked @endif type="radio" name="final_settlement" value="2"> No
                                            <input @if($final_settlement==3) checked @endif type="radio" name="final_settlement" value="3"> N/A
                                        </td>
                                        <td class="text-center">
                                            <textarea class="form-control" rows="2" name="final_settlement_remarks"> @if($final_settlement_remarks!='') {{trim($final_settlement_remarks)}} @endif </textarea>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <label for=""> Note :</label><br>
                                <textarea style="max-width: 100%" class="form-control" name="note" id="note" >@if($note!='') {{ $note }} @endif</textarea>
                            </div>

                            <br>
                            <div style="float: right;">
                                <button style="text-align: center" class="btn btn-sm btn-success" type="submit" value="Submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>