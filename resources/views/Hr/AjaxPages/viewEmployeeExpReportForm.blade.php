<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><b><u>Employee Experience Report</u></b></h3>
    </div>
</div>
<hr>
<div class="panel"> 
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label pointer">Department</label>
                <span class="rflabelsteric"><strong></strong></span>
                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()" >
                    <option value="">Select Department</option>
                    @foreach($department_id as $key => $y)
                        <option value="<?php echo $y->id ?>"> 				{{ $y->department_name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			   <label class="sf-label pointer">Department</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
					<option value="">Select Department</option>
					@foreach($sub_department as $key => $y)
						<option value="<?php echo $y->id ?>"> 				{{ $y->sub_department_name}}
						</option>
					@endforeach
				</select>
			</div>
           

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Employee:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control" name="emp_id" id="emp_id" >
                    <option value="">-</option>
                </select>
                <div id="emp_loader"></div>
            </div>
           
        </div>
        <br>
        <div class="row">
			 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">From Date</label>
                <input type="date" class="form-control" id="from_date" name="from_date" required>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">To Date</label>
                <input type="date" class="form-control" id="to_date" name="to_date" required>
            </div>
            <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12 text-right" style="margin-top: 10px;">
            <br>    
            <label>Show All
                    <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1" style="margin-top: -2px;">
                </label>
                <button class="btn_search btn btn-sm btn-info" id="search"> <i class="fas fa-search fa"></i>Search</button>
            </div>
           
        </div>
    </div>
</div>
<input type="hidden" id="baseUrl" value="{{ url('/') }}">
<script>
    var baseUrl=$('#baseUrl').val();
    var validate=0;
    function jqueryValidationCustom() {

        var requiredField = document.getElementsByClassName('requiredField');

        for (i = 0; i < requiredField.length; i++) {

            var rf = requiredField[i].id;

            var checkType = requiredField[i].type;

            /*if(checkType == 'text'){
             alert('Please type text');
             }else if(checkType == 'select-one'){
             alert('Please select one option');
             }else if(checkType == 'number'){
             alert('Please type number');
             }else if(checkType == 'date'){
             alert('Please type date');
             }*/
            if ($('#' + rf).val() == '') {
                $('#' + rf).css('border-color', 'red');
                $('#' + rf).focus();
                validate = 1;
                return false;
            } else {
                $('#' + rf).css('border-color', '#ccc');
                validate = 0;
            }
        }


        /*var requiredField1 = document.getElementsByClassName('requiredField');
         for (i = 0; i < requiredField1.length; i++){
         var rf1 = requiredField[i].id;
         if($('#'+rf1+'').val() == ''){
         validate = 1;
         }else{
         validate = 0;
         }
         }*/
        return validate;
    }

    $(document).ready(function(){

        $('.checkbox').change(function () {
            if ($(this).is(':checked')) {
                $('#sub_department').attr('disabled', true);
                $('#employee_project_id').attr('disabled', true);
                $('#emp_id').attr('disabled', true);
                $('#from_date').attr('disabled', true);
                $('#to_date').attr('disabled', true);

                $('#sub_department_id_1').removeClass('requiredField').removeAttr('required');
                $('#emp_id').removeClass('requiredField').removeAttr('required');
                $('#from_date').removeClass('requiredField').removeAttr('required');
                $('#to_date').removeClass('requiredField').removeAttr('required');


            } else {
                $('#sub_department_id_1').attr('disabled', false);
                $('#employee_project_id').attr('disabled', false);
                $('#emp_id').attr('disabled', false);
                $('#from_date').attr('disabled', false);
                $('#to_date').attr('disabled', false);

                $('#sub_department_id_1').addClass('requiredField').attr('required');
                $('#emp_id').addClass('requiredField').attr('required');
                $('#from_date').addClass('requiredField').attr('required');
                $('#to_date').addClass('requiredField').attr('required');

            }
        });

        $(".btn-info").click(function(e){
            var degreeType = new Array();
            var val;
            $("input[name='HrReports[]']").each(function(){
                degreeType.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val in degreeType) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });


      
        $('#sub_department_id').select2();
        $('#department_id').select2();
        $('#emp_id').select2();

    });


    $('#search').click(function() {
        var sub_department_id_1 = $('#sub_department_id_1').val();
        var employee_project_id = $('#employee_project_id').val();
        var emp_id = $('#emp_id').val();
        var emp_joining_date = $('#emp_joining_date').val();
        var show_all = $("input[name='show_all']:checked"). val();
        var m = $('#m').val();
        var to_date = $("#to_date").val();
        var from_date = $("#from_date").val();
        var data ='';

        if(show_all == 1)
        {
             data = {
                     show_all:show_all,
                     m:m,
                     employee_project_id:'0',
                    };
        }
        else
        {
             data = {
                 m:m,
                 sub_department_id_1:sub_department_id_1,
                 employee_project_id:employee_project_id,
                 emp_id:emp_id,
                 from_date:from_date,
                 to_date:to_date, 
             };
        }
        jqueryValidationCustom();
        if(validate == 0)
        {
            $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: baseUrl+"/HrReports/viewEmployeeExpReport",
                type:'GET',
                data:data,
                success: function (response){
                    $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-area').html(response);

                }
            });
        }
        else
        {
            $('#report-area').html('');
            return false;
        }
    });

    function employeeProject() {
        var emp_category_id = $("#employee_category_id").val();
        var region_id = $("#region_id").val();
        var employee_project_id = $("#employee_project_id").val();
        if(employee_project_id == '0'){
            empCategory()
        }
        if (region_id == '') {
            alert('Please Select Region !');
            return false;
        } else if (emp_category_id == '') {
            alert('Please Select Cateogery !');
            return false;
        } else {
            var m = '<?= Input::get('m'); ?>';
            if (employee_project_id) {
                $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/slal/getEmployeeProjectList',
                    type: "GET",
                    data: {
                        emp_category_id: emp_category_id,
                        region_id: region_id,
                        employee_project_id: employee_project_id,
                        m: m
                    },
                    success: function (data) {
                        $('#emp_loader').html('');
                        $('select[name="emr_no"]').empty();
                        $('select[name="emr_no"]').html(data);
                    }
                });
            } else {
                $('select[name="emr_no"]').empty();
            }
        }
    }

    function empCategory() {
        var emp_category_id = $("#employee_category_id").val();
        var region_id = $("#region_id").val();
        if (region_id == '') {
            alert('Please Select Region !');
            return false;
        } else {
            var m = '<?= Input::get('m'); ?>';
            if (emp_category_id) {
                $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                    type: "GET",
                    data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
                    success: function (data) {
                        $('#emp_loader').html('');
                        $('select[name="emr_no"]').empty();
                        $('select[name="emr_no"]').html(data);
                    }
                });
            } else {
                $('select[name="emr_no"]').empty();
            }
        }
    }

    function getEmployee(){
        var department = $("#department_id").val();
        var sub_department = $("#sub_department_id").val();

        if(department == '0'){
            $("#department_id_").val('0');
            $("#sub_department").val('0');
            $('select[name="emp_id"]').empty();
            $("#emp_id").prepend("<option value='0'>-</option>");
            return false;
        }
        if(department != '0' && sub_department == ''){
            data = {department:department,sub_department:'0',m:m}
        }
        else if(department != '' && sub_department != ''){
            data = {department:department,sub_department:sub_department,m:m}
        }
        if(department != ''){
            $('#emp_loader_1').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                type:'GET',
                url:baseUrl+'/slal/getSubDepartment',
                data:data,
                success:function(res){
                    $('#emp_loader_1').html('');
                    $('select[name="emp_id"]').empty();
                    $('select[name="emp_id"]').html(res);

                }
            })
        }
        else{
            $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
            $('select[name="sub_department_id_1"]').empty();
            $('select[name="emp_id"]').empty();
        }
    }

</script>

