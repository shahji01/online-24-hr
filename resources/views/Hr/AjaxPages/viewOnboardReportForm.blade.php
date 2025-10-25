<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><b><u>Onboard Report Form</u></b></h3>
    </div>
</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label class="sf-label pointer">Department</label>
				<span class="rflabelsteric"><strong></strong></span>
				<select class="form-control" name="department_id_" id="department_id" onchange="getEmployee()" >
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
				<select class="form-control " name="department_id_" id="sub_department_id" onchange="getEmployee()">
					<option value="">Select Department</option>
					@foreach($SubDepartment as $key => $y)
						<option value="<?php echo $y->id ?>"> 				{{ $y->sub_department_name}}
						</option>
					@endforeach
				</select>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label class="sf-label">Employee:</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<select class="form-control" name="emp_id" id="emp_id">
					<option value="">-</option>
				</select>
				<div id="emp_loader_1"></div>
			</div>
		</div>
        <br>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12"></div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 text-right">
                
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 text-right">
			<label>Show All
                <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1" style="margin-top: 15px;margin-right:15px;">
                </label>
                <button class="btn btn-sm btn-info" id="search" style="margin-top: 6px;"><i class="fas fa-search fa"></i> Search</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="baseUrl" value="{{ url('/') }}">
<script>
    var baseUrl=$('#baseUrl').val();

    $(document).ready(function(){

        $(".btn-info").click(function(e){
            var degreeType = new Array();
            var val;
            $("input[name='HrReports[]']").each(function(){
                degreeType.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of degreeType) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });
		
		$('#department_id').select2();
	    $('#sub_department_id').select2();
	    $('#emp_id').select2();
	});

    $('#search').click(function() {
        var department_id = $('#department_id').val();
        var sub_department_id = $('#sub_department_id').val();
        var emp_id = $('#emp_id').val();
        var m = $('#m').val();
		var show_all = $('#show_all').val();
		var data = '';
		
		if ($("#show_all").is(":checked")) {
			var data = {show_all: show_all, m:m};
			}			
		else{
			var data = {department_id:department_id,sub_department_id:sub_department_id,emp_id:emp_id,m:m};
			}
		
        $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        
            $.ajax({
                url: baseUrl+"/HrReports/viewOnboardReport",
                type: 'GET',
                data: data, 
                success: function (response){
                    $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#report-area').html(response);

                }
            });
        
    });

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
	
		$("#show_all").change(function(){
			if($('#show_all').is(':checked')){
				$('#department_id_').prop("disabled", true);
				$('#employee_project_id').prop("disabled", true);
				$('#emp_id').prop("disabled", true);
			}
			else{
				$('#department_id_').prop("disabled", false);
				$('#employee_project_id').prop("disabled", false);
				$('#emp_id').prop("disabled", false);
			}
			
		});
</script>