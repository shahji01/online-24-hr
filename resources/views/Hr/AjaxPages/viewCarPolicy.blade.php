<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;


$data = '

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="text-decoration: underline;"><h2>'.$carPolicyData->policy_name.'</h2></div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list">
                <thead>
                <th class="text-center col-sm-1">Designation</th>
                <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$carPolicyData->designation_id).'</td>
                </thead>
                <thead>
                <th class="text-center col-sm-1">Vehicle Type & CC</th>
                <td class="text-center col-sm-1">'
                .HrHelper::getMasterTableValueById(Input::get('m'),'vehicle_type','vehicle_type_name',$carPolicyData->vehicle_type_id).'&nbsp;'
                .HrHelper::getMasterTableValueById(Input::get('m'),'vehicle_type','vehicle_type_cc',$carPolicyData->vehicle_type_id).'</td>
                </thead>

            </table>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list">

                <thead>
                <th class="text-center col-sm-1">Start Salary Range</th>
                <td class="text-center col-sm-1">'.$carPolicyData->start_salary_range.'</td>
                </thead>
                <thead>
                <th class="text-center col-sm-1">End Salary Range</th>
                <td class="text-center col-sm-1">'.$carPolicyData->end_salary_range.'</td>
                </thead>

            </table>
        </div>
    </div>
</div>';

echo json_encode(array('data'=>$data));
?>

