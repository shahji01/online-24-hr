<?php
$m = Input::get('m');
$counter = 1;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">Allowance Type</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">
                                        <input id="checkbox" type="checkbox" name="checkbox" value="">
                                    </th>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ $counter++ }}
                                                <input type="hidden" name="employee_id[]" value="{{ $value->id }}" />
                                            </td>
                                            <td class="text-center">{{ $value->emp_id }}</td>
                                            <td>{{ $value->emp_name }}</td>
                                            <td>
                                                <select style="width: 100%;" name="allowance_type_id[]" id="allowance_type_id_{{ $value->id }}" class="form-control requiredField">
                                                    <option disabled value="">Select Allowance Type</option>
                                                    @foreach($allowance_types as $key => $y)
                                                        <option @if($y->id == $allowance_type) selected @endif value="{{ $y->id }}">{{ $y->allowance_type}}</option>
                                                    @endforeach
                                                </select>
                                                <script>
                                                    $("#allowance_type_id_"+'{{$value->id}}').select2();
                                                </script>
                                            </td>
                                            <td>
                                                <input type="text" name="allowance_amount[]" id="allowance_amount" class="form-control requiredField" value="{{ $allowance_amount }}" />
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
            </div>
        </div>
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

</script>