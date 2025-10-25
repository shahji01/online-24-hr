<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
$leave_day_type = [1 => 'Full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
$counter = 1;
$leave_type_name = '';
?>
@if (!empty($totalAbsentDay))
    @foreach ($totalAbsentDay as $value)
    <?php
    $jsonEncodedValue = htmlspecialchars(json_encode($value), ENT_QUOTES, 'UTF-8');
    ?>
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">{{ $value->emp_id }}</td>
            <td>{{ $value->emp_name }}</td>
            <td>@if(array_key_exists($value->department_id, $departments)) {{ $departments[$value->department_id]->department_name }}@endif</td>
            <td class="text-center">{{ HrHelper::date_format($value->from_date) }}</td>
            <td class="text-center">{{ HrHelper::date_format($value->to_date) }}</td>
            <td class="text-center">{{ str_replace('_', ' ', $value->type) }}</td>
            <td class="text-center">{{ $value->total_days }}</td>
            <td class="text-center hidden-print" id="hide-table-row">
                <a style="color: white" class="btn btn-sm btn-primary"
                    onclick="showDetailModelFourParamerter('hr/ApplyLeaveApplicationDetailForm','<?php echo $value->id.'--'.$value->emp_name.'--'.$value->emp_id.'--'.$value->department_id.'--'.
                    $value->working_hours_policy_id.'--'.$value->type.'--'.$value->from_date.'--'.$value->to_date.'--'.$value->total_days ?>','Apply Leave Application Form','{{ $m }}')">
                    Apply leave
                </a>
            </td>    
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="12" class="text-center text-danger">No Record Found !</td>
    </tr>
@endif
