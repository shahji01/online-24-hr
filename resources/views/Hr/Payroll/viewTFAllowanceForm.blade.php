<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$m = Input::get('m');
?>

<style>
    .show_data {
        display: none;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="printList">
                                {{ Form::open(['url' => 'had/addTFAllowanceDetail']) }}
                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                <input type="hidden" name="month_year" value="{{ $month_year }}" />
                                <input type="hidden" name="m" value="{{ $m }}">
                                <div class="wrapper">
                                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead table-hover" id="exportList">
                                        <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">EMP ID.</th>
                                            <th class="text-center">Emp Name</th>
                                            <th class="text-center col-sm-1">TF Allowance</th>
                                            <th class="text-center">Month - Year</th>
                                            <th class="text-center col-sm-1">TF Paid</th>
                                            <th class="text-center col-sm-1">Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $count = 1; ?>
                                        @if (!empty($tf_allowance))
                                            @foreach ($tf_allowance as $val)
                                                <?php
                                                $tf_allowance = $val->total_allowance_amount;
                                                $tf_paid = $val->tf_paid;
                                                $tf_balance = 0;
                                                if($tf_paid == 0) {
                                                    $tf_balance = $tf_allowance / 2;
                                                } else {
                                                    $tf_balance = $tf_allowance - $tf_paid;
                                                }
                                                ?>

                                                <tr>
                                                    <td class="text-center">{{ $count++ }}
                                                        <input type="hidden" name="employee_id[]" value="{{ $val->employee_id }}">
                                                    </td>
                                                    <td class="text-center">@if(array_key_exists($val->employee_id, $employees)){{ $employees[$val->employee_id]->emp_id }} @endif</td>
                                                    <td class="text-left">@if(array_key_exists($val->employee_id, $employees)){{ $employees[$val->employee_id]->emp_name }} @endif</td>
                                                    <td class="text-right">{{ number_format($tf_allowance)}}</td>
                                                    <td class="text-center">{{ date('M Y', strtotime($month_year)) }}</p></td>
                                                    <td class="text-right">{{ number_format($val->tf_paid) ?? 0 }}</td>
                                                    <td class="text-center"><p class="show_data">{{ number_format($tf_balance) ?? 0}}</p>
                                                        <input type="number" class="form-control" name="amount[]" value="{{ $tf_balance ?? 0 }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class='text-center' colspan='7' style='color:red'><b>Allowance Not Found !</b></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>