<?php

$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
?>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body" id="PrintLoanRequestList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                                        <thead>
                                        <th class="text-center">Emr No</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Designation</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center">Increment</th>
                                        <th class="text-center">Salary</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Approval Status</th>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{$employeePromotions->emr_no}}</td>
                                            <td class="text-center">{{HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$employeePromotions->emr_no,'emr_no')}}</td>
                                            <td class="text-center">{{HrHelper::getMasterTableValueById($m,'designation','designation_name',$employeePromotions->designation_id)}}</td>
                                            <td class="text-center">{{HrHelper::getMasterTableValueById($m,'grades','employee_grade_type',$employeePromotions->grade_id)}}</td>
                                            <td class="text-right">{{ number_format($employeePromotions->increment,0) }}</td>
                                            <td class="text-right">{{ number_format($employeePromotions->salary,0) }}</td>
                                            <td class="text-center">{{HrHelper::date_format($employeePromotions->promotion_date)}}</td>
                                            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($employeePromotions->approval_status) }}</td>
                                        </tr>
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