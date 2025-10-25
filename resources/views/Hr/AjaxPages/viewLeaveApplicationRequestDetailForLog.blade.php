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
                                        <th class="text-center">Leave Type</th>
                                        <th class="text-center">Leave Day Type</th>
                                        <th class="text-center">No Of Days</th>
                                        <th class="text-center">From</th>
                                        <th class="text-center">Till</th>
                                        <th class="text-center">Backup Contact</th>
                                        <th class="text-center">Approval Status</th>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{$leaveApplication->emr_no}}</td>
                                            <td class="text-center">{{HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$leaveApplication ->emr_no,'emr_no')}}</td>
                                            <td class="text-center">
                                                @if($leaveApplication->leave_type == 1) Annual
                                                @elseif($leaveApplication->leave_type == 2) Sick
                                                @elseif($leaveApplication->leave_type == 3) Casual
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($leaveApplication->leave_day_type == 1) Full Day
                                                @elseif($leaveApplication->leave_day_type == 2) Half Day
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $leaveApplicationData->no_of_days }}</td>
                                            <td class="text-center">{{ HrHelper::date_format($leaveApplicationData->from_date) }}</td>
                                            <td class="text-center">{{ HrHelper::date_format($leaveApplicationData->to_date) }}</td>
                                            <td class="text-center">{{ $leaveApplication->backup_contact }}</td>
                                            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($leaveApplication->approval_status) }}</td>
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

