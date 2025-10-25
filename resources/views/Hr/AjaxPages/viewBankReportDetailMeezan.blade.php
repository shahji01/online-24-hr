<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

use App\Models\PayrollData;
use App\Models\Payslip;

$count_net_salary = 0;
$current_date = date('Y-m-d');
$month_year = Input::get('month_year');
$count =1;

$timestamp = strtotime($month_year);
 $month = date('F', $timestamp);
 $year = date('Y', $timestamp);

$timestamp = strtotime($cheque_date);
$cheque_day = date('d', $timestamp);
$cheque_month = date('F', $timestamp);
$cheque_year = date('Y', $timestamp);


// Set the timezone to Pakistan Standard Time (UTC+5)
date_default_timezone_set('Asia/Karachi');

// Get the current date in the specified format
$currentDate = date('l, F j, Y');

// Print the formatted date
 $currentDate;

?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
    
</style>
<!-- style="background-image: url({{ asset('assets/images/bank/meezanbank.png') }}); height:100vh ; width:100%" -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body" id="printList">
                        <div class="row" style="position: relative;">
                            <img src="{{ asset('assets/images/bank/meezanbank.png') }}" alt="" style="width: 100%;">
                            <div class="text-over-image" style="position: absolute; top: 15%; left: %; transform: translate(20%, -0%); text-align: ;">
                                <p>{{ $currentDate }}</p>
                            </div>
                            <div style="display:flex;flex-direction:column;">

                                <div class="text-over-image" style="position: absolute; top: 18%; left: 0%; transform: translate(20%, -0%); text-align: ;">
                                    
                                    <span style="width:150px;display:inline-block">
                                        T0
                                    </span>
                                    <span style="width:50px;display:inline-block">
                                        :
                                    </span>
                                    <span style="width: 404px;  display: inline-block;  position: absolute;">
                                                M/s. Meezan Bank Limited <br> Branch at Grey Garden, Block 16, <br> Gulistan e Jouhar, Karachi, Pakistan.
                                    </span>
                                </div>
                                <div class="text-over-image" style="position: absolute; top: 24%; left: 0%; transform: translate(20%, -0%); text-align: ;">
                                    
                                    <span style="width:150px;display:inline-block">
                                        Kind Attn.
                                    </span>
                                    <span style="width:50px;display:inline-block">
                                        :
                                    </span>
                                    <span style="width: 404px;  display: inline-block;  position: absolute;">
                                                The Branch Manager
                                    </span>
                                </div>
                                <div class="text-over-image" style="position: absolute; top: 27%; left: 0%; transform: translate(20%, -0%); text-align: ;">
                                    
                                    <span style="width:150px;display:inline-block">
                                        Subject
                                    </span>
                                    <span style="width:50px;display:inline-block">
                                        :
                                    </span>
                                    <span style="width: 550px; display: inline-block; position: absolute;">
                                            Employees’ Salaries Transfer Request for the Month of {{ $month }} {{ $year }}
                                    </span>
                                </div>
                                <div class="text-over-image" style="position: absolute; top: 30%; left: 0%; transform: translate(3%, -0%); text-align: ;width: 95%;">
                                    
                                    <span>
                                    Dear Manager, <br>
                                    I am Muhammad Usman Ali Khan, Proprietor of M/s. Dynamic Elevator Services Pakistan requesting you
                                    to transfer salaries of the employees from Account No. 10340106242327 as per the following details. 
                                    </span>
                                    
                                </div>
                                <div class="text-over-image" style="position: absolute; top: 36%; left: 0%; transform: translate(3%, -0%); text-align: ; width:92%;">
                                    
                                    <table>
                                        <tr>
                                            <th class="text-center" style="border: 1px solid black">S. No</th>
                                            <th class="text-center" style="border: 1px solid black">Emp. No</th>
                                            <th class="text-center" style="border: 1px solid black">Emp. Name </th>
                                            <th class="text-center" style="border: 1px solid black">Net Salary Payable</th>
                                            <th class="text-center" style="border: 1px solid black">Account No</th>
                                        </tr>

                                        @foreach($payslip as $value)
                                            <tr>
                                                <td style="border: 1px solid black" class="text-center">{{ $count++ }}</td>
                                                <td style="border: 1px solid black" class="text-center">{{ $value->emp_id }}</td>
                                                <td style="border: 1px solid black" class="">{{ $value->account_title }}</td>
                                                <td style="border: 1px solid black" class="text-center">{{ number_format($value->net_salary,0) }}</td>
                                                <td style="border: 1px solid black" class="text-center">{{ $value->account_no}}</td>
                                            </tr>

                                        @endforeach
                                    <?php $count=1;?>
                                    <tr>
                                        <td style="border: 1px solid black" colspan="3" class="text-right">
                                            <strong>TOTAL : </strong>
                                        </td>
                                        <td style="border: 1px solid black" class="text-center" style="" >
                                            <b style=""><?php echo number_format($payslip->sum('net_salary')+$payslip->sum('pf_company_fund'),0);?></b>
                                        </td>
                                    </tr>
                                    </table>
                                    
                                    <br>
                                    <p>
                                        The Cheque No. A-57847992 for the Above Total Amount is attached herewith. <br>
                                        If you require any further information or clarification, please do not hesitate to contact me. <br><br>
                                        Thank you for your cooperation and support.
                                    </p>

                                    <br>
                                    <br>
                                    <br>

                                    <p style="border-top:2px solid black;display:inline-block">
                                        Muhammad Usman Ali Khan
                                    </p>
                                    <br>

                                    <strong>
                                        DELSP-Dynamic Elevator Services Pakistan
                                    </strong>
                                </div>



                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
