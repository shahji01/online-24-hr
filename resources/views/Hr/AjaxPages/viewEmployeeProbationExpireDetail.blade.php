<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>

<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp ID</th>
                    <th class="text-center">Emp Name</th>
                    <th class="text-center">Joining Date</th>
                    <th class="text-center">Expire Date</th>
                    <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if(!empty($employeesProbationExpires))
                        @foreach($employeesProbationExpires as $value)
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value->emp_id}}</td>
                                <td class="text-center">{{$value->emp_name}}</td>
                                <td class="text-center" style="color:green;font-weight: bold;">{{HrHelper::date_format($value->emp_joining_date)}}</td>
                                <td class="text-center" style="color:green;font-weight: bold;">{{HrHelper::date_format($value->probation_expire_date)}}</td>
                                <td class="text-center"><a class="btn btn-sm btn-info" target="_blank" href="<?= url("/hr/editEmployeeDetailForm/{$value->id}/{$m}?pageType=viewlist&&parentCode=27&&m={$m}")?>"><i class="fas fa-edit"></i></a></td>
                            </tr>
                        @endforeach
                   @else
                     <tr>
                         <td colspan="6" class="text-center" style="color:red;">No record found !</td>
                     </tr>
                @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
