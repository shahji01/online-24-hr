<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
$counter = 1;
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive wrapper" id="printList">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixed" id="exportList">
                        <thead>
                        <th class="text-center">S.No</th>
                        <th class="text-center">EMP ID</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Joining Date</th>
                        <th class="text-center">Gross Salary</th>
                        <th class="text-center">Loan Limit</th>
                        <th class="text-center">Loan Amount</th>
                        <th class="text-center">Top Up Amount</th>
                        <th class="text-center">Total Loan</th>
                        <th class="text-center">July</th>
                        <th class="text-center">Aug</th>
                        <th class="text-center">Sep</th>
                        <th class="text-center">Oct</th>
                        <th class="text-center">Nov</th>
                        <th class="text-center">Dec</th>
                        <th class="text-center">Jan</th>
                        <th class="text-center">Feb</th>
                        <th class="text-center">Mar</th>
                        <th class="text-center">Apr</th>
                        <th class="text-center">May</th>
                        <th class="text-center">June</th>
                        <th class="text-center">Balance</th>
                        </thead>
                        <tbody>
                        @foreach($loan_request as $key => $val)
                            <?php
                            // echo "<pre>";
                            // print_r($val);
                            // die;
                            $gratuity_amount = 0;
                            $years = $gratuity[$val->employee_id][0]->years;
                            $months = $gratuity[$val->employee_id][0]->months;
                            $salary = round($gratuity[$val->employee_id][0]->salary);
                            $per_day_salary = $salary / 30;
                            if($years >= 1 && $months >= 6):
                                $years += 1;
                            endif;
                            if($years >= 1):
                                $per_year_gratuity = $per_day_salary * 20;
                                $gratuity_amount = $years * $per_year_gratuity;
                            endif;
                            $per_gratuity = number_format($gratuity_amount * 0.7);

                            ?>
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td class="text-center">@if(array_key_exists($val->employee_id, $employees)) {{ $employees[$val->employee_id]->emp_id }} @endif</td>
                                <td>@if(array_key_exists($val->employee_id, $employees)) {{ $employees[$val->employee_id]->emp_name }} @endif</td>
                                <td class="text-center">@if(array_key_exists($val->employee_id, $employees)) {{ HrHelper::date_format($employees[$val->employee_id]->joining_date) }} @endif</td>
                                <td class="text-right">@if(array_key_exists($val->employee_id, $employees)) {{ number_format($employees[$val->employee_id]->new_salary) }} @endif</td>
                                <td class="text-right">{{ $per_gratuity }}</td>
                                <td class="text-right">{{ number_format($val->loan_amount,0) }}</td>
                                <td class="text-right">{{ number_format($val->loan_top_up,0) }}</td>
                                <td class="text-right">{{ number_format($val->loan_amount + $val->loan_top_up,0) }}</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('07', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['07']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('08', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['08']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('09', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['09']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('10', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['10']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('11', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['11']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('12', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['12']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('01', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['01']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('02', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['02']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('03', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['03']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('04', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['04']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('05', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['05']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">@if (array_key_exists($val->id, $loan_paid_data) && array_key_exists('06', $loan_paid_data[$val->id])) {{ number_format($loan_paid_data[$val->id]['06']->loan_amount_paid,0) }} @endif</td>
                                <td class="text-right">{{ number_format(($val->loan_amount + $val->loan_top_up) - $val->paid_amount - $val->loan_adjustment_amount, 0) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

