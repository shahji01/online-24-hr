<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\EmployeeCategory;
use App\Models\Regions;
use App\Models\EmployeePromotion;

$gross_salaries='';
$basic_salaries='';
$hr_utility_allowance='';
$fix_medical='';
$vr='';
$Arrears='';
$other_allowance='';
$vehicle_addit_='';
$taxable_salary='';
$monthNameFrom='';
$monhly_income_tax='';
$pf_amount='';
$loan_amount_paid='';
$bonus_amount='';
$other_deduct='';
$lwp_deduction='';
$penalty='';
$total_deduction='';
$net_salary='';
$transfer_mode_salary='';
$cheque_mode_salary='';
$gross_with_allowance='';

if($explodeMonthYearFrom[1] > 0){
    $monthNameFrom = date('M', mktime(0, 0, 0, $explodeMonthYearFrom[1]=(int)$explodeMonthYearFrom[1], 10));
}
$gross_salaries =$payslipFrom->sum('gross_salaries');
$basic_salaries =$payslipFrom->sum('basic_salary');
$hr_utility_allowance=$payslipFrom->sum('hr_utility_allowance');
$fix_medical=$payslipFrom->sum('fix_medical');
$vr=$payslipFrom->sum('vr_other_allow');
$Arrears=$payslipFrom->sum('Arrears');
$other_allowance=$payslipFrom->sum('other_allowance');
$vehicle_addit_=$payslipFrom->sum('vehicle_addit_');
$taxable_salary=$payslipFrom->sum('taxable_salary');
$monhly_income_tax=$payslipFrom->sum('tax_amount');
$pf_amount=$payslipFrom->sum('pf_amount');
$advance_salary_amount=$payslipFrom->sum('advance_salary_amount');
$eobi_amount=$payslipFrom->sum('eobi_amount');
$loan_amount_paid=$payslipFrom->sum('loan_amount_paid');
$bonus_amount=$payslipFrom->sum('bonus_amount');
$other_deduct=$payslipFrom->sum('other_deduct');
$lwp_deduction=$payslipFrom->sum('lwp_deduction');
$penalty=$payslipFrom->sum('penalty');
$total_deduction=$payslipFrom->sum('total_deduction');
$net_salary=$payslipFrom->sum('net_salary');
$transfer_mode_salary=$payslipFrom->where('payment_mode','=','Transfer')->sum('net_salary');
$cheque_mode_salary=$payslipFrom->where('payment_mode','=','Cheque')->sum('net_salary');
$count=$payslipFrom->count();

$gross_with_allowance=($gross_salaries+$vr+$other_allowance+$Arrears+$vehicle_addit_);



$gross_salaries_to='';
$basic_salaries_to='';
$hr_utility_allowance_to='';
$fix_medical_to='';
$vr_to='';
$Arrears_to='';
$other_allowance_to='';
$vehicle_addit_to='';
$taxable_salary_to='';
$monthNameTo='';
$monhly_income_tax_to='';
$pf_amount_to='';
$loan_amount_paid_to='';
$bonus_amount_to='';
$other_deduct_to='';
$lwp_deduction_to='';
$penalty_to='';
$total_deduction_to='';
$net_salary_to='';
$transfer_mode_salary_to='';
$cheque_mode_salary_to='';
$gross_with_allowance_to='';

if($explodeMonthYearTo[1] > 0){
    $monthNameTo = date('M', mktime(0, 0, 0, $explodeMonthYearTo[1]=(int)$explodeMonthYearTo[1], 10));
}


