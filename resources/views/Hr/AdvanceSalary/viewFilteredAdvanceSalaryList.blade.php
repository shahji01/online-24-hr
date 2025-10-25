<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('company_id');
$counter = 1;
?>
@if(!empty($advance_salary))
    @foreach($advance_salary as $key => $y)
        <?php
        $monthName = "";
        if($y->deduction_month > 0){
            $monthName = date('M', mktime(0, 0, 0, $y->deduction_month=(int)$y->deduction_month, 10));
        }
        ?>
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">@if(array_key_exists($y->employee_id, $employees)) {{ $employees[$y->employee_id]->emp_id }} @endif</td>
            <td>@if(array_key_exists($y->employee_id, $employees)) {{ $employees[$y->employee_id]->emp_name }} @endif</td>
            <td class="text-right">{{ number_format($y->advance_salary_amount,0) }}</td>
            <td class="text-center">{{ HrHelper::date_format($y->salary_needed_on) }}</td>
            <td class="text-center">{{ $monthName.' / '.$y->deduction_year }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($y->approval_status) }}</td>
            <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
            @if (Auth::user()->acc_type == 'client')
                <td class="text-center">
                    @if ($y->approval_status == '1')
                        <input type="checkbox" class="check_input" name="check_input[]" id="check_input_{{ $y->id }}" value="{{ $y->id }}" />
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
                        @if(in_array('approve', $operation_rights2))
                            @if ($y->approval_status != 2)
                                <li role="presentation" class="actionsLink">
                                    <a class="edit-modal btn" onclick="approveAndRejectTableRecords('{{ $m }}','<?php echo $y->id;?>', 2, 'advance_salary')">
                                        Approve
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(in_array('reject', $operation_rights2))
                            @if ($y->approval_status != 3)
                                <li role="presentation" class="actionsLink">
                                    <a class="edit-modal btn" onclick="approveAndRejectTableRecords('{{ $m }}','{{ $y->id }}', '3', 'advance_salary')">
                                        Reject
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(in_array('view', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewAdvanceSalaryDetail','{{ $y->id }}','View Advance Salary Detail','{{ $m }}')">
                                    View
                                </a>
                            </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editAdvanceSalaryDetailForm','{{ $y->id }}','Edit Advance Salary Detail','{{ $m }}')">
                                    Edit
                                </a>
                            </li>
                        @endif
                        @if(in_array('repost', $operation_rights2))
                            @if ($y->status == 2)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="repostOneTableRecords('{{ $m }}','{{ $y->id }}','advance_salary','approval_status')">
                                        Repost
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(in_array('delete', $operation_rights2))
                            @if ($y->status == 1)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="deleteRowCompanyRecords('{{ $m }}','{{ $y->id }}','advance_salary')">
                                        Delete
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach

    @if (Auth::user()->acc_type == 'client')
        <tr>
            <td colspan="16">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-sm btn-success" onclick="approveAndRejectAll('2','advance_salary')" style="margin-right: 2%;">Approve</button>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="approveAndRejectAll('3','advance_salary')">Reject</button>
                    </div>
                </div>
            </td>
        </tr>
    @endif
@else
    <tr>
        <td class="text-danger text-center" colspan="9">No Record Found !</td>
    </tr>
@endif