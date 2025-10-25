<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <table class="table table-sm mb-0 table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">Emp ID</th>
                <th class="text-center">Employee Name</th>
                <th class="text-center">Deduction Type</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Remarks</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">@if(array_key_exists($deduction->employee_id, $employees)) {{ $employees[$deduction->employee_id]->emp_id }} @endif</td>
                <td>@if(array_key_exists($deduction->employee_id, $employees)) {{ $employees[$deduction->employee_id]->emp_name }} @endif</td>
                <td>{{ $deduction->deduction_type }}</td>
                <td class="text-right">{{ $deduction->deduction_amount }}</td>
                <td>{{ $deduction->Remarks }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>