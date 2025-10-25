<?php
$accType = Auth::user()->acc_type;
/*if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}*/
//$parentCode = $_GET['parentCode'];
$m = $_GET['m'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>

<style>
    hr{border-top: 1px solid cadetblue}
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}
</style>

<input type="hidden" value="{{ $emr_no }}" id="emr_no" />
<input type="hidden" value="{{ $m }}" id="m" />

<br>



<span id="employee-fuel">
    <div class="panel">
        <div class="panel-body" id="PrintEmployeeList">
    <!--        --><?php //echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered sf-table-list table-hover" id="EmployeeFuelList">
                            <thead>
                            <th class="text-center col-sm-1">S.No</th>
                            <th class="text-center col-sm-1">Date</th>
                            <th class="text-center col-sm-3 ">From</th>
                            <th class="text-center col-sm-3">To</th>
                            <th class="text-center col-sm-1 ">KM</th>
                            <th class="text-center col-sm-1 ">Fuel Amount</th>
                            <th class="text-center col-sm-1">Approval Status</th>
                            <th class="text-center hidden-print col-sm-1">Action</th>
                            </thead>
                            <tbody>
                            <?php if($employeeFuelData->count() > 0):?>
                            <?php $counter = 1;?>
                            @foreach($employeeFuelData->get() as $key => $y)
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center">{{ HrHelper::date_format($y->fuel_date) }}</td>
                                    <td class="text-center">{{ $y->from}}</td>
                                    <td class="text-center">{{ $y->to}}</td>
                                    <td class="text-center">{{ $y->km}}</td>
                                    <td class="text-center">{{ number_format((float)(92.83/14)* $y->km, 2, '.', '') }}</td>
                                    <td class="text-center">{{HrHelper::getApprovalStatusLabel($y->approval_status)}}</td>
                                    <td class="text-center hidden-print">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                <li role="presentation">
                                                    <a  class="delete-modal btn"onclick="showMasterTableEditModel('hr/editEmployeeFuelDetailForm','<?php echo $y->id;?>','Edit Employee Fuel Detail','<?php echo $m; ?>')">
                                                        Edit
                                                    </a>
                                                </li>
                                                @if($y->status == 2)
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id ?>','employee_fuel_data')">
                                                            Repost
                                                        </a>
                                                    </li>
                                                @else
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $y->id ?>','employee_fuel_data')">
                                                            Delete
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="8" style="color:red;font-weight: bold;">Record Not Found !</td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</span>

