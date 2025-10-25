<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;



?>
<link href="{{ URL::asset('assets/dist/css/style.css') }}" rel="stylesheet">
<script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
<style>

    .start-content-sub {
        margin: 0 auto;
        float: left;
    }
    .start-content-sub-span{
        text-align: center;
        margin: 0 auto;
        display: block;
    }

    td{ padding: 1px !important;}
    th{ padding: 1px !important;}

    table {
        font-size: 12px;
    }
    div {
        font-size: 12px;
    }

    @if($type!='')
        .page-padding {
        margin-left: 5px;
        margin-right: 5px;
    }

    .nicebutton{
        display: none;
    }

    .header-margin {
        margin-top: 30px !important;
    }

    .start-content-margin {
        margin-top: 4px;
    }

    .start-content-margin2 {
        margin-top: 16px;
    }

    .subject-center {
        display: inline-block;
        vertical-align: middle;
        line-height: normal;
    }

    .table-content-margin {
        margin-top: 6px;
    }

    .table-content-margin-top {
        margin-top: 8px;
    }

    .table-left-margin {
        margin-left: 60px;
    }

    .bottom-line {
        margin-top: 30px;
    }

    table {
        font-size: 12px;
    }

    div {
        font-size: 12px;
    }
    @endif


    @media print {

        .page-padding {
            margin-left: 5px;
            margin-right: 5px;
        }

        .nicebutton{
            display: none;
        }

        .header-margin {
            margin-top: 140px !important;
        }

        .start-content-margin {
            margin-top: 4px;
        }

        .start-content-margin2 {
            margin-top: 16px;
        }

        .subject-center {
            display: inline-block;
            vertical-align: middle;
            line-height: normal;
        }

        .table-content-margin {
            margin-top: 6px;
        }

        .table-content-margin-top {
            margin-top: 8px;
        }

        .table-left-margin {
            margin-left: 60px;
        }

        .bottom-line {
            margin-top: 30px;
        }

        table {
            font-size: 12px;
        }

        div {
            font-size: 12px;
        }
    }

</style>

<div class="row">&nbsp;</div>

@if($type=='')
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right nicebutton">
            {{ CommonHelper::displayPrintButtonInBlade('PrintLetter','','1') }}
        </div>
    </div>
