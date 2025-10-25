<?php


use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
?>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><b><u>Medical Report Form</u></b></h3>
    </div>
</div>
<hr>
<div class="panel">
    <div class="panel-body">
        <div class="row">
             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			   <label class="sf-label pointer">Department</label>
				<span class="rflabelsteric"><strong>*</strong></span>
				<select class="form-control" name="sub_department_id_" id="sub_department_id_" onchange="getEmployee()">
					<option value="">Select Department</option>
					@foreach($SubDepartment as $key => $y)
						<option value="<?php echo $y->id ?>"> 				{{ $y->sub_department_name}}
						</option>
					@endforeach
				</select>
			</div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Employee Project</label>
                <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="getEmployee()">
                    <option value="0">Select Project</option>
                    @foreach($Employeeprojects as $value)
                        <option value="{{$value->id}}">{{$value->project_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Employee:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="emp_id" id="emp_id" required>
                </select>
                <div id="emp_loader"></div>
            </div>
           
        </div>
        <br>
        <div class="row">
			 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">From Date</label>
                <input type="date" class="form-control requiredField" id="from_date" name="from_date`" required>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">To Date</label>
                <input type="date" class="form-control requiredField" id="to_date" name="to_date" required>
             </div>
            <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12 text-right" style="margin-top:10px;">
                <br>
            <label>Show All
                    <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1">
            </label>
            <button class="btn btn-sm btn-info" id="search"><i class="fas fa-search fa"></i> Search</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="baseUrl" value="{{ url('/') }}">
<script>
    var baseUrl=$('#baseUrl').val();

    $(document).ready(function(){
        $('.checkbox').change(function () {
            if ($(this).is(':checked')) {
                $('#emp_id').attr('disabled', true);
                $('#sub_department_id_1').attr('disabled', true);
                $("#employee_project_id").attr('disabled', true);
                $("#from_date").attr('disabled', true);
                $("#to_date").attr('disabled', true);


                $('#emp_id').removeClass('requiredField').removeAttr('required');
                $('#sub_department_id_1').removeClass('requiredField').removeAttr('required');
                $('#from_date').removeClass('requiredField').removeAttr('required');
                $('#to_date').removeClass('requiredField').removeAttr('required');
            } else {
                $('#emp_id').attr('disabled', false);
                $("#sub_department_id_1").attr('disabled', false);
                $("#employee_project_id").attr('disabled', false);
                $("#from_date").attr('disabled', false);
                $("#to_date").attr('disabled', false);

                $('#emp_id').addClass('requiredField').attr('required');
                $('#sub_department_id_1').addClass('requiredField').attr('required');
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
            for (val of degreeType) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });


        $('#search').click(function() {
            var emp_id = $('#emp_id').val();
			var sub_department_id_1 = $('#sub_department_id_1').val();
            var show_all = $("input[name='show_all']:checked"). val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var employee_project_id = $("#employee_project_id").val();
            $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            var data = '';
            if(show_all == '1'){
                data = {m : m,show_all:show_all,employee_project_id:'0'};
            }
            else{
                data = {m : m, emp_id:emp_id,sub_department_id_1:sub_department_id_1,from_date:from_date,to_date:to_date,employee_project_id:employee_project_id};
            }
            jqueryValidationCustom();
            if(validate == 0)
            {
                $.ajax({
                    url: baseUrl+"/HrReports/viewMedicalReport",
                    type: 'GET',
                    data: data,
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

        $('#emp_id').select2();
        $('#sub_department_id_').select2();
        $('#employee_project_id').select2();
    });


	function getEmployee(){
			var m = '<?php echo $m ?>';
			var department = $("#sub_department_id_").val();
			var employee_project_id = $("#employee_project_id").val();
			
			if(department == '' && employee_project_id != ''){
				alert('Please Select Department');
			}
			if(department != '' && employee_project_id == ''){
				data = {department:department,employee_project_id:'0',m:m}
			}
			else if(department != '' && employee_project_id != ''){
				data = {department:department,employee_project_id:employee_project_id,m:m}
			}
			if(department != ''){
				$('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
				$.ajax({
					type:'GET',
					url:'<?php echo url('/') ?>/slal/getSubDepartment',
				    data:data,
					success:function(res){
						$('#emp_loader').html('');
						$('select[name="emp_id"]').empty();
						$('select[name="emp_id"]').html(res);
						$("#emp_id").prepend("<option value='' selected='selected'>Select Employee</option>");
					}			
				})
			}
			else{
				
				$("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
				$('select[name="sub_department_id_1"]').empty();
				$('select[name="emr_no"]').empty();
			}
		}

</script>

