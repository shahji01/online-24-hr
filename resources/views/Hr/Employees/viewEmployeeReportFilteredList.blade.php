<?php
$m = Input::get('company_id');
$counter = 1;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>

<table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
    <thead>
    <tr>
        <th class="text-center">S.No</th>
        <th class="text-center">Emp ID</th>
        <th class="text-center">Attendance id</th>
        <th class="text-center">Emp Name</th>
        <th class="text-center">Department</th>
        <th class="text-center">Category</th>
        <th class="text-center">Address</th>
        <th class="text-center">Location</th>
        <th class="text-center">Designation</th>
        <th class="text-center">Birth Date</th>
        <th class="text-center">Joining Date</th>
        <th class="text-center">CNIC</th>
        <th class="text-center">Contact</th>
        <th class="text-center">Salary</th>
        <!-- <th class="text-center hidden-print">Status</th> -->
        <th id="hide-table-row" class="text-center hidden-print exclude-export">Action</th>
    </tr>
    </thead>
    <tbody id="tableData">
    @if(count($employees) > 0)
        @foreach($employees as $key => $y)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $y->emp_id}}</td>
                <td>{{ $y->attendance_id}}</td>

                <td>{{ $y->emp_name }}</td>
                <td>
                    @if(array_key_exists($y->department_id, $departments)){{ $departments[$y->department_id]->department_name }} @endif
                </td>
                <td>
                    @if(array_key_exists($y->category_id, $category)){{ $category[$y->category_id]->employee_category_name }} @endif
                </td>
                <td style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $y->permanent_address }}">
                   {{ $y->permanent_address }}
                </td>
                <td>
                    @if(array_key_exists($y->location_id, $locations)){{ $locations[$y->location_id]->location_name }} @endif
                </td>
                <td>
                    @if(array_key_exists($y->designation_id, $designations)){{ $designations[$y->designation_id]->designation_name }} @endif
                </td>

                <td class="text-center">{{ HrHelper::date_format($y->date_of_birth) }}</td>
                <td class="text-center">{{ HrHelper::date_format($y->joining_date) }}</td>
                <td class="text-center">{{ $y->cnic}}</td>
                <td class="text-center">{{ $y->contact_no }}</td>
                <td class="text-right">{{ number_format($y->salary,0) }}</td>
                <!-- <td class="hidden-print">{{ HrHelper::getStatusLabel($y->status)}}</td> -->
                <td id="hide-table-row" class="hide-table text-center hidden-print exclude-export">
                    <div class="dropdown">
                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                            &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                            @if(in_array('view', $operation_rights2))
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn"  href="{{ url("/hdc/viewEmployeeDetail/{$y->id}/{$m}?m={$m}") }}" target="_blank">
                                        View
                                    </a>
                                </li>
                            @endif
                            @if(in_array('edit', $operation_rights2))
                                <li role="presentation" class="actionsLink">
                                    <a  class="delete-modal btn" href="{{ url("/hr/editEmployeeDetailForm/{$y->id}/{$m}?m={$m}#vis") }}" target="_blank">
                                        Edit
                                    </a>
                                </li>
                            @endif
                            @if(in_array('repost', $operation_rights2))
                                @if($y->status == 2)
                                    <li role="presentation" class="actionsLink" onclick="repostCompanyTableRecord('{{ $m }}','{{ $y->id }}','employee')">
                                        <a class="delete-modal btn">
                                            Repost
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(in_array('view', $operation_rights2))
                                <li role="presentation">
                                    <a class="delete-modal btn" href="{{ url("/hdc/viewEmploymentCertificate/{$y->id}") }}" target="_blank">
                                        Employment Certificate
                                    </a>
                                </li>
                            @endif
                            @if(in_array('delete', $operation_rights2))
                                @if($y->status == 1)
                                    <li role="presentation" class="actionsLink" onclick="deleteEmployee('{{ $m }}','{{ $y->id }}','employee','{{ $y->emp_id }}')">
                                        <a class="delete-modal btn" >
                                            Delete
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(in_array('edit', $operation_rights2))
                                @if($y->status == 4 || $y->status == 3)
                                    <li role="presentation" class="actionsLink" onclick="restoreEmployee('{{ $m }}','{{ $y->id }}','employee')">
                                        <a class="delete-modal btn" >
                                            Active
                                        </a>
                                    </li>
                                @else
                                    <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hmfal/makeFormEmployeeInActive','{{ $y->id }}','Active / InActive Employee','{{ $m }}')">
                                        <a class="delete-modal btn" >
                                            InActive
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>