<?php
use App\Helpers\HrHelper;
use App\Models\TransferedLeaves;
use App\Helpers\CommonHelper;
use App\Models\Employee;

$id = Input::get('id');
$m = Input::get('m');

if ($leave_application_data->leave_day_type == 1):
    $leave_from = $leave_application_data->from_date;
    $leave_to = $leave_application_data->to_date;
elseif ($leave_application_data->leave_day_type == 2):
    $leave_from = $leave_application_data->first_second_half_date;
    $leave_to = '';
endif;

?>
<div class="row"></div>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ClsHide" style="border-bottom: double;">
        @if(Auth::user()->acc_type == 'client')
            @if ($leave_application_data->approval_status != 3 && $leave_application_data->approval_status != 2)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication('{{ $leave_application_data->id }}',2,'{{ $leave_day_type }}')">Approve (HR)</button>
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication('{{ $leave_application_data->id }}',3,'{{ $leave_day_type }}')">Reject (HR)</button>
            @endif
        @endif

        @if(Auth::user()->acc_type == 'user' && $leave_application_data->employee_id != Auth::user()->employee_id)
            @if ($leave_application_data->approval_status_lm != 3 && $leave_application_data->approval_status_lm != 2)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication2('{{ $leave_application_data }}',2,'{{ $leave_day_type }}')">Approve(LM)</button>
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication2('{{ $leave_application_data->id }}',3,'{{ $leave_day_type }}')">Reject (LM)</button>
            @endif
        @endif

        @if(Auth::user()->acc_type == 'client')
            @if ($leave_application_data->approval_status == 2 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication('{{ $leave_application_data->id }}',3,'{{ $leave_day_type }}')">Reject (HR)</button>
            @endif
        @endif


        @if(Auth::user()->acc_type == 'user')
            @if ($leave_application_data->approval_status_lm == 2 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication2('{{ $leave_application_data->id }}',3,'{{ $leave_day_type }}')">Reject (LM)</button>
            @endif
        @endif

        @if(Auth::user()->acc_type == 'client')
            @if ($leave_application_data->approval_status == 3 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication('{{ $leave_application_data->id }}',2,'{{ $leave_day_type }}')">Approve (HR)</button>
            @endif
        @endif

        @if(Auth::user()->acc_type == 'user')
            @if ($leave_application_data->approval_status_lm == 3 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication2('{{ $leave_application_data->id }}',2,'{{ $leave_day_type }}')">Approve (LM)</button>
            @endif
        @endif

        @if ($leave_application_data->status == 2)
            <button class="btn btn-sm btn-info" onclick="RepostLeaveApplicationData('{{ $m }}','{{ $leave_application_data->id }}')">Repost</button>
        @elseif($leave_application_data->status == 1 && $leave_application_data->approval_status == 1)
            <button class="btn btn-sm btn-danger" onclick="deleteLeaveApplicationData('{{ $m }}','{{ $leave_application_data->id }}')">Delete</button>
        @endif
    </div>
</div>

@include('Hr.LeaveApplication.viewLeaveBalanceTable')
<div class="row">&nbsp;</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-hover">
                <thead>
                <th>Employee Name</th>
                <td>{{ $employee->emp_name }}</td>
                <th>Designation</th>
                <td>{{ $employee->new_designation }}</td>
                </thead>
                <thead>
                <th>Leave Type</th>
                <td>@if(array_key_exists($leave_application_data->leave_type, $leave_type)){{ $leave_type[$leave_application_data->leave_type]->leave_type_name }}@endif</td>
                <th>Day Type</th>
                <td>{{ $leave_day_type_label }}</td>
                </thead>
                @if($leave_day_type == 1)
                    <thead>
                    <th>Leave From </th>
                    <td>{{ date("d-M-Y", strtotime($leave_application_data->from_date)) }}</td>
                    <th>Leave Till </th>
                    <td>{{ date("d-M-Y", strtotime($leave_application_data->to_date)) }}</td>
                    </thead>
                @elseif($leave_day_type == 2)
                    <thead>
                    <th>First  / Second Half</th>
                    <td>{{ $leave_half_day[$leave_application_data->first_second_half] }}</td>
                    <th>Leave Date </th>
                    <td>{{ date("d-M-Y", strtotime($leave_application_data->first_second_half_date)) }}</td>
                    </thead>
                @endif
                <thead>
                <th>No.of Days</th>
                <td>{{ $leave_application_data->no_of_days }}</td>
                <th>Leave From</th>
                <td>{{ HrHelper::date_format($leave_from) }}</td>
                </thead>
                <thead>
                <th>Approval Status (HR)</th>
                <td>{{ HrHelper::getApprovalStatusLabel($leave_application_data->approval_status) }}</td>
                <th>Leave Till</th>
                <td>{{ HrHelper::date_format($leave_to) }}</td>
                </thead>
                <thead>
                <th>Approval Status (LM)</th>
                <td>{{ HrHelper::getApprovalStatusLabel($leave_application_data->approval_status_lm) }}</td>
                <th>Reason</th>
                <td>{{ $leave_application_data->reason }}</td>
                </thead>
                <thead>

                <th>Created On</th>
                <td>{{ date("d-M-Y", strtotime($leave_application_data->date)) }}</td>
                <th>Address While On Leave</th>
                <td>{{ $leave_application_data->leave_address }}</td>

                </thead>
            </table>
        </div>
    </div>
</div>