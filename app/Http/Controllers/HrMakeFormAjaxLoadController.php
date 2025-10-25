<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\EmployeeLeavingReason;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Models\LeavesPolicy;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\JobType;
use App\Models\MaritalStatus;
use App\Models\Countries;
use App\Models\Institute;
use App\Models\Designation;
use App\Models\Qualification;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\Employee;
class HrMakeFormAjaxLoadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

	public function makeFormHealthInsuranceDetail(){
		$_GET['id'];
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="healthInsuranceSection[]" class="form-control" id="healthInsuranceSection" value="<?php echo $_GET['id']?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label>Health Insurance Name:</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<input type="text" name="healthInsurance_name_<?php echo $_GET['id']?>" id="healthInsurance_name_<?php echo $_GET['id']?>" value="" class="form-control requiredField" />
			</div>
		</div>
	<?php
	}

	public function makeFormEmployeeCategoryDetail(){
		$_GET['id'];
	?>
		<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="EmployeeCategorySection[]" class="form-control" id="EmployeeCategorySection" value="1" />
            </div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Employee Category Name:</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<input type="text" name="emp_category_name_<?php echo $_GET['id']?>" id="emp_category_name_<?php echo $_GET['id']?>" value="" class="form-control requiredField" />
			</div>
		</div>
	<?php
	}

	public function makeFormQualificationDetail(){

		$countries = Countries::where('status', '=', 1)->get();
        $institutes = Institute::where('status', '=', 1)->get();
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="qualificationSection[]" class="form-control" id="qualificationSection" value="<?php echo $_GET['id']?>" />
			</div>		
		</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Institute Name:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select name="institute_name_1" class="form-control requiredField">
                    <?php foreach($institutes as $key => $i):?>
                    <option value="<?= $i->id ?>"><?= $i->institute_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Qualification Name:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="qualification_name_1" id="qualification_name_1" placeholder="Qualification Name" value="" class="form-control requiredField" />
            </div>

        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Country</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select name="country_1" id="country_1" class="form-control requiredField" onchange="changeState(this.id)">
                    <option value="">Select Country</option>
                    <?php foreach($countries as $key => $y):?>
                    <option value="<?= $y->id ?>"><?= $y->nicename ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>State</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select name="state_1" id="state_1" class="form-control requiredField" onchange="changeCity(this.id)">
                    <option value="">Select State</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>City</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select name="city_1" id="city_1" class="form-control requiredField">
                    <option value="">Select City</option>
                </select>
            </div>
        </div>
	<?php
	}


	public function makeFormLeaveTypeDetail(){
		$_GET['id'];
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="leaveTypeSection[]" class="form-control" id="leaveTypeSection" value="<?php echo $_GET['id']?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label>Leave Type Name:</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<input type="text" name="leave_type_name_<?php echo $_GET['id']?>" id="leave_type_name_<?php echo $_GET['id']?>" value="" class="form-control requiredField" />
			</div>
		</div>
	<?php
	}


	public function makeFormAdvanceTypeDetail(){
		$_GET['id'];
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="advanceTypeSection[]" class="form-control" id="advanceTypeSection" value="<?php echo $_GET['id']?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label>Advance Type Name:</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<input type="text" name="advance_type_name_<?php echo $_GET['id']?>" id="advance_type_name_<?php echo $_GET['id']?>" value="" class="form-control requiredField" />
			</div>
		</div>
	<?php
	}

	public function makeFormShiftTypeDetail(){
		$_GET['id'];
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="shiftTypeSection[]" class="form-control" id="shiftTypeSection" value="<?php echo $_GET['id']?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label>Shift Type Name:</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<input type="text" name="shift_type_name_<?php echo $_GET['id']?>" id="shift_type_name_<?php echo $_GET['id']?>" value="" class="form-control requiredField" />
			</div>
		</div>
	<?php
	}
	

	


	public function makeFormEmployeeDetail(){

        $subdepartments = new SubDepartment;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leaves_policy = LeavesPolicy::where([['status','=','1']])->get();
        CommonHelper::reconnectMasterDatabase();
        $jobtype = JobType::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();;
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();
        $qualification = Qualification::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();
        $eobi = Eobi::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $tax= Tax::select('id','tax_name')->where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="<?php echo Input::get('id');?>" />
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">Employee Name</label>
			<span class="rflabelsteric"><strong>*</strong></span>
			<input type="text" class="form-control requiredField" placeholder="Employee Name" name="employee_name_<?php echo Input::get('id');?>" id="employee_name_<?php echo Input::get('id');?>" value="" />
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">Father Name</label>
			<span class="rflabelsteric"><strong>*</strong></span>
			<input type="text" class="form-control requiredField" placeholder="Father Name" name="father_name_<?php echo Input::get('id');?>" id="father_name_<?php echo Input::get('id');?>" value="" />
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">Department / Sub Department</label>
			<select class="form-control requiredField" name="sub_department_id_<?php echo Input::get('id');?>" id="sub_department_id_<?php echo Input::get('id');?>">

				<?php foreach($departments as $row)
				{?>
					<optgroup label="<?php echo $row['department_name'];?>">
					<?php
					$subdepartments = new SubDepartment;
					$subdepartments = $subdepartments::where([['company_id', '=', $_GET['m']], ['department_id', '=', $row['id']], ])->orderBy('id')->get();
					foreach( $subdepartments  as $val )
					{?>
						<option value="<?= $val['id'] ?>"><?= $val['sub_department_name'] ?></option>
			<?php   }
				}
			?>
				</optgroup>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">Date of Birth</label>
			<span class="rflabelsteric"><strong>*</strong></span>
			<input type="date" class="form-control requiredField" placeholder="Date of Birth" name="date_of_birth_<?php echo Input::get('id');?>" id="date_of_birth_<?php echo Input::get('id');?>" value="" />
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">Joining Date</label>
			<span class="rflabelsteric"><strong>*</strong></span>
			<input type="date" class="form-control requiredField" placeholder="Joining Date" name="joining_date_<?php echo Input::get('id');?>" id="joining_date_<?php echo Input::get('id');?>" value="" />
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">Gender</label>
			<span class="rflabelsteric"><strong>*</strong></span>
			<select class="form-control requiredField" name="gender_<?php echo Input::get('id');?>" id="gender_<?php echo Input::get('id');?>">
				<option value="">Select Gender</option>
				<option value="1">Male</option>
				<option value="2">Female</option>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">CNIC</label>
			<span class="rflabelsteric"><strong>*</strong></span>
			<input type="text" class="form-control requiredField" placeholder="CNIC Number" name="cnic_<?php echo Input::get('id');?>" id="cnic_<?php echo Input::get('id');?>" value="" />
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="sf-label">Cell No/Mobile No</label>
			<span class="rflabelsteric"><strong>*</strong></span>
			<input type="text" class="form-control requiredField" placeholder="Cell No/Mobile No" name="contact_no_<?php echo $_GET['id']?>" id="contact_no_<?php echo $_GET['id']?>" value="" />
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label class="sf-label">Employment Status</label>
				<span class="rflabelsteric"><strong>*</strong></span>

				<select class="form-control requiredField" name="employee_status_<?php echo Input::get('id');?>" id="employee_status_<?php echo Input::get('id');?>">
				<option value="">Select Employment Status</option>
				<?php 	foreach($jobtype as $key3 => $value): ?>
				<option value="<?= $value->id ?>"><?php echo $value->job_type_name; ?></option>
				<?php 	endforeach; ?>
                </select>
		</div>
	</div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Email</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" class="form-control requiredField" placeholder="Email Address" name="email_<?php echo Input::get('id');?>" id="email_<?php echo Input::get('id');?>" value="" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Salary</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" class="form-control requiredField" placeholder="Salary" name="salary_<?php echo Input::get('id');?>" id="salary_<?php echo $_GET['id']?>" value="" />
            </div>

        </div>
	<div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label class="sf-label">Marital Status</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <select class="form-control requiredField" name="marital_status_<?php echo Input::get('id');?>" id="marital_status_<?php echo Input::get('id');?>">

                <option value="">Select Marital</option>
                <?php foreach($marital_status as $key4 => $value2):?>
                    <option value="<?= $value2->id ?>"><?= $value2->marital_status_name ?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label class="sf-label">Designation</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <select class="form-control requiredField" name="designation_<?php echo Input::get('id');?>">
                <option value="">Select</option>
                <?php foreach($designation as $key5 => $value5):?>
                    <option value="<?= $value5->id ?>"><?= $value5->designation_name ?></option>
                <?php endforeach; ?>
            </select>
        </div>

	</div>
        <div class="row">

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Qualification</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="qualification_<?php echo Input::get('id');?>">
                    <option value="">Select</option>
                    <?php foreach($qualification as $key6 => $value6):?>
                    <option value="<?= $value6->id; ?>"><?= $value6->qualification_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Eobi</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="eobi_id_<?php echo Input::get('id')?>" id="eobi_id_<?php echo Input::get('id')?>">
                    <option value="0">--</option>
                    <?php foreach($eobi as $value8): ?>
                    <option value="<?= $value8->id ?>"><?= $value8->EOBI_name?>
                        &nbsp;&nbsp;
                        &nbsp;&nbsp;
                        (<?=$value8->month_year?>)
                        &nbsp;&nbsp;
                        &nbsp;&nbsp;
                        Amount=(<?=$value8->EOBI_amount ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label style="cursor:pointer">
					<input type="file" id="file_<?php echo Input::get('id');?>" name="fileToUpload_<?php echo Input::get('id');?>" accept="image/*" capture style="display:none"/>
					<img style="width: 50px;height: 50px;" src="<?= url('assets/img/cam.png') ?>" id="img_file_<?php echo Input::get('id');?>" style="cursor:pointer" />
					Select Image (Optional)
				</label>
				<br>
				<span style="color:red">Please upload a valid image file</span>
			</div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <label class="sf-label">Tax</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <select class="form-control requiredField" name="tax_id_<?php echo Input::get('id')?>" id="tax_id_<?php echo Input::get('id')?>">
                        <option value="0">--</option>
                        <?php foreach($tax as $value7):?>
                        <option value="<?= $value7->id ?>"><?= $value7->tax_name ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding-top: 35px;">
                    <button type="button" class="btn-xs btn-success" id="view_tax_<?php echo Input::get('id')?>">View Tax</button>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <label class="sf-label">Leaves Policy</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <select class="form-control requiredField" name="leaves_policy_<?php echo Input::get('id')?>" id="leaves_policy_<?php echo Input::get('id')?>">
                        <option value="">Select</option>
                        <?php foreach($leaves_policy as $key4 => $value3): ?>
                        <option value="<?= $value3->id ?>"><?= $value3->leaves_policy_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding-top: 35px;">
                    <button type="button" class="btn-xs btn-success" id="leaves_policy_id_<?php echo Input::get('id')?>">View Policy</button>
                </div>
            </div>
        </div>
		<div class="row">&nbsp;</div>
		<div class="row" style="background-color: gainsboro">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<h4 style="text-decoration: underline;">Login Credentials</h4>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<h4 style="text-decoration: underline;">Can Login ? :
					<input type="checkbox" name="can_login_<?php echo Input::get('id')?>" id="can_login_<?php echo Input::get('id')?>" value="yes">
				</h4>
			</div>
		</div>
		<div class="row" id="credential_area_<?php echo Input::get('id')?>" style="display: none;">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label class="sf-label">Account type</label>
				<select class="form-control" name="account_type_<?php echo Input::get('id')?>">
					<option value="user">User</option>
					<option value="client">Client</option>
					<option value="company">Company</option>
					<option value="master">Master</option>
				</select>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<label class="sf-label">Password</label>
				<input type="text" class="form-control" name="password_<?php echo Input::get('id')?>">
			</div>
		</div>

        <script>
            $(document).ready(function() {
                var row = "<?php echo Input::get('id')?>";
                $('#leaves_policy_id_'+row+'').click(function (e) {
                    var leaves_policy_id =  $('#leaves_policy_'+row+'').val();
                    if (leaves_policy_id != '') {

                        showDetailModelTwoParamerter('hdc/viewLeavePolicyDetail', leaves_policy_id, 'View Leaves Policy Detail ', '<?=Input::get('m')?>');
                    }
                    else {
                        alert('Please Select Policy !');
                    }
                });
            });
        </script>
        <script>

            $(document).ready(function() {
                var row = "<?php echo Input::get('id')?>";
                 $('#view_tax_'+row+'').click(function (e) {
                    var tax_id =  $('#tax_id_'+row+'').val();
                   
                    if (tax_id != '') {

                        showDetailModelTwoParamerterJson('hdc/viewTax', tax_id, 'View Tax Detail ', '<?=Input::get('m')?>');
                    }
                    else {
                        alert('Please Select Policy !');
                    }
                });


				$('#can_login_'+row+'').click(function (e)
				{
					if($("#can_login_"+row+'').prop('checked') == true)
					{
						$('#credential_area_'+row+'').fadeIn();
					}
					else
					{
						$('#credential_area_'+row+'').fadeOut();
					}

				})

				$("#file_"+row+'').change(function(){
					readURL(this);
				});

				function readURL(input) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();

						reader.onload = function (e) {
							$('#img_file_'+row+'').attr('src', e.target.result);
						}

						reader.readAsDataURL(input.files[0]);
					}
				}

            });


        </script>
	<?php
	}
	
	public function addMoreAllowancesDetailRows(){
		Config::set(['database.connections.tenant.database' => Auth::user()->dbName]);
		Config::set(['database.connections.tenant.username' => 'root']);
		Config::set('database.default', 'tenant');
		DB::reconnect('tenant');
		$_GET['id'];
		$_GET['counter'];
	?>
		<tr id="trAllowanceRow_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>">
			<td>
				<input type="hidden" name="allowancesDataSection_<?php echo $_GET['id'];?>[]" class="form-control" id="allowancesDataSection_<?php echo $_GET['id'];?>" value="<?php echo $_GET['counter'];?>" />
				<input type="text" name="allowances_type_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" id="allowances_type_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" value="--" placeholder="Allowance Type" class="form-control countrow" />
			</td>
			<td>
				<input type="number" name="allowances_amount_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" id="allowances_amount_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" value="0" placeholder="Allowance Amount" class="form-control" />
			</td>
			<td class="text-center">
				<a href="#" onclick="removeAllowancesDetailRow(<?php echo $_GET['id'];?>,<?php echo $_GET['counter']?>)" class="btn btn-xs btn-danger">Remove</a>
			</td>
		</tr>
	<?php
	}
	
	
	public function addMoreDeductionsDetailRows(){
		Config::set(['database.connections.tenant.database' => Auth::user()->dbName]);
		Config::set(['database.connections.tenant.username' => 'root']);
		Config::set('database.default', 'tenant');
		DB::reconnect('tenant');
		$_GET['id'];
		$_GET['counter'];
	?>
		<tr id="trDeductionRow_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>">
			<td>
				<input type="hidden" name="deductionsDataSection_<?php echo $_GET['id'];?>[]" class="form-control" id="deductionsDataSection_<?php echo $_GET['id'];?>" value="<?php echo $_GET['counter'];?>" />
				<input type="text" name="deductions_type_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" id="deductions_type_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" value="--" placeholder="Deduction Type" class="form-control countrow2" />
			</td>
			<td>
				<input type="number" name="deductions_amount_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" id="deductions_amount_<?php echo $_GET['id'];?>_<?php echo $_GET['counter']?>" value="0" placeholder="Deduction Amount" class="form-control" />
			</td>
			<td class="text-center">
				<a href="#" onclick="removeDeductionsDetailRow(<?php echo $_GET['id'];?>,<?php echo $_GET['counter']?>)" class="btn btn-xs btn-danger">Remove</a>
			</td>
		</tr>
	<?php
	}
	 

	public function makeFormMaritalStatusDetail(){?>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="hidden" name="martitalStatusSection[]" class="form-control" id="martitalStatusSection" value="<?php echo $_GET['id']?>" />
				</div>		
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label>Martital Status Name</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="text" name="marital_status_name_<?php echo $_GET['id']?>" id="marital_status_name_<?php echo $_GET['id']?>" value="" class="form-control requiredField" />
				</div>
			</div>
	<?php }

	public function makeFormLoanRequestDetail()
    {
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();

    ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label class="sf-label">Regions:</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <select class="form-control requiredField" name="region_id" id="region_id">
                        <option value="">Select Region</option>
                        @foreach($employee_regions as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label class="sf-label">Category:</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <select class="form-control requiredField" name="emp_category_id" id="emp_category_id">
                        <option value="">Select Category</option>
                        @foreach($employee_category as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label class="sf-label">Employee:</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <select class="form-control requiredField" name="emp_id[]" id="emp_id<?= Input::get('count_rows')?>" required>
                        <option value="">-</option>
                    </select>
                    <div id="run_loader"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label class="sf-label">Needed on Month & Year:</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <input type="month" name="needed_on_date[]" id="needed_on_date_<?= Input::get('count_rows')?>" value="" class="form-control requiredField count_rows" required />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label class="sf-label">Loan Amount</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <input type="number" name="loan_amount[]" id="loan_amount<?= Input::get('count_rows')?>" value="" class="form-control requiredField count_rows" required />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label class="sf-label">Per Month Deduction</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <input type="number" name="per_month_deduction[]" id="per_month_deduction<?= Input::get('count_rows')?>" value="" class="form-control requiredField count_rows" required />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label class="sf-label">Loan Description</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <textarea required name="loan_description[]" class="form-control" id="loan_description_<?= Input::get('count_rows')?>"></textarea>
                </div>
            </div>
        <script>

            $(function(){
                $('#sub_department_id_<?= Input::get('count_rows')?>').on('change', function() {
                    var sub_department_id = $(this).val();
                    var m = '<?= Input::get('m'); ?>';
                    if(sub_department_id) {
                        $.ajax({
                            url: '<?php echo url('/')?>/slal/employeeLoadDependentDepartmentID',
                            type: "GET",
                            data: { sub_department_id:sub_department_id,m:m},
                            success:function(data) {
                                $('#employee_id_<?= Input::get('count_rows')?>').empty();
                                $('#employee_id_<?= Input::get('count_rows')?>').html(data);
                                $('#employee_id_<?= Input::get('count_rows')?>').find('option').get(0).remove();


                            }
                        });
                    }else{
                        $('#employee_id_<?= Input::get('count_rows')?>').empty();
                    }
                });
            });

        </script>

    <?php }

    public function makeFormEOBIDetail()
    {?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="EOBISection[]" class="form-control" id="sectionEOBI" value="1" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>EOBI Name:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="EOBI_name[]" id="EOBI_name[]" value="" class="form-control requiredField" />
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>EOBI Amount:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="EOBI_amount[]" id="EOBI_amount[]" value="" class="form-control requiredField" />
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Month & Year:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="month" name="month_year[]" id="month_year[]" value="" class="form-control requiredField" />
            </div>
        </div>
    <?php }


    public function makeFormTaxesDetail()
    {?>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="TaxesSection[]" class="form-control" id="sectionEOBI" value="1" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Tax Name:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="tax_name[]" id="tax_name[]" value="" class="form-control requiredField" />
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Salary Range From:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="salary_range_from[]" id="salary_range_from[]" value="" class="form-control requiredField" />
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Salary Range To:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="salary_range_to[]" id="salary_range_to[]" value="" class="form-control requiredField" />
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Tax Percent (%):</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="tax_percent[]" id="tax_percent[]" value="" class="form-control requiredField" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Tax Month & Year :</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="month" name="tax_month_year[]" id="tax_month_year[]" value="" class="form-control requiredField" />
            </div>
        </div>
    <?php
    }

    public function makeFormBonusDetail()
    {?>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Bonus Name:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="Bonus_name[]" id="EOBI_name[]" value="" class="form-control requiredField" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>% of Salary:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="percent_of_salary[]" id="EOBI_amount[]" value="" class="form-control requiredField" />
            </div>

        </div>
    <?php
    }

    public function makeFormEmployeeLeaveApplicationDetailByEmployeeId(){
        $m = $_GET['m'];
        $employeeId = $_GET['employeeId'];
        //$employeeFileNo = $_GET['employeeFileNo'];
        //$employeeNo = $_GET['employeeNo'];
        //$employeeName = $_GET['employeeName'];
	    CommonHelper::companyDatabaseConnection(Input::get('m'));

        $leaves_policy = DB::table('employee')
            ->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*','leaves_data.*')
            ->where([['employee.id','=',$employeeId]])
            ->get();


        $total_leaves = DB::table("leaves_data")
            ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
            ->where([['leaves_policy_id' ,'=', $leaves_policy[0]->leaves_policy_id]])
            ->first();

        $taken_leaves = DB::table("leave_application_data")
            ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.emp_id' ,'=', $employeeId]])
            ->first();

        $emp_data = Employee::select('emp_name','designation_id')->where([['id','=',$employeeId]])->orderBy('id')->first();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.makeFormEmployeeLeaveApplicationDetailByEmployeeId',compact('leaves_policy','emp_data','total_leaves','taken_leaves'));
    }


    public function makeFormEmployeeInActive()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $empLeavingReason = EmployeeLeavingReason::where([['employee_id','=',Input::get('id')]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.makeFormEmployeeInActive',compact('empLeavingReason'));
    }
}