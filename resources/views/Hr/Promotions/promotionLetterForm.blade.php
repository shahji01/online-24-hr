<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

use App\Models\IncrementLettersDetail;
use App\Models\EmployeePromotion;

CommonHelper::companyDatabaseConnection('12');
$promoted = 0;
$employeePromotionDetail = EmployeePromotion::where([['id','=',$id],['status','=',1]])->first();
$emp_name=DB::table('employee')->select('emp_name')->where('id',$employeePromotionDetail->employee_id)->value('emp_name');
$designation_id = '';
$designation_name = '';
$promoted_designation_name='';

$previous_salary='';
$promotion_exist=DB::table('employee_promotion')->where('status',1)->where('employee_id',$employeePromotionDetail->employee_id)->where('id','!=',$id)->orderBy('id','desc');
if($promotion_exist->count()>0):
    $promotion_exist_first=$promotion_exist->first();
    $designation_id = $promotion_exist_first->designation_id;
    $previous_salary=$promotion_exist_first->salary;
else:    
    $designation_id=DB::table('employee')->select('designation_id')->where('id',$employeePromotionDetail->employee_id)->value('designation_id');
    $previous_salary=DB::table('employee')->select('salary')->where('id',$employeePromotionDetail->employee_id)->value('salary');
endif;
CommonHelper::reconnectMasterDatabase();
$designation_name=DB::table('designation')->select('designation_name')->where('id',$designation_id)->value('designation_name');
$promoted_designation_name=DB::table('designation')->select('designation_name')->where('id',$employeePromotionDetail->designation_id)->value('designation_name');

// $fuel_pf_time = strtotime($emp_data_array[$employeePromotionDetail->emp_code]['confirmation_date']);

// $shift = '';
// if(array_key_exists($employeePromotionDetail->emp_code, $emp_shift_array)):
//     $shift = $emp_shift_array[$employeePromotionDetail->emp_code]['shift_type_id'];
// endif;

// $increment_detail = IncrementLettersDetail::where([['emp_code', '=', $employeePromotionDetail->emp_code], ['status', '=', 1]])->orderBy('id', 'desc');
// $internet_allowance = $increment_detail->value('internet_allowance');
$incremented_salary = 0;
$fuel_amount = 0;
$pf_employer_contribution = 0;
$total_compensation = 0;

$incremented_salary = $employeePromotionDetail->salary;

$promotion_date = explode('-',$employeePromotionDetail->promotion_date);
$effective_date = DateTime::createFromFormat('!m', $promotion_date[1])->format('F');

//if($increment_detail->value('new_designation') != '' && strcmp( $increment_detail->value('previous_designation'), $increment_detail->value('new_designation')) != false):
    $promoted = 1;
// else:
//     $promoted = 0;
// endif;

$pf_employer_contribution = (($employeePromotionDetail->salary+$fuel_amount) / 1.65) * 0.05;
$total_compensation = $incremented_salary + $fuel_amount + $pf_employer_contribution;

?>

<script src="{{ URL::asset('assets/custom/js/customMainFunction.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>


