<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
use App\Models\EmployeeTransfer;
$location_id = '';

?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="HrReport">
                        @if($employee_medical->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Employee Project</th>
                            <th class="text-center">Father Name</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Date of Birth</th>
                            <th class="text-center">Disease</th>
                            <th class="text-center"> Date</th>
                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($employee_medical->get() as $key => $y)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                if($employee_project_id != '0'){
                                    $employee_detail = Employee::where([['emr_no', '=', $y->emp_id],['employee_project_id','=',$employee_project_id]])->select('id','emp_name','emp_father_name', 'emp_date_of_birth',
                                        'designation_id','employee_project_id')->first();
                                }
                                else{
                                    $employee_detail = Employee::where([['emp_id', '=', $y->emp_id]])->select('id','emp_name','emp_father_name', 'emp_date_of_birth',
                                        'region_id', 'designation_id', 'branch_id','employee_project_id')->first();
                                }

                                $EmployeeTransfer = EmployeeTransfer::where([['emr_no', '=', $y->emr_no]])->orderBy('id','desc')->first();
                                if(count($EmployeeTransfer) != '0'){
                                    $location_id = $EmployeeTransfer->location_id;
                                }
                                else{
                                    $location_id = $employee_detail->branch_id;
                                }

                                CommonHelper::reconnectMasterDatabase();
                                if($employee_detail != ''){
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center">{{ $y->emr_no}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$employee_detail->employee_project_id)}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_father_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee_detail->designation_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$employee_detail->region_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$location_id)}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($employee_detail->emp_date_of_birth) }}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'diseases','disease_type',$y->disease_type_id)}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->disease_date) }}</td>
                                </tr>
                                <?php } else { ?>
                                <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="14">Record Not Found !</td></tr>
                                <?php } ?>
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