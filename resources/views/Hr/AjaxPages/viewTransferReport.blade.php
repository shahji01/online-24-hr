<?php
use \App\Models\EmployeeTransfer;
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;



?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="HrReport">
                        @if($employee_transfer->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMR NO</th>
                            <th class="text-center">EMR Project</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Father Name</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Region</th>
                            <th class="text-center">Category</th>
                            <th class="text-center"></th>
                            <th class="text-center">Location</th>
                            <th class="text-center">CNIC</th>
                            <th class="text-center">Birth Date</th>
                            <th class="text-center">Transfer Date</th>
                            <th class="text-center">Reason</th>
                            </thead>
                            <tbody>

                            <?php $counter = 1;?>
                            <?php $LocationCounter = 0;?>

                            @foreach($employee_transfer as $key => $y)
                                <?php

                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                if ($employee_project_id != '0'){
                                   $employee_detail = Employee::where([['emr_no', '=', $y->emr_no],['employee_project_id', '=', $employee_project_id]])->select('id','emp_name','emp_father_name', 'employee_category_id', 'emp_date_of_birth',
                                    'region_id', 'designation_id', 'emp_cnic','branch_id', 'emp_contact_no', 'emergency_no','employee_project_id')->first();
                                }
                                else {
                                    $employee_detail = Employee::where([['emr_no', '=', $y->emr_no]])->select('id','emp_name','emp_father_name', 'employee_category_id', 'emp_date_of_birth',
                                        'region_id', 'designation_id', 'emp_cnic','branch_id', 'emp_contact_no', 'emergency_no','employee_project_id')->first();
                                }

                                CommonHelper::reconnectMasterDatabase();
                                if ($key > 0):
                                    $prev = $employee_transfer[$key-1];
                                    if($y->emr_no != $prev['emr_no']):
                                        $LocationCounter = 0;
                                    endif;
                                endif;

                                ?>

                                @if($employee_detail->employee_project_id != 0 && $LocationCounter == 0)
                                    <tr>
                                        <td class="text-center"><?php echo $counter++;?></td>
                                        <td class="text-center">{{ $y->emr_no }}</td>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$employee_detail->employee_project_id)}}</td>
                                        <td class="text-center">{{ $employee_detail->emp_name}}</td>
                                        <td class="text-center">{{ $employee_detail->emp_father_name}}</td>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee_detail->designation_id)}}</td>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$employee_detail->region_id) }} </td>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$employee_detail->employee_category_id)}}</td>
                                        <td class="text-center"><span class="badge badge-pill badge-secondary">{{ HrHelper::ordinal($LocationCounter = 1) }}</span></td>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$employee_detail->branch_id)}} </td>
                                        <td class="text-center">{{ $employee_detail->emp_cnic}}</td>
                                        <td class="text-center">{{ HrHelper::date_format($employee_detail->emp_date_of_birth) }}</td>
                                        <td class="text-center">--</td>
                                        <td class="text-center">--</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center">{{ $y->emr_no }}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$employee_detail->employee_project_id)}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_name}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_father_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee_detail->designation_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$employee_detail->region_id)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$employee_detail->employee_category_id)}}</td>
                                    <td class="text-center"><span class="badge badge-pill badge-secondary">{{ HrHelper::ordinal(++$LocationCounter) }}</span></td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$y->location_id)}} </td>
                                    <td class="text-center">{{ $employee_detail->emp_cnic}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($employee_detail->emp_date_of_birth) }}</td>
                                    <td class="text-center">{{ $y->date }}</td>
                                    <td class="text-center">--</td>
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