$gross_salaries_to =$payslipTo->sum('gross_salaries');
$basic_salaries_to =$payslipTo->sum('basic_salary');
$hr_utility_allowance_to=$payslipTo->sum('hr_utility_allowance');
$fix_medical_to=$payslipTo->sum('fix_medical');
$vr_to=$payslipTo->sum('vr_other_allow');
$Arrears_to=$payslipTo->sum('Arrears');
$other_allowance_to=$payslipTo->sum('other_allowance');
$vehicle_addit_to=$payslipTo->sum('vehicle_addit_');
$taxable_salary_to=$payslipTo->sum('taxable_salary');
$monhly_income_tax_to=$payslipTo->sum('tax_amount');
$pf_amount_to=$payslipTo->sum('pf_amount');
$advance_salary_amount_to=$payslipTo->sum('advance_salary_amount');
$eobi_amount_to=$payslipTo->sum('eobi_amount');
$loan_amount_paid_to=$payslipTo->sum('loan_amount_paid');
$bonus_amount_to=$payslipTo->sum('bonus_amount');
$other_deduct_to=$payslipTo->sum('other_deduct');
$lwp_deduction_to=$payslipTo->sum('lwp_deduction');
$penalty_to=$payslipTo->sum('penalty');
$total_deduction_to=$payslipTo->sum('total_deduction');
$net_salary_to=$payslipTo->sum('net_salary');
$transfer_mode_salary_to=$payslipTo->where('payment_mode','=','Transfer')->sum('net_salary');
$cheque_mode_salary_to=$payslipTo->where('payment_mode','=','Cheque')->sum('net_salary');

$gross_with_allowance_to=($gross_salaries_to+$vr_to+$other_allowance_to+$Arrears_to+$vehicle_addit_to);

?>

<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }
    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }


</style>

