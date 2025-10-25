<?php 
    use App\Models\Employee;
	use App\Models\SubDepartment;
	use App\Models\EmployeeProjects;
    use App\Models\EmployeeEducationalData;
    use App\Helpers\CommonHelper;
    use App\Helpers\HrHelper;
    use App\Models\EmployeePromotion;
	use App\Models\Policies;
	
    $counter = 1;
    $countersss = 1;
    $counting = 0;
	$countUsedLeavess = 0;
	$countRemainingLeaves = 0;
    $salarys = array();
	$current_date = date('Y-m-d');
	$currentMonthStartDate = date('Y-m-01');
	$currentMonthEndDate   = date('Y-m-t');
	$m = Input::get('m');
	
	
	CommonHelper::companyDatabaseConnection(Input::get('m'));
    $employee = Employee::where([['emp_id','=',Input::get('emp_id')],['status','=',1]]);
	$team_lead_exists = Employee::where([['reporting_manager','=',Input::get('emp_id')],['status','=',1]]);

    $employeeName = Employee::where([['emp_id','=',$employee->value('reporting_manager')],['status','=',1],['reporting_manager','!=','0']]);
    $employee_education = EmployeeEducationalData::where([['emp_id','=',Input::get('emp_id')],['status','=',1]])->get();
	
	 if($employeeName->value('emp_name') != ''){
		  $reporting_manager = $employeeName->value('emp_name');
	  }
	  else{
		  $reporting_manager = '--';
	  }

    $EmployeePromotion = EmployeePromotion::where([['emp_id','=',Input::get('emp_id')],['status','=',1]])->orderBy('emp_id')->orderBy('id','desc');
    CommonHelper::reconnectMasterDatabase();

