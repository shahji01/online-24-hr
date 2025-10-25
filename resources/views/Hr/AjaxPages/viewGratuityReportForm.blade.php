<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><b><u>Gratuity Report Form</u></b></h3>
    </div>
</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Regions:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="region_id" id="region_id">
                    <option value="">Select Region</option>
                    @foreach($employee_regions as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Category:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="emp_category_id" id="emp_category_id" onchange="empCategory()">
                    <option value="">Select Category</option>
                    @foreach($employee_category as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>Employee Project</label>
                <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()">
                    <option value="0">Select Project</option>
                    @foreach($Employeeprojects as $value)
                        <option value="{{$value->id}}">{{$value->project_name}}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Employee:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="emr_no" id="emr_no" required></select>
                <div id="emp_loader"></div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">From Date</label>
                <input type="date" class="form-control requiredField " id="from_date" name="from_date" required>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">To Date</label>
                <input type="date" class="form-control requiredField " id="to_date" name="to_date" required>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12"></div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 text-right">
                <label>Show All
                    <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1" style="margin-top: -2px;">
                </label>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
                <button class="btn btn-info" id="search">Search</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){

        $('.checkbox').change(function () {
            if ($(this).is(':checked')) {
                $("#employee_project_id").attr('disabled', true);
                $('#region_id').attr('disabled', true);
                $('#emp_category_id').attr('disabled', true);
                $("#emr_no").attr('disabled', true);
                $('#from_date').attr('disabled', true);
                $('#to_date').attr('disabled', true);
                $('#region_id').removeClass('requiredField').removeAttr('required');
                $('#emp_category_id').removeClass('requiredField').removeAttr('required');
                $('#employee_project_id').removeClass('requiredField').removeAttr('required');
                $('#emr_no').removeClass('requiredField').removeAttr('required');
                $('#from_date').removeClass('requiredField').removeAttr('required');
                $('#to_date').removeClass('requiredField').removeAttr('required');
            } else {
                $("#employee_project_id").attr('disabled', false);
                $('#region_id').attr('disabled', false);
                $('#emp_category_id').attr('disabled', false);
                $('#emr_no').attr('disabled', false);
                $('#from_date').attr('disabled', false);
                $('#to_date').attr('disabled', false);

                $('#region_id').addClass('requiredField').attr('required');
                $('#emp_category_id').addClass('requiredField').attr('required');
                $('#emr_no').addClass('requiredField').attr('required');
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
            var data = '';
            var employee_category_id = $('#emp_category_id').val();
            var emr_no = $('#emr_no').val();
            var employee_project_id = $('#employee_project_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var region_id = $('#region_id').val();
            var show_all = $("input[name='show_all']:checked"). val();
            $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            if(validate == 0)
            {
                if(show_all == '1'){
                    data = {m : m,show_all:show_all,employee_project_id:'0'};
                }
                else{
                    data = {m : m, region_id:region_id, employee_category_id:employee_category_id, show_all:show_all,employee_project_id:employee_project_id,from_date:from_date,to_date:to_date,emr_no:emr_no};
                }

                $.ajax({
                    url: "/HrReports/viewGratuityReport",
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

        $('#region_id').select2();
        $('#emp_category_id').select2();

    });

    function employeeProject() {
        var emp_category_id = $("#emp_category_id").val();
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
        var emp_category_id = $("#emp_category_id").val();
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


</script>