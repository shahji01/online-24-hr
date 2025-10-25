<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$designation_name = HrHelper::getMasterTableValueById($m, 'designation', 'designation_name', $employeeData->designation_id);
$employee_location = HrHelper::getMasterTableValueById($m, 'locations', 'employee_location', $employeeData->branch_id);
$account_no = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee_bank_data', 'account_no', $employeeData->emr_no, 'emr_no');
?>
<style>

    hr{border-top: 1px solid cadetblue}

    .name-d-d ul li {
        font-size: 17px;
        margin: 10px 0px 22px 0px;
    }

    .name-d-d-input ul li {
        margin: 7px 0px 10px 0px;
    }

    .depart-row .col-lg-3 {
        background-color: #080808;
        color: #fff;
        border-left: 1px solid #fff;
    }

    input[type="radio"]{ width:30px;
        height:20px;
    }

    .depart-row-two .col-lg-4 {
        background-color: #999;
        color: #fff;
        border-left: 1px solid #fff;
        padding: 7px 0px 2px 0px;
    }


</style>

&nbsp
<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
            <label>Designation:</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input readonly name="designation_name" id="designation_name" type="text" value="{{ $designation_name }}" class="form-control">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
            <label>Location:</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input readonly name="employee_location" id="employee_location" type="text" value="{{ $employee_location }}" class="form-control">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
            <label>Account No:</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input readonly name="account_no" id="account_no" type="text" value="{{ $account_no }}" class="form-control">
        </div>
    </div>
</div>

<div class="row" style="background-color: gainsboro">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h4 style="text-decoration: underline;font-weight: bold;">Add Fuel Data</h4>
        </div>
    </div>
</div>

<br>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <input type="hidden" name="fuel_data[]" value="1"><div class="">
        <table class="table table-bordered sf-table-list" >
            <thead>
            <th class="text-center col-sm-1">S.No</th>
            <th class="text-center col-sm-2">Date <span class="rflabelsteric"><strong> *</strong></span></th>
            <th class="text-center col-sm-4">From <span class="rflabelsteric"><strong> *</strong></span></th>
            <th class="text-center col-sm-4">To <span class="rflabelsteric"><strong> *</strong></span></th>
            <th class="text-center col-sm-1">KM <span class="rflabelsteric"><strong> *</strong></span></th>
            <th class="text-center"><button type="button" class="btn btn-xs btn-primary" id="addMoreLanguage">Add More Fuel </button></th>
            </thead>
            <tbody id="insert_clone7">
            <tr class="get_rows7">
                <td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                <td id="get_clone7" class="text-center"><input name="fuel_date_1" id="fuel_date_1" type="date" value="" class="form-control" required> </td>
                <td class="text-center"> <input name="from_1" id="from_1" type="text" value="" class="form-control" required> </td>
                <td class="text-center"> <input name="to_1" id="to_1" type="text" value="" class="form-control" required> </td>
                <td class="text-center"> <input name="km_1" id="km_1" type="number" value="" class="form-control" required> </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<br>
<div style="float: right;">
    <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Submit</button>
</div>

<script>

    $("#addMoreLanguage").click(function(e){
        var form_rows_count = $(".get_rows7").length;
        form_rows_count++;
        $("#insert_clone7").append("<tr class='get_rows7' id='remove_area7_"+form_rows_count+"' ><td class='text-center'>" +
            '<input type="hidden" name="fuel_data[]" value="'+form_rows_count+'">' +
            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
            "<td class='text-center'><input type='date' class='form-control requiredField' name='fuel_date_"+form_rows_count+"' value='' id='fuel_date"+form_rows_count+"' required></td>" +
            "<td class='text-center'><input type='text' class='form-control requiredField' name='from_"+form_rows_count+"' value=''>" +
            "<td class='text-center'><input type='text' class='form-control requiredField' name='to_"+form_rows_count+"' value=''>" +
            "<td class='text-center'><input type='number' class='form-control requiredField' name='km_"+form_rows_count+"' value=''>" +
            "<td class='text-center'><button onclick='removeFuelSection("+form_rows_count+")' type='button'class='btn btn-xs btn-danger'>Remove</button>" +
            "</td>" +
            "</tr>");


    });

    function removeFuelSection(id) {
        $("#remove_area7_"+id).remove();
    }

</script>

