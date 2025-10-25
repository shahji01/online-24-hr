<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
use App\Models\EmployeeTransfer;


?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="HrReport">
                        @if($employee_detail->count() > 0)
                            <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">EMP ID</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Father Name</th>
                                <th class="text-center">Designation</th>
                                <th class="text-center">Dep / Sub Dep</th>
                                <th class="text-center">CNIC</th>
                                <th class="text-center">Contact No</th>
                                <th class="text-center">Emergency No</th>
                                <th class="text-center">Joining Date</th>
                                <th class="text-center">Birth Date</th>
                                <th class="text-center">Joining Salary</th>
                                <th class="text-center">Current Salary</th>
                                <th class="text-center">Job Type</th>
                                {{--<th class="text-center">Fuel Allowances</th>--}}
                                {{--<th class="text-center">Supervisory Allowances</th>--}}
                                <th class="text-center">Status</th>
                            </thead>
                            <tbody>

                            <?php $counter = 1;?>
                            @foreach($employee_detail->get() as $key => $y)
                                <?php
									
                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $current_salary = $y->emp_salary;
                                    if(EmployeePromotion::where([['emp_id', '=', $y->emp_id]])->exists()):
                                        $employee_promotion = EmployeePromotion::where([['emp_id', '=', $y->emp_id]])->orderBy('id', 'desc')->first();
                                        $current_salary = $employee_promotion->salary;
                                    endif;
                                    $EmployeeTransfer = EmployeeTransfer::where([['emp_id', '=', $y->emp_id]])->orderBy('id','desc');

                                     CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center ">{{ $y->emp_id}}</td>
                                    <td class="text-center">{{ $y->emp_name}}</td>
                                    <td class="text-center">{{ $y->emp_father_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$y->designation_id)}}</td>
                                    <td class="text-center">
`                                   {{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$y->emp_department_id) ?? "--" }} /
									<small>{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$y->emp_sub_department_id) ?? "--" }}</small>

									</td>
                                    <td class="text-center">{{ $y->emp_cnic}}</td>
                                    <td class="text-center">{{ $y->emp_contact_no}}</td>
                                    <td class="text-center">{{ $y->emergency_no}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->emp_joining_date) }}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->emp_date_of_birth) }}</td>
                                    <td class="text-center">{{ number_format($y->emp_salary,0) }}</td>
                                    <td class="text-center">{{ number_format($current_salary,0) }}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'job_type','job_type_name',$y->emp_employementstatus_id)}}</td>
                                    <td class="text-center">{{HrHelper::getStatusLabel($y->status)}}</td>

                                </tr>
                            @endforeach
                            @else
                                <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="14">Record Not Found !</td></tr>
                            @endif
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>