<div class="table-responsive">
    <table class="table table-sm mb-0 table-bordered table-striped pagebreak">
        <thead>

        <th class="text-center" ><b style="font-size:18px;">Total Month of {{ $monthNameFrom.' '.$explodeMonthYearFrom[0].' & '.$monthNameTo.' '.$explodeMonthYearTo[0]  }}  </b></th>
        <th class="text-center" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
        <th class="text-center" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
        </thead>
        <tbody>
        <tr>

            <th class="">Gross Salary</th>
            <td class="text-right"><b>{{ number_format($gross_salaries,0) }}</b></td>
            <td class="text-right"><b @if($gross_salaries != $gross_salaries_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($gross_salaries_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Basic Salary</th>
            <td class="text-right"><b>{{ number_format($basic_salaries,0) }}</b></td>
            <td class="text-right"><b @if($basic_salaries != $basic_salaries_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($basic_salaries_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Hr Utility</th>
            <td class="text-right"><b>{{ number_format($hr_utility_allowance,0) }}</b></td>
            <td class="text-right"><b @if($hr_utility_allowance != $hr_utility_allowance_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($hr_utility_allowance_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Medical</th>
            <td class="text-right"><b>{{ number_format($fix_medical,0) }}</b></td>
            <td class="text-right"><b @if($fix_medical != $fix_medical_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($fix_medical_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class=""> VR</th>
            <td class="text-right"><b>{{ number_format($vr,0) }}</b></td>
            <td class="text-right"><b @if($vr != $vr_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($vr_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Arrear</th>
            <td class="text-right"><b>{{ number_format($Arrears,0) }}</b></td>
            <td class="text-right"><b @if($Arrears != $Arrears_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($Arrears_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Other</th>
            <td class="text-right"><b>{{ number_format($other_allowance,0) }}</b></td>
            <td class="text-right"><b @if($other_allowance != $other_allowance_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($other_allowance_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Vechile Addition @5 %</th>
            <td class="text-right"><b>{{ number_format($vehicle_addit_,0) }}</b></td>
            <td class="text-right"><b @if($vehicle_addit_ != $vehicle_addit_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($vehicle_addit_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Gross With Allowance</th>
            <td class="text-right"><b>{{ number_format($gross_with_allowance,0) }}</b></td>
            <td class="text-right"><b @if($gross_with_allowance != $gross_with_allowance_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($gross_with_allowance_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Taxable Salary </th>
            <td class="text-right"><b>{{ number_format($taxable_salary,0) }}</b></td>
            <td class="text-right"><b @if($taxable_salary != $taxable_salary_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($taxable_salary_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Monthly Income Tax </th>
            <td class="text-right"><b>{{ number_format($monhly_income_tax,0) }}</b></td>
            <td class="text-right"><b @if($monhly_income_tax != $monhly_income_tax_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($monhly_income_tax_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Monthly.P.Fund </th>
            <td class="text-right"><b>{{ number_format($pf_amount,0) }}</b></td>
            <td class="text-right"><b @if($pf_amount != $pf_amount_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($pf_amount_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Adv Salary </th>
            <td class="text-right"><b>{{ number_format($advance_salary_amount,0) }}</b></td>
            <td class="text-right"><b @if($advance_salary_amount != $advance_salary_amount_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($advance_salary_amount_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">EOBI </th>
            <td class="text-right"><b>{{ number_format($eobi_amount,0) }}</b></td>
            <td class="text-right"><b @if($eobi_amount != $eobi_amount_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($eobi_amount_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Loan </th>
            <td class="text-right"><b>{{ number_format($loan_amount_paid,0) }}</b></td>
            <td class="text-right"><b @if($loan_amount_paid != $loan_amount_paid_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($loan_amount_paid_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class=""> Bonus </th>
            <td class="text-right"><b>{{ number_format($bonus_amount,0) }}</b></td>
            <td class="text-right"><b @if($bonus_amount != $bonus_amount_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($bonus_amount_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Other </th>
            <td class="text-right"><b>{{ number_format($other_deduct,0) }}</b></td>
            <td class="text-right"><b @if($other_deduct != $other_deduct_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($other_deduct_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">LWP</th>
            <td class="text-right"><b>{{ number_format($lwp_deduction,0) }}</b></td>
            <td class="text-right"><b @if($lwp_deduction != $lwp_deduction_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($lwp_deduction_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class=""> Penalty </th>
            <td class="text-right"><b>{{ number_format($penalty,0) }}</b></td>
            <td class="text-right"><b @if($penalty != $penalty_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($penalty_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Total Deduction </th>
            <td class="text-right"><b>{{ number_format($total_deduction,0) }}</b></td>
            <td class="text-right"><b @if($total_deduction != $total_deduction_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($total_deduction_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Net Salary</th>
            <td class="text-right"><b>{{ number_format($net_salary,0) }}</b></td>
            <td class="text-right"><b @if($net_salary_to != $net_salary) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($net_salary_to,0) }}</b>
            </td>
        </tr>

        </tbody>
        <tfoot>
        <tr>

            <th class="">Transfer Amount</th>
            <td class="text-right"><b>{{ number_format($transfer_mode_salary,0) }}</b></td>
            <td class="text-right"><b @if($transfer_mode_salary != $transfer_mode_salary_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($transfer_mode_salary_to,0) }}</b>
            </td>
        </tr>
        <tr>

            <th class="">Cheque Amount</th>
            <th class="text-right">{{ number_format($cheque_mode_salary,0) }}</th>
            <td class="text-right"><b @if($cheque_mode_salary != $cheque_mode_salary_to) class="fas fa-arrow-right" style="color: red";@endif>
                    {{ number_format($cheque_mode_salary_to,0) }}</b>
            </td>
        </tr>

        </tfoot>


    </table>
</div>

<hr><br>

<div class="table-responsive wrapper" id="emp_payroll_count" style="min-height: auto;">
    <?php
    $count =1;
    ?>
    <table class="table tbl_payroll table-sm mb-0 table-bordered table-striped tableFixHead pagebreak" id="regionWisePayrollReport">
        <tbody>

        @foreach($employees as $value)
            <?php
            CommonHelper::companyDatabaseConnection($_GET['m']);
            $designation_id = $value['designation_id'];
            $employeeCurrentPositions = EmployeePromotion::select('designation_id')->where([['emp_id','=',$value['emp_id']],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
            $payslip = Payslip::where([['emp_id','=',$value["emp_id"]],["month","=",$explodeMonthYearFrom[1]],["year","=",$explodeMonthYearFrom[0]]]);
            $payslipTo = Payslip::where([['emp_id','=',$value["emp_id"]],["month","=",$explodeMonthYearTo[1]],["year","=",$explodeMonthYearTo[0]]]);
            //$payroll = PayrollData::where([['emp_id','=',$value["emp_id"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);

            if($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
            endif;
            //  $empCategory = HrHelper::getMasterTableValueById($companyData->id,'employee_category','employee_category_name',$value["employee_category_id"]);
            $emp_designation =  HrHelper::getMasterTableValueById($_GET['m'],'designation','designation_name', $designation_id);
            $department_name = HrHelper::getMasterTableValueById($_GET['m'],'department','department_name',$value['emp_department_id']);
            $sub_department_name = HrHelper::getMasterTableValueById($_GET['m'],'sub_department','sub_department_name',$value['emp_sub_department_id']);


            $emp_name=$value["emp_name"];


            if($payslip->count()>0 && $payslipTo->count()>0):
            //$payroll_data =$payroll->first();
            $payslip =$payslip->first();
            $payslipTo =$payslipTo->first();
            if($payslip["net_salary"] != $payslipTo["net_salary"]){
            CommonHelper::companyDatabaseConnection($_GET['m']);
            $account_no =Employee::select('emp_id','bank_account')->where('emp_id', $payslip['emp_id'])->where('status', '1')->first();
            CommonHelper::reconnectMasterDatabase();

            $count_grossWithAllowance=($payslip['gross_salaries']+$payslip['vr_other_allow']+$payslip['other_allowance']+$payslip['Arrears']+$payslip['vehicle_addit_']);
            $count_grossWithAllowanceTo=($payslipTo['gross_salaries']+$payslipTo['vr_other_allow']+$payslipTo['other_allowance']+$payslipTo['Arrears']+$payslipTo['vehicle_addit_']);
            ?>

            <tr class="th-user" >
                <th colspan="24">
                    <h3>
                        {{ $value["emp_name"] }} &nbsp;&nbsp;|&nbsp;&nbsp;
                        {{  $sub_department_name }}
                    </h3>
                </th>
                <th colspan="24">
                    <h3>
                        {{ $value["emp_name"] }} &nbsp;&nbsp;|&nbsp;&nbsp;
                        {{  $sub_department_name }}
                    </h3>
                </th>

            </tr>
            <tr>
                <th colspan="4" class="text-center">{{$count++ }}</th>
                <th colspan="2" class="text-center" >Gross</th>
                <th colspan="2" class="text-center">Basic</th>
                <th colspan="2" class="text-center">HR & Utility</th>
                <th colspan="2" class="text-center">Medical</th>
                <th colspan="2" class="text-center">V.R</th>
                <th colspan="2" class="text-center">Others</th>
                <th colspan="2" class="text-center">Arrears</th>
                <th colspan="2" class="text-center">Vehicle Addit.@5%</th>
                <th colspan="2" class="text-center">Gross With Allowance</th>
                <th colspan="2" class="text-center">Taxable Salary</th>
                <th colspan="2" class="text-center">Monthly I Tax</th>
                <th colspan="2" class="text-center">Monthly.P.Fund</th>
                <th colspan="2" class="text-center">Adv Salary</th>
                <th colspan="2" class="text-center">EOBI </th>
                <th colspan="2" class="text-center">Loan</th>
                <th colspan="2" class="text-center">Eid Bonus</th>
                <th colspan="2" class="text-center">l.w/p</th>
                <th colspan="2" class="text-center">Penalty</th>
                <th colspan="2" class="text-center">Others</th>

                <th colspan="2" class="text-center">Total Deduction</th>
                <th colspan="2" class="text-center">Net Salary </th>
            </tr>
            <tr class="text-center">
                <th class="text-center" colspan="4"> <i class="monthFrom">{{ $monthNameFrom }}</i>
                </th>
                <td colspan="2">{{ $payslip["gross_salaries"] }}</td>

                <td colspan="2">{{ number_format($payslip["total_salary"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["hr_utility_allowance"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["fix_medical"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["vr_other_allow"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["other_allowance"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["Arrears"],0)  }}</td>

                <td colspan="2">{{ number_format($payslip["vehicle_addit_"],0) }}</td>

                <td colspan="2">{{ number_format($count_grossWithAllowance,0) }}</td>

                <td colspan="2">{{ number_format($payslip["taxable_salary"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["tax_amount"],0) }}</td>


                <td colspan="2">{{ number_format($payslip["pf_amount"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["advance_salary_amount"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["eobi_amount"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["loan_amount_paid"],0)}}</td>

                <td colspan="2">{{ number_format($payslip["bonus_amount"],0) }}</td>

                <td colspan="2">{{ number_format(($payslip["lwp_deduction"]+$payslip['deduction_days_amount']),0) }}</td>

                <td colspan="2">{{ number_format($payslip["penalty"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["other_deduct"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["total_deduction"],0) }}</td>

                <td colspan="2">{{ number_format($payslip["net_salary"],0) }}</td>


            </tr>
            <tr class="text-center">
                <th class="text-center" colspan="4"> <i class="monthTo">{{ $monthNameTo }}</i>
                </th>
                <td colspan="2" @if($payslipTo["gross_salaries"] != $payslip["gross_salaries"]) style="color: red" @endif>
                    {{ $payslipTo["gross_salaries"] }}
                    <br><span @if($payslipTo["gross_salaries"] != $payslip["gross_salaries"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["total_salary"] != $payslip["total_salary"]) style="color: red" @endif>
                    {{ number_format($payslipTo["total_salary"],0) }}
                    <br><span @if($payslipTo["total_salary"] != $payslip["total_salary"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["hr_utility_allowance"] != $payslip["hr_utility_allowance"]) style="color: red" @endif>
                    {{ number_format($payslipTo["hr_utility_allowance"],0) }}
                    <br><span @if($payslipTo["hr_utility_allowance"] != $payslip["hr_utility_allowance"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["fix_medical"] != $payslip["fix_medical"]) style="color: red" @endif>
                    {{ number_format($payslipTo["fix_medical"],0) }}
                    <br><span @if($payslipTo["fix_medical"] != $payslip["fix_medical"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["vr_other_allow"] != $payslip["vr_other_allow"]) style="color: red" @endif>
                    {{ number_format($payslipTo["vr_other_allow"],0) }}
                    <br><span @if($payslipTo["vr_other_allow"] != $payslip["vr_other_allow"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["other_allowance"] != $payslip["other_allowance"]) style="color: red" @endif>
                    {{ number_format($payslipTo["other_allowance"],0) }}
                    <br><span @if($payslipTo["other_allowance"] != $payslip["other_allowance"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["Arrears"] != $payslip["Arrears"]) style="color: red" @endif>
                    {{ number_format($payslipTo["Arrears"],0)  }}
                    <br><span @if($payslipTo["Arrears"] != $payslip["Arrears"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["vehicle_addit_"] != $payslip["vehicle_addit_"]) style="color: red" @endif>
                    {{ number_format($payslipTo["vehicle_addit_"],0) }}
                    <br><span @if($payslipTo["vehicle_addit_"] != $payslip["vehicle_addit_"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($count_grossWithAllowanceTo != $count_grossWithAllowance) style="color: red" @endif>
                    {{ number_format($count_grossWithAllowanceTo,0) }}
                    <br><span @if($count_grossWithAllowanceTo != $count_grossWithAllowance) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["taxable_salary"] != $payslip["taxable_salary"]) style="color: red" @endif>
                    {{ number_format($payslipTo["taxable_salary"],0) }}
                    <br><span @if($payslipTo["taxable_salary"] != $payslip["taxable_salary"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["tax_amount"] != $payslip["tax_amount"]) style="color: red" @endif>
                    {{ number_format($payslipTo["tax_amount"],0) }}
                    <br><span @if($payslipTo["tax_amount"] != $payslip["tax_amount"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["pf_amount"] != $payslip["pf_amount"]) style="color: red" @endif>
                    {{ number_format($payslipTo["pf_amount"],0) }}
                    <br><span @if($payslipTo["pf_amount"] != $payslip["pf_amount"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["advance_salary_amount"] != $payslip["advance_salary_amount"]) style="color: red" @endif>
                    {{ number_format($payslipTo["advance_salary_amount"],0) }}
                    <br><span @if($payslipTo["advance_salary_amount"] != $payslip["advance_salary_amount"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["eobi_amount"] != $payslip["eobi_amount"]) style="color: red" @endif>
                    {{ number_format($payslipTo["eobi_amount"],0) }}
                    <br><span @if($payslipTo["eobi_amount"] != $payslip["eobi_amount"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["loan_amount_paid"] != $payslip["loan_amount_paid"]) style="color: red" @endif>
                    {{ number_format($payslipTo["loan_amount_paid"],0)}}
                    <br><span @if($payslipTo["loan_amount_paid"] != $payslip["loan_amount_paid"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["bonus_amount"] != $payslip["bonus_amount"]) style="color: red" @endif>
                    {{ number_format($payslipTo["bonus_amount"],0) }}
                    <br><span @if($payslipTo["bonus_amount"] != $payslip["bonus_amount"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2"  @if($payslip["lwp_deduction"]+$payslip['deduction_days_amount'] != $payslipTo["lwp_deduction"]+$payslipTo['deduction_days_amount']) style="color: red" @endif>
                    {{ number_format(($payslipTo["lwp_deduction"]+$payslipTo['deduction_days_amount']),0) }}
                    <br><span @if($payslipTo["lwp_deduction"] != $payslip["lwp_deduction"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["penalty"] != $payslip["penalty"]) style="color: red" @endif>
                    {{ number_format($payslipTo["penalty"],0) }}
                    <br><span @if($payslipTo["penalty"] != $payslip["penalty"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["other_deduct"] != $payslip["other_deduct"]) style="color: red" @endif>
                    {{ number_format($payslipTo["other_deduct"],0) }}
                    <br><span @if($payslipTo["other_deduct"] != $payslip["other_deduct"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["total_deduction"] != $payslip["total_deduction"]) style="color: red" @endif>
                    {{ number_format($payslipTo["total_deduction"],0) }}
                    <br><span @if($payslipTo["total_deduction"] != $payslip["total_deduction"]) class="fas fa-arrow-up" @endif></span>
                </td>
                <td colspan="2" @if($payslipTo["net_salary"] != $payslip["net_salary"]) style="color: red";@endif>
                    {{ number_format($payslipTo["net_salary"],0) }}
                    <br><span @if($payslipTo["net_salary"] != $payslip["net_salary"]) class="fas fa-arrow-up";@endif></span>
                </td>
            </tr>
            <?php } else{ ?>

            <?php } else: ?>
                <?php
                $recordNotFound[] = "<tr class='text-center'><td colspan='27'><b style='color:red;'> $emp_name Payroll Not Found !</b></td></tr>";
            endif; ?>

            <?php CommonHelper::reconnectMasterDatabase(); ?>
        </tbody>

        @endforeach
    </table>
</div>

<hr><br>

<?php


if($explodeMonthYearFrom[1] > 0){
    $monthNameFrom = date('M', mktime(0, 0, 0, $explodeMonthYearFrom[1]=(int)$explodeMonthYearFrom[1], 10));
}



if($explodeMonthYearTo[1] > 0){
    $monthNameTo = date('M', mktime(0, 0, 0, $explodeMonthYearTo[1]=(int)$explodeMonthYearTo[1], 10));
}



?>


<div class="pagebreak">
<div class="row">
    <div class="col-sm-12">
        <h3 style="background-color: #f5f3ff;padding: 5px 0px 5px 0px;font-size:22px;" class="text-center"><b>Employees Count</b></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="3" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Joining Date</b></th>
                </thead>
                <tbody>
                @if($employeesFrom->count() >0)
                    <?php $counter = 1;?>
                    @foreach($employeesFrom as $emp_from)
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ $emp_from->emp_name }}</b></td>
                            <td class=""><b>{{ HrHelper::date_format($emp_from->emp_joining_date) }}</b></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="3" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>


            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="3" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Joining Date</b></th>
                </thead>
                <tbody>
                @if($employeesTo->count() >0)
                    <?php $counter1 = 1;?>
                    @foreach($employeesTo as $emp_to)
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter1;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter1++;?></span>
                            </td>
                            <td class=""><b>{{ $emp_to->emp_name }}</b></td>
                            <td class=""><b>{{ HrHelper::date_format($emp_to->emp_joining_date) }}</b></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="3" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>


            </table>
        </div>

    </div>
</div>

<hr><br>



<div class="row">
    <div class="col-sm-12">
        <h3 style="background-color: #f5f3ff;padding: 5px 0px 5px 0px;font-size:22px;" class="text-center"><b>Employees Exit Count</b></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="3" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Last Working Date</b></th>
                </thead>
                <tbody>
                @if($employeesExitFrom->count() >0)
                    <?php $counter = 1;?>
                    @foreach($employeesExitFrom as $emp_from)
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$emp_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ HrHelper::date_format($emp_from->last_working_date) }}</b></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="3" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>


            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="3" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Last Working Date</b></th>
                </thead>
                <tbody>
                @if($employeesExitTo->count() >0)
                    <?php $counter1 = 1;?>
                    @foreach($employeesExitTo as $emp_to)
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter1;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter1++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$emp_to ->emp_id,'emp_id')}}</b></td>
                            <td class=""><b>{{ HrHelper::date_format($emp_to->last_working_date) }}</b></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="3" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>


            </table>
        </div>

    </div>
</div>
</div>
<hr><br>


<div class="pagebreak">
<div class="row">
    <div class="col-sm-12">
        <h3 style="background-color: #f5f3ff;padding: 5px 0px 5px 0px;font-size:22px;" class="text-center"><b>Allowance Count</b></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>

                </thead>
                <tbody>
                @if($allowance_from->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($allowance_from as $allow_from)
                        @if($allow_from->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $allow_from->month=(int)$allow_from->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$allow_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ HrHelper::getMasterTableValueById(Input::get('m'),'allowance_types','allowance_type', $allow_from->allowance_type_id) }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$allow_from->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($allow_from->allowance_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($allowance_from->count() >0)
                    <tfoot>
                    <tr>
                        <th class="text-right" colspan="4"><b>Total Amount</b></th>
                        <td class="text-right"><b>{{ number_format($allowance_from->sum('allowance_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>


                </thead>
                <tbody>
                @if($allowance_to->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($allowance_to as $allow_to)
                        @if($allow_to->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $allow_to->month=(int)$allow_to->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$allow_to->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ HrHelper::getMasterTableValueById(Input::get('m'),'allowance_types','allowance_type', $allow_to->allowance_type_id) }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$allow_to->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($allow_to->allowance_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($allowance_to->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($allowance_to->sum('allowance_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>

    </div>
</div>


<hr><br>

<div class="row">
    <div class="col-sm-12">
        <h3 style="background-color: #f5f3ff;padding: 5px 0px 5px 0px;font-size:22px;" class="text-center"><b>Deduction Count</b></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Deduction</b></th>


                </thead>
                <tbody>
                @if($deduction_from->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($deduction_from as $deduct_from)
                        @if($deduct_from->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $deduct_from->month=(int)$deduct_from->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$deduct_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $deduct_from->deduction_type}}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$deduct_from->year }}</b></td>
                            <td class="text-right"><b>{{ $deduct_from->deduction_type=="LWP"? $deduct_from->deduction_amount.' Day':number_format($deduct_from->deduction_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($deduction_from->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($deduction_from->whereIn('deduction_type',['Other','Penalty'])->sum('deduction_amount'),0) }}</b></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Days</b></td>
                        <td class="text-right" colspan="1"><b>{{ $deduction_from->whereIn('deduction_type',['LWP'])->sum('deduction_amount')." Days" }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Deduction</b></th>


                </thead>
                <tbody>
                @if($deduction_to->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($deduction_to as $deduct_to)
                        @if($deduct_to->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $deduct_to->month=(int)$deduct_to->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$deduct_to->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $deduct_to->deduction_type }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$deduct_to->year }}</b></td>
                            <td class="text-right"><b>{{ $deduct_to->deduction_type=="LWP"? $deduct_to->deduction_amount.' Day':number_format($deduct_to->deduction_amount,0) }}</b></td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($deduction_to->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($deduction_to->whereIn('deduction_type',['Other','Penalty'])->sum('deduction_amount'),0) }}</b></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Days</b></td>
                        <td class="text-right" colspan="1"><b>{{ $deduction_to->whereIn('deduction_type',['LWP'])->sum('deduction_amount')." Days" }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>

    </div>
</div>
</div>


<hr><br>
<div class="pagebreak">
    <div class="row">
        <div class="col-sm-12">
            <h3 style="background-color: #f5f3ff;padding: 5px 0px 5px 0px;font-size:22px;" class="text-center"><b>Increment Count</b></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="">
                <table class="table table-sm mb-0 table-bordered table-striped">
                    <thead>
                    <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                    </thead>
                    <thead>
                    <th class=""><b style="font-size:18px;">S.No</b></th>
                    <th class=""><b style="font-size:18px;">Name</b></th>
                    <th class=""><b style="font-size:18px;">Date</b></th>
                    <th class=""><b style="font-size:18px;">Increment</b></th>
                    </thead>
                    <tbody>
                    @if($increment_from->count() >0)
                        <?php $counter = 1;
                        $monthName;
                        ?>
                        @foreach($increment_from as $inc_from)
                           <tr>
                                <td class="text-center counterId" id="<?php echo $counter;?>">
                                    <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                                </td>
                                <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$inc_from->emp_id,'emp_id') }}</b></td>
                               <td class=""><b>{{ HrHelper::date_format($inc_from->promotion_date) }}</b></td>
                               <td class="text-right"><b>{{ number_format($inc_from->increment,0) }}</b></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                        </tr>
                    @endif
                    </tbody>
                    @if($increment_from->count() >0)
                        <tfoot>
                        <tr>
                            <td class="text-right" colspan="3"><b>Total Amount</b></td>
                            <td class="text-right" colspan="1"><b>{{ number_format($increment_from->sum('increment'),0) }}</b></td>
                        </tr>
                        </tfoot>
                    @endif

                </table>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="">
                <table class="table table-sm mb-0 table-bordered table-striped">
                    <thead>
                    <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                    </thead>
                    <thead>
                    <th class=""><b style="font-size:18px;">S.No</b></th>
                    <th class=""><b style="font-size:18px;">Name</b></th>
                    <th class=""><b style="font-size:18px;">Date</b></th>
                    <th class=""><b style="font-size:18px;">Increment</b></th>
                    </thead>
                    <tbody>
                    @if($increment_to->count() >0)
                        <?php $counter = 1;
                        $monthName;
                        ?>
                        @foreach($increment_to as $inc_to)
                           <tr>
                                <td class="text-center counterId" id="<?php echo $counter;?>">
                                    <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                                </td>
                                <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$inc_to->emp_id,'emp_id') }}</b></td>
                               <td class=""><b>{{ HrHelper::date_format($inc_to->promotion_date) }}</b></td>
                               <td class="text-right"><b>{{ number_format($inc_to->increment,0) }}</b></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                        </tr>
                    @endif
                    </tbody>
                    @if($increment_to->count() >0)
                        <tfoot>
                        <tr>
                            <td class="text-right" colspan="3"><b>Total Amount</b></td>
                            <td class="text-right" colspan="1"><b>{{ number_format($increment_to->sum('increment'),0) }}</b></td>
                        </tr>
                        </tfoot>
                    @endif

                </table>
            </div>

        </div>
    </div>
</div>


<hr><br>
<div class="pagebreak">
<div class="row">
    <div class="col-sm-12">
        <h3 style="background-color: #f5f3ff;padding: 5px 0px 5px 0px;font-size:22px;" class="text-center"><b>Loan Applied Count</b></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>


                </thead>
                <tbody>
                @if($loan_from->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($loan_from as $l_from)
                        @if($l_from->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $l_from->month=(int)$l_from->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$l_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$l_from->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($l_from->loan_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($loan_from->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="3"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($loan_from->sum('loan_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif



            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>

                </thead>

                <tbody>

                @if($loan_to->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($loan_to as $l_to)
                        @if($l_to->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $l_to->month=(int)$l_to->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$l_to->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$l_to->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($l_to->loan_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($loan_to->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="3"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($loan_to->sum('loan_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>

    </div>
</div>

<hr><br>

<div class="row">
    <div class="col-sm-12">
        <h3 style="background-color: #f5f3ff;padding: 5px 0px 5px 0px;font-size:22px;" class="text-center"><b>Loan Paid Count</b></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>


                </thead>
                <tbody>
                @if($loan_paid_from->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($loan_paid_from as $l_from)
                        @if($l_from->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $l_from->month=(int)$l_from->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$l_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$l_from->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($l_from->loan_amount_paid,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($loan_paid_from->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="3"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($loan_paid_from->sum('loan_amount_paid'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif



            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>

                </thead>

                <tbody>

                @if($loan_paid_to->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($loan_paid_to as $l_to)
                        @if($l_to->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $l_to->month=(int)$l_to->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$l_to->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$l_to->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($l_to->loan_amount_paid,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($loan_paid_to->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="3"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($loan_paid_to->sum('loan_amount_paid'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>

    </div>
</div>
</div>

