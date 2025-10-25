<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$addition = 0;
$less = 0;
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper" id="printList">
                <table class="table table-sm mb-0 table-bordered tableFixHead table-hover" id="exportList">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="3">Reconciliation Report</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <th></th>
                        <th>Gross Salary Balance - {{ date('F, Y', $prev_month) }}</th>
                        <th class="text-right">{{ number_format($gross_salary_pre_month->salary) }}</th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="3">Add: New Employee</th>
                    </tr>
                    @foreach($new_employees as $value)
                        <?php $addition += $value->salary; ?>
                        <tr>
                                <td class="text-center">{{ $value->emp_id }}</td>
                            <td>{{ $value->emp_name }}</td>
                            <td class="text-right">{{ number_format($value->salary) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <th class="text-center" colspan="3">Add: Increment Adjustment</th>
                    </tr>
                    @foreach($employee_promotions as $value)
                        <?php $addition += $value->increment; ?>
                        <tr>
                            <td class="text-center">{{ $value->emp_id }}</td>
                            <td>{{ $value->emp_name }}</td>
                            <td class="text-right">{{ number_format($value->increment) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th></th>
                        <th>Total Add Amount</th>
                        <th class="text-right">{{ number_format($addition) }}</th>
                    </tr>

                    <tr>
                        <th class="text-center" colspan="3">Less: Left Employees</th>
                    </tr>
                    @foreach($left_employees as $value)
                        <?php $less += $value->salary; ?>
                        <tr>
                            <td class="text-center">{{ $value->emp_id }}</td>
                            <td>{{ $value->emp_name }}</td>
                            <td class="text-right">{{ number_format($value->salary) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th></th>
                        <th>Total Less Amount</th>
                        <th class="text-right">{{ number_format($less) }}</th>
                    </tr>

                    <tr>
                        <th></th>
                        <th>Gross Salary Balance - {{ date('F, Y', strtotime($month_year)) }}</th>
                        <th class="text-right">{{ number_format($gross_salary_curr_month->salary) }}</th>
                    </tr>

                    <tr>
                        <th></th>
                        <th>Balance</th>
                        <th class="text-right">{{ number_format($gross_salary_curr_month->salary - (($gross_salary_pre_month->salary + $addition) - $less))  }}</th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>