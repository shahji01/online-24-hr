<?php
use App\Helpers\HrHelper;

$counter = 1;
?>

<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                <thead>
                <th class="text-center">S No.</th>
                <th class="text-center">Emp ID</th>
                <th class="text-center">Emp Name</th>
                <th class="text-center">Leave Type</th>
                <th class="text-center">Day Type</th>
                <th class="text-center">From</th>
                <th class="text-center">To</th>
                <th class="text-center">Approval Status (HR)</th>
                <th class="text-center">Approval Status (RM)</th>
                <th class="text-center">Status</th>
                <th class="text-center hidden-print">Action</th>
                </thead>
                <tbody>
                @if(count($leave_application_request_list) != '0')
                    @foreach($leave_application_request_list as $value)
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td class="text-center">{{ $emp_data->emp_id }}</td>
                            <td class="text-center">{{ $emp_data->emp_name }}</td>
                            <td>@if (array_key_exists($value->leave_type, $leave_type)){{ $leave_type_name = $leave_type[$value->leave_type]->leave_type_name }}@endif</td>
                            @if($value->name=='')
                                <td class="text-center">{{ $leave_day_type[$value->leave_day_type] }}</td>
                            @else
                                <td class="text-center">{{ $value->no_of_days." Day Leave" }}</td>
                            @endif
                            @if($value->name=='')
                                <td class="text-center">{{ $value->leave_day_type == '1'? HrHelper::date_format($value->from_date) : HrHelper::date_format($value->first_second_half_date)  }}</td>
                                <td class="text-center">{{ $value->leave_day_type == '1'? HrHelper::date_format($value->to_date)  : HrHelper::date_format($value->first_second_half_date)  }}</td>
                            @else
                                <?php
                                $explode_month_name=explode('-',$value->name);
                                $month_name = date("F", mktime(0, 0, 0, $explode_month_name[1], 10)); ?>
                                <td class="text-center">{{ $month_name }} Generated Leave</td>
                                <td class="text-center">{{ $month_name }} Generated Leave</td>
                            @endif
                            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>
                            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                            <td class="text-center hidden-print">
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                        <i class="fa fa-chevron-down" ></i></button>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                        @if($value->name=='')
                                            @if(Auth::user()->acc_type=='client' || $emp_data->id == Auth::user()->employee_id)
                                                @if($value->approval_status_lm != 2 || Auth::user()->acc_type == 'client')
                                                    <li role="presentation" class="actionsLink">
                                                        <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','{{ $value->id . '|' . $value->employee_id }}','Edit Leave Application Form','{{ $m }}')">
                                                            Edit
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                            <li role="presentation" class="actionsLink">
                                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewLeaveApplicationRequestDetail','{{ $value->id }}','View Leave Application Detail','{{ $m }}')">
                                                    View
                                                </a>
                                            </li>
                                        @endif
                                        @if ($value->status == 1 && $value->approval_status == 1)
                                            <li role="presentation" class="actionsLink">
                                                <a class="delete-modal btn"
                                                   onclick="deleteLeaveApplicationData('{{ $m }}','{{ $value->id }}')">
                                                    Delete
                                                </a>
                                            </li>
                                        @endif
                                        @if ($value->status == 2)
                                            <li role="presentation" class="actionsLink">
                                                <a class="delete-modal btn"
                                                   onclick="RepostLeaveApplicationData('{{ $m }}','{{ $value->id }}')">
                                                    Repost
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11" class="text-center text-danger"><strong>No Record Found</strong></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
{{--<div class="tab-pane fade in" id="mineLeaves"></div>--}}