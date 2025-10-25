<?php use App\Helpers\HrHelper;

?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
    <h3 style="font-weight: bold;text-decoration: underline">Previous Employee Project & Salary</h3>
</div>
<div class="table-responsive">
    <input type="hidden" value="{{ $salary }}" id="previousSalary" name="previousSalary">
    <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
        <thead>
        <th class="text-center">Designation</th>
        <th class="text-center">Grade</th>
        <th class="text-center">Employee Project</th>
        <th class="text-center">Salary</th>
        <th class="text-center">Date</th>
        </thead>
        <tbody>
        <tr>
            <td class="text-center">{{HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name', $designation_id)}}</td>
            <td class="text-center">{{HrHelper::getMasterTableValueById(Input::get('m'),'grades','employee_grade_type', $grade_id)}}</td>
            <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$employee_project)}}</td>
            <td class="text-right">{{ number_format($salary) }}</td>
            <td class="text-center">{{HrHelper::date_format($date)}}</td>
        </tr>
        </tbody>
    </table>
</div>