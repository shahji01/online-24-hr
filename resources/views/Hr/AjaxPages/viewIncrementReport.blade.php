<?php
use \App\Models\EmployeeTransfer;
use \App\Models\Employee;
use \App\Models\HrWarningLetter;
use \App\Models\EmployeePromotion;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="HrReport">
                        @if($employee_promotion->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMR ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Father Name</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Joining Salary</th>
                            <th class="text-center">Increment </th>
                            <th class="text-center">Current Incremented Amount </th>
                            <th class="text-center">Promotion Date</th>
                            {{--<th class="text-center">Supervisory Allowances </th>--}}
                            {{--<th class="text-center">Fuel Amount </th>--}}
                            {{--<th class="text-center">Total Package </th>--}}
                            {{--<th class="text-center">Percentage </th>--}}
                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($employee_promotion->get() as $key => $y)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $employee_detail = Employee::where([['emp_id', '=', $y->emp_id]])->select('id','emp_name','emp_father_name','designation_id',
                                         'emp_salary')->first();

                                CommonHelper::reconnectMasterDatabase();
                                ?>

                                <tr>
                                    <td class="text-center">{{ $counter++ }} </td>
                                    <td class="text-center">{{ $y->emp_id}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_name}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_father_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$y->designation_id)}}</td>
                                    <td class="text-right">{{ number_format($employee_detail->emp_salary,0) }}</td>
                                    <td class="text-right">{{ number_format($y->increment,0) }}</td>
                                    <td class="text-right">{{ number_format($y->salary,0) }}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->promotion_date) }}</td>
                                    {{--<td class="text-center"></td>--}}
                                    {{--<td class="text-center"></td>--}}
                                    {{--<td class="text-center"></td>--}}
                                    {{--<td class="text-center">0</td>--}}
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