<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('company_id');
$counter = 1;
?>
@if(!empty($loan_request))
    @foreach($loan_request as $key => $val)
        <?php
        $expectedMonthYear = '';
        //$expectedMonthYear = number_format(($val->loan_amount + $val->loan_top_up)/$val->per_month_deduction,0);
        //$expectedMonthYear = $expectedMonthYear - 1;
        ?>
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">@if(array_key_exists($val->employee_id, $employees)) {{ $employees[$val->employee_id]->emp_id }} @endif</td>
            <td>@if(array_key_exists($val->employee_id, $employees)) {{ $employees[$val->employee_id]->emp_name }} @endif</td>
            <td class="text-center">{{ date("F-Y", strtotime($val->needed_on_date)) }}</td>
            <td class="text-center">{{ date("F-Y", strtotime($val->deduction_start_date)) }}</td>
            <td class="text-right">{{ number_format($val->per_month_deduction) }}</td>
            <td class="text-right">{{ number_format($val->loan_amount,0) }}</td>
            <td class="text-right">{{ number_format($val->loan_top_up,0) }}</td>
            <td class="text-right">{{ number_format($val->loan_amount + $val->loan_top_up,0) }}</td>
            <!--<td class="text-center">{{ date('F-Y', strtotime("+$expectedMonthYear months", strtotime($val->deduction_start_date))) }}</td>-->
            <td class="text-right">{{ number_format(($val->loan_amount + $val->loan_top_up) - $val->paid_amount - $val->loan_adjustment_amount, 0) }}</td>
            <td class="text-center">@if($val->stop_payment_year != '' && $val->stop_payment_month != '') {{ HrHelper::getLoanStopPaymentLabel(date("F-Y", strtotime($val->stop_payment_year.'-'.$val->stop_payment_month))) }} @endif</td>
            <td class="text-center">{{ HrHelper::getLoanStatusLabel($val->loan_status) }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($val->approval_status) }}</td>
            <td class="text-center">{{ HrHelper::getStatusLabel($val->status) }}</td>
            @if (Auth::user()->acc_type == 'client' || Auth::user()->acc_type == 'admin')
                <td class="text-center">
                    @if ($val->approval_status == '1')
                        <input type="checkbox" class="check_input" name="check_input[]" id="check_input_{{ $val->id }}" value="{{ $val->id }}" />
                    @else
                        -
                    @endif
                </td>
            @endif
            <td class="text-center hidden-print" id="hide-table-row">
                <div class="dropdown">
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                        &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        @if(in_array('view', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="delete-modal btn" onclick="showDetailModelFourParamerter('hdc/viewLoanRequestDetail','{{ $val->id }}','Loan Detail','{{ $m }}')">
                                    View
                                </a>
                            </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editLoanRequestDetailForm','{{ $val->id }}','Edit Loan Form','{{ $m }}')">
                                    Edit
                                </a>
                            </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/createLoanTopUpForm','{{ $val->id }}','Loan Top Up Form','{{ $m }}')">
                                    Loan Top Up
                                </a>
                            </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                        <li role="presentation" class="actionsLink">
                            <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/createLoanAdjustmentForm','{{ $val->id }}','Loan Adjustment Form','{{ $m }}')">
                                Loan Adjustment
                            </a>
                        </li>
                        @endif
                        @if(in_array('edit', $operation_rights2))
                            <li role="presentation" class="actionsLink">
                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/createStopPaymentForm','{{ $val->id }}','Stop Payment Form','{{ $m }}')">
                                    Stop Payment
                                </a>
                            </li>
                        @endif
                        @if(in_array('repost', $operation_rights2))
                            @if($val->status == 2)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="deleteRowCompanyRecords('{{ $m }}','{{ $val->id }}','loan_request')">
                                        Repost
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(in_array('delete', $operation_rights2))
                            @if($val->status == 1)
                                <li role="presentation" class="actionsLink">
                                    <a class="delete-modal btn" onclick="deleteRowCompanyRecords('{{ $m }}','{{ $val->id }}','loan_request')">
                                        Delete
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    @if (Auth::user()->acc_type == 'client' || Auth::user()->acc_type == 'admin')
        <tr>
            <td colspan="16">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-sm btn-success" onclick="approveAndRejectAll('2','loan_request')" style="margin-right: 2%;">Approve</button>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="approveAndRejectAll('3','loan_request')">Reject</button>
                    </div>
                </div>
            </td>
        </tr>
    @endif
@else
    <tr>
        <td class="text-danger text-center" colspan="15">No Record Found !</td>
    </tr>
@endif