<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
    <?php echo CommonHelper::displayPrintButtonInBlade('OvertimeDetailListPrint','','1');?>
    <?php echo CommonHelper::displayExportButton('OvertimeDetailList','','1')?>
</div>
<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list" id="OvertimeDetailList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp Name</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Day</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>

                    @foreach($attData as $value)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                            <td class="text-center">{{$value->emp_name}}</td>
                            <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                            <td class="text-center">{{$value->day}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
