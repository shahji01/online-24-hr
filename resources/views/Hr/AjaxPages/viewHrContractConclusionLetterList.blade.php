<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
//$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>


<div class="lineHeight">&nbsp;</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="EmployIdCardRequestList">
                        @if($hr_contract_conclusion_letter->count() > 0)
                            <thead>
                            <th class="text-center col-sm-1">S.No</th>
                            <th class="text-center">EMR No.</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Note</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Action</th>

                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($hr_contract_conclusion_letter->get() as  $row)

                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $row->emr_no }}</td>
                                    <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $row->emr_no, 'emr_no') }} </td>
                                    <td class="text-center">{{ $row->note }}</td>
                                    <td class="text-center">{{HrHelper::date_format($row->date) }}</td>

                                    <td class="text-center hidden-print">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                @if(in_array('view', $operation_rights2))
                                                    <li role="presentation">
                                                        <a class="delete-modal btn"  href="<?= url("/hdc/viewHrContractConclusionLetter/{$row->id}/{$m}?pageType=viewlist&&parentCode=27&&m={$m}")?>" target="_blank">
                                                            View
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(in_array('delete', $operation_rights2))
                                                    <li role="presentation">
                                                        <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $row->id ?>','hr_warning_letter')">
                                                            Delete
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="14">Record Not Found !</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
