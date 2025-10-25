<?php
use App\Helpers\HrHelper;
$attendance_days=[];

if($url=='Half Days Alert'){
    $attendance_days=$attendance_half_days;
}else if($url=='Lates Alert'){
    $attendance_days=$attendance_lates;
}
?>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp Name.</th>
                    <th class="text-center">HalfDay Date</th>
                    <th class="text-center">Day</th>
                    <th class="text-center">Check In</th>
                    @if($url=='Half Days Alert')
                    <th class="text-center">Check Out</th>
                    @endif
                </thead>
                <tbody>
                <?php $counter = 0; ?>
                @if(!empty($attendance_days))
                @foreach($attendance_days as $value)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ ++$counter }}</span></td>
                            <td class="text-center">{{$value['emp_name']}}</td>
                            <td class="text-center"><?php echo HrHelper::date_format($value['attendance_date']);?></td>
                            <td class="text-center">{{$value['day']}}</td>
                            <td class="text-center" style="background-color: #FFC0CB;">{{$value['clock_in']}}</td>
                            @if($url=='Half Days Alert')
                            <td class="text-center">{{$value['clock_out']}}</td>
                            @endif
                            
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