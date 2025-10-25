<style>
    .panel-heading {
        padding: 0px 15px;}
    .field_width {width: 120px;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  {  }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

    div.wrapper {
        overflow: scroll;
        max-height: 630px;

    }



</style>
<?php

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payroll;
use App\Models\Tax;
use App\Models\TaxSlabs;
use App\Models\LeaveApplicationData;
use App\Models\Eobi;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\LoanRequest;
use App\Models\AdvanceSalary;
use App\Models\EmployeePromotion;
use App\Models\EmployeeBankData;
use App\Models\Arrears;
use App\Models\EmployeeTransfer;
use App\Models\EmployeeExit;
use App\Models\Rebate;

$employeeArray = [];
$recordNotFound = [];

$result=[];

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" id="TaxesList">
                        <thead>

                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center hidden-print">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th colspan="2" class="text-center">Provident Fund Amount</th>
                            <th colspan="2" class="text-center">Total Pf (Loan / Withdrawal)</th>
                            <th colspan="2" class="text-center">Pf Loan Paid</th>
                            
                            <!--<th colspan="2" class="text-center">Profit</th>-->
                            <!--<th colspan="2" class="text-center">Profit Year From</th>-->
                            <!--<th colspan="2" class="text-center">Profit Year Till</th>-->
                            <th colspan="2" class="text-center">Withdrawal Type</th>

                            <th class="text-center loan_fields_th">Deduction <br><small>(Per Month)</small></th>
                            <th class="text-center loan_fields_th">Needed <br><small>(Month-Year)</small></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 0;?>
                        <?php foreach($employees as $row1){?>
                        <?php
                        $counter++;
                        CommonHelper::companyDatabaseConnection(Input::get('m'));

                        ?>
                        <tr>
                            <td class="text-center ">{{$counter}}</td>
                            <td class="hidden-print">
                                {{ $row1->emp_id }}
                                <input type="hidden" value="{{ $row1->emp_id }}" name="emp_id[]">
                                <input type="hidden" value="{{ $row1->id }}" name="employeeId[]">
                            </td>
                            <td class="text-center">{{ $row1->emp_name }}

                            </td>
                            <td colspan="2" class="text-center">
                                <input type="number" class="form-control" value="" name="amount[]">
                            </td>
                            <td colspan="2" class="text-center">
                                <input type="number" class="form-control" value="" name="total_pf_loan[]">
                            </td>
                            <td colspan="2" class="text-center">
                                <input type="number" class="form-control" value="" name="total_loan_paid[]">
                            </td>

                            <!--<td colspan="2" class="text-center">-->
                            <!--    <input type="number" class="form-control" value="" name="profit[]">-->
                            <!--</td>-->
                            <!--<td colspan="2" class="text-center">-->

                            <!--    <input type="number" class="form-control" min="1900" max="2099" step="1" maxlength="4" minlength="4" value="2020" name="profit_year_from[]">-->
                            <!--</td>-->
                            <!--<td colspan="2" class="text-center">-->
                            <!--    <input type="number" class="form-control" min="1900" maxlength="4" minlength="4" max="2099" step="1" value="2021"  name="profit_year_till[]">-->
                            <!--</td>-->
                            <td colspan="2" class="text-center ">
                                <select style="width: 120px" name="type[]" onchange="loanFields(this.value,'<?php echo $counter ?>')" class="form-control requuiredField" id="type">
                                    <option value="">Select</option>
                                    <option value="loan">Loan</option>
                                    <option value="permanent">Permanent</option>
                                </select>
                            </td>
                            <td class="text-center" >
                                <input style="width: 110px" type="number" class="form-control" id="per_month_deduction_{{$counter}}" name="per_month_deduction[]" readonly value="">
                                </td>
                            <td class="text-center" >
                                <input style="width: 170px" type="month"  name="needed_on_date[]" id="needed_on_date_{{$counter}}" value="" class="form-control" readonly>
                             </td>

                        </tr>

                        <?php

                        CommonHelper::reconnectMasterDatabase();

                        ?>

                        <?php } ?>
                        </tbody>
                    </table>

                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <?php
                                foreach ($recordNotFound as $value):
                                    echo $value;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



        </div>
        <div class="row text-right">&nbsp;
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="submit" name="submit" class="btn btn-sm btn-success" />
            </div>
        </div>

    </div>`
</div>

<script>
    function loanFields(val,counter){

        if(val =='loan'){

            $('#per_month_deduction_'+counter).prop("readonly", false);
            $('#needed_on_date_'+counter).prop("readonly", false);
        }
        else{


            $('#per_month_deduction_'+counter).prop("readonly", true);
            $('#needed_on_date_'+counter).prop("readonly", true);
        }
    }
</script>



