<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$emp_name = $employee->emp_name;



$id = $exit_employee_data->id;
$emp_id = $exit_employee_data->emp_id;
$leaving_type = $exit_employee_data->leaving_type;
$last_working_date = $exit_employee_data->last_working_date;
$approval_status = $exit_employee_data->approval_status;
$supervisor_name = $exit_employee_data->supervisor_name;
$signed_by_supervisor = $exit_employee_data->signed_by_supervisor;

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

<div class="container">
    <div class="row text-right">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@if($type != 'log')
				@if(in_array('approve', $operation_rights2))
					@if ($approval_status != 2)
						<button type="button" class="btn btn-primary btn-sm" onclick="approveAndRejectEmployeeExit('<?php echo $m ?>','<?php echo $id;?>', '2', 'employee_exit', '<?php echo $emp_id ?>', '3')"> Approve </button>
					@endif
				@endif
				@if(in_array('reject', $operation_rights2))
					@if ($approval_status != 3)
						<button type="button" class="btn btn-danger btn-sm" onclick="approveAndRejectEmployeeExit('<?php echo $m ?>','<?php echo $id;?>', '3', 'employee_exit', '<?php echo $emp_id ?>', '1')"> Reject </button>
					@endif
				@endif

				@if(in_array('print', $operation_rights2))
					@if ($approval_status == 2)
						<?php echo CommonHelper::displayPrintButtonInBlade('PrintExitClearenceDetail','','1');?>
					@endif
				@endif
			@endif
        </div>
    </div>
