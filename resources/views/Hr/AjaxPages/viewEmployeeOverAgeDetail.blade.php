<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="row text-center"><h3><b>View Employee Over Age Detail</b></h3></div>
<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list table-hover" id="OvertimeDetailList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emr No#</th>
                    <th class="text-center">Emp Name</th>
                    <th class="text-center">DOB</th>
                    <th class="text-center">Age</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if(!empty($over_age_employee_detail))
                    @foreach($over_age_employee_detail as $value)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                            <td class="text-center">{{$value->emp_id}}</td>
                            <td class="text-center">
                                <a target="_blank" class="delete-modal btn" href="<?=url('')?>/hr/editEmployeeDetailForm/{{$value->id}}/{{Input::get('m')}}?m={{Input::get('m')}}">
                                    {{$value->emp_name}}
                                </a>
                            </td>
                            <td class="text-center" style="color:green;font-weight: bold;">{{HrHelper::date_format($value->emp_date_of_birth)}}</td>
                            <td class="text-center" style="color:green;font-weight: bold;">
                                <?php
                                $today = date("Y-m-d");
                                $diff = date_diff(date_create(HrHelper::date_format($value->emp_date_of_birth)), date_create($today));
                                echo $diff->format('%y');

                                ?>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center" style="color:red;"><b>Record Not Found !</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
