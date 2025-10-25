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
$selectEmployee = $_GET['selectEmployee'];
$attendanceStatus = $_GET['attendanceStatus'];
if(!empty($selectEmployee)){
    $selectEmployeeTitle = $selectEmployee;
}else{
    $selectEmployeeTitle = 'All Employees';
}

if(empty($attendanceStatus)){
    $selectEmployeeAttendanceTitle = 'All Attendance Status';
}else if($attendanceStatus == '1'){
    $selectEmployeeAttendanceTitle = 'Present Status';
}else if($attendanceStatus == '2'){
    $selectEmployeeAttendanceTitle = 'Absent Status';
}else if($attendanceStatus == '3'){
    $selectEmployeeAttendanceTitle = 'Late Status';
}else if($attendanceStatus == '4'){
    $selectEmployeeAttendanceTitle = 'Clock In Status';
}else if($attendanceStatus == '5'){
    $selectEmployeeAttendanceTitle = 'Clock Out Status';
}

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$data .='<tr><td colspan="15" class="text-center"><strong>Filter By : (Employees => '.$selectEmployeeTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Attandence Status => '.$selectEmployeeAttendanceTitle.')</strong></td></tr>';
foreach ($employeeAttendanceDetail as $row){
    //$paramOne = "pdc/viewGoodsReceiptNoteDetail";
    //$paramTwo = $row['grn_no'];
    //$paramThree = "View Goods Receipt Note Voucher Detail";
    //$paramFour = "purchase/editGoodsReceiptNoteVoucherForm";
    $totalLateThisRange = HrHelper::totalLateForThisRange($m,$fromDate,$toDate,$row->acc_no);
    $totalAbsentThisRange = HrHelper::totalAbsentForThisRange($m,$fromDate,$toDate,$row->acc_no);

    $totalLateThisAccountingYear = HrHelper::totalLateForThisAccountingYear();
    $totalAbsentThisAccountingYear = HrHelper::totalAbsentForThisAccountingYear();

    $dapartmentName = CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',CommonHelper::getCompanyDatabaseTableValueByColumnNameValue($m,'employee','emp_sub_department_id','acc_no',$row->acc_no));
    $data.='<tr><td class="text-center">'.$counter++.'</td><td class="text-center">'.$row->acc_no.'</td><td class="text-center">'.$row->emp_name.'</td><td class="text-center">'.$dapartmentName.'</td><td class="text-center">'.$row->ddate.'</td><td class="text-center">'.$row->clock_in.'</td><td class="text-center">'.$row->clock_out.'</td><td class="text-center">'.$row->total_in_time.'</td><td class="text-center">'.$row->late.'</td><td class="text-center">'.$row->absent.'</td><td class="text-center">'.$totalLateThisRange.'</td><td class="text-center">'.$totalAbsentThisRange.'</td><td class="text-center">'.$totalLateThisAccountingYear.'</td><td class="text-center">'.$totalAbsentThisAccountingYear.'</td><td class="text-center hidden-print">';
    //$data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';
    //$data.='&nbsp'.PurchaseHelper::displayGoodsReceiptNoteVoucherListButton($m,$row->grn_status,$row->status,$row->grn_no,'grn_no','grn_status','status','goods_receipt_note','grn_data',$paramFour,'Goods Receipt Note Voucher Edit Detail Form').'&nbsp;';
    $data.='</td></tr>';
}
?>

<?php
echo json_encode(array('data' => $data));
?>