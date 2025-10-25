<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$accType = Auth::user()->acc_type;
$counter = 1;
CommonHelper::companyDatabaseConnection(Input::get('m'));
?>
 
@foreach($final_settlement as $key => $y)

    <?php $emp_name = Employee::select('emp_id','emp_name','emp_father_name')->where([['id', '=', $y->id]])->first(); ?>
    <tr>
        <td class="text-center">{{ $counter++ }}</td>
        <td class="text-center">{{ $emp_name->emp_id }}</td>
        <td>{{ $emp_name->emp_name.' '. $emp_name->emp_father_name }}</td>
        <td class="text-center">{{ HrHelper::date_format($y->last_working_date) }}</td>
        <td class="text-center">{{HrHelper::getStatusLabel($y->status)}}</td>
        <td class="text-center hidden-print">
            <div class="dropdown">
				<button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
					<i class="fas fa-chevron-down"
                       ></i></button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    
                    <li role="presentation">
                        <a style="cursor:pointer" class="edit-modal btn" href="<?= url("/hdc/viewFinalSettlementDetail/{$y->id}?m=12")?>" target="_blank">
                            View
                        </a>
                    </li>
                    
                    @if(in_array('edit', $operation_rights))
                    <li role="presentation">
                        <a style="cursor:pointer" class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editFinalSettlementDetailForm','{{ $y->id }}','Edit Final Settlement Detail','12')">
                            Edit
                        </a>
                    </li>
                    @endif
                     <li>
                        <a class="edit-modal btn" target="_blank" href="{{ url('/') }}/hr/downloadFnf/{{ Input::get('m') }}/{{ $y->id }}">
                            Downlaod
                        </a>
                    </li>
                    <!--@if ($y->status == 2)-->
                    <!--    <li role="presentation">-->
                    <!--        <a style="cursor:pointer" class="delete-modal btn" onclick="repostOneTableRecords('','{{ $y->id }}','final_settlement')">-->
                    <!--            Repost-->
                    <!--        </a>-->
                    <!--    </li>-->
                    <!--@endif-->
                    @if(in_array('delete', $operation_rights))
                    @if ($y->status == 1)
                        <li role="presentation">
                            <a class="delete-modal btn"
                            onclick="deleteEmployeeFinalSettlement('{{ Input::get('m')}}','{{ $y->id }}','{{ $emp_name->id }}','final_settlement')">
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
@php CommonHelper::reconnectMasterDatabase(); @endphp