<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
<div class="row">
    <div class="col-12">
        {{ Form::open(array('url' => 'had/addManualyAttendances')) }}
        <input type="hidden" name="m" id="m" value="{{ $m }}" />
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control requiredField">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>To Date</label>
                                <input type="date" name="to_date" id="to_date" class="requiredField form-control">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Remarks:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="remarks" id="remarks" value="-" class="requiredField form-control" required />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <button id="btn_search" type="button" class="btn_search btn btn-sm btn-primary" onclick="viewEmployeeManualAttendance()" style="margin-top: 32px;" ><i class="fa fa-search"></i>  Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="attendanceSection"></div>
        {{ Form::close() }}
    </div>
</div>
<script>

    $('#department_id').select2();
    $('#sub_department_id').select2();
    $('#project_id').select2();
    $('#employee_id').select2();
    $('#category_id').select2();
    $('#location_id').select2();

    function viewEmployeeManualAttendance(){

        var department_id = $('#department_id').val();
        var sub_department_id = $('#sub_department_id').val();
        var project_id = $('#project_id').val();
        var category_id = $('#category_id').val();
        var month_year = $('#month_year').val();
        var employee_id = $('#employee_id').val();
        var filter_value = $('#filter_value').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var m = '{{ $m }}';

        jqueryValidationCustom();
        if(validate == 0){
            $('#attendanceSection').html('<div class="loader"></div>');
            $.ajax({
                url: '{{ url('/') }}/hdc/viewEmployeeManualAttendance',
                type: "GET",
                data: {category_id:category_id,project_id:project_id,department_id:department_id,sub_department_id:sub_department_id,month_year:month_year,m:m,employee_id:employee_id,filter_value:filter_value,from_date:from_date,to_date:to_date},
                success:function(data) {
                    $('#attendanceSection').html(data);
                },
                error: function(error) {
                    $('#attendance-area').html('');
                    swalError();
                }
            });
        }else{
            return false;
        }
    }

</script>