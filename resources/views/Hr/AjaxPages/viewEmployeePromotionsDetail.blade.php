<?php
//$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
//$parentCode = $_GET['parentCode'];
$m = $_GET['company_id'];
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
?>
<style>
    hr{border-top: 1px solid cadetblue}
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="panel">

    <div class="panel-body" id="PrintLeaveApplicationRequestList">
        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive LeavesData">
                    <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                        <thead>
                            <th class="text-center">S No</th>
                            <th class="text-center">Emr No</th>
                            <th class="text-center">Emp Name</th>
                            <th class="text-center">Dept / Subdept</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Increment</th>
                            <th class="text-center">Salary</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Approval Status</th>
                            <th class="text-center">Status</th>
                            <th class="text-center hidden-print">Action</th>
                        </thead>
                        <tbody>
                        <?php if($employeePromotions->count() > 0):?>
                        <?php $counter = 1;?>
                        @foreach($employeePromotions->get() as $value)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                            $employeeData = App\Models\Employee::where('emr_no','=',$value->emr_no);
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                <td class="text-center">{{$value->emr_no}}</td>
                                <td class="text-center">{{HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emr_no,'emr_no')}}</td>
                                <td class="text-center">{{HrHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$employeeData->value('emp_sub_department_id'))}}</td>
                                <td class="text-center">{{HrHelper::getMasterTableValueById($m,'designation','designation_name',$value->designation_id)}}</td>
                                <td class="text-center">{{HrHelper::getMasterTableValueById($m,'grades','employee_grade_type',$value->grade_id)}}</td>
                                <td class="text-right">{{ number_format($value->increment,0) }}</td>
                                <td class="text-right">{{ number_format($value->salary,0) }}</td>
                                <td class="text-center">{{HrHelper::date_format($value->promotion_date)}}</td>
                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                            @if(in_array('view', $operation_rights))
                                                <li role="presentation">
                                                    <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewPromotionLetter','<?php echo $value->id ?>','View Promotion Letter','<?php echo $m ?>')">
                                                        View Letter
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array('approve', $operation_rights2))
                                                @if($value->approval_status != '2')
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="approveAndRejectTableRecord('{{ $m }}','{{ $value->id }}','2','employee_promotion')">
                                                        Approve
                                                    </a>
                                                </li>
                                                @endif
                                            @endif
                                            @if(in_array('reject', $operation_rights2))
                                                @if($value->approval_status != '3')
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="approveAndRejectTableRecord('{{ $m }}','{{ $value->id }}','3','employee_promotion')">
                                                        Reject
                                                    </a>
                                                </li>
                                                @endif
                                            @endif
                                            @if(in_array('edit', $operation_rights2))
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeePromotionDetailForm','<?= $value->id ?>','View Employee Promotions Detail','<?php echo $m; ?>')">
                                                        Edit
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array('delete', $operation_rights2))
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m; ?>','<?=$value->id?>', 'employee_promotion')">
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
                            <td class="text-center" colspan="12" style="color:red;font-weight: bold;">Record Not Found !</td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>