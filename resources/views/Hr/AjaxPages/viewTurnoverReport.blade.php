<?php
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
                        @if($employee_exit->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Father Name</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">CNIC</th>
                            <th class="text-center">Joining Date</th>
                            <th class="text-center">Date Of Exit</th>
                            <th class="text-center">Reason</th>

                            </thead>
                            <tbody>
                            <?php $counter = 1;?> 
                            @foreach($employee_exit->get() as $key => $y)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $employee_detail = Employee::where([['emp_id', '=', $y->emp_id]])->select('id','emp_name','emp_father_name','emp_joining_date','designation_id', 'emp_cnic')->first();
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center">{{ $y->emp_id}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_name}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_father_name }}</td> 
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee_detail->designation_id)}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_cnic}}</td>
                                    <td class="text-center">{{ HrHelper::date_format($employee_detail->emp_joining_date) }}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->last_working_date) }}</td>
                                    <td class="text-center">
                                        @if($y->leaving_type == 1) Resignation @endif
                                        @if($y->leaving_type == 2) Retirement @endif
                                        @if($y->leaving_type == 3) Termination @endif
                                        @if($y->leaving_type == 4) Dismissal @endif
                                        @if($y->leaving_type == 5) Demise @endif
                                    </td>
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