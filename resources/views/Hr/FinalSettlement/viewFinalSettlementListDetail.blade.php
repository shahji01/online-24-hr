<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;
?>
@foreach($final_settlement as $key => $y)
    <tr>
        <td class="text-center">{{ $counter++ }}</td>
        <td class="text-center">{{ $employees[$y->employee_id]->emp_id }}</td>
        <td>{{ $employees[$y->employee_id]->emp_name }}</td>
        <td class="text-center">{{ HrHelper::date_format($y->last_working_date) }}</td>
        <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
        <td class="text-center hidden-print">
            <div class="dropdown">
                <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                    &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    @if(in_array('view', $operation_rights2))
                        <li role="presentation" class="actionsLink">
                            <a class="edit-modal btn" href="<?= url("/hdc/viewFinalSettlementDetail/{$y->id}?m=12")?>" target="_blank">
                                View
                            </a>
                        </li>
                    @endif
                    @if(in_array('edit', $operation_rights2))
                        <li role="presentation" class="actionsLink">
                            <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editFinalSettlementDetailForm','{{ $y->id }}','Edit Final Settlement Form','12')">
                                Edit
                            </a>
                        </li>
                    @endif
                    @if(in_array('view', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                            <a class="edit-modal btn" target="_blank" href="{{ url('/') }}/hr/downloadFnf/{{ Input::get('m') }}/{{ $y->id }}">
                                Download
                            </a>
                        </li>
                    @endif
                    @if(in_array('delete', $operation_rights2))
                        @if($y->status == 1)
                            <li role="presentation" class="actionsLink">
                                <a class="delete-modal btn" onclick="deleteEmployeeFinalSettlement('{{ $company_id }}','{{ $y->id }}','{{ $y->employee_id }}','final_settlement')">
                                    Delete
                                </a>
                            </li>
                        @endif
                    @endif
                    @if(in_array('repost', $operation_rights2))
                        @if($y->status == 2)
                            <li role="presentation" class="actionsLink">
                                <a class="delete-modal btn" onclick="repostOneTableRecords('','{{ $y->id }}','final_settlement')">
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