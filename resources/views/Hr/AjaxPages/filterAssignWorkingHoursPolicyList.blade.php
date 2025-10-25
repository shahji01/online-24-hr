<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$counter = 1;
$data1 ='';

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-sm mb-0 table-bordered table-striped">
                    <thead>
                    <th class="text-center">S.No</th>
                    <th class="text-center">EMP ID</th>
                    <th class="text-center">Emp Name</th>
                    <th class="text-center">W-H Policy</th>
                    <th class="text-center">From Date</th>
                    <th class="text-center">To Date</th>
                    <th class="text-center">Fixed</th>
                    <th class="text-center">Check All <br>
                        <input id="checkbox" type="checkbox" name="checkbox" value="">
                    </th>
                    </thead>
                    <tbody>
                    @foreach($policies->get() as $key => $value)
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td class="text-center">{{ $value->emp_id }}</td>
                            <td>{{ $value->emp_name }}</td>
                            <td class="text-center">
                                <select class="form-control requiredField" name="working_hours_policy[]" id="working_hours_policy">
                                    <option value="">Select Policy</option>
                                    @foreach($workingHoursPolicy as $key => $y)
                                        <option @if($y->id == $value->working_hours_policy_id) selected @endif value="{{ $y->id }}">{{ $y->working_hours_policy}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <input type='date' name="from_date[]" class="form-control from_date_{{$value->id}}" @if($value->fixed!=1) value="{{ $value->from_date }}" @else readonly @endif />
                            </td>
                            <td class="text-center">
                                <input type='date' name="to_date[]" class="form-control to_date_{{$value->id}}" @if($value->fixed!=1) value="{{ $value->to_date }}" @else readonly @endif />
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="id[]" value="{{ $value->id }}" />
                                <input type="hidden" name="employee_id[]" value="{{ $value->employee_id }}" />
                                <input type="hidden" name="emp_id[]" value="{{ $value->emp_id }}" />
                                <input @if($value->fixed==1) checked @endif onchange="fixedChange('{{ $value->id }}')" class="fixed_{{$value->id}}" type="checkbox" name="fixed[]" id="fixed_{{$value->id}}" value="0">
                                <input type="hidden" class="fixed_input" name="fixed_input[]" id="fixed_input_{{ $value->id }}" @if($value->fixed==1) value="1" @else value="0" @endif />
                            </td>
                            <td class="text-center">
                                <input class="check_list" onchange="checkListChange('{{$value->id}}')" id="check_list_{{ $value->id }}" type="checkbox" name="check_list[]" value="0">
                                <input type="hidden" class="check_input" name="check_input[]" id="check_input_{{ $value->id }}" value="0" />
                            </td>
                        </tr>
                    @endforeach
                    @if($policies->count() == 0)
                        <tr>
                            <td colspan="8" class="text-center text-danger" style="font-weight:bold;">No record found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        @if(in_array('edit', $operation_rights2))
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                    <button class="btn btn-sm btn-success" type="submit">Update</button>
                </div>
            </div>
        @endif
    </div>
</div>

<script>

    $(function(){
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

    function fixedChange(emp_id){

        if($("#fixed_"+emp_id).prop("checked") == true)
        {
            $(".from_date_"+emp_id).prop("readonly",true);
            $(".to_date_"+emp_id).prop("readonly",true);
            $("#fixed_input_"+emp_id).val('1');
        }
        else
        {
            $(".from_date_"+emp_id).prop("readonly",false);
            $(".to_date_"+emp_id).prop("readonly",false);
            $("#fixed_input_"+emp_id).val('0');
        }
    }

</script>