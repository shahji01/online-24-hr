<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-sm mb-0 table-bordered table-hover" id="exportList">
                    <thead>
                    <tr>
                        <th class="text-center">Leaves Policy Name</th>
                        <th class="text-center">Policy Month - Year From</th>
                        <th class="text-center">Policy Month - Year Till</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center">{{ $leaves_policy[$leaves_policy_id]->leaves_policy_name }}</td>
                        <td class="text-center">{{ date('d-F', strtotime($leaves_policy[$leaves_policy_id]->policy_date_from)) }}</td>
                        <td class="text-center">{{ date('d-F', strtotime($leaves_policy[$leaves_policy_id]->policy_date_till)) }}</td>
                    </tr>
                    </tbody>
                    <thead>
                    <tr>
                        <th class="text-center">Full Day Deduction Rate</th>
                        <th class="text-center">Half Day Deduction Rate</th>
                        <th class="text-center">Per Hour Deduction Rate</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center">1 (Day)</td>
                        <td class="text-center">0.5 (Day)</td>
                        <td class="text-center">0.25 (Day)</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                    <thead>
                    <th class="text-center">Leaves Type</th>
                    <th class="text-center">No. of Leaves</th>
                    </thead>
                    <tbody>
                    @foreach($leaves_data as $value)
                        @if($value->leaves_policy_id == $leaves_policy_id)
                            <tr>
                                <td class="text-center">@if(array_key_exists($value['leave_type_id'],$leave_type)) {{ $leave_type[$value['leave_type_id']]->leave_type_name }} @endif</td>
                                <td class="text-center">{{ $value['no_of_leaves'] }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>