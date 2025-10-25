<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;
?>
@foreach($employee_projects as $key => $y)
    <tr>
        <td class="text-center">{{ $counter++ }}</td>
        <td>{{ $y->project_name }}</td>
        <td class="text-center">{{ $y->project_code }}</td>
        <td>{{ $y->username }}</td>
        <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
        <td id="hide-table-row" class="hide-table text-center hidden-print">
            <div class="dropdown">
                <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                    &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    @if(in_array('edit', $operation_rights))
                        <li role="presentation" class="actionsLink">
                            <a  class="delete-modal btn" onclick="showMasterTableEditModel('hr/editProjectsForm','{{ $y->id }}','Edit Porjects Form','{{ $m }}')">
                                Edit
                            </a>
                        </li>
                    @endif
                    @if(in_array('repost', $operation_rights))
                        @if($y->status == 2)
                            <li role="presentation" class="actionsLink" onclick="repostMasterTableRecords('{{ $y->id }}','employee_projects')">
                                <a class="delete-modal btn">
                                    Repost
                                </a>
                            </li>
                        @endif
                    @endif
                    @if(in_array('delete', $operation_rights))
                        @if($y->status == 1)
                            <li role="presentation" class="actionsLink" onclick="deleteRowMasterTable('{{ $y->id }}','employee_projects')">
                                <a class="delete-modal btn" >
                                    Delete
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </td>
    </tr>

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
@endforeach