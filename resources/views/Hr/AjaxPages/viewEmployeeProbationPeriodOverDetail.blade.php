<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
$counter = 1;
?>
@if(!empty($probationEmployees))
    @foreach($probationEmployees as $value)
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td class="text-center">{{ $value->emp_id }}</td>
            <td class="text-center">{{ $value->emp_name }}</td>
            <td class="text-center">{{ HrHelper::date_format($value->emp_joining_date) }}</td>
            <td class="text-center"><a target="_blank" href="{{ url("/hr/editEmployeeDetailForm/{$value->emp_id}/{$m}?m={$m}") }}">Edit</a></td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center text-danger">Record Not Found !</td>
    </tr>
@endif
