<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-hover">
                <tr>
                    <th>Emp ID</th>
                    <td class="text-center">{{ $employees[$advance_salary->employee_id]->emp_id }}</td>
                </tr>
                <tr>
                    <th>Employee Name</th>
                    <td class="text-center">{{ $employees[$advance_salary->employee_id]->emp_name }}</td>
                </tr>
                <tr>
                    <th>Deduction Month - Year</th>
                    <td class="text-center">{{ $advance_salary->deduction_month."-".$advance_salary->deduction_year }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td class="text-right">{{ number_format($advance_salary->advance_salary_amount) }}</td>
                </tr>
                <tr>
                    <th>Advance Salary Needed on</th>
                    <td class="text-center">{{ HrHelper::date_format($advance_salary->salary_needed_on) }}</td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td class="text-center">{{ $advance_salary->detail }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
