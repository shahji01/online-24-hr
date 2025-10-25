<?php
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;

$counter = 1;
?>
<style>
    .field_width {width: 120px;}
    .show_data {
        display: none;
    }
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
</style>
<div class="card">
    <div class="card-body">
        {{ Form::open(['url' => 'had/addLeavesEncashmentDetail']) }}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="printList">
                <div class="table-responsive wrapper">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="m" value="{{ $company_id }}">
                    <input type="hidden" name="leave_from" id="leave_from" value="{{ $leave_from }}">
                    <input type="hidden" name="leave_to" id="leave_to" value="{{ $leave_to }}">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                        <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Salary</th>
                            <th class="text-center">Used Annual</th>
                            <th class="text-center">Used Sick</th>
                            <th class="text-center">Used Casual</th>
                            <th class="text-center">Total Remaining Leaves</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">
                                <input id="checkbox" type="checkbox" name="checkbox" value="">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($taken_leaves as $key => $val)
                            <?php
                            if(array_key_exists($val->id, $leave_encashment_data)):
                                $total_remaining_leaves = $leave_encashment_data[$val->id]['total_remaining_leaves'];
                                $amount = $leave_encashment_data[$val->id]['amount'];
                                $status = 'Submitted';
                            else:
                                $total_remaining_leaves = ($total_annual_leaves + $total_sick_leaves + $total_casual_leaves) - $val->total_used_leaves;
                                $amount = round(($val->salary / 30) * $total_remaining_leaves);
                                $status = '';
                            endif;
                            ?>
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td class="text-center">{{ $val->emp_id }}</td>
                                <td>{{ $val->emp_name }}</td>
                                <td>@if(array_key_exists($val->department_id, $departments)){{ $departments[$val->department_id]->department_name }} @endif</td>
                                <td class="text-right">{{ number_format($val->salary) }}</td>
                                <td class="text-center">{{ $val->taken_annual_leaves }}</td>
                                <td class="text-center">{{ $val->taken_sick_leaves }}</td>
                                <td class="text-center">{{ $val->taken_casual_leaves }}</td>
                                <td class="text-center"><p class="show_data total_remaining_leaves_{{ $val->id }}">{{ $total_remaining_leaves }}</p>
                                    <input type="number" class="form-control" step="any" name="total_remaining_leaves[]" id="total_remaining_leaves_{{ $val->id }}" value="{{ $total_remaining_leaves }}" onkeyup="calculateLeaveEncashAmount('{{ $val->id }}', this.value, '{{ $val->salary }}')">
                                    <input type="hidden" name="employee_id[]" id="employee_id_{{ $val->id }}" value="{{ $val->id }}">
                                </td>
                                <td class="text-center"><p class="show_data amount_{{ $val->id }}">{{ $amount }}</p>
                                    <input type="number" name="amount[]" id="amount_{{ $val->id }}" class="form-control" value="{{ $amount }}">
                                </td>
                                <td class="text-center">{{ $status }}</td>
                                <td class="text-center">
                                    <input class="check_list" onchange="checkListChange('{{$val->id}}')" id="check_list_{{ $val->id }}" type="checkbox" name="check_list[]" value="{{ $val->id }}">
                                    <input type="hidden" class="check_input" name="check_input[]" id="check_input_{{ $val->id }}" value="0" />
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <input type="submit" name="submit" class="btn btn-sm btn-success" />
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
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

    function calculateLeaveEncashAmount(id, remaining_leaves, salary) {
        remaining_leaves = Number(remaining_leaves);
        salary = Number(salary);
        if(remaining_leaves) {
            $('#amount_'+id).val(Math.round((salary / 30) * remaining_leaves));
        } else {
            $('#amount_'+id).val(0);
        }
    }
</script>