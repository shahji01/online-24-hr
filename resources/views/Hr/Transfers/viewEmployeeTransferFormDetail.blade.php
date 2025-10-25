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
                                    <th class="text-center">Project <br>
                                        <select style="width: 100%;" class="form-control" name="project_id_main" id="project_id_main" onchange="transferLocationsList(); applyToAll('project', this.value)">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $key => $val)
                                                <option value="{{ $val->id}}">{{ $val->project_code.' -- '.$val->project_name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="text-center">Location <br>
                                        <select style="width: 100%;" class="form-control" name="location_id_main[]" id="location_id_main" multiple aria-multiselectable="true" onchange="applyToAll('location', this.value)">
                                            <option disabled value="">Select Location</option>
                                        </select>
                                        <span id="location_loader_main"></span>
                                    </th>
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
                                                <select style="width: 100%;" class="form-control project_id" name="project_id[]" id="project_id_{{ $value->id }}" onchange="locationsListUsingId('{{ $value->id }}')">
                                                    <option value="">Select Project</option>
                                                    @foreach($projects as $key => $val)
                                                        <option @if($value->project_id == $val->id) selected @endif value="{{ $val->id}}">{{ $val->project_code.' -- '.$val->project_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select style="width: 100%;" class="form-control requiredField location_id" name="location_id_{{ $value->id }}[]" id="location_id_{{ $value->id }}" multiple aria-multiselectable="true">
                                                    <option disabled value="">Select Location</option>
                                                    <option @if(in_array(0, $employee_location_array[$value->id])) selected @endif value="0">All</option>
                                                    @foreach($locations as $key => $val)
                                                        <option @if(in_array($val->id, $employee_location_array[$value->id])) selected @endif  value="{{ $val->id}}">{{ $val->location_code.' -- '.$val->location_name}}</option>
                                                    @endforeach
                                                </select>
                                                <span id="location_loader_{{ $value->id }}"></span>
                                            </td>
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
                                            $("#project_id_"+'{{$value->id}}').select2();
                                            $("#location_id_"+'{{$value->id}}').select2();
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

        $("#project_id_main").select2();
        $("#location_id_main").select2();
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
        if(type == 'project') {
            $('.project_id').val(value).change();
        } else if(type == 'location') {
            var locations = [];
            $("#location_id_main :selected").map(function(i, el) {
                locations.push($(el).val());
            }).get();
            $('.location_id').val(locations).change();
        } else if(type == 'manager') {
            $('.reporting_manager_id').val(value).change();
        }
    }

    function transferLocationsList() {
        var project_id = $("#project_id_main").val();
        $('#location_loader_main').html('<div class="animated yt-loader"></div>');
        $.ajax({
            url: baseUrl + '/slal/locationsListWithAllOption',
            type: "GET",
            data: {project_id: project_id, m: m},
            success: function (res) {
                $('#location_loader_main').html('');
                $('#location_id_main').html(res);
            }
        });
    }

</script>