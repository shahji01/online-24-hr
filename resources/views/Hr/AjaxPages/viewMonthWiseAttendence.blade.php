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
            <input type="hidden" name="attendance_type" id="attendance_type" value="1">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Regions:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="region_id" id="region_id">
                    <option value="">Select Region</option>
                    @foreach($employee_regions as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Category:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="emp_category_id" id="emp_category_id">
                    <option value="">Select Category</option>
                    @foreach($employee_category as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Employee:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="emr_no" id="emr_no" required>
                    <option value="">-</option>
                </select>
                <div id="emp_loader"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Date From</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" class="form-control requiredField" name="date_from" id="date_from" required value="">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Date To</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" class="form-control requiredField" name="date_to" id="date_to" required value="">
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Present Days:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" name="present_days" id="present_days" value="" class="form-control requiredField" required />
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Absent Days:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" name="absent_days" id="absent_days" value="" class="form-control requiredField" required />
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Overtime:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" name="overtime" id="overtime" value="" class="form-control requiredField" placeholder="Overtime in hours" required />
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 32px">
            <button class="btn btn-success" onclick="addManualyAttendance()" style="float: right">Submit</button> &nbsp &nbsp
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12"></div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <span style="color: green; display: none; font-size: 18px" id="success-message">Attendence Submited</span>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12"></div>
        </div>
    </div>
</div>

<script>

    $(".btn-success").click(function(e){
        var employee = new Array();
        var val;
        $("input[name='employeeSection[]']").each(function(){
            employee.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val of employee) {
            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });


    $(function(){
        $('select[name="emp_category_id"]').on('change', function() {
            var emp_category_id = $(this).val();
            var region_id = $("#region_id").val();
            if(region_id == ''){alert('Please Select Region !');return false;}
            var m = '<?= Input::get('m'); ?>';
            if(emp_category_id) {
                $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                    type: "GET",
                    data: { emp_category_id:emp_category_id,region_id:region_id,m:m},
                    success:function(data) {
                        $('#emp_loader').html('');
                        $('select[name="emr_no"]').empty();
                        $('select[name="emr_no"]').html(data);
                    }
                });
            }else{
                $('select[name="emr_no"]').empty();
            }
        });
    });

    $(document).ready(function () {
        $('#emp_category_id').select2();
        $('#region_id').select2();
        $('#emr_no').select2();

    });

</script>