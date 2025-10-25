<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;




//$m = Auth::user()->company_id;
$m = '12';
$bank_name="";
$account_title="";
$account_no="";
CommonHelper::companyDatabaseConnection($m);

$employee_bank_data=DB::table('employee_bank_data')->where('status',1)->where('emp_id',$payslip_data->emp_id)->orderBy('id','desc');

CommonHelper::reconnectMasterDatabase();
if($employee_bank_data->count()>0){
    $banks=DB::table('banks')->where('id',$employee_bank_data->value('bank_name'));
    if($banks->count()>0){
        $banks=$banks->first();
        $bank_name=$banks->bank_name;
    }
    else{
        $bank_name="";
    }
    $account_title=$employee_bank_data->value('account_title');
    $account_no=$employee_bank_data->value('account_no');
}else{
    $account_title="";
    $account_no="";
}





?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>

<div style="position:absolute;left:50%;margin-left:-311px;top:0px;width:623px;height:849px;border-style:outset;overflow:hidden">

    <table style="width:100%;text-align: left;border-collapse: collapse;">
        <tr>
            <td style="border-color: #fff;">

                <span><b>AAA FM</b></span>
                
            </td>

            <td style="font-size: 12px;border-color: #fff">
                <b>

                    Employee Bonus Payslip for the year ended 30th June <?= $payslip_data->bonus_year; ?></b>
            </td>
            <td style="font-size: 12px;text-align: right;border-color: #fff">
                <b> Date:  <?= date('d-m-Y') ?></b>
            </td>
        </tr>
    </table>
    <br>
    <table style="width:100%;text-align: left !important;border-collapse: collapse;font-size:13px;">
        <tr>
            <th style="border: 1px solid grey;">Employee Name</th>
            <td style="border: 1px solid grey;"><?=$payslip_data->emp_name?></td>
            <th style="border: 1px solid grey;" colspan="2">Father Name</th>
            <td style="border: 1px solid grey;" colspan="2"><?=$payslip_data->emp_father_name?></td>

        </tr>
        <tr>
            <th style="border: 1px solid grey;">Date of Joining</th>
            <td style="border: 1px solid grey;"><?=HrHelper::date_format($payslip_data->joining_date)?></td>
            <th style="border: 1px solid grey;" colspan="2">Employee Id</th>
            <td style="border: 1px solid grey;" colspan="2"><?=$payslip_data->emp_id?></td>
        </tr>

        <tr>
            <th style="border: 1px solid grey;">Designation</th>
            <td style="border: 1px solid grey;"><?=HrHelper::getMasterTableValueById($m,'designation','designation_name',$payslip_data->designation_id)?></td>
            <th style="border: 1px solid grey;" colspan="2">Email Id</th>
            <td style="border: 1px solid grey;" colspan="2"><?=$payslip_data->official_email?></td>

        </tr>
        <tr>
            <th style="border: 1px solid grey;">CNIC</th>
            <td style="border: 1px solid grey;"><?=$payslip_data->cnic?></td>
            <th style="border: 1px solid grey;" colspan="2">Dept</th>
            <td style="border: 1px solid grey;" colspan="2"><?=HrHelper::getMasterTableValueById($m,'department','department_name',$payslip_data->department_id)?></td>
        </tr>


    </table>
    
    <br><br>
    <table style="width:100%;text-align: left;border-collapse: collapse;font-size:13px;">
        <tr>
            <td style="border: 1px solid grey;"><strong>Bonus Amount</strong></td>
            <td style="border: 1px solid grey;text-align: right;" colspan="2">
                <strong> <?=number_format($payslip_data->bonus_amount,0)?></strong>
            </td>

        </tr>
        <tr>
            <td style="border: 1px solid grey;"><strong>Tax Deducted</strong></td>
            <td style="border: 1px solid grey;text-align: right;" colspan="2">
            <strong> <?=number_format($payslip_data->bonus_tax,0)?> </strong>
            </td>

        </tr>
        

    </table>
    
    <br><br>
    <table style="width:100%;text-align: left;border-collapse: collapse;font-size:13px;">
        
        <tr>
            <td style="border: 1px solid grey;"><strong>Net Payments Rs</strong></td>
            <td style="border: 1px solid grey;text-align: right;" colspan="4">
                <strong> <?=number_format($payslip_data->total_net,0)?></strong>
            </td>

        </tr>
        <tr>
            <td style="border: 1px solid grey;">Bank</td>
            <td style="border: 1px solid grey;" colspan="4"><?php echo $bank_name ?></td>

        </tr>

        <tr>
            <td style="border: 1px solid grey;">Employee A/c No</td>
            <td style="border: 1px solid grey;" colspan="4"><?=$account_no?></td>

        </tr>

    </table>
    <br><br>

    <br><br><br><br><br>
    <table style="width:100%;text-align: left;border-collapse: collapse;font-size:13px;">
        <tr>
            <td>
                <p style="font-size:12px;">Note : <span style="color:red;">This is a system generated document and doesn't require any signature. Thank You !</span></p>
            </td>
        </tr>


    </table>
</div>

</body>
</html>


