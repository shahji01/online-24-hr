<?php
$m = Input::get('m');
$counter = 1;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }

</style>
<link rel="stylesheet" href="{{ URL::asset('assets/dist/css/select2.min.css') }}">
<script src="{{ URL::asset('assets/dist/js/select2.min.js') }}"></script>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-sm mb-0 table-bordered table-striped">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">Designation</th>
                                    <th class="text-center">Manager <br>
                                        <select style="width: 100%;" class="form-control" name="reporting_manager_id_main" id="reporting_manager_id_main" onchange="applyToAll('manager', this.value)">
                                            <option value="">Select Reporting Manager</option>
                                            @foreach($reporting_to as $key => $val)
                                                <option value="{{ $val->id }}">{{ $val->emp_id.' -- '.$val->emp_name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <input id="checkbox" type="checkbox" name="checkbox" value="">
                                    </th>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $key => $value)
                                        <?php if($value->promoted_designation != ''): $designation_id = $value->promoted_designation; else: $designation_id = $value->designation_id; endif; ?>
                                        <tr>
                                            <td class="text-center">{{ $counter++ }}
                                                <input type="hidden" name="employee_id[]" value="{{ $value->id }}" />
                                            </td>
                                            <td class="text-center">{{ $value->emp_id }}</td>
                                            <td>{{ $value->emp_name }}</td>
                                            <td>@if(array_key_exists($designation_id, $designations)){{ $designations[$designation_id]->designation_name }} @endif</td>
                                            <td>
                                                <select style="width: 100%;" class="form-control reporting_manager_id" name="reporting_manager_id[]" id="reporting_manager_id_{{ $value->id }}">
                                                    <option value="">Select Reporting Manager</option>
                                                    @foreach($reporting_to as $key => $val)
                                                        <option @if($value->reporting_manager_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->emp_id.' -- '.$val->emp_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input class="check_list" onchange="checkListChange('{{$value->id}}')" id="check_list_{{ $value->id }}" type="checkbox" name="check_list[]" value="0">
                                                <input type="hidden" class="check_input" name="check_input[]" id="check_input_{{ $value->id }}" value="0" />
                                            </td>
                                        </tr>
                                        <script>

                                            $("#reporting_manager_id_"+'{{$value->id}}').select2();
                                        </script>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                                <button class="btn btn-sm btn-success" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(function(){

        $("#reporting_manager_id_main").select2();

        $("#checkbox").click(function(){
            if($("#checkbox").prop("checked") == true) {
                $(".check_list").prop("checked",true);
                $(".check_input").val('1');
            }
            else {
                $(".check_list").prop("checked",false);
                $(".check_input").val('0');
            }
        });
    });

    function checkListChange(emp_id){
        if($("#check_list_"+emp_id).prop("checked") == true)
        {
            $("#check_input_"+emp_id).val('1');
        }else{
            $("#check_input_"+emp_id).val('0');
        }
    }

    function applyToAll(type, value) {
        $('.reporting_manager_id').val(value).change();
    }
</script>