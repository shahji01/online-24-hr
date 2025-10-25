<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeeHrAudit;
$date1 = date('6-Y');
$date2 = date('12-Y');
$month_and_year1 = explode('-',$date1);
$month_and_year2 = explode('-',$date2);

?>
<div class="row text-center"><h3><b>Hr Employee Audit Detail</b></h3></div>
<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list table-hover" id="OvertimeDetailList">
                    <thead>
                        <th class="text-center">S No.</th>
                        <th class="text-center">Emp ID#</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Audit (<?=$date1?>)</th>
                        <th class="text-center">Audit (<?=$date2?>)</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if(!empty($employeeAuditDetail))
                        @foreach($employeeAuditDetail as $key => $value)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $countsixthMonth = EmployeeHrAudit::where([['emp_id','=',$value['emp_id']],['month','=',$month_and_year1[0]],['year','=',$month_and_year1[1]]])->count();
                            $countTwelfthMonth = EmployeeHrAudit::where([['emp_id','=',$value['emp_id']],['month','=',$month_and_year2[0]],['year','=',$month_and_year2[1]]])->count();
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value['emp_id']}}</td>
                                <td class="text-center">{{$value['emp_name']}}</td>
                                <td class="text-center" id="sixthHrAudit<?=$value['emp_id']?>" @if($countsixthMonth > 0) style="background-color: #CFE7CF;" @endif>
                                    <input @if($countsixthMonth > 0) checked @endif type="checkbox" id="insertEmployeeSixthMonthAuditDetail<?=$value['emp_id']?>" onclick="addEmployeeSixthMonthAuditDetail('<?=Input::get('m')?>','<?=$value['emp_id']?>','<?=$date1?>')">
                                </td>
                                <td class="text-center" id="twelfthHrAudit<?=$value['emp_id']?>" @if($countTwelfthMonth > 0) style="background-color: #CFE7CF;" @endif>
                                    <input @if($countTwelfthMonth > 0) checked @endif type="checkbox" id="insertEmployeeTwelfthMonthAuditDetail<?=$value['emp_id']?>" onclick="addEmployeeTwelfthMonthAuditDetail('<?=Input::get('m')?>','<?=$value['emp_id']?>','<?=$date2?>')">
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4" style="color:red;"><b>Record Not Found !</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
