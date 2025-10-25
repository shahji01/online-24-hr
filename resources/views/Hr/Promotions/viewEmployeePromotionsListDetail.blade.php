<?php
$m = Input::get('company_id');
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
$counter = 1;
?>
@if(!empty($employee_promotions))
    @foreach($employee_promotions as $value)
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">
                @if(array_key_exists($value->employee_id, $employees)){{ $employees[$value->employee_id]->emp_id }} @endif
            </td>
            <td>
                @if(array_key_exists($value->employee_id, $employees)){{ $employees[$value->employee_id]->emp_name }} @endif
            </td>
            @if($value->designation_id  > 0)
                <td>
                    @if(array_key_exists($value->designation_id, $designations)){{ $designations[$value->designation_id]->designation_name }} @endif
                </td>
            @else
                <td class="text-center">-</td>
            @endif
            <td class="text-right">{{ number_format($value->increment,0) }}</td>
            <td class="text-right">{{ number_format($value->salary,0) }}</td>
            <td class="text-center">{{ HrHelper::date_format($value->promotion_date) }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
            <td class="text-center">
                <a target="_blank" href="{{ url('/') }}/hr/viewEmployeePromotionLetter/{{ $value->id }}/{{ $value->employee_id }}">
                    View
                </a>
            </td>
            <input type="hidden" name="promotion_id[]" id="promotion_id_{{ $value->id }}" value="{{ $value->id }}">
            <input type="hidden" name="employee_id[]" id="employee_id_{{ $value->employee_id }}" value="{{ $value->employee_id }}">
            @if (Auth::user()->acc_type == 'client')
                <td class="text-center">
                    @if ($value->approval_status == '1')
                        <input type="checkbox" class="check_input" name="check_input[]" id="check_input_{{ $value->id }}" value="{{ $value->id }}" />
                    @else
                        -
                    @endif
                </td>
            @endif
            <td class="text-center hidden-print" id="hide-table-row">
                <div class="dropdown">
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                        &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        @if(in_array('view', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hdc/viewPromotionLetter','{{ $value->id }}','Promotion Detail','{{ $m }}')">
                                    View
                                </a>
                            </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editEmployeePromotionDetailForm','{{ $value->id }}','Edit Employee Promotion Form','{{ $m }}')">
                                    Edit
                                </a>
                            </li>
                        @endif
                        @if(in_array('delete', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('{{ $m }}','{{ $value->id }}', 'employee_promotion')">
                                    Delete
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
        <td class="text-center text-danger" colspan="12">Record Not Found !</td>
    </tr>
@endif

@if (Auth::user()->acc_type == 'client')
    <tr>
        <td colspan="16">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row" style="justify-content: flex-end;">
                    <button type="submit" class="btn btn-sm btn-success" onclick="approveAndRejectAll('2','employee_promotion')" style="margin-right: 2%;">Approve</button>
                    <button type="submit" class="btn btn-sm btn-danger" onclick="approveAndRejectAll('3','employee_promotion')">Reject</button>
                </div>
            </div>
        </td>
    </tr>
@endif