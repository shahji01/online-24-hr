<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('company_id');
$leave_day_type = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
$counter = 1;
$leave_type_name = '';
?>
@if (!empty($leave_application_request_list))
    @foreach ($leave_application_request_list as $value)
        <?php
        if ($value->leave_day_type == 1):
            $leave_from = $value->from_date;
            $leave_to = $value->to_date;
        elseif ($value->leave_day_type == 2):
            $leave_from = $value->first_second_half_date;
            $leave_to = '';
        endif;
        ?>
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">@if (array_key_exists($value->employee_id, $employees)){{ $employees[$value->employee_id]->emp_id }}@endif</td>
            <td>@if (array_key_exists($value->employee_id, $employees)){{ $employees[$value->employee_id]->emp_name }}@endif</td>
            <td>@if (array_key_exists($value->leave_type, $leave_types)){{ $leave_type_name = $leave_types[$value->leave_type]->leave_type_name }}@endif</td>
            <td class="text-center">{{ HrHelper::date_format($leave_from) }}</td>
            <td class="text-center">{{ HrHelper::date_format($leave_to) }}</td>
            <td>@if ($value->name == ''){{ $leave_day_type[$value->leave_day_type] }}@else{{ 'System Generated ' . $value->no_of_days . ' Day Leave' }}@endif</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>
            <td class="text-center">{{ HrHelper::getLeaveStatusLabel($value->status) }}</td>
            @if (Auth::user()->acc_type == 'client' || Auth::user()->acc_type == 'admin')
                <td class="text-center">
                    <input type="hidden" name="leave_id[]" id="leave_id_{{ $value->id }}" value="{{ $value->id }}">
                    <input type="hidden" name="employee_id[]" id="employee_id_{{ $value->employee_id }}" value="{{ $value->employee_id }}">
                    <input class="check_list" onchange="checkListChange('{{$value->id}}')" id="check_list_{{ $value->id }}" type="checkbox" name="check_list[]" value="0">
                    <input type="hidden" class="check_input" name="check_input[]" id="check_input_{{ $value->id }}" value="0" />
                </td>
            @endif
            <td class="text-center hidden-print" id="hide-table-row">
                <div class="dropdown">
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1"
                            data-toggle="dropdown">
                        &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        @if (in_array('view', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewLeaveApplicationRequestDetail','{{ $value->id }}','View Leave Application Detail','{{ $m }}')">
                                    View
                                </a>
                            </li>
                        @endif
                        @if (in_array('edit', $operation_rights2))
                            @if ($value->status == 1 && $value->approval_status == 1)
                                <li role="presentation" class="actionsLink">
                                    <a class="edit-modal btn"
                                       onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','{{ $value->id . '|' . $value->employee_id }}','Edit Leave Application Form','{{ $m }}')">
                                        Edit
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if (in_array('delete', $operation_rights2))
                            @if ($value->status == 1 && $value->approval_status == 1)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn"
                                       onclick="deleteLeaveApplicationData('{{ $m }}','{{ $value->id }}')">
                                        Delete
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if (in_array('repost', $operation_rights2))
                            @if ($value->status == 2)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn"
                                       onclick="RepostLeaveApplicationData('{{ $m }}','{{ $value->id }}')">
                                        Repost
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    @if (Auth::user()->acc_type == 'client' || Auth::user()->acc_type == 'admin')
        <tr>
            <td colspan="12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="">
                    <div class="row" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-sm btn-success " onclick="leaveApprovOrReject('approve')" style="margin-right: 2%;">Approve</button>
                        <button type="submit" class="btn btn-sm btn-danger " onclick="leaveApprovOrReject('reject')">Reject</button>
                    </div>
                </div>
            </td>
        </tr>
    @endif
@else
    <tr>
        <td colspan="12" class="text-center text-danger">No Record Found !</td>
    </tr>
@endif
