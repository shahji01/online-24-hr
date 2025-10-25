<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];

$remaining_amount = ($loanRequest->loan_amount + $loan_top_up) - ($paid_amount->paid_amount + $loan_adjusted_amount->sum('amount'));
$counter = 1;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if(in_array('print', $operation_rights2))
            {{ CommonHelper::displayPrintButtonInBlade('printList2','','1') }}
        @endif
    </div>
</div>
<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive" id="printList2">
            <table class="table table-sm mb-0 table-bordered table-hover">
                <tr>
                    <th >Emp ID</th>
                    <td class="text-center">{{ $employees[$loanRequest->employee_id]->emp_id }}</td>
                </tr>
                <tr>
                    <th >Emp Name</th>
                    <td class="text-center">{{ $employees[$loanRequest->employee_id]->emp_name }}</td>
                </tr>
                <tr>
                    <th>Month - Year</th>
                    <td class="text-center">{{ $loanRequest->month.'-'.$loanRequest->year }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td class="text-center ">{{ $loanRequest->username }}</td>
                </tr>
                <tr>
                    <th>Created Date</th>
                    <td class="text-center">{{ HrHelper::date_format($loanRequest->date) }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td class="text-center">{{ $loanRequest->description }}</td>
                </tr>
                <tr>
                    <th>Per Month Deduction</th>
                    <td class="text-right">{{ number_format($loanRequest->per_month_deduction,0) }}</td>
                </tr>
                <tr>
                    <th>Loan Amount</th>
                    <td class="text-right">{{ number_format($loanRequest->loan_amount,0) }}</td>
                </tr>
                <tr>
                    <th>Loan Top Up Amount</th>
                    <td class="text-right">{{ number_format($loan_top_up,0) }}</td>
                </tr>
                <tr>
                    <th>Paid loan Amount</th>
                    <td class="text-right">{{ number_format($paid_amount->paid_amount,0) }}</td>
                </tr>
                @if($loan_adjusted_amount->count() > 0)
                    <tr>
                        <th>loan Adjustment Amount</th>
                        <td class="text-right">{{ number_format($loan_adjusted_amount->sum('amount'),0) }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Remaining Amount</th>
                    <td class="text-right">{{ number_format($remaining_amount,0) }}</td>
                </tr>
            </table>

            @if(count($loan_Detail) != '0')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: #f5f3ff">
                    <h4 style="font-weight: bold;text-align: center;padding:5px;">Loan Repayment Detail</h4>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Deduction Month & Date</th>
                                <th class="text-center">Deduction Amount</th>
                                </thead>
                                <tbody>
                                @foreach($loan_Detail as $value)
                                    <?php
                                    $monthNum  = $value->month;
                                    $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                    $monthName = $dateObj->format('F'); // March
                                    ?>
                                    <tr>
                                        <td class="text-center">{{ $counter++ }}</td>
                                        <td class="text-center">{{ $monthName.' '.$value->year }}</td>
                                        <td class="text-center">{{ number_format($value->loan_amount_paid,0) }}</td>
                                    </tr>

                                    @if($loan_adjusted_amount->count() > 0)
                                        @foreach($loan_adjusted_amount->get() as $value1)
                                            <?php
                                            $monthNum1  = $value1->month;
                                            $dateObj1   = DateTime::createFromFormat('!m', $monthNum1);
                                            $monthName1 = $dateObj1->format('F'); // March
                                            ?>
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $monthName1.' '.$value1->year }}</td>
                                                <td class="text-center">{{ number_format($value1->amount,0) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                <tr>
                                    <th colspan="2" class="text-right">Total</th>
                                    <th class="text-center">{{ number_format(($paid_amount->paid_amount + $loan_adjusted_amount->sum('amount')),0)}}</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>

