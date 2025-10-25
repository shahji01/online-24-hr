<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}

//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Employee:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="emr_no" id="emr_no" required>
                    <option value="">Select Employee</option>@foreach($employees as $value)
                        <option value="{{ $value->emr_no }}">EMR No: .{{ $value->emr_no."---".$value->emp_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Date:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" name="attendance_date" id="attendance_date" value="<?php echo $currentDate?>" class="form-control requiredField" required />
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Attendance Status:</label><span class="rflabelsteric"><strong>*</strong></span><br>
                <b><input type="radio" name="attendance_type" id="attendance_type" value="1">Present</b> &nbsp
                <b><input type="radio" name="attendance_type" id="attendance_type" value="2">Absent</b>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 32px">
                <button class="btn btn-success" onclick="addManualyAttendance()">Submit</button> &nbsp &nbsp
                <span style="color: green; display: none" id="success-message">Attendence Submited</span>
            </div>
        </div>
    </div>
</div>