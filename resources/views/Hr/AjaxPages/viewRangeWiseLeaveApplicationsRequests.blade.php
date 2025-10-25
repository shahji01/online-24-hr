<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
$m = Input::get('m');
?>
<table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
    <thead>
    <th class="text-center">S No.</th>
    <th class="text-center">Emp Name</th>
    <th class="text-center">Emp ID</th>
    <th class="text-center">Leave Type</th>
    <th class="text-center">Day Type</th>
    <th class="text-center">Approval Status(HR)</th>
    <th class="text-center">Approval Status(LM)</th>
    <th class="text-center">Status</th>
    <th class="text-center hidden-print">Action</th>

    </thead>
    <tbody>
    <?php $counter = 1;
	
        if(count($leave_application_request_list) != '0'){
    ?>

    @foreach($leave_application_request_list as $value)
        <?php
        CommonHelper::companyDatabaseConnection($value->company_id);
        $emp_name =  Employee::where([['leaves_policy_id','=',$value->leave_policy_id],['emp_id','=',$value->emp_id]]);
        CommonHelper::reconnectMasterDatabase();
        if($emp_name->first() != ''){
        ?>
        <tr>
            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
            <td class="text-center">{{$emp_name->value('emp_name')}}</td>
            <td class="text-center">{{ $emp_name->value('emp_id') }}</td>
            <td class="text-center" style="color:green">
			{{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->leave_type)}}</td>
            <td class="text-center" style="color:green">
                @if($value->name=='')
                {{ $leave_day_type[$value->leave_day_type] }}
                @else
                    {{ "System Generated ".$value->no_of_days." Day Leave" }}
                @endif
            </td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>
            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
            <td class="text-center hidden-print">
                <div class="dropdown">
                    
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                        <i data-feather="chevron-down"
                           class="fas fa-arrow-down"></i></button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        @if($value->name=='')
                        <li role="presentation" class="actionsLink">
                            <a  class="delete-modal btn" onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_id?>','<?=$value->company_id?>')">
                                View
                            </a>
                        </li>
                        <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?=$value->company_id?>')">
                           <a class="delete-modal btn">
                               Edit
                           </a>
                        </li>
                        @endif
                        @if ($value->status == 2)
                            <li role="presentation" class="actionsLink" onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                    <a class="delete-modal btn">
                                        Repost
                                    </a>
                            </li>
                        @else
                            <li role="presentation" class="actionsLink" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                <a class="delete-modal btn">
                                    Delete
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                    <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                </div>
            </td>
        </tr>
        <?php } ?>
    @endforeach
    <?php } else { ?>
        <tr>
            <td colspan="12" class="text-danger text-center">No Record Found</td>
        </tr>
    <?php } ?>
    </tbody>
</table>