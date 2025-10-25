<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\TransferedLeaves;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
$empCode = array();
$current_date = date('Y-m-d');

?>
<style>
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}
    input[type='checkbox'] {
        transform: scale(2);
    }
</style>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo Form::open(array('url' => 'had/addEmployeeTransferLeave?','id'=>'EmployeeTransferLeave')); ?>
        <div class="row">

            <input type="hidden" name="company_id" value="<?=Input::get('m')?>">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h6><strong style="color:red;">Transfer Leaves to Policy:</strong></h6>
                <select class="form-control requiredField" name="leaves_policy_id" id="leaves_policy_id" required>
                    <option value="">Select</option>
                    @foreach($LeavePolicy as $value)
                        <option value="<?=$value->id?>"><?=$value->leaves_policy_name?></option>
                    @endforeach
                </select>
            </div>



            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h6><strong style="color:red;">Assign Selected Policy to all Employees ,(Select yes):</strong></h6>
                <select class="form-control requiredField" name="assign_all_emp" id="assign_all_emp">
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>


        </div>
        <br><br><br>
        @foreach($companiesList as $companyData)
            <div class="table-responsive">
                <?php $count =1;
                CommonHelper::companyDatabaseConnection($companyData->id);
                $departments = Employee::select('emp_sub_department_id')->groupBy('emp_sub_department_id')->get()->toArray();
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                    </div>


                </div>
            </div>
            @foreach($departments as $value)

                <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                    <thead>
                    <tr style="background-color: #ddd;" >
                        <td colspan="28">
                            <div class="row text-center">
                                <div class="col-sm-12">
                                    <h4><b><?= HrHelper::getMasterTableValueById($companyData->id,'sub_department','sub_department_name',$value["emp_sub_department_id"])?>
                                        </b>
                                    </h4>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <thead>
                    <tr>
                        <th >S No.</th>
                        <th>Emr No.</th>
                        <th>Emp Name </th>
                        <th >Casual</th>
                        <th>Sick</th>
                        <th>Annual</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    CommonHelper::companyDatabaseConnection($companyData->id);
                    $all_emp = Employee::select("emp_id","emp_name",'leaves_policy_id')
                        ->where([['status','=',1],['leaves_policy_id','>',0],["emp_sub_department_id","=",$value["emp_sub_department_id"]]])
                        ->get()->toArray(); ?>
                    <?php
                    CommonHelper::reconnectMasterDatabase();

                    ?>
                    @foreach($all_emp as $value)
                        <?php $empCode[] = $value["emp_id"];  ?>
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ $value["emp_id"] }}</td>
                            <td>{{ $value["emp_name"] }}</td>
                            <td>
                                <?php
                                $TransferedCasualLeaves = TransferedLeaves::where([['emp_id','=',$value["emp_id"]],['leaves_policy_id','=',$value["leaves_policy_id"]],['status','=','1']])->value('casual_leaves');
                                $total_casual_leaves = DB::table("leaves_data")
                                    ->select('no_of_leaves')
                                    ->where([['leave_type_id','=',3],['leaves_policy_id', '=', $value["leaves_policy_id"]]]);

                                $taken_casual_leaves = DB::table("leave_application_data")
                                    ->select(DB::raw("SUM(no_of_days) as taken_casual_leaves"))
                                    ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                    ->where([['leave_application.emp_id', '=', $value["emp_id"]], ['leave_application.status', '=', '1'],
                                        ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','3'],['leave_application.leave_policy_id','=',$value["leaves_policy_id"]]])
                                    ->first();



                                ?>
                                <input class="form-control" name="casualLeaves_<?=$value['emp_id']?>" value="<?=$total_casual_leaves->value('no_of_leaves')+$TransferedCasualLeaves-$taken_casual_leaves->taken_casual_leaves?>">

                            </td>

                            <td>
                                <?php
                                $total_sick_leaves = DB::table("leaves_data")
                                    ->select('no_of_leaves')
                                    ->where([['leave_type_id','=',2],['leaves_policy_id', '=', $value["leaves_policy_id"]]]);


                                $taken_sick_leaves = DB::table("leave_application_data")
                                    ->select(DB::raw("SUM(no_of_days) as taken_sick_leaves"))
                                    ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                    ->where([['leave_application.emp_id', '=', $value["emp_id"]], ['leave_application.status', '=', '1'],
                                        ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','2'],['leave_application.leave_policy_id','=',$value["leaves_policy_id"]]])
                                    ->first();

                                ?>
                                <input class="form-control" name="sickLeaves_<?=$value['emp_id']?>" value="<?=$total_sick_leaves->value('no_of_leaves')-$taken_sick_leaves->taken_sick_leaves?>">

                            </td>

                            <td>
                                <?php
                                $TransferedAnnualLeaves = TransferedLeaves::where([['emp_id','=',$value["emp_id"]],['leaves_policy_id','=',$value["leaves_policy_id"]],['status','=','1']])->value('annual_leaves');
                                $total_annual_leaves = DB::table("leaves_data")
                                    ->select('no_of_leaves')
                                    ->where([['leave_type_id','=',1],['leaves_policy_id', '=', $value["leaves_policy_id"]]]);

                                $taken_annual_leaves = DB::table("leave_application_data")
                                    ->select(DB::raw("SUM(no_of_days) as taken_annual_leaves"))
                                    ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                    ->where([['leave_application.emp_id', '=', $value["emp_id"]], ['leave_application.status', '=', '1'],
                                        ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','1'],['leave_application.leave_policy_id','=',$value["leaves_policy_id"]]])
                                    ->first();

                                ?>
                                <input class="form-control" name="annualLeaves_<?=$value['emp_id']?>" value="<?=$total_annual_leaves->value('no_of_leaves')+$TransferedAnnualLeaves-$taken_annual_leaves->taken_annual_leaves?>">

                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
            @endforeach

        @endforeach
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
            </div>
        </div>



        <input type="hidden" name="empCode" value="<?=base64_encode(serialize(array_unique($empCode)))?>">
        <?php echo Form::close();?>
    </div>
</div>




