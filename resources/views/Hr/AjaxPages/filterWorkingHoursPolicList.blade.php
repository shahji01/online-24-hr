<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Config;
use Session;

$counter = 1;
$m = $_GET['m'];
$data ='';
$selectVoucherStatus = $_GET['selectVoucherStatus'];

if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
}

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$data .='<tr><td colspan="13" class="text-center"><strong>Filter By : (From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')</strong></td></tr>';
foreach ($workingHoursPolicyDetail as $row){
    $paramOne = "hdc/viewWorkingHoursPolicyDetail";
    $paramTwo = $row->id;
    $paramThree = "View Working Hours Detail";
    $paramFour = "hr/editWorkingHoursPolicyDetailForm";
    $paramFive = "Edit Working Hours Policy Detail";
    $paramSix = "working_hours_policy";
    $statusButton = HrHelper::getStatusLabel($row->status);
    //$paramOne = "pdc/viewGoodsReceiptNoteDetail";
    //$paramTwo = $row['grn_no'];
    //$paramThree = "View Goods Receipt Note Voucher Detail";
    //$paramFour = "purchase/editGoodsReceiptNoteVoucherForm";
    $data.='<tr><td class="text-center">'.$counter++.'</td><td class="text-center">'.$row->working_hours_policy.'</td><td class="text-center">'.$row->start_working_hours_time.'</td><td class="text-center">'.$row->end_working_hours_time.'</td><td class="text-center">'.$row->working_hours_grace_time.'</td><td class="text-center">'.$row->end_time_for_comming_deduct_half_day.'</td><td class="text-center">'.$row->short_leave_time.'</td><td class="text-center">'.$row->half_day_time.'</td><td class="text-center">'.$statusButton.'</td><td class="text-center hidden-print">';
    $data.=' <a onclick="showDetailModelTwoParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\',\''.$m.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';
    $data .=' <button class="edit-modal btn btn-info btn-xs" onclick="showDetailModelTwoParamerter(\''.$paramFour.'\',\''.$paramTwo.'\',\''.$paramFive.'\',\''.$m.'\')"><span class="glyphicon glyphicon-edit"></span></button>';
    if($row->status == 2){
        $data .=' <button class="delete-modal btn btn-primary btn-xs" onclick="repostCompanyTableRecord(\''.$m.'\',\''.$paramTwo.'\',\''.$paramSix.'\')"><span class="glyphicon glyphicon-refresh"></span></button>';
    }else{
        $data .=' <button class="delete-modal btn btn-danger btn-xs" onclick="deleteRowCompanyHRRecords(\''.$m.'\',\''.$paramTwo.'\',\''.$paramSix.'\')"><span class="glyphicon glyphicon-trash"></span></button>';
    }
    //$data.='&nbsp'.PurchaseHelper::displayDemandVoucherListButton($m,$row->demand_status,$row->status,$row->demand_no,'demand_no','demand_status','status','demand','demand_data',$paramFour,'Demand Voucher Edit Detail Form').'&nbsp;';
    $data.='</td></tr>';
}
?>

<?php
echo json_encode(array('data' => $data));
?>