@endif
<br>
<div class="lineHeight">&nbsp;</div>
<div class="container page-padding" id="PrintLetter">
    <div class="row header-margin">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
            <p>{{ HrHelper::hr_date_format($final_settlement->date) }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p class="start-content-margin">@if($employees[$employee_id]->gender_id == '1') Mr. @elseif($employees[$employee_id]->gender_id == '2') Ms. @else @endif {{ $employees[$employee_id]->emp_name }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p class="start-content-margin start-content-sub"> Subject:</p> <span class="start-content-sub-span"><b>Full And Final Settlement</b></span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="@if($type!='') margin-top:25px; @endif">
            <p class="start-content-margin">Dear @if($employees[$employee_id]->gender_id == '1') Mr. @elseif($employees[$employee_id]->gender_id == '2') Ms. @else @endif {{ $employees[$employee_id]->emp_name }},</p>
        </div>
    </div>
    <div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p>
                I , {{ $employees[$employee_id]->emp_name }} hereby accept the above final settlement of my account in respect of my employment with {{ env('APP_NAME') }} as true and correct and acknowledge receipt of the Sum
                of Rs. {{ number_format($total_amount_payable) }}/- in full and final settlement of dues owned to me by {{ env('APP_NAME') }} and confirm that I have no further claims against the Company.
            </p>
        </div>
    </div>
    <div class="container">
        <div class="row table-content-margin-top table-left-margin">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered">
                        <tbody>
                        <tr>
                            <th colspan="2">Salary</th>
                        </tr>
                        @if(!empty($explode_hold_salary[0]))
                            @foreach($explode_hold_salary as $key => $val)
                                <tr>
                                    <td class="col-sm-3 table-padd">Salary for the month of {{ date("F", strtotime("2001-" . $explode_hold_salary_month[$key] . "-01")).', '.$explode_hold_salary_year[$key] }} </td>
                                    <td class="col-sm-3 table-padd text-right">{{ number_format((int)$val) }}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if($final_settlement->salary_amount != 0)
                            <tr>
                                <td class="col-sm-3 table-padd">
                                    @if($final_settlement->salary_upto != '')
                                        Salary upto {{ HrHelper::hr_date_format($final_settlement->salary_upto) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="col-sm-3 table-padd text-right">{{ number_format($final_settlement->salary_amount) }}</td>
                            </tr>
                        @endif
                        @if($final_settlement->leave_encashment_amount > 0)
                            <tr>
                                <td class="col-sm-3 table-padd">Leave Encashment  {{ $final_settlement->leave_encashment_days }} days </td>
                                <td class="col-sm-3 table-padd text-right">{{ number_format($final_settlement->leave_encashment_amount) }}</td>
                            </tr>
                        @endif
                        @if($final_settlement->other_amount > 0)
                            <tr>
                                <td class="col-sm-3 table-padd">{{ $final_settlement->other_amount_narration }} </td>
                                <td class="col-sm-3 table-padd text-right">{{ number_format($final_settlement->other_amount) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="col-sm-3 table-padd"><b>Total Salary Amount</b></td>
                            <td class="col-sm-3 table-padd text-right"><b>PKR {{ number_format($salary_amount_payable) }}</b></td>
                        </tr>
                        </tbody>
                    </table>

                    @if($deduction > 0)
                        <table class="table table-sm table-bordered">
                            <tbody>
                            <tr>
                                <th colspan="2">Deductions</th>
                            </tr>
                            @if($final_settlement->opd_excess > 0)
                                <tr>
                                    <td class="col-sm-3 table-padd">OPD Deduction</td>
                                    <td class="col-sm-3 table-padd text-right">({{ number_format($final_settlement->opd_excess) }})</td>
                                </tr>
                            @endif
                            @if($final_settlement->leave_deduction_days > 0)
                                <tr>
                                    <td class="col-sm-3 table-padd">Leave Deduction {{ $final_settlement->leave_deduction_days }} days</td>
                                    <td class="col-sm-3 table-padd text-right">({{ number_format($final_settlement->leave_deduction_amount) }})</td>
                                </tr>
                            @endif
                            @if($final_settlement->other_deduction_amount > 0)
                                <tr>
                                    <td class="col-sm-3 table-padd">{{ $final_settlement->other_deduction_type }}</td>
                                    <td class="col-sm-3 table-padd text-right">({{ number_format($final_settlement->other_deduction_amount) }})</td>
                                </tr>
                            @endif

                            @if($final_settlement->loan_deduction > 0)
                                <tr>
                                    <td class="col-sm-3 table-padd">Loan</td>
                                    <td class="col-sm-3 table-padd text-right">({{ number_format($final_settlement->loan_deduction) }})</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="col-sm-3 table-padd"><b>Total Deduction</b></td>
                                <td class="col-sm-3 table-padd text-right"><b>PKR {{ number_format($deduction) }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    @endif
                    @if($gratuity > 0)
                        <table class="table table-sm table-bordered">
                            <tbody>
                            <tr>
                                <th colspan="2">Gratuity</th>
                            </tr>
                            <tr>
                                <td class="col-sm-3 table-padd">Employee Gratuity</td>
                                <td class="col-sm-3 table-padd text-right">PKR {{ number_format($final_settlement->gratuity) }}</td>
                            </tr>
                            @if($salary_amount_payable < 0)
                                <tr>
                                    <td class="col-sm-3 table-padd">Salary negative amount adjusted to gratuity</td>
                                    <td class="col-sm-3 table-padd text-right">({{ number_format(-$salary_amount_payable) }})</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="col-sm-3 table-padd"><b>Total Gratuity Amount</b></td>
                                <td class="col-sm-3 table-padd text-right"><b>PKR {{ number_format($gratuity) }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    @endif

                    <table class="table table-sm table-bordered">
                        <tbody>
                        <tr>
                            <td class="col-sm-3 table-padd"><b>Total Amount</b></td>
                            <td class="col-sm-3 table-padd text-right"><b>PKR {{ number_format($total_amount_payable) }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
        </div>
    </div>

    <br/><br/>
    @if($type=='')
        <div class="row table-content-margin">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p>Please acknowledge the copy of this letter for our record.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="start-content-margin">Sincerely Yours,</p>
            </div>
        </div>

        <div class="row" style="margin-top:15px;">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
                <p class="bottom-line">____________________</p>
                <p class="bottom-margin">Authorized Signatory Onbehalf</p>
                <!--<p class="bottom-line">____________________</p>
                <p class="bottom-margin">DATE</p>-->
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                <p class="bottom-line">____________________</p>
                <p class="bottom-margin">Signature of {{ $employees[$employee_id]->emp_name }}</p>
                <!--<p class="bottom-line">____________________</p>
                <p class="bottom-margin" style="margin-right:125px;">DATE</p>-->

            </div>
        </div>
    @endif
</div>