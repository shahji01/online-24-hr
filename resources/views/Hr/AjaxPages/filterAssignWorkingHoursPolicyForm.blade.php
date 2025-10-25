<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
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
                    <th class="text-center">Fixed<br>
                        <input id="all_fixed_input" type="checkbox" name="all_fixed_input" value="">
                    </th>
                    <th class="text-center">Check All<br>
                        <input id="checkbox" type="checkbox" name="checkbox" value="">
                    </th>
                    </thead>
                    <tbody>
                    @foreach($all_employees as $key => $value)
                        <tr >
                            <td class="text-center">{{ $counter++ }}</td>
                            <td class="text-center">{{ $value->emp_id }}</td>
                            <td>{{ $value->emp_name }}</td>
                            <td class="text-center">
                                <select class="form-control requiredField" name="working_hours_policy[]" id="working_hours_policy">
                                    <option value="">Select Policy</option>
                                    @foreach($workingHoursPolicy as $key => $y)
                                        <option @if($y->id == $working_hours_policy_id) selected @endif value="{{ $y->id }}">{{ $y->working_hours_policy}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <input type='date' name="from_date[]" class="form-control from_date_{{$value->id}}" value="{{ $from_date }}" />
                            </td>
                            <td class="text-center">
                                <input type='date' name="to_date[]" class="form-control to_date_{{$value->id}}" value="{{ $to_date }}" />
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="employee_id[]" value="{{ $value->id }}" />
                                <input type="hidden" name="emp_id[]" value="{{ $value->emp_id }}" />
                                <input onchange="fixedChange('{{ $value->id }}')" class="fixed_check_list" type="checkbox" name="fixed[]" id="fixed_{{$value->id}}" value="0">
                                <input type="hidden" class="fixed_input" name="fixed_input[]" id="fixed_input_{{ $value->id }}" value="0" />
                            </td>
                            <td class="text-center">
                                <input class="check_list" onchange="checkListChange('{{$value->id}}')" id="check_list_{{ $value->id }}" type="checkbox" name="check_list[]" value="0">
                                <input type="hidden" class="check_input" name="check_input[]" id="check_input_{{ $value->id }}" value="0" />
                            </td>
                        </tr>
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

<script>

    $(function(){

        $("#all_fixed_input").click(function(){

            if($("#all_fixed_input").prop("checked") == true)
            {

                $(".fixed_check_list").prop("checked",true);
                $(".fixed_input").val('1');
                //$('.btn-success').prop('disabled', false);
            }
            else
            {

                $(".fixed_check_list").prop("checked",false);
                $(".fixed_input").val('0');
                //$('.btn-success').prop('disabled', true);
            }


        });


        $("#checkbox").click(function(){

            if($("#checkbox").prop("checked") == true)
            {

                $(".check_list").prop("checked",true);
                $(".check_input").val('1');
                //$('.btn-success').prop('disabled', false);
            }
            else
            {

                $(".check_list").prop("checked",false);
                $(".check_input").val('0');
                //$('.btn-success').prop('disabled', true);
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
            $(".to_date_"+emp_id).prop("readonly",true)

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