?>
					<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-10"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><!--left col-->
                                        <div class="text-center">
                                            <?php if($employee->value('img_path') != ''){ ?>
                                            <img src="<?php echo Storage::url($employee->value('img_path')) ?>" class="avatar img-circle img-thumbnail" alt="avatar">
                                            <?php } else{ ?>
                                            <img id="img_file_1" class="img-circle" src="<?= Storage::url('app/uploads/employee_images/user-dummy.png')?>">
                                            <?php } ?>
                                            <h1 id="username"><?php echo $employee->value('emp_name') ?></h1>
                                        </div>
                                       <br>
                                        <ul class="list-group">
                                            <li class="list-group-item text-right"><span class="pull-left"><strong>Emp ID</strong></span> <?php echo $employee->value('emp_id') ?></li>
                                            <li class="list-group-item text-right"><span class="pull-left" ><strong>Name</strong></span><p id="name_1"><?php echo $employee->value('emp_name') ?></p></li>
                                            <li class="list-group-item text-right"><span class="pull-left"><strong>Department</strong></span> <?php echo HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$employee->value('emp_sub_department_id')); ?></li>
                                            <li class="list-group-item text-right"><span class="pull-left"><strong>Designation</strong></span> <?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee->value('designation_id'))?></li>
                                            <li class="list-group-item text-right"><span class="pull-left"><strong>Reporting Manager</strong></span> <?php echo $reporting_manager ?></li>
                                            <li class="list-group-item text-right"><span class="pull-left"><strong>Cell #</strong></span><p id="cell_1"><?php echo $employee->value('emp_contact_no') ?></p></li>
                                            <li class="list-group-item"><span class="pull-left" ><strong>Official Email</strong></span><br><p id="p_email_1"><?php echo $employee->value('professional_email') ?></p></li>
                                            <li class="list-group-item text-right"><span class="pull-left"><strong>DOJ #</strong></span> <?php echo  date("d-m-Y", strtotime($employee->value('emp_joining_date'))); ?></li>
                                            <li class="list-group-item text-right" ><span class="pull-left" id><strong>DOB</strong></span><p id="dob_1"><?php echo  date("d-m-Y", strtotime($employee->value('emp_date_of_birth'))) ?></p></li>
											@if($team_lead_exists->exists())
											 <li class="list-group-item"><strong>Team Member</strong>
												<ol>
												@foreach($team_lead_exists->get() as $value)
												  <li>{{$value->emp_name}}</li>

												@endforeach
												</ul>
											  </ol>
											@endif
                                        </ul>
                                    </div><!--/col-3-->
                                    <div class="col-sm-9">
                                        <img src="http://innovative-net.com/assets/img/logo.png" style="width: 99%;height: 290px;" alt="">
                                    </div>

                                    <div class="col-sm-9">
                                        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
                                            <div class="btn-group" role="group">
                                                <button type="button" id="" class="btn btn-primary" href="#basic_info" data-toggle="tab"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                                    <div class="hidden-xs">Basic Info</div>
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" id="" class="btn btn-default" href="#Leavessss" data-toggle="tab"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                                                    <div class="hidden-xs">Leaves</div>
                                                </button>
                                            </div>

											<div class="btn-group" role="group">
                                                <button type="button" id="" class="btn btn-default" href="#pendingLeaves" data-toggle="tab"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                                                    <div class="hidden-xs">Pending Leaves</div>
                                                </button>
                                            </div>

                                            <div class="btn-group" role="group">
                                                <button type="button" id="" class="btn btn-default" href="#Educations" data-toggle="tab"><span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                                    <div class="hidden-xs">Education</div>
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" id="favorites" class="btn btn-default" href="#JobHistory" data-toggle="tab"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                                                    <div class="hidden-xs">Job History</div>
                                                </button>
                                            </div>
                                             <div class="btn-group" role="group">
                                                <button type="button" id="favorites" class="btn btn-default" href="#policies" data-toggle="tab"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                                                    <div class="hidden-xs">Policies / Forms</div>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="basic_info">

                                                <h2>Basic Info</h2>
                                                <p class="bg-success text-center" id="response"></p>
                                                <p class="p-3 mb-2 bg-danger text-white text-center"  id="error_cnic"></p>
                                                <form class="form" id="basic_info_form">
                                                    <div id="ajax_form"></div>
                                                    <div id="without_ajax_form">
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <label for="name"><h4>Name</h4></label>
                                                                <input type="text" class="form-control requiredField" name="name" id="name" placeholder="Name" value="<?php echo $employee->value('emp_name') ?>" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <label for="p_email"><h4>Personal Email</h4></label>
                                                                <input type="email" class="form-control requiredField" name="p_email" id="p_email" placeholder="Personal Email" value="<?php echo $employee->value('professional_email') ?>">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">

                                                            <div class="col-xs-6">
                                                                <label for="cnic"><h4>Cnic</h4></label>
                                                                <input type="text" class="form-control requiredField" name="cnic" id="cnic" placeholder="Cnic" value="<?php echo $employee->value('emp_cnic') ?>" >
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <label for="phone"><h4>Phone</h4></label>
                                                                <input type="number" class="form-control requiredField" name="phone" id="phone" placeholder="enter mobile number" value="<?php echo $employee->value('emp_contact_no') ?>" >
                                                            </div>
                                                        </div>

                                                        <div class="form-group">

                                                            <div class="col-xs-6">
                                                                <label for="email"><h4>Dob</h4></label>
                                                                <input type="date" class="form-control" name="dob" id="dob" placeholder="DOB" value="<?php echo $employee->value('emp_date_of_birth') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <br>
                                                                <button type="submit" class="btn btn-lg btn-success" id="btn_check" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Loading...">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                <hr>
                                            </div>
                                            <div class="tab-pane" id="Leavessss">


                                                    <?php
                                                    if($WithoutLeavePolicy[0] == 'Select Leave Policy'){
                                                    ?>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="alert alert-warning">
                                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                            </button>
                                                            <span class="glyphicon glyphicon-record"></span> <strong>Warning Message</strong>
                                                            <hr class="message-inner-separator">
                                                            <p>
                                                                Please Select Leave Policy.</p>
                                                        </div>
                                                    </div>
                                                    <?php

                                                    }
                                                    else{
                                                    ?>

                                                <div class="lineHeight">&nbsp;</div>
                                                <?php echo Form::open(array('url' => 'had/addTaxesDetail','id'=>'EOBIform'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>EMP ID:</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="text" name="emp_id" id="emp_id" value="{{ $emp_data->emp_id }}" disabled class="form-control requiredField" />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Employee Name:</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="text" name="" id="" value="<?= $emp_data->emp_name ?>" disabled class="form-control requiredField" />
                                                            </div>

                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Department:</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="text" name="" id="" value="{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$emp_data->emp_sub_department_id)}}" disabled class="form-control requiredField" />
                                                            </div>

                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Designation:</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="text" name="" id="" value="{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$emp_data->designation_id)}}" disabled class="form-control requiredField" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Joining Date:</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="text" name="" id="" value="<?= $emp_data->emp_joining_date ?>" disabled class="form-control requiredField" />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Contact No:</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="number" name="" id="" value="<?= $emp_data->emp_contact_no ?>" disabled class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="row">&nbsp;</div>
                                                        <div class="row">
                                                            <table class="table table-bordered sf-table-list">
                                                                <thead>
                                                                <tr>
                                                                    <th style="background-color: rgb(42, 110, 207);padding: 2px;">
                                                                        <div style="">
                                                                            <div style="display:block;float:left;width:50%; margin-top: 7px;">&nbsp;&nbsp;<span style="color:white;">LEAVES BALANCE</span></div>
                                                                            <div class="text-right">
                                                                                <?php
                                                                                $total_leaves = $total_leaves->total_leaves;
                                                                                $taken_leaves = $taken_leaves->taken_leaves;?>
                                                                                <span class="btn btn-success btn-sm" style="cursor: default">Taken Leaves = <?= ($taken_leaves == '')? '0': $taken_leaves ?></span>
                                                                                <span class="btn btn-danger btn-sm" style="cursor: default">Remaining Leaves= <?=($total_leaves-$taken_leaves)?></span>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                            </table>
                                                            <table class="table table-bordered sf-table-list">
                                                                <thead>
                                                                <tr>
                                                                    <th>S No#</th>
                                                                    <th>Leaves Name</th>
                                                                    <th class="text-center">No of leaves</th>
                                                                    <th class="text-center">Used</th>
                                                                    <th class="text-center">Remaining</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $count =0 ;
                                                                $count_leaves = '0';
                                                                ?>
                                                                @foreach($leaves_policy as $val)
                                                                    <?php
                                                                    $count_leaves+=$val->no_of_leaves ;
                                                                    $count++;
                                                                    ?>
                                                                    <tr>
                                                                        <td style="color: green;"><b>{{ $count }}</b></td>
                                                                        <td style="color: green;"><b>{{ HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )}}</b></td>
                                                                        <td class="text-center" style="color: green;"><b>
                                                                                <?php

                                                                                if($val->leave_type_id == 1):
                                                                                    echo $val->no_of_leaves;
                                                                                elseif($val->leave_type_id == 3):
                                                                                    echo $val->no_of_leaves;
                                                                                else:
                                                                                    echo $val->no_of_leaves;
                                                                                endif;
                                                                                ?>
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php

                                                                            //CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                                            $getUsedLeaves =DB::table('leave_application_data')
                                                                                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                                                ->where([['leave_application.emp_id','=',$emp_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
                                                                                    ['leave_application.status', '=', '1'],
                                                                                    ['leave_application.approval_status_lm', '=', '2'],
                                                                                    ['leave_application.approval_status', '=', '2']])
                                                                                ->sum('no_of_days');
                                                                            $countUsedLeavess +=$getUsedLeaves;
                                                                            echo $getUsedLeaves;
                                                                            //CommonHelper::reconnectMasterDatabase();

                                                                            ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php
                                                                            if($val->leave_type_id == 1):
                                                                                $remaining = $val->no_of_leaves-$getUsedLeaves;

                                                                            elseif($val->leave_type_id == 3):
                                                                                $remaining = $val->no_of_leaves-$getUsedLeaves;
                                                                            else:
                                                                                $remaining = $val->no_of_leaves-$getUsedLeaves;
                                                                            endif;



                                                                            if($remaining < 0):
                                                                                echo "<span style='color:red;'>$remaining</span>";
                                                                            else:
                                                                                $countRemainingLeaves +=$remaining;
                                                                                echo $remaining;
                                                                            endif;
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                    <th class="text-right"  style="color: #fff;background-color: #2a6ecf;" colspan="2"><b>Total</b></th>
                                                                    <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf;"><b>{{ $count_leaves }}</b></th>
                                                                    <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf; "><?php print_r($countUsedLeavess)?></th>
                                                                    <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf; "><?=$countRemainingLeaves?></th>


                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                                <div class="row" style="color: #fff;background-color: #2a6ecf; ">
                                                                    <b>SELECT LEAVE TYPE</b>
                                                                    &ensp;
                                                                    <span class="glyphicon glyphicon-arrow-down"></span>
                                                                </div>
                                                                <div class="btn-group" data-toggle="buttons" style="padding: 4px;">
                                                                    @foreach($leaves_policy as $val)
                                                                        <?php $leaveName = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )?>

                                                                        <label style="border:1px solid #fff;" class="btn btn-success" onclick="viewEmployeeLeavesDetail('<?=$val->id?>','{{ $val->no_of_leaves }}','<?php echo $val->leave_type_id ?>')">
                                                                            <input required="required" autocomplete="off" type="radio" name="leave_type" id="leave_type" class="requiredField" value="<?=$val->leave_type_id?>">
                                                                            {{ $leaveName }}
                                                                            <span class="glyphicon glyphicon-ok"></span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="lineHeight">&nbsp;</div>
                                                        <div class="lineHeight">&nbsp;</div>
                                                        <div class="" id="leavesData"></div>
                                                        <div class="" id="leave_days_area"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label>Reason For Leave</label>
                                                                <textarea id="reason" class="form-control requiredField">-</textarea>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label>Address While on Leaave</label>
                                                                <textarea id="leave_address" class="form-control requiredField">-</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                        <span id="errorMesg" style="color:red"></span>
                                                        <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-success">Submit</button>
                                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                                    </div>
                                                </div>

                                                <?php echo Form::close();?>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="tab-pane" id="pendingLeaves">
                                                <?php
                                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                $attendance_machine_id = Employee::select('emp_id')->where([['emp_id','=',Auth::user()->emp_id]])->value('emp_id');
                                                CommonHelper::reconnectMasterDatabase();
                                                $leave_application_list = DB::table('leave_application')
                                                    ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                                                    ->select('leave_application.*')
                                                    ->where([['leave_application.emp_id', '=',$attendance_machine_id]])
                                                    ->get();

                                                ?>
                                                @if($team_lead_exists->exists())
                                                    <?php
                                                    $line_manager_employees = [];
                                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                    $line_manager_emp = Employee::select('emp_id')->where([['reporting_manager','=',Auth::user()->emp_id],['status','=',1]])->get();
                                                    foreach($line_manager_emp as $value){
                                                        $line_manager_employees[] = $value->emp_id;
                                                    }
                                                    CommonHelper::reconnectMasterDatabase();

                                                    $leave_application_request_list = DB::table('leave_application')
                                                        ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                                                        ->select('leave_application.*')
                                                        ->whereIn('leave_application.emp_id',$line_manager_employees)
                                                        ->where('leave_application.view','=','yes')
                                                        ->orderBy('leave_application.approval_status')
                                                        ->get();
                                                    $m = Input::get('m');
                                                    $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
                                                    $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
                                                    $companies = DB::table('company')->where('status',1)->get();
                                                    ?>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="">
                                                                                <div class="row">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                            <span class="subHeadingLabelClass">View Team Leave Application Lists</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                                                                                <div class="lineHeight">&nbsp;</div>
                                                                                <div class="panel">
                                                                                    <div class="panel-body" id="PrintLeaveApplicationRequestList">

                                                                                        <div class="row">
                                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                                <div class="table-responsive LeavesData">

                                                                                                    <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                                                                                                        <thead>
                                                                                                        <th class="text-center">S No.</th>
                                                                                                        <th class="text-center">Emp ID</th>
                                                                                                        <th class="text-center">Emp Name</th>
                                                                                                        <th class="text-center">Leave Type</th>
                                                                                                        <th class="text-center">Day Type</th>
                                                                                                        <th class="text-center">Approval Status(HR)</th>
                                                                                                        <th class="text-center">Approval Status(GM)</th>
                                                                                                        <th class="text-center">Status</th>
                                                                                                        <th class="text-center">Created on</th>
                                                                                                        <th class="text-center hidden-print">Action</th>

                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                        <?php if(count($leave_application_request_list) != '0'){ ?>
                                                                                                        @foreach($leave_application_request_list as $value)
                                                                                                            <?php
                                                                                                            CommonHelper::companyDatabaseConnection($m);
                                                                                                            $emp_data =  Employee::where([['emp_id','=',$value->emp_id],['status','=',1]]);
                                                                                                            CommonHelper::reconnectMasterDatabase();
                                                                                                            ?>
                                                                                                            <tr>
                                                                                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $countersss++ }}</span></td>
                                                                                                                <td class="text-center"><?php echo $emp_data->value('emp_id') ?></td>
                                                                                                                <td class="text-center"><strong><?php echo $emp_data->value('emp_name') ?></strong></td>
                                                                                                                <td class="text-center" style="color:green">{{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->leave_type)}}</td>
                                                                                                                <td class="text-center" style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</td>
                                                                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                                                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>

                                                                                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                                                                                <td class="text-center" style="text-decoration: underline">
                                                                                                                    {{ HrHelper::date_format($value->date) }}
                                                                                                                </td>
                                                                                                                <td class="text-center hidden-print">
                                                                                                                    <button class="btn-info btn-xs" onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?=$m?>')">
                                                                                                                        <span class="glyphicon glyphicon-edit"></span>
                                                                                                                    </button>
                                                                                                                    <button onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_id?>','<?=$m?>')" class="btn btn-xs btn-primary" type="button">
                                                                                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                                                                                    </button>
                                                                                                                    @if ($value->status == 2)
                                                                                                                        <button data-toggle="tooltip" data-placement="right" title="Repost" onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-info" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                                                                                    @else
                                                                                                                        <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-danger" type="button"><span class="glyphicon glyphicon-remove"></span></button>
                                                                                                                    @endif
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="10">
                                                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                                                                        <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>

                                                                                                        @endforeach
                                                                                                        <?php } else { ?>
                                                                                                        <tr>
                                                                                                            <td colspan="9" class="text-center text-danger"><strong>No Reord Found</strong></td>
                                                                                                        </tr>
                                                                                                        <?php } ?>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <span class="subHeadingLabelClass">View User Leave Application List</span>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <?php $leave_type = [4 => 'Maternity Leaves',1 => 'Annual/Earned Leave',2 => 'Sick Leave',3 => 'Casual'];?>
                                                                    <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                                                                    <div class="lineHeight">&nbsp;</div>
                                                                    <div class="panel">
                                                                        <div class="panel-body" id="PrintLeaveApplicationList">

                                                                            <div class="row">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <tr class="table-responsive">
                                                                                        <table class="table table-bordered sf-table-list" id="LeaveApplicationList">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>S No.</th>
                                                                                                <th>Leave Type</th>
                                                                                                <th>Day Type</th>
                                                                                                <th class="text-center">Approval Status</th>
                                                                                                <th class="text-center">Status</th>
                                                                                                <th class="text-center">Created on</th>
                                                                                                <th class="text-center hidden-print">Action</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            <?php $counter = 1;?>
                                                                                            <?php if(count($leave_application_list) != '0'){ ?>
                                                                                            @foreach($leave_application_list as $value)
                                                                                                <?php
                                                                                                if($value->approval_status == 2 && $value->approval_status_lm == 2){
                                                                                                    $approval_status_final = 2;
                                                                                                }
                                                                                                else if($value->approval_status == 3 && $value->approval_status_lm == 3){
                                                                                                    $approval_status_final = 3;
                                                                                                }
                                                                                                else if($value->approval_status == 1 && $value->approval_status_lm == 1){
                                                                                                    $approval_status_final = 1;
                                                                                                }
                                                                                                else{
                                                                                                    $approval_status_final = 1;
                                                                                                }

                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td class="">
                                                                                                        <span class="badge badge-pill badge-secondary">{{ $counter++ }}</span>
                                                                                                    </td>
                                                                                                    <td class="">
                                                                                                        <span style="color:green">{{ $leave_type[$value->leave_type] }}</span>
                                                                                                    </td>
                                                                                                    <td class="">
                                                                                                        <span style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</span>
                                                                                                    </td>
                                                                                                    <td class="text-center">
                                                                                                        {{ HrHelper::getApprovalStatusLabel($approval_status_final) }}
                                                                                                    </td>
                                                                                                    <td class="text-center">
                                                                                                        {{ HrHelper::getStatusLabel($value->status) }}
                                                                                                    </td>
                                                                                                    <td class="text-center" style="text-decoration: underline">
                                                                                                        {{ HrHelper::date_format($value->date) }}
                                                                                                    </td>
                                                                                                    <td class="text-center hidden-print">
                                                                                                        <button onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?php echo $m; ?>')" class="btn btn-xs btn-info" type="button">
                                                                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                                                                        </button>
                                                                                                        <button onclick="getLeavesData('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$value->leave_type?>')" class="btn btn-xs btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?=$value->id?>" aria-expanded="false" aria-controls="collapseExample">
                                                                                                            <span class="glyphicon glyphicon-eye-open"></span>
                                                                                                        </button>
                                                                                                        <button onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-danger " type="button"><span class="glyphicon glyphicon-remove"></span></button>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="7">
                                                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                                                            <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>

                                                                                            @endforeach
                                                                                            <?php }else { ?>
                                                                                            <tr>
                                                                                                <td colspan="7" class="text-center text-danger">
                                                                                                    <strong>No Record Found</strong>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php } ?>
                                                                                            </tbody>
                                                                                        </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <span class="subHeadingLabelClass">View User Leave Application List</span>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <?php $leave_type = [4 => 'Maternity Leaves',1 => 'Annual/Earned Leave',2 => 'Sick Leave',3 => 'Casual'];?>
                                                                    <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                                                                    <div class="lineHeight">&nbsp;</div>
                                                                    <div class="panel">
                                                                        <div class="panel-body" id="PrintLeaveApplicationList">

                                                                            <div class="row">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <tr class="table-responsive">
                                                                                        <table class="table table-bordered sf-table-list" id="LeaveApplicationList">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>S No.</th>
                                                                                                <th>Leave Type</th>
                                                                                                <th>Day Type</th>
                                                                                                <th class="text-center">Approval Status</th>
                                                                                                <th class="text-center">Status</th>
                                                                                                <th class="text-center">Created on</th>
                                                                                                <th class="text-center hidden-print">Action</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            <?php $counter = 1;?>
                                                                                            <?php if(count($leave_application_list) != '0'){ ?>
                                                                                            @foreach($leave_application_list as $value)
                                                                                                <?php
                                                                                                if($value->approval_status == 2 && $value->approval_status_lm == 2){
                                                                                                    $approval_status_final = 2;
                                                                                                }
                                                                                                else if($value->approval_status == 3 && $value->approval_status_lm == 3){
                                                                                                    $approval_status_final = 3;
                                                                                                }
                                                                                                else if($value->approval_status == 1 && $value->approval_status_lm == 1){
                                                                                                    $approval_status_final = 1;
                                                                                                }
                                                                                                else{
                                                                                                    $approval_status_final = 1;
                                                                                                }

                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td class="">
                                                                                                        <span class="badge badge-pill badge-secondary">{{ $counter++ }}</span>
                                                                                                    </td>
                                                                                                    <td class="">
                                                                                                        <span style="color:green">{{ $leave_type[$value->leave_type] }}</span>
                                                                                                    </td>
                                                                                                    <td class="">
                                                                                                        <span style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</span>
                                                                                                    </td>
                                                                                                    <td class="text-center">
                                                                                                        {{ HrHelper::getApprovalStatusLabel($approval_status_final) }}
                                                                                                    </td>
                                                                                                    <td class="text-center">
                                                                                                        {{ HrHelper::getStatusLabel($value->status) }}
                                                                                                    </td>
                                                                                                    <td class="text-center" style="text-decoration: underline">
                                                                                                        {{ HrHelper::date_format($value->date) }}
                                                                                                    </td>
                                                                                                    <td class="text-center hidden-print">
                                                                                                        <button onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?php echo $m; ?>')" class="btn btn-xs btn-info" type="button">
                                                                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                                                                        </button>
                                                                                                        <button onclick="getLeavesData('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$value->leave_type?>')" class="btn btn-xs btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?=$value->id?>" aria-expanded="false" aria-controls="collapseExample">
                                                                                                            <span class="glyphicon glyphicon-eye-open"></span>
                                                                                                        </button>
                                                                                                        <button onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-danger " type="button"><span class="glyphicon glyphicon-remove"></span></button>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="7">
                                                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                                                            <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>

                                                                                            @endforeach
                                                                                            <?php }else { ?>
                                                                                            <tr>
                                                                                                <td colspan="7" class="text-center text-danger">
                                                                                                    <strong>No Record Found</strong>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php } ?>
                                                                                            </tbody>
                                                                                        </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>f
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="tab-pane" id="Educations">
                                                <h2>Education</h2>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                                                        <input type="button" name="add_edu" id="add_edu" value="Add Education" class="btn btn-success"	/>
                                                    </div>
                                                </div>
                                                <!--Table-->
                                                <table class="table table-hover table-fixed">
                                                    <!--Table head-->
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">S.no</th>
                                                        <th class="text-center">Institute Name</th>
                                                        <th class="text-center">Degree Type</th>
                                                        <th class="text-center">Year Of Admission</th>
                                                        <th class="text-center">Year Of Passing</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(count($employee_education) != 0){ ?>

                                                    <?php foreach($employee_education as $value){ ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $counter++ ?></td>
                                                        <td class="text-center"><?php echo $value->institute_name ?></td>
                                                        <td class="text-center"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'degree_type','degree_type_name',$value->degree_type) ?></td>
                                                        <td class="text-center"><?php echo date("d-m-Y", strtotime($value->year_of_admission)); ?></td>
                                                        <td class="text-center"><?php echo date("d-m-Y", strtotime($value->year_of_passing)); ?></td>
                                                    </tr>
                                                    <?php } ?>

                                                    <?php } else{ ?>
                                                    <tr>
                                                        <td colspan="4" class="text-danger text-center"><h2>No Record Found</h2></td>
                                                    </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                    <!--Table body-->

                                                </table>
                                                <!--Table-->
                                            </div>
                                            <div class="tab-pane" id="JobHistory">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <h2>Job History</h2>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right" style="margin-top: 19px;">
                                                        <button type="button" class="btn btn-default btn-md" id="salaryPrivacy" style="margin-right: -35px;">
                                                            <span class="glyphicon glyphicon-eye-open"></span>
                                                        </button>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden text-right" id="pass_eye" style="margin-top: 19px">
                                                        <input type="password" class="form-control" name="p_user" id="p_user" placeholder="password" style="margin-left: 20px;" >
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 hidden checkSubmit text-right">
                                                        <button type="button" class="btn btn-default btn-md" id="check_pass" style="margin-top: 20px;margin-right: -3px;">
                                                            <span class="">Ok</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <p class="bg-danger text-center" id="error_password" style="width: 276px;margin-left: 525px;" ></p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 offset-md-3">
                                                        <ul class="timeline">
                                                            <?php if($EmployeePromotion->count() > 0){

                                                            ?>

                                                            <?php foreach($EmployeePromotion->get() as $value){
                                                            $salarys[] = number_format($value->salary,0);

                                                            ?>
                                                            <li>
                                                                <a target="_blank" href="#"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$value->designation_id) ?></a>
                                                                <a href="#" class="float-right"><?php echo date("F d, yy", strtotime($value->date)) ?></a>
                                                                <h5>Salary: <span class="salary_hidden demi" id="check_<?= $counting++ ?>" ><?php echo number_format($value->salary) ?></span></h5>
                                                            </li>
                                                            <?php } ?>
                                                            <?php } ?>
                                                            <li>
                                                                <a target="_blank" href="#"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee->value('designation_id')) ?></a>
                                                                <a href="#" class="float-right"><?php echo date("F d, yy", strtotime($employee->value('date'))) ?></a>
                                                                <h5>Salary: <span class="salary_hidden demi" id="check_<?php echo $counting++ ?>" ><?php echo number_format($employee->value('emp_salary'));    ?></span></h5>
                                                                <?php $salarys[] = number_format($employee->value('emp_salary'),0); ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="policies">
                                                <?php
                                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                $policies = Policies::where([['status', '=', 1],['category_id', '=', 1]])->get();
                                                $forms = Policies::where([['status', '=', 1],['category_id', '=', 2]])->get();
                                                CommonHelper::reconnectMasterDatabase();
                                                ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h2>Policies</h2>
                                                        <div class="row">
                                                            <ul>
                                                                @foreach($policies as $key => $val)
                                                                    <li><h4><a target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h4></li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h2>Forms</h2>
                                                        <div class="row">
                                                            <ul>
                                                                @foreach($forms as $key => $val)
                                                                    <li><h4><a target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h4></li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                </div><!--/col-9-->
                            </div>
							</div>
                        <div id="notify_area1" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                            <br>
                            <p class="notify">Upcoming Birthday's</p><hr style="border-top:1px solid green"/>
                            @foreach($upcoming_birthdays_detail as $upcoming_birthday)
                                <p class="birthday_block">
                                    <span class="glyphicon glyphicon-gift" aria-hidden="true" style="color:red"></span>
                                    <b>{{$upcoming_birthday->emp_name}} ( {{date("M-d", strtotime($upcoming_birthday->emp_date_of_birth))}} )</b>
                                </p>
                            @endforeach
                            <br>

                            @if(count($empWorkAnvs) > 0)

                                <p class="notify">Work Anniversary's</p><hr style="border-top:1px solid green"/>
                                @foreach($empWorkAnvs as $empWorkAnv)
                                    <p class="birthday_block">
                                        <span class="glyphicon glyphicon-heart" aria-hidden="true" style="color:red"></span>
                                        <b>{{$empWorkAnv->emp_name}} ( {{date("M-d", strtotime($empWorkAnv->emp_joining_date))}} )</b>
                                    </p>
                                @endforeach
                            @endif
                            <br>

                            @if($EmployeeOfTheMonth->count() > 0)
                                <h4><b>Employees Of The Month</b></h4>
                                @foreach($EmployeeOfTheMonth->get() as $Eom)
                                    <p style="background-color: white;border-radius: 15px;padding: 12px;">
                                        Emp Name: <b>{{HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$Eom->emp_id) }}</b>
                                        Remarks : <b>{{$Eom->remarks}}</b>
                                    </p>
                                @endforeach
                            @endif

                        </div>


                        </div>
 <div class="modal fade" id="myModalss" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content--> 
      <div class="modal-content custom_width">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <div id="education_area_1">
		  
		  </div>
		   <input type="button" name="addSubmit" id="addSubmit" class="btn btn-primary" value="Submit" />
		 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
	<script>
        $("#basic_info_form").submit(function(e){
            e.preventDefault();
            var name = $("#name").val();
            var p_email = $("#p_email").val();
            var cnic = $("#cnic").val();
            var phone = $("#phone").val();
            var dob = $("#dob").val();
            var url = '<?php echo url("/") ?>/ddc/basicInfo';
            var emp_id = '<?php echo $employee->value("emp_id"); ?>';
            var m = '<?php echo Input::get("m") ?>';
            jqueryValidationCustom()
            if(validate == '0'){
                $.ajax({
                    type:'GET',
                    url:url,
                    data:{name:name,p_email:p_email,cnic:cnic,phone:phone,dob:dob,emp_id:emp_id,m:m},
                    success:function(res){
                        if(res == 1){
                            $('#error_cnic').hide().html('<strong>Cnic Already Exists !!</strong>').fadeIn('slow').delay(6000).hide(1);
                        }
                        else{
                            $("#name").val(res[0]);
                            $("#p_email").val(res[1]);
                            $("#cnic").val(res[2]);
                            $("#phone").val(res[3]);
                            $("#dob").val(res[4]);

                            $("#username").html(res[0]);
                            $("#p_email_1").html(res[1]);
                            $("#cell_1").html(res[3]);
                            $("#dob_1").html(res[4]);
                            $("#name_1").html(res[0]);
                            $('#response').hide().html('<strong>Successfully Updated !!</strong>').fadeIn('slow').delay(3000).hide(1);
                        }
                    }
                });
            }
        });

        function jqueryValidationCustom(){
            var requiredField = document.getElementsByClassName('requiredField');
            for (i = 0; i < requiredField.length; i++){
                var rf = requiredField[i].id;
                var checkType = requiredField[i].type;
                /*if(checkType == 'text'){
                 alert('Please type text');
                 }else if(checkType == 'select-one'){
                 alert('Please select one option');
                 }else if(checkType == 'number'){
                 alert('Please type number');
                 }else if(checkType == 'date'){
                 alert('Please type date');
                 }*/
                if($('#'+rf).val() == ''){
                    $('#'+rf).css('border-color', 'red');
                    $('#'+rf).focus();
                    validate = 1;
                    return false;
                }else{
                    $('#'+rf).css('border-color', '#ccc');
                    validate = 0;
                }
            }


            /*var requiredField1 = document.getElementsByClassName('requiredField');
             for (i = 0; i < requiredField1.length; i++){
             var rf1 = requiredField[i].id;
             if($('#'+rf1+'').val() == ''){
             validate = 1;
             }else{
             validate = 0;
             }
             }*/
            return validate;
        }

        $("#reset_btn").click(function(){
            var name = $("#name").val();
            var p_email = $("#p_email").val();
            var cnic = $("#cnic").val();
            var phone = $("#phone").val();
            var dob = $("#dob").val();
            var url = '<?php echo url("/") ?>/ddc/basicInfo';
            var emp_id = '<?php echo $employee->value("emp_id"); ?>';
            var m = '<?php echo Input::get("m") ?>';

            if(name != ''){
                $("#name").val('');
                $('#name').attr('placeholder', 'Name');
            }
            if(p_email != ''){
                $("#p_email").val('');
                $('#p_email').attr('placeholder', 'Personal Email');
            }
            if(cnic != ''){
                $("#cnic").val('');
                $('#cnic').attr('placeholder', 'Cnic');
            }
            if(phone != ''){
                $("#phone").val('');
                $('#phone').attr('placeholder', 'phone');
            }
            if(dob != ''){
                $("#dob").val('');
                $('#dob').attr('placeholder', 'DOB');
            }


        });

        $('#btn_check').on('click', function() {
            var $this = $(this);
            $this.button('loading');
            setTimeout(function() {
                $this.button('reset');
            }, 3000);
        });

     

        $("#check_pass").click(function(e){
            e.preventDefault();
            var p_user = $("#p_user").val();
            var url = '<?php echo url("/") ?>/ddc/checkingPassword';
            var emp_id = '<?php echo $employee->value("emp_id"); ?>';
            var passedArrays = <?php echo json_encode($salarys); ?>;
            $.ajax({ 
                type:'GET',
                url:url,
                data:{p_user:p_user,emp_id:emp_id},
                success:function(res){
                    if(res == 'matched'){
                        $(".demi").removeClass('salary_hidden');
                        $('#password_matched').html('<strong>Password Matched !!</strong>');
                        for(var i = 0; i < passedArrays.length; i++){
                            $("#check_"+i).html(passedArrays[i]);
                        }
                    }
                    else{
                        $('#error_password').hide().html('<strong>Password Not Matched !!</strong>').fadeIn('slow').delay(3000).hide(1);
                        $(".demi").addClass('salary_hidden');
                        $(".salary_hidden").html('******');
                    }
                }
            });
        });

        $(document).ready(function(){
            $(".salary_hidden").html('******');
        });
		
		
		
    @if($WithoutLeavePolicy[0] != 'Select Leave Policy')		
        @if($leaves_policy_validatity == 0)
            setInterval(function () {
            $(".policy_expire_mesg").css("color","#fff");
            $(".policy_expire_mesg").css("background-color","#a94442");
            $(".policy_expire_mesg").css("border-color","#a94442");
            setTimeout(function () {
                $(".policy_expire_mesg").removeAttr("style");

            },500)
        },900);
        @endif

        function leaves_day_type(type)
        {

            var current_date  = '<?= date("Y-m-d") ?>';
            var leave_type = $("input[id='leave_type']:checked").val();

            if(leave_type == 2)
            {
                if(type == 'full_day_leave')
                {

                    $("#leave_days_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> No. of Days</label><input type="number" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                        '<span id="warning_message" style="color:red"></span></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> To </label><input type="date" class="form-control requiredField" name="to_date" id="to_date"></div>');
                }
                else if(type == 'half_day_leave')
                {

                    $("#leave_days_area").html('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                        '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                        '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                        '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div>');
                }
                else if(type == 'short_leave')
                {
                    $("#leave_days_area").html('');
                    $("#leave_days_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div>');

                }
            }
            else if(leave_type == 3)
            {
                if(type == 'full_day_leave')
                {

                    $("#leave_days_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> No. of Days</label><input type="number" onclick="checkCasualLeave()" onkeyup="checkCasualLeave()" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                        '<span id="warning_message" style="color:red"></span></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> To </label><input type="date" class="form-control requiredField" onchange="checkCasualLeavesDifference()" name="to_date" id="to_date"></div>');


                }
                else if(type == 'half_day_leave')
                {

                    $("#leave_days_area").html('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                        '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                        '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                        '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div>');
                }
                else if(type == 'short_leave')
                {

                    $("#leave_days_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                        '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div>');

                }
            }


        }
        function check_days()
        {
		  
            var leave_type = $("input[id='leave_type']:checked").val();
            var leaves_day_type = $("input[id='leave_day_type']:checked").val();
            var leave_policy_id = '<?=$leaves_policy[0]->leaves_policy_id?>';
         	var emp_id = $("#emp_id").val(); 
           
                $('.leaveAppLoader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
				

                if(leave_type == 4)
                {
                    var emp_id = emp_id;
                    var company_id = '<?= Input::get('m') ?>';
                    var no_of_days = $("#no_of_days").val();
                    var from_date =  $("#from_date").val();
                    var to_date   =  $("#to_date").val();
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var leave_day_type = 1;
                    var reason   = $("#reason").val();
                    var backup_contact   = $("#backup_contact").val();
                    var leave_address =  $("#leave_address").val();
                    var data = {
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        company_id:company_id,
                        leave_type:leave_type,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        from_date:from_date,
                        to_date:to_date,
                        reason:reason,
                        leave_address:leave_address,
                        backup_contact:backup_contact,
                    };

                    var from_date = $('#from_date').val();
                    var to_date   = $("#to_date").val();
                    var date1 = new Date(from_date);
                    var date2 = new Date(to_date);
                    var no_of_days = $("#no_of_days").val();
                    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                    /* if(diffDays != 90 )
                     {
                         $("#maternity_date_error").html("Please Correct Date Difference !");
                         return false;
                     }
                     else
                     {
                         $("#maternity_date_error").html("");
                     }*/


                }

                else if(leave_type == 1)
                {

                    if(leaves_day_type == 'full_day_leave'){


                        var inform_days_two = '29';
                        var from_date = $('#from_date').val();
                        var to_date   = $("#to_date").val();
                        var no_of_days = $('#no_of_days').val();
                        var current_date = '<?= date("Y-m-d"); ?>';
                        var date1 = current_date;
                        var date2 = from_date;
                        date1 = date1.split('-');
                        date2 = date2.split('-');
                        date1 = new Date(date1[0], date1[1], date1[2]);
                        date2 = new Date(date2[0], date2[1], date2[2]);
                        date1_unixtime = parseInt(date1.getTime() / 1000);
                        date2_unixtime = parseInt(date2.getTime() / 1000);
                        var timeDifference = date2_unixtime - date1_unixtime;
                        var timeDifferenceInHours = timeDifference / 60 / 60;
                        var timeDifferenceInDays = timeDifferenceInHours  / 24;

                        /*   var check1= checkLeavesDifference();
                             var check2= checkAnnualLeaveDays();

                           if((inform_days_two-1) > timeDifferenceInDays)
                           {

                               $('#warning_message').html('For Anuual Leaves , Inform Administration Before '+inform_days_two+' days !');
                               var check = false;

                           }
                           else
                           {
                               $('#warning_message').html('');
                               var check = true;
                           }


                           if(check == true && check1 == true && check2 == true){*/

                        var emp_id = emp_id;
                        var company_id = '<?= Input::get('m') ?>';
                        var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                        var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                        var from_date =  $("#from_date").val();
                        var to_date   = $("#to_date").val();
                        var leave_type = $("input[id='leave_type']:checked").val();
                        var leave_day_type = 1
                        var reason   = $("#reason").val();
                        var backup_contact   = $("#backup_contact").val();
                        var leave_address =  $("#leave_address").val();
                        var data = {
                            emp_id:emp_id,
                            leave_policy_id:leave_policy_id,
                            company_id:company_id,
                            full_day_deduction_rate:full_day_deduction_rate,
                            leave_type:leave_type,
                            leave_day_type:leave_day_type,
                            no_of_days:no_of_days,
                            from_date:from_date,
                            to_date:to_date,
                            reason:reason,
                            leave_address:leave_address,
                            backup_contact:backup_contact,
                        };

                        //   }
                        // else
                        // {
                        //      return false;
                        // }


                    }

                }
                else if(leave_type == 2)
                {
                    if(leaves_day_type == 'full_day_leave'){


                        var from_date = $('#from_date').val();
                        var no_of_days = $('#no_of_days').val();
                        var company_id = '<?= Input::get('m') ?>';
                        var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                        var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                        var from_date =  $("#from_date").val();
                        var to_date   = $("#to_date").val();
                        var leave_type = $("input[id='leave_type']:checked").val();
                        var leave_day_type = 1
                        var reason   = $("#reason").val();
                        var backup_contact   = $("#backup_contact").val();
                        var leave_address =  $("#leave_address").val();
                        var data = {

                            leave_type:leave_type,
                            emp_id:emp_id,
                            leave_policy_id:leave_policy_id,
                            company_id:company_id,
                            full_day_deduction_rate:full_day_deduction_rate,
                            leave_day_type:leave_day_type,
                            no_of_days:no_of_days,
                            from_date:from_date,
                            to_date:to_date,
                            reason:reason,
                            leave_address:leave_address,
                            backup_contact:backup_contact,
                        };



                    }
                    else if(leaves_day_type == 'half_day_leave')
                    {

                        var emp_id = emp_id;
                        var company_id = '<?= Input::get('m') ?>';
                        var reason   = $("#reason").val();
                        var backup_contact   = $("#backup_contact").val();
                        var leave_address =  $("#leave_address").val();
                        var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                        var first_second_half = $("input[id='first_second_half']:checked").val();
                        var no_of_days = (1*half_day_deduction_rate);
                        var first_second_half_date =  $("#first_second_half_date").val();
                        var leave_day_type = 2
                        var leave_type = $("input[id='leave_type']:checked").val();
                        var data = {
                            leave_type:leave_type,
                            company_id:company_id,
                            emp_id:emp_id,
                            leave_policy_id:leave_policy_id,
                            leave_day_type:leave_day_type,
                            no_of_days:no_of_days,
                            first_second_half:first_second_half,
                            first_second_half_date:first_second_half_date,
                            leave_address:leave_address,
                            reason:reason,
                            first_second_half_date:first_second_half_date,
                            backup_contact:backup_contact,
                        };



                    }
                    else if(leaves_day_type == 'short_leave')
                    {

                        var emp_id = emp_id;
                        var company_id = '<?= Input::get('m') ?>';
                        var reason   = $("#reason").val();
                        var backup_contact   = $("#backup_contact").val();
                        var leave_address =  $("#leave_address").val();
                        var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                        var short_leave_time_from = $("#short_leave_time_from").val();
                        var short_leave_time_to = $("#short_leave_time_to").val();
                        var short_leave_date = $("#short_leave_date").val();
                        var no_of_days = (1*per_hour_deduction_rate);
                        var first_second_half_date =  $("#first_second_half_date").val();
                        var leave_day_type = 3;
                        var leave_type = $("input[id='leave_type']:checked").val();

                        var data = {
                            leave_type:leave_type,
                            company_id:company_id,
                            emp_id:emp_id,
                            leave_policy_id:leave_policy_id,
                            leave_day_type:leave_day_type,
                            no_of_days:no_of_days,
                            short_leave_time_from:short_leave_time_from,
                            short_leave_time_to:short_leave_time_to,
                            short_leave_date:short_leave_date,
                            leave_address:leave_address,
                            reason:reason,
                            backup_contact:backup_contact,
                        };

                    }
                    else
                    {
                        alert('Error ! Select Full/Half/Short Leave Type !');
                        return false;
                    }
                }
                else if(leave_type == 3)
                {
                    if(leaves_day_type == 'full_day_leave'){

                        var from_date = $('#from_date').val();
                        var no_of_days = $('#no_of_days').val();
                        var emp_id = emp_id;
                        var company_id = '<?= Input::get('m') ?>';
                        var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                        var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                        var from_date =  $("#from_date").val();
                        var to_date   = $("#to_date").val();
                        var leave_type = $("input[id='leave_type']:checked").val();
                        var leave_day_type = 1
                        var reason   = $("#reason").val();
                        var backup_contact   = $("#backup_contact").val();
                        var leave_address =  $("#leave_address").val();
                        var data = {
                            leave_type:leave_type,
                            emp_id:emp_id,
                            leave_policy_id:leave_policy_id,
                            company_id:company_id,
                            full_day_deduction_rate:full_day_deduction_rate,
                            leave_day_type:leave_day_type,
                            no_of_days:no_of_days,
                            from_date:from_date,
                            to_date:to_date,
                            reason:reason,
                            leave_address:leave_address,
                            backup_contact:backup_contact
                        };


                    }
                    else if(leaves_day_type == 'half_day_leave')
                    {

                        var emp_id = emp_id;
                        var reason   = $("#reason").val();
                        var backup_contact   = $("#backup_contact").val();
                        var company_id = '<?= Input::get('m') ?>';
                        var leave_address =  $("#leave_address").val();
                        var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                        var first_second_half = $("input[id='first_second_half']:checked").val();
                        var no_of_days = (1*half_day_deduction_rate);
                        var first_second_half_date =  $("#first_second_half_date").val();
                        var leave_day_type = 2
                        var leave_type = $("input[id='leave_type']:checked").val();
                        var data = {
                            leave_type:leave_type,
                            company_id:company_id,
                            emp_id:emp_id,
                            leave_policy_id:leave_policy_id,
                            leave_day_type:leave_day_type,
                            no_of_days:no_of_days,
                            first_second_half:first_second_half,
                            first_second_half_date:first_second_half_date,
                            leave_address:leave_address,
                            reason:reason,
                            first_second_half_date:first_second_half_date,
                            backup_contact:backup_contact,
                        };

                    }
                    else if(leaves_day_type == 'short_leave')
                    {

                        var emp_id = emp_id;
                        var company_id = '<?= Input::get('m') ?>';
                        var reason   = $("#reason").val();
                        var backup_contact   = $("#backup_contact").val();
                        var leave_address =  $("#leave_address").val();
                        var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                        var short_leave_time_from = $("#short_leave_time_from").val();
                        var short_leave_time_to = $("#short_leave_time_to").val();
                        var short_leave_date = $("#short_leave_date").val();
                        var no_of_days = (1*per_hour_deduction_rate);
                        var first_second_half_date =  $("#first_second_half_date").val();
                        var leave_day_type = 3;
                        var leave_type = $("input[id='leave_type']:checked").val();

                        var data = {
                            leave_type:leave_type,
                            company_id:company_id,
                            emp_id:emp_id,
                            leave_policy_id:leave_policy_id,
                            leave_day_type:leave_day_type,
                            no_of_days:no_of_days,
                            short_leave_time_from:short_leave_time_from,
                            short_leave_time_to:short_leave_time_to,
                            short_leave_date:short_leave_date,
                            leave_address:leave_address,
                            reason:reason,
                            backup_contact:backup_contact,
                        };

                    }
                    else
                    {
                        alert('Error ! Select Full/Half/Short Leave Type !');
                        return false;
                    }
                }  
                else
                {
                    alert('Please Select Leaves Type !')
                }
                var company_id = '<?= Input::get('m') ?>';
				
				
                $.ajax({
                    url: '<?php echo url('/')?>/hadbac/addLeaveApplicationDetail',
                    type: "GET",
                    data: data,
                    success:function(data) {
						
                        if(data == 1) {
                            location.reload();
                        }
                        else {
                            alert(data);
                            $(".leaveAppLoader").html("");
                        }
                    }
                });
        }


        function viewEmployeeLeavesDetail(id,leavesCount,leaveType) {
			
            //alert(leaveType); return false;
            var current_date = '<?= date("Y-m-d") ?>';
            $('#leavesData').append('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $("#leave_days_area").html('');

            var url = '<?php echo url('/')?>/hdc/viewEmployeeLeaveDetail';
            var data = {
                company_id: '<?= Input::get('m') ?>',
                emp_id: '<?=$attendance_machine_id?>',
                leave_id: id,
                leavesCount: leavesCount,
                leaveType: leaveType,
            };
            $.ajax({
                url: url,
                data :data,
                success:function(result) {
                    $('#leavesData').html(result);
                }
            });

        }

       /* function checkAllowedLeaveDays(remainingLeaves,check)
        {
            var no_of_days = $("#no_of_days").val();

            if(check == 1)
            {

                if(no_of_days > remainingLeaves)
                {
                    $("#no_of_days").val('');
                    $("#warning_message").html('You cannot carry Leaves , More Then '+remainingLeaves+' In 2nd segment !');

                }
                else if(no_of_days < remainingLeaves)
                {
                    $("#no_of_days").val('');
                    $("#warning_message").html('You cannot carry Less Leaves , You Have to Carry '+remainingLeaves+' Leaves In 2nd segment !');

                }
                else
                {
                    $("#warning_message").html('');
                }
            }
            else if(check == 0)
            {
                if(no_of_days > remainingLeaves)
                {
                    $("#no_of_days").val('');
                    $("#warning_message").html('You cannot carry Leaves , More Then '+remainingLeaves+' In 1st segment !');

                }
                else
                {
                    $("#warning_message").html('');
                }
            }
        }

        function checkCasualLeave()
        {
            var no_of_days =  $("#no_of_days").val();

            if(no_of_days > 3)
            {
                $("#no_of_days").val('');
                $("#warning_message").html('You cannot carry More then 3 Casual Leaves !');
                $("#submitBtn").attr('disabled','disabled');
                $("#errorMesg").html('Please Remove All Errors First !');




            }
            else
            {
                $("#warning_message").html('');
                $("#errorMesg").html('');
                $("#submitBtn").removeAttr('disabled');
            }

        }

        function checkCasualLeavesDifference(leavesCount)
        {

            var from_date = $('#from_date').val();
            var to_date   = $("#to_date").val();
            var date1 = new Date(from_date);
            var date2 = new Date(to_date);
            var no_of_days = $("#no_of_days").val();
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if(no_of_days == (diffDays+1))
            {
                $("#warning_message").html('');
                $("#errorMesg").html('');
                $("#submitBtn").removeAttr('disabled');

                return true;
            }
            else
            {
                $("#warning_message").html('Please Correct Date Difference !');
                $("#submitBtn").attr('disabled','disabled');
                $("#errorMesg").html('Please Remove All Errors First !');

            }


        }
        function checkAnnualLeaveDays(leavesCount)
        {


            var no_of_days =  $("#no_of_days").val();

            if(no_of_days < 4  )
            {

                $("#warning_message").html('You cannot take less then 4 and More Then '+leavesCount+' Annual Leaves !');
                $("#submitBtn").attr('disabled','disabled');
                $("#errorMesg").html('Please Remove All Errors First !');

            }
            else if(no_of_days > leavesCount)
            {


                $("#warning_message").html('You cannot take less then 4 and More Then '+leavesCount+' Annual Leaves !');
                $("#submitBtn").attr('disabled','disabled');
                $("#errorMesg").html('Please Remove All Errors First !');
            }
            else
            {

                $("#warning_message").html('');
                $("#errorMesg").html('');
                $("#submitBtn").removeAttr('disabled');
                return true;
            }


        }


        function checkLeavesDifference(leavesCount)
        {

            var from_date = $('#from_date').val();
            var to_date   = $("#to_date").val();
            var date1 = new Date(from_date);
            var date2 = new Date(to_date);
            var no_of_days = $("#no_of_days").val();
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if(no_of_days == (diffDays+1))
            {
                $("#warning_message").html('');
                $("#errorMesg").html('');
                $("#submitBtn").removeAttr('disabled');
                return true;
            }
            else
            {
                $("#submitBtn").attr('disabled','disabled');
                $("#errorMesg").html('Please Remove All Errors First !');
                $("#warning_message").html('You cannot take less then 4 and More Then '+leavesCount+' Annual Leaves !');
                $("#warning_message").append('<br>');
                $("#warning_message").append('Please Enter Correct Date !');
            }


        }*/
	//@endif
	
	
        function viewRangeWiseLeaveApplicationsRequests()
        {
            jqueryValidationCustom();
            if(validate == 0) {
                $('#leavesLoader').append('<div class="row">&nbsp;</div><div class="loader"></div>');
                var data = '';
                var gm_Approvals;
                var fromDate = $("#fromDate").val();
                var toDate = $("#toDate").val();
                var LeavesStatus = $("#LeavesStatus").val();
                var department_id = $("#department_id").val();
                var sub_department_id_1 = $("#sub_department_id_1").val();
                var employee_id = $("#emp_id").val();
                var company_id = $("#company_id").val();
                var m = '<?= Input::get('m') ?>';
                var url = '<?php echo url('/')?>/hdc/viewRangeWiseLeaveApplicationsRequests';
                if($("#gm_Approval").prop("checked") == true){
                     gm_Approvals = 1;
                     data = {fromDate: fromDate, toDate: toDate, LeavesStatus: LeavesStatus,employee_id:employee_id,m:m,company_id:company_id,department_id:department_id,sub_department_id_1:sub_department_id_1,gm_Approvals:gm_Approvals}
                }
                else{
                    data = {fromDate: fromDate, toDate: toDate, LeavesStatus: LeavesStatus,employee_id:employee_id,m:m,company_id:company_id,department_id:department_id,sub_department_id_1:sub_department_id_1}
                }
                $.ajax({
                    url: url,
                    type: "GET",
                    data:data,
                    success: function (data) {
                        $('#leavesLoader').html("");
                        $('.LeavesData').html(data);


                    }
                })
            }

        }
        function LeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id,company_id)
        {
			
            $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
            var m = '<?= Input::get('m'); ?>';
            var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';
            $.ajax({
                url: url,
                type: "GET",
                data: {id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id,m:company_id},
                success: function (data) {
					
                    jQuery('#showDetailModelTwoParamerter').modal('show', {backdrop: 'false'});
                    jQuery('#showDetailModelTwoParamerter .modalTitle').html('View Leave Application Detail');
                    jQuery('#showDetailModelTwoParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    jQuery('#showDetailModelTwoParamerter .modal-body').html(data);


                }
            })


        }
        function editLeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id,company_id)
        {
            // alert();
            $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
            var m = '<?= Input::get('m'); ?>';
            var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';
            var data = {id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id,m:company_id};
            $.getJSON(url, data ,function(result){
                $.each(result, function(i, field){
                    $('#leave_area'+id).html('<hr>' +
                        '<div class="row text-center" style="background-color: gainsboro">' +
                        '<h4><b>Leave Application Details</b></h4>' +
                        '</div>' +
                        '<div class="row">&nbsp;</div>'+field);

                });
            })

        }

        function approveAndRejectLeaveApplication(recordId,approval_status,leave_day_type)
        {
			alert('hello');
			return;
            var check = (approval_status == 2) ? "Approve":"Reject";
            
            var companyId = '<?= Input::get('m'); ?>';

            if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
            {

                $.ajax({
                    url: '<?php echo url("/")?>/hdc/approveAndRejectLeaveApplication',
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,approval_status:approval_status},
                    success:function(data) {
                       alert(data);
                    }
                });
            }
        } 

        function approveAndRejectLeaveApplication2(recordId,approval_status_lm,leave_day_type)
        {
            var check = (approval_status_lm == 2) ? "Approve":"Reject"; 
      
            var companyId = '<?= Input::get('m'); ?>';

            if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
            { 
 
                $.ajax({
                    url: '<?php echo url("/")?>/hdc/approveAndRejectLeaveApplication2',
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,approval_status_lm:approval_status_lm},
                    success:function(data) {
						location.reload();
                        //getPendingLeaveApplicationDetail('approval_status_lm',leave_day_type);
                    }
                });
            }
        }

        function approveAndRejectLeaveApplication3(recordId,approval_status_hd,leave_day_type)
        {

            var check = (approval_status_hd == 2) ? "Approve":"Reject";
            var url= '<?php echo url('/')?>/cdOne/approveAndRejectLeaveApplication3';
            var companyId = '<?= Input::get('m'); ?>';

            if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
            {

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,approval_status_hd:approval_status_hd},
                    success:function(data) {
                        getPendingLeaveApplicationDetail('approval_status_hd',approval_status_hd,leave_day_type);
                    }
                });
            }
        }

        function RepostLeaveApplicationData(companyId,recordId)
        {
            if(confirm('Do you want to Repost Leave Applicaiton ?'))
            {
                repostMasterTableRecords(recordId,'leave_application');

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
        function getPendingLeaveApplicationDetail(type,leave_day_type){
            var companyId = '<?= Input::get('m'); ?>';
            jQuery('#showDetailModelTwoParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '<?php echo url('/')?>/hdc/getPendingLeaveApplicationDetail',
                type: "GET",
                data: {m:companyId,type:type,leave_day_type:leave_day_type},
                success:function(data) {
                    if(data == 0)
                    {
                        location.reload();
                    }else{
                        jQuery('#showDetailModelTwoParamerter .modal-body').html(data);
                    }

                }
            });
        }

        
		

	   function getEmployee(){
			var m = '<?php echo $m ?>';
			var department = $("#department_id_").val();
			var employee_project_id = $("#employee_project_id").val();
			var line_managers = 'line_manager';
			
			if(department == '' && employee_project_id != ''){
				alert('Please Select Department');
			}
			if(department != '' && employee_project_id == ''){
				data = {department:department,employee_project_id:'0',m:m,line_managers:'line_managers'}
			}
			else if(department != '' && employee_project_id != ''){
				data = {department:department,employee_project_id:employee_project_id,m:m,line_managers:'line_managers'}
			}
			if(department != ''){
				$('#emp_loader_1').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
				$.ajax({
					type:'GET',
					url:'<?php echo url('/') ?>/slal/getSubDepartment',
				    data:data,
					success:function(res){
						$('#emp_loader_1').html('');
						$('select[name="emp_id"]').empty();
						$('select[name="emp_id"]').html(res);
						$("#emp_id").prepend("<option value='' selected='selected'>Select Employee</option>");
					}			
				})
			}
			else{
				
				$("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
				$('select[name="sub_department_id_1"]').empty();
				$('select[name="emr_no"]').empty();
			}
		}

		
		
		
		
	
		function getLeavesData(id,leave_day_type,leave_type)
        {
            $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
            var m = '<?= Input::get('m'); ?>';
            var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationDetail';
            var data = {m:m,id:id,leave_day_type:leave_day_type,leave_type:leave_type};
            $.getJSON(url, data ,function(result){
                $.each(result, function(i, field){
                    $('#leave_area'+id).html('<hr>' +
                        '<div class="row text-center" style="background-color: gainsboro">' +
                        '<h4><b>Leave Application Details</b></h4>' +
                        '</div>' +
                        '<div class="row">&nbsp;</div>'+field);

                });
            }) 

        }
		
		  $("#salaryPrivacy").click(function(){
            $("#pass_eye").removeClass("hidden");
            $(".checkSubmit").removeClass('hidden');
        });
		
		        $("#add_edu").click(function(){
            $('#myModalss').modal('toggle');
            
            $("#education_area_1").html('<form id="checking_edu"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" name="check_emp_id" id="check_emp_id" value="<?php echo Auth::user()->emp_id ?>"/><input type="hidden" name="m" id="m" value="<?php echo $m ?>"/><div class="table-responsive">' +
                        '<input type="hidden" name="education_data[]" id="education_data[]" value="1"><table class="table table-bordered sf-table-list"><thead><th class="text-center col-sm-1">S.No</th>' + 
                        '<th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>' +
                        '<th class="text-center">Degree / Diploma</th>' +
                        '<th class="text-center"><button type="button" id="addMoreQualification" class="btn btn-xs btn-primary">Add More Qualification</button></th>' +
                        '</thead><tbody id="insert_clone"><tr class="get_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +  
                        '<td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1" value=""></td>' +
                        '<td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>' +
                        '<td class="text-center"><input type="hidden" name="qualificationSection[]">' +
                        '<select style="width:300px;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1"><option value="">Select</option>'+
                        '@foreach($DegreeType as $DegreeTypeValue)<option value="{{ $DegreeTypeValue->id }}">{{ $DegreeTypeValue->degree_type_name }}</option>@endforeach</select><span id="other_option_1"></span></td>'+
                        '<td class="text-center">-</td></tr></tbody></table></div></div></form>');


                $("#addMoreQualification").click(function(e){
                    var clone = $(".get_clone_1").html();

                    var form_rows_count = $(".get_rows").length;
                    form_rows_count++;
                    $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                            "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                            "<td class='text-center'><input name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                            "<td class='text-center'><input name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                            "<td><input type='hidden' name='education_data[]' id='education_datas[]' value="+form_rows_count+">" +
                            "<select style='width:300px;' id='degree_type_"+form_rows_count+"' class='form-control requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                            "<span id='other_option_"+form_rows_count+"'></span></td>" +
                            "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='btn btn-xs btn-danger'>Remove</button>" +
                            "</td>" +
                            "</tr>");
                    $('#degree_type_'+form_rows_count+'').select2();

                });
                $('#degree_type_1').select2();
                       
        });
        
        
        
        
        $("#addSubmit").click(function(){
            jqueryValidationCustom()
            if(validate == '0'){
            $.ajax({  
                type:'GET',  
                url:'<?php echo url("/")?>/ddc/addEducationDetails',
                data:$("#checking_edu").serialize(),
                success:function(res){
                    location.reload();     
                }
            });
        }
        });
	</script>	
	<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
					
							