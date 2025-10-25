<?php
$m = Input::get('m');
$counter = 1;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

<div class="card">
    <div class="card-body">
        {{ Form::open(['url' => 'had/']) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper" id="printList">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                    <thead>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Emp ID</th>
                    <th class="text-center">Employee Name</th>
                    <th class="text-center">Joining Date</th>
                    <th class="text-center">Tenure</th>
                    <th class="text-center">Monthly Salary</th>
                    <th class="text-center">Per Day Salary</th>
                    <th class="text-center">Per Year Gratuity</th>
                    <th class="text-center">Total Gratuity</th>
                    <th class="text-center">
                        <input id="checkbox" type="checkbox" name="checkbox" value="">
                    </th>
                    </thead>
                    <tbody>
                    @foreach($employees_gratuity as $key => $value)
                        <?php
                        $gratuity = 0;
                        $per_year_gratuity = 0;
                        $years = $value->years;
                        $months = $value->months;
                        $salary = round($value->salary);
                        $per_day_salary = $salary / 30;
                        if($years >= 1 && $months >= 6):
                            $years += 1;
                        endif;
                        if($years >= 1):
                            $per_year_gratuity = $per_day_salary * 20;
                            $gratuity = $years * $per_year_gratuity;
                        endif;

                        ?>
                        <tr>
                            <td class="text-center">{{ $counter++ }}
                                <input type="hidden" name="employee_id[]" value="{{ $value->id }}" />
                            </td>
                            <td class="text-center">{{ $value->emp_id }}</td>
                            <td>{{ $value->emp_name }}</td>
                            <td class="text-center">{{ HrHelper::date_format($value->joining_date) }}</td>
                            <td class="text-center">{{ $value->years.' Years - '.$value->months.' Months' }}</td>
                            <td class="text-right">{{ number_format($salary) }}</td>
                            <td class="text-right">{{ number_format($per_day_salary) }}</td>
                            <td class="text-right">{{ number_format($per_year_gratuity) }}</td>
                            <td class="text-right">{{ number_format($gratuity) }}</td>
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
        <!--<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                <button class="btn btn-sm btn-success" type="submit">Submit</button>
            </div>
        </div>-->
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

</script>