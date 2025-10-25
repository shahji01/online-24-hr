<?php use App\Helpers\HrHelper; ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
  <h3 style="font-weight: bold;text-decoration: underline">Previous Designation & Salary</h3>
</div>
<div class="row">&nbsp;</div>
<table class="table table-bordered sf-table-list">
    <input type="hidden" name="previousSalary" id="previousSalary" value="{{ $salary }}">
    <input type="hidden" name="previous_designation_id" id="previous_designation_id" value="{{ $designation_id }}">
    <thead>
        <th class="text-center">Designation</th>
        <th class="text-center">Grade</th>
        <th class="text-center">Salary</th>
        <th class="text-center">Date</th>
    </thead>
    <tbody>
        <tr>
            <td>
                @if(array_key_exists($designation_id, $designations)){{ $designations[$designation_id]->designation_name }} @endif
            </td>
            <td>
                @if(array_key_exists($grade_id, $grades)){{ $grades[$grade_id]->employee_grade_type }} @endif
            </td>
            <td class="text-right">{{ number_format($salary) }}</td>
            <td class="text-center">{{ HrHelper::date_format($date) }}</td>
        </tr>
    </tbody>
</table>