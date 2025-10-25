<?php
use App\Models\Employee;
use App\Models\EmployeeExit;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\Gratuity;
$total_gratuity = 0;
?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="HrReport">
                        @if($gratuityDetails->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMR No</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Employee Project</th>
                            <th class="text-center">Region</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Joining Date</th>
                            <th class="text-center">Till Date</th>
                            <th class="text-center">Total Months</th>
                            <th class="text-center">Gratuity</th>
                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($gratuityDetails->get() as $key => $y)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                if($employee_project_id != '0'){
                                     $employee = Employee::select('id','employee_project_id')->where([['emr_no', '=', $y->emr_no],['status', '=', 1],['employee_project_id','=',$employee_project_id]])->first();
                                     }
                                else{
                                    $employee = Employee::select('id','employee_project_id')->where([['emr_no', '=', $y->emr_no],['status', '=', 1]])->first();
                                }
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                @if(count($employee) > 0)
                                    <tr>
                                        <td class="text-center"><?php $total_gratuity+=$y->gratuity;  echo $counter++;?></td>
                                        <td class="text-center">{{ $y->emr_no}}</td>
                                        <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$y->emr_no, 'emr_no') }}</td>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$employee->employee_project_id)}}</td>
                                        <td class="text-center">@if($y->region_id != ''){{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$y->region_id)}}@endif</td>
                                        <td class="text-center">@if($y->employee_category_id != ''){{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$y->employee_category_id)}}@endif</td>
                                        <td class="text-center">{{ HrHelper::date_format($y->from_date) }}</td>
                                        <td class="text-center">{{ HrHelper::date_format($y->to_date) }}</td>
                                        <td class="text-center">{{ $y->year_month }}</td>
                                        <td class="text-right">{{ number_format($y->gratuity,0) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tfoot>
                            <tr>
                                <td colspan="9" class="text-right"><b>Total</b></td>
                                <td colspan="1" class="text-center"><b><?=number_format($total_gratuity,1)?></b></td>
                            </tr>
                            </tfoot>
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