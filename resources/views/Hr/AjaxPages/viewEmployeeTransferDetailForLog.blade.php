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
                                        <th class="text-center">EMR No</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Designation</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center">Increment</th>
                                        <th class="text-center">Salary</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Approval Status</th>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $designation = 0;
                                        $salary = 0;
                                        $grade_id = 0;
                                        $increment = 0;
                                        if($employeeTransfers->promotion_id != 0):
                                            $designation  = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','designation_id',$employeeTransfers->promotion_id,'id');
                                            $salary  = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','salary',$employeeTransfers->promotion_id,'id');
                                            $increment  = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','increment',$employeeTransfers->promotion_id,'id');
                                            $grade_id = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','grade_id',$employeeTransfers->promotion_id,'id');
                                        endif;
                                        ?>

                                        <tr>
                                            <td class="text-center">{{$employeeTransfers->emr_no}}</td>
                                            <td class="text-center">{{HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$employeeTransfers->emr_no,'emr_no')}}</td>
                                            <td class="text-center">{{HrHelper::getMasterTableValueById($m,'locations','employee_location',$employeeTransfers->location_id)}}</td>
                                            <td class="text-center">
                                                @if($designation != 0)
                                                    {{ HrHelper::getMasterTableValueById($m,'designation','designation_name',$designation) }}
                                                @else
                                                    --</td>
                                            @endif

                                            <td class="text-center">
                                                @if($grade_id != 0)
                                                    {{ HrHelper::getMasterTableValueById($m,'grades','employee_grade_type',$grade_id) }}
                                                @else
                                                    --</td>
                                            @endif
                                            <td class="text-right">
                                                @if($increment != 0)
                                                    {{ number_format($increment,0) }}
                                                @else
                                                    --</td>
                                            @endif
                                            <td class="text-right">
                                                @if($salary != 0)
                                                    {{ number_format($salary,0) }}
                                                @else
                                                    --</td>
                                            @endif
                                            <td class="text-center">{{HrHelper::date_format($employeeTransfers->date)}}</td>
                                            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($employeeTransfers->approval_status) }}</td>
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

