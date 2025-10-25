<?php
//$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
//$parentCode = $_GET['parentCode'];
$m = $_GET['m'];
use App\Models\Employee;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
?>
<style>
    hr{border-top: 1px solid cadetblue}
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}
</style>

    <div class="panel-body" id="PrintLeaveApplicationRequestList">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive LeavesData">
                    <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                        <thead>
                        <th class="text-center">S No.</th>
                        <th class="text-center">Emr No#</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Dept / Subdept</th>
                        <th class="text-center">Location</th>
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
                        <?php if($employeeLocations->count() > 0):?>
                        <?php $counter = 1;?>
                        @foreach($employeeLocations->get() as $value)
                            <?php
                            $designation = 0;
                            $salary = 0;
                            $grade_id = 0;
                            $increment = 0;
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $employeeData = Employee::where('emr_no','=',$value->emr_no);
                            CommonHelper::reconnectMasterDatabase();
                            if($value->promotion_id != 0):
                                $designation  = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','designation_id',$value->promotion_id,'id');
                                $salary  = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','salary',$value->promotion_id,'id');
                                $increment  = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','increment',$value->promotion_id,'id');
                                $grade_id = HrHelper::getCompanyTableValueByIdAndColumn($m,'employee_promotion','grade_id',$value->promotion_id,'id');
                                endif;
                            ?>

                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                <td class="text-center">{{$value->emr_no}}</td>
                                <td class="text-center">{{HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emr_no,'emr_no')}}</td>
                                <td class="text-center">{{HrHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$employeeData->value('emp_sub_department_id'))}}</td>
                                <td class="text-center">{{HrHelper::getMasterTableValueById($m,'locations','employee_location',$value->location_id)}}</td>
                                <td class="text-center">
                                    @if($designation != 0)
                                    {{ HrHelper::getMasterTableValueById($m,'designation','designation_name',$designation) }}
                                    @else
                                        --</td>
                                    @endif

                                <td class="text-center">
                                @if($grade_id != 0)
                                    {{ HrHelper::getMasterTableValueById($m,'grades','employee_grade_type',$grade_id) }}
                                @else
                                    --</td>
                                @endif
                                <td class="text-right">
                                    @if($increment != 0)
                                        {{ number_format($increment,0) }}
                                    @else
                                        --</td>
                                @endif
                                <td class="text-right">
                                    @if($salary != 0)
                                        {{ number_format($salary,0) }}
                                    @else
                                        --</td>
                                @endif
                                <td class="text-center">{{HrHelper::date_format($value->date)}}</td>
                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                            @if(in_array('view', $operation_rights))
                                                <li role="presentation">
                                                    <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewTransferLetter','<?php echo $value->id ?>','View Transfer Letter','<?php echo $m ?>')">
                                                        View Letter
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array('approve', $operation_rights2))
                                                @if($value->approval_status != '2')
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="approveAndRejectEmployeeLocationAndPromotion('<?= $m ?>','<?= $value->id ;?>', '2')"> Approve </a>
                                                    </li>
                                                @endif
                                            @endif
                                            @if(in_array('reject', $operation_rights2))
                                                @if($value->approval_status != '3')
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="approveAndRejectEmployeeLocationAndPromotion('<?= $m ?>','<?= $value->id ;?>', '3')"> Reject </a>
                                                    </li>
                                                @endif
                                            @endif
                                            @if(in_array('edit', $operation_rights2))
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeeTransferDetailForm','<?= $value->id ?>','Edit Employee Transfer Detail','<?php echo $m; ?>')">
                                                        Edit
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array('delete', $operation_rights2))
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="deleteEmployeeLocationAndPromotion('<?= $m ?>','<?= $value->id ?>')">
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
                        <tr><td class="text-center" colspan="13" style="color:red;font-weight: bold;">Record Not Found !</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    function deleteEmployeeLocationAndPromotion(companyId,recordId,tableName){
        var companyId;
        var recordId;

        if(confirm("Do you want to delete this record ?") == true){
            $.ajax({
                url: '<?php echo url('/')?>/cdOne/deleteEmployeeLocationAndPromotion',
                type: "GET",
                data: {companyId:companyId,recordId:recordId},
                success:function(data) {
                    console.log(data);
                    $("#searchPromotions").click();
                },
                error: function() {
                    console.log("error");
                }
            });
        }
        else{
            return false;
        }
    }

</script>