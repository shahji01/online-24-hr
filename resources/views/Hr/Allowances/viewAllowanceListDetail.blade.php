<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;
?>
@if(!empty($allowance))
    @foreach($allowance as $key => $value)
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">{{ $value->emp_id }}</td>
            <td>{{ $value->emp_name }}</td>
            <td>@if(array_key_exists($value->department_id, $departments)) {{ $departments[$value->department_id]->department_name }} @endif</td>
            <td>@if(array_key_exists($value->category_id, $category)) {{ $category[$value->category_id]->employee_category_name }} @endif</td>
            <td>@if(array_key_exists($value->project_id, $projects)) {{ $projects[$value->project_id]->project_name }} @endif</td>
            <td>@if(array_key_exists($value->allowance_type_id, $allowance_types)) {{ $allowance_types[$value->allowance_type_id]->allowance_type }} @endif</td>
            <td class="text-center">@if($value->year != '') {{ date("F Y", strtotime($value->year.'-'.$value->month.'-01')) }} @endif</td>
            <td class="text-right">{{ number_format($value->allowance_amount,0) }}</td>
            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
            <td class="text-center hidden-print" id="hide-table-row">
                <div class="dropdown">
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                        &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        @if(in_array('view', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewAllowanceDetail','{{ $value->id }}','Allowance Detail','{{ $company_id }}')">
                                    View
                                </a>
                            </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editAllowanceDetailForm','{{ $value->id }}','Edit Allowance Form','{{ $company_id }}')">
                                    Edit
                                </a>
                            </li>
                        @endif
                        @if(in_array('delete', $operation_rights2))
                            @if($value->status == 1)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('{{ $company_id }}','{{ $value->id }}','allowance')">
                                        Delete
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(in_array('repost', $operation_rights2))
                            @if($value->status == 2)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="repostOneTableRecords('{{ $company_id }}','{{ $value->id }}','allowance')">
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
@else
    <tr>
        <td class="text-danger text-center" colspan="12">No Record Found !</td>
    </tr>
@endif
