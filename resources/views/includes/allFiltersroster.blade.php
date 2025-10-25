<?php
use App\Helpers\CommonHelper;
if(isset($_GET['m'])) {
    $m = $_GET['m'];
} else {
    $m = Input::get('company_id');
}
$user_roles = CommonHelper::userRoles($m);
?>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"  id="filterDepartment">
    <label class="sf-label pointer">Department:</label>
    <select class="form-control" name="department_id" id="department_id" onchange="getEmployee('sub_department','')">
        <option value="">Select Option</option>
        @foreach($departments as $key => $y)
            @if(in_array($y->id, $user_roles['department_ids']))
                <option value="{{ $y->id }}">{{ $y->department_name }}</option>
            @endif
        @endforeach
    </select>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <label class="sf-label pointer">Location:</label>
    <select class="form-control" name="location_id" id="location_id" onchange="getEmployee('','')">
        <option value="">Select Option</option>
        @foreach($locations as $key => $y)
            @if(in_array($y->id, $user_roles['location_ids']))
                <option value="{{ $y->id }}">{{ $y->location_code.' -- '.$y->location_name}}</option>
            @endif
        @endforeach
    </select>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <label class="sf-label pointer">Plants:</label>
    <select class="form-control" name="project_id" id="project_id" onchange="getEmployee('','')">
        <option value="">Select Option</option>
        @foreach($projects as $key => $y)
            @if(in_array($y->id, ($user_roles['project_ids'])))
                <option value="{{ $y->id }}">{{ $y->project_code.' -- '.$y->project_name}}</option>
            @endif
        @endforeach
    </select>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <label class="sf-label pointer">Category:</label>
    <select class="form-control" name="category_id" id="category_id" onchange="getEmployee('','')">
        <option value="">Select Option</option>
        @foreach($category as $key => $y)
            @if(in_array($y->id, ($user_roles['category_ids'])))
                <option value="{{ $y->id }}">{{ $y->employee_category_name}}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <label class="sf-label">Employee:</label>
    <span class="rflabelsteric"><strong>*</strong></span>
    <select class="form-control requiredField" name="employee_id" id="employee_ids">
        @foreach($employees as $key => $y)
            <option value="{{ $y->id }}">{{ $y->emp_id.' -- '.$y->emp_name}}</option>
        @endforeach
    </select>
    <div id="emp_loader"></div>
</div>