</div>
<br>
<div class="container" id="PrintExitClearenceDetail">
    <div class="print-font2">
		<!--<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
				<img src="../assets/img/mima_logo1.png" alt="" class="mima-logo">
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
				<h4 class="text-bold print-font-size2 text-center">EMPLOYEE EXIT CHECKLIST <br>MIMA FACILITY MANAGEMENT</h4>
			</div>
		</div>-->
		<div class="row">&nbsp</div>
		<div class="row war-margin1">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Name: {{ $emp_name }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Job Tille: {{ HrHelper::getMasterTableValueById($m, 'designation', 'designation_name', $designation_id ) }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Date: {{ date('d-m-Y') }} </h5>
			</div>
		</div>
		<hr>
		<div class="row war-margin1">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Emp Id: {{ $emp_id }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Sub Department: {{  HrHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$sub_department_id ) }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Last day of employment: {{ HrHelper::date_format($last_working_date) }}</h5>
			</div>
		</div>
		<hr>

			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<table class="table table-sm mb-0 table-bordered table-striped table-hover warning-mar2" >
				<tbody>
				<tr>
					<td></td>
					<td class="text-center text-bold print-sett">Items</td>
					<td colspan="3" class="text-center text-bold print-sett">Status</td>
					<td class="text-center text-bold print-sett">Remarks (If any)</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td class="text-center text-bold print-sett">Yes</td>
					<td class="text-center text-bold print-sett">No</td>
					<td class="text-center text-bold print-sett">N/A</td>
					<td></td>
				</tr>
				<tr>
					<td class="text-center print-black text-bold print-sett">1</td>
					<td class="print-black text-bold print-sett">HR & Admin</td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
				</tr>

				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Laptop/Other Devices
					</td>
					<td class="text-center print-sett">@if($laptop_other_devices == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($laptop_other_devices == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($laptop_other_devices == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $laptop_other_devices_remarks }}</td>
				</tr>

				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						CR Manuals
					</td>
					<td class="text-center print-sett">@if($cr_manuals == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($cr_manuals == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($cr_manuals == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $cr_manuals_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						CR Reference Books
					</td>
					<td class="text-center print-sett">@if($cr_reference_books == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($cr_reference_books == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($cr_reference_books == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $cr_reference_books_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Other
					</td>
					<td class="text-center print-sett">@if($other == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($other == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($other == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $other_remarks }}</td>
				</tr>


				<tr>
					<td class="text-center print-black text-bold print-sett">2</td>
					<td class="print-black text-bold print-sett">IT & Store</td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
					<td class="print-black print-sett"></td>
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett"> Exit From Attendance System</td>
					<td class="text-center print-sett">@if($exit_from_attendance_system == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($exit_from_attendance_system == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($exit_from_attendance_system == 3) &#10003; @endif</td>
					<td  class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Inactivate "pm.vis.com.pk" Login/Password
					</td>
					<td class="text-center print-sett">@if($inactivate_vis_login == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_vis_login == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_vis_login == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Network Login/Password
					</td>
					<td class="text-center print-sett">@if($network_login == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($network_login == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($network_login == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Inactivate Vis Ratio System Login/Password
					</td>
					<td class="text-center print-sett">@if($inactivate_vis_ratio_login == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_vis_ratio_login == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_vis_ratio_login == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">
						Change Email/Password
					</td>
					<td class="text-center print-sett">@if($change_email_password == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($change_email_password == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($change_email_password == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">f</td>
					<td class="print-sett">
						Omit Name from list of group emails<br>

						<small>Rating.committee@vis.com.pk</small><br>
						<small>rating@vis.com.pk</small><br>
						<small>everyone@vis.com.pk</small>
					</td>
					<td class="text-center print-sett">@if($omit_name == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($omit_name == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($omit_name == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">g</td>
					<td class="print-sett">
						Email have been diverted to the supervisor
					</td>
					<td class="text-center print-sett">@if($email_diverted_to_supervisor == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($email_diverted_to_supervisor == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($email_diverted_to_supervisor == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">h</td>
					<td class="print-sett">
						Remove name from the website
					</td>
					<td class="text-center print-sett">@if($remove_name_from_web == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($remove_name_from_web == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($remove_name_from_web == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">i</td>
					<td class="print-sett">
						Inactivate VISTA Plus login/Password
					</td>
					<td class="text-center print-sett">@if($inactivate_vista_plus_login == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_vista_plus_login == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_vista_plus_login == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">j</td>
					<td class="print-sett">
						Remove from VIS,s whatsapp official group
					</td>
					<td class="text-center print-sett">@if($remove_from_vis_whatsapp == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($remove_from_vis_whatsapp == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($remove_from_vis_whatsapp == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">k</td>
					<td class="print-sett">
						nactivate Own Cloud login/password
					</td>
					<td class="text-center print-sett">@if($inactivate_own_cloud_login == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_own_cloud_login == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($inactivate_own_cloud_login == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>
				<tr>
					<td class="text-center print-sett">l</td>
					<td class="print-sett">
						Leaving Statement
					</td>
					<td class="text-center print-sett">@if($leaving_statement == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($leaving_statement == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($leaving_statement == 3) &#10003; @endif</td>
					<td class="print-sett">--</td>
				</tr>

				<tr>
					<td class="text-center print-black text-bold print-sett">3</td>
					<td class="print-black text-bold print-sett">Finance</td>
					<td class="print-black text-center print-sett"></td>
					<td class="print-black text-center print-sett"></td>
					<td class="print-black text-center print-sett"></td>
					<td class="print-black print-sett"></td>
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Advance/Loan adjusted if any taken by the employee
					</td>
					<td class="text-center print-sett">@if($advance_loan == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($advance_loan == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($advance_loan == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $advance_loan_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Extra Leaves adjusted from final settlement
					</td>
					<td class="text-center print-sett">@if($extra_leaves == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($extra_leaves == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($extra_leaves == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $extra_leaves_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Final settlement processed
					</td>
					<td class="text-center print-sett">@if($final_settlement == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($final_settlement == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($final_settlement == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $final_settlement_remarks }}</td>
				</tr>
				</tbody>
            </table>
			</div>
		</div>

		<div class="row">&nbsp</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">


				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For Supervisor:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For HR Department:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For IT and Store:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For Finance:</b></p></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				</div>
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script>
    $(':radio:not(:checked)').attr('disabled', true);
</script>

