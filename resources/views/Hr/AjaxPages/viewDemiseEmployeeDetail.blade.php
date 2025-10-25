<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="row text-center">
    <h3><b>Demise Employees Detail</b></h3>
    <span style="color:red;">Review Insurance ,Documents Etc.</span>
</div>
<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list table-hover" id="OvertimeDetailList">
                    <thead>
                        <th class="text-center">S No.</th>
                        <th class="text-center">Emr No#</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Review</th>

                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if(!empty($DemiseEmployee))
                        @foreach($DemiseEmployee as $key => $value)
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value['emp_id']}}</td>
                                <td class="text-center">{{HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value['emp_id'],'emp_id')}}</td>
                                <td class="text-center demiseEmployee<?=$value['emp_id']?>" @if($value['hr_review'] == 'yes') style="background-color: #CFE7CF;" @endif>
                                    <input @if($value['hr_review'] == 'yes') checked @endif onclick="updateDemiseEmployeeReview('<?=Input::get('m')?>','<?=$value['emp_id']?>')" id="DemiseEmployeeReview<?=$value['emp_id']?>" type="checkbox" value="{{$value['emp_id']}}">
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
