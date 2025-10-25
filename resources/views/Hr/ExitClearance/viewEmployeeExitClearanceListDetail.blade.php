<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;
$m = $company_id;
?>
@if(!empty($employee_exit))
    @foreach($employee_exit as  $row)
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">{{ $row->emp_id }}</td>
            <td>{{ $row->emp_name }} </td>
            <td>
                @if(array_key_exists($row->department_id, $departments)){{ $departments[$row->department_id]->department_name }} @endif
            </td>
            <td>
                @if(array_key_exists($row->designation_id, $designations)){{ $designations[$row->designation_id]->designation_name }} @endif
            </td>
            <td class="text-center">{{ HrHelper::date_format($row->last_working_date) }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($row->approval_status) }}</td>
            <td class="text-center hidden-print">
                <div class="dropdown">
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                        &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        @if(in_array('view', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hdc/viewEmployeeExitClearanceDetail','{{ $row->id }}','Employee Exit CLearance Detail','{{ $m }}','hr/viewEmployeeExitClearanceList')">
                                    View
                                </a>
                            </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editEmployeeExitClearanceDetailForm','{{ $row->id }}','Edit Employee Exit CLearance Form','{{ $m }}')">
                                    Edit
                                </a>
                            </li>
                        @endif
                        @if(in_array('repost', $operation_rights2))
                            @if($row->status == 2)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="repostCompanyTableRecord('{{ $m }}','{{ $row->id }}','employee_exit')">
                                        Repost
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(in_array('delete', $operation_rights2))
                            @if($row->status == 1)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="deleteEmployeeExitClearance('{{ $m }}','{{ $row->id }}', '{{ $row->employee_id }}','employee_exit')">
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
@else
    <tr>
        <td class="text-danger text-center" colspan="8">No Record Found !</td>
    </tr>
@endif
<script>

    var table = $('#exportList').DataTable({
        "dom": "t",
        "bPaginate" : false,
        "bLengthChange" : true,
        "bSort" : false,
        "bInfo" : false,
        "bAutoWidth" : false

    });

    $('#searchId').keyup( function() {
        table.search(this.value).draw();
    });

</script>
