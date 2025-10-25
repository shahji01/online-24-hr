<?php
$m = Input::get('company_id');
$counter = 1;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>

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
                {{$y->location_id}}
            </td>
            <td>
                @if(array_key_exists($y->designation_id, $designations)){{ $designations[$y->designation_id]->designation_name }} @endif
            </td>

            <td class="text-center">{{ HrHelper::date_format($y->date_of_birth) }}</td>
            <td class="text-center">{{ HrHelper::date_format($y->joining_date) }}</td>
            <td class="text-center">{{ $y->cnic}}</td>
            <td class="text-center">{{ $y->contact_no }}</td>
            
            <!-- View Salary button with password protection -->
            <td class="text-right">
                <!-- Button to trigger the password modal -->
                <button class="btn btn-warning btn-sm" onclick="showSalaryModal('{{ $y->id }}')">View Salary</button>

                <!-- Modal for password input -->
                <div id="salaryModal_{{ $y->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="salaryModalLabel_{{ $y->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="salaryModalLabel_{{ $y->id }}">Enter Password to View Salary</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Password input field -->
                                <input type="password" id="salaryPassword_{{ $y->id }}" class="form-control" placeholder="Enter Password">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="verifySalaryPassword('{{ $y->id }}')">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden salary field that will be revealed if password is correct -->
                <span id="salary_{{ $y->id }}" style="display: none;">
                    {{ number_format($y->salary, 0) }}
                </span>
            </td>
            <!-- End of View Salary logic -->
            
            <td id="hide-table-row" class="hide-table text-center hidden-print">
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
@else
    <tr>
        <td colspan="15" class="text-danger text-center">No Record Found</td>
    </tr>
@endif

<!-- Add JavaScript to handle modal logic -->
<script>
    function showSalaryModal(employeeId) {
        // Show the modal for the specific employee
        $('#salaryModal_' + employeeId).modal('show');
    }

    function verifySalaryPassword(employeeId) {
        // Hardcoded password for demonstration (you can replace this with dynamic verification logic)
        var correctPassword = 'your-secure-password'; // Replace this with real password logic

        var enteredPassword = $('#salaryPassword_' + employeeId).val();

        if (enteredPassword === correctPassword) {
            // Show salary and hide the modal
            $('#salary_' + employeeId).show();
            $('#salaryModal_' + employeeId).modal('hide');
        } else {
            alert('Incorrect password, please try again.');
        }
    }
</script>
