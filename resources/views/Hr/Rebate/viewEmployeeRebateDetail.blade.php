<?php
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\EmployeeRebateDocuments;
$counter = 1;
CommonHelper::companyDatabaseConnection($company_id);
?>
@if($rebate->count() > 0)
    @foreach($rebate->get() as $value)
        <?php
        $rebate_documents = EmployeeRebateDocuments::where([['rebate_id', '=', $value->id],['status', '=', 1]]);
        ?>
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">@if(array_key_exists($value->employee_id,$employees)) {{ $employees[$value->employee_id]->emp_id }} @endif</td>
            <td>@if(array_key_exists($value->employee_id,$employees)) {{ $employees[$value->employee_id]->emp_name }} @endif</td>
            <td class="text-center">{{ date("F-Y", strtotime($value->year.'-'.$value->month)) }}</td>
            <td>{{ $value->type }}</td>
            <td>{{ $value->nature }}</td>
            <td class="text-right">{{ number_format($value->actual_investment,0) }}</td>
            <td class="text-right">{{ number_format($value->rebate_amount,0) }}</td>
            @if($rebate_documents->count() > 0)
                <td class="text-center hidden-print">
                    <a onclick="showDetailModelFourParamerter('hdc/viewEmployeeRebateDocuments','{{ $value->id }}','View Rebate Document','{{ $m }}','hr/viewRebateList')" class=" btn btn-info btn-sm" style="color: white;cursor: pointer;">View</a>
                </td>
            @else
                <td class="text-center hidden-print"> -- </td>
            @endif
            <td class="text-center hidden-print">{{ HrHelper::getStatusLabel($value->status) }}</td>
            <td class="text-center hidden-print">
                <div class="dropdown">
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                        &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        @if($value->status == 1)
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="deleteEmployeeRebate('{{ $value->id }}','{{ $company_id }}', 'rebate')">
                                    Delete
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    <?php CommonHelper::reconnectMasterDatabase(); ?>
@else
    <tr>
        <td class="text-center text-danger" colspan="11">Record Not Found !</td>
    </tr>
@endif