<style type="text/css">

    .salary-table {
        padding: 4px;
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

<body style="margin: 0px 35px 0px 35px !important; font-size: 14px;">
    @if($type=='view')
    <div class="row">
        <div class="col-sm-12">
             <?php echo CommonHelper::displayPrintButtonInBlade('PrintLetter','','1');?>
        </div>
    </div>
    @endif
<div id="PrintLetter">
<div  style="margin-top: 5px;">
    <!--<div>-->
    <!--   <img src="https://finance.tenpearls.com/assets/img/logo-live.png" alt="10Pearls" width="170px">-->
    <!--      <p style="margin-top: 0px">Innovate | Educate | Empower | Accelerate</p>-->
    <!--</div>-->
    
    <div>
        <p style="text-align: right;">{{HrHelper::date_format(date('Y-m-d'))}}</p>
        <p><b>{{ $emp_name }}</b></p>
        <p style="margin-top: -8px">{{ $designation_name }}</p>
    </div>
    <div style="text-align: center;">
        @if($employeePromotionDetail->designation_id != $designation_id && $employeePromotionDetail->increment == 0)
            <span><b style="border-bottom: solid 1px #000;">PROMOTION </b></span>
        @else
            <span><b style="border-bottom: solid 1px #000;">SALARY INCREMENT @if($promoted == 1) AND PROMOTION @endif</b></span>
        @endif
    </div>

    <p>Dear <b> {{$emp_name }},</b></p>

    <div>
        @if($employeePromotionDetail->designation_id != $designation_id && $employeePromotionDetail->increment == 0)
            <p>
                The management has reviewed your performance and has acknowledged your achievements with great appreciation.
                Therefore, we are pleased to inform you that you have been promoted to <b>{{ $promoted_designation_name }}</b>
                with effect from {{ $effective_date }} {{ $promotion_date[2] }}, {{ $promotion_date[0] }}.
            </p>
        @else
            <p>The management has reviewed your performance and has acknowledged your achievements with great appreciation.</p>

            <p>
                We are pleased to inform you that you have been
                @if($employeePromotionDetail->designation_id != $designation_id) promoted to <b>{{ $promoted_designation_name }}</b>
                and @endif awarded a salary increase of <b>Rs. {{ number_format($employeePromotionDetail->increment) }}/-</b>.
                Therefore, your revised monthly salary with effect from {{ $effective_date }} {{ $promotion_date[2] }}, {{ $promotion_date[0] }}
                is <b>Rs. {{ number_format($employeePromotionDetail->salary ,0) }}/-</b>
            </p>
        @endif
    </div>
    <div>
        <p>Following are the details of your updated package:</p>
    </div>
</div>


<div style="text-align: right;">
    <table class="salary-table">
        <tbody>
        <tr>
            <td class="salary-table">Previous Salary</td>
            <td class="salary-table">Rs. {{ number_format($previous_salary,0) }}</td>
        </tr>
        <tr>
            <td class="salary-table">Increment</td>
            <td class="salary-table">Rs. {{ number_format($employeePromotionDetail->increment,0) }}</td>
        </tr>
        <tr>
            <td class="salary-table">Revised Salary</td>
            <td class="salary-table">Rs. {{ number_format($employeePromotionDetail->salary, 0) }}</td>
        </tr>
        </tbody>
    </table>
</div>
<div>
    <p>Your salary information is highly confidential. Disciplinary action shall be taken in case of disclosure.</p>
</div>
<div>
    <p>We thank you for your efforts and contributions and hope that you will continue to provide the same level of motivation going forward.</p>
</div>
<div>
    <p>Sincerely,</p>
</div>
<!--<div>-->
<!--    <img src="https://finance.tenpearls.com/assets/img/Sana%20H%20E-Signature.png" style="width: 104px;margin-bottom: -9px;padding: 15px 160px 0px 30px;">-->
<!--    @if($employeePromotionDetail->promotion_date >= '2021-07-01')-->
<!--        <img src="https://finance.tenpearls.com/assets/img/HasanAhmedTariqSignature.jpg" style="width: 104px;margin: 0px 0px -9px 200px;">-->
<!--        <p><b style="padding: 15px 10px 0px 10px;border-top: solid 1px #000;margin: 11px 116px 0px -1px;">Senior Director Human Capital</b><b style="border-top: solid 1px #000;margin: 11px 0px 0px 160px;padding: 15px 10px 0px 10px">Director Finance</b></p>-->
<!--    @else-->
<!--        <img src="https://finance.tenpearls.com/assets/img/ShahidHameedSign.jpg" style="width: 104px;margin: 0px 0px -9px 200px;">-->
<!--        <p><b style="padding: 15px 10px 0px 10px;border-top: solid 1px #000;margin: 11px 116px 0px -1px;">Senior Director Human Capital</b><b style="border-top: solid 1px #000;margin: 11px 0px 0px 160px;padding: 15px 10px 0px 10px">Manager Finance</b></p>-->
<!--    @endif-->
<!--</div>-->
@if($employeePromotionDetail->increment > 0)
    <div>
        <p style="margin-top: 15px;"><b>Note: </b>Income Tax deduction will be applicable on the revised salary.</p>
    </div>
@endif

<!--<div style="float: right; margin-top:50px;">-->

<!--    <table style="border: 1px solid white; text-align: right">-->

<!--        <tr>-->
<!--            <td style=" border-right-color: gainsboro;border-right-style: solid; padding-right: 30px;">9th Floor, Parsa Tower</td>-->
<!--            <td style="padding-left: 30px;">02134328447 - 9</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style=" border-right-color: gainsboro;border-right-style: solid; padding-right: 30px;">Sharah-e-Faisal</td>-->
<!--            <td style="padding-left: 30px;">02134383854</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style=" border-right-color: gainsboro;border-right-style: solid; padding-right: 30px;"> Karachi, Pakistan</td>-->
<!--            <td style="padding-left: 30px;">www.10pearls.com</td>-->
<!--        </tr>-->
<!--    </table>-->

<!--</div>-->

</div>
<script>
    function printView(param1,param2,param3) {

    // $('.table-responsive').removeClass('table-responsive');
    // $('.wrapper').removeClass('wrapper');
    // $( ".qrCodeDiv" ).removeClass( "hidden" );
    var printContents = document.getElementById(param1).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>

</body>