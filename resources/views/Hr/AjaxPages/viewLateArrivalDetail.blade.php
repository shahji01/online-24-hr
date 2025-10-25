<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

<style>
    hr{border-top: 1px solid cadetblue}

    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="well">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="text-center" style="color:green">
                                <b>Grace Time : <?=$graceTime2?></b>
                            </h3>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 table-bordered table-striped" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Late Arrival Date</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Check In</th>
                                    <th class="text-center">Check Out</th>
                                    <th class="text-center">Late</th>
                                 
                                   <?php
                                    //<th class="text-center">Action</th>
                                    ?>
                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;
                                    $totalLateHoursCount=0;
                                    $countTotalate=0;
                                    ?>

                                    @foreach($lateArrivalData as $value)
                                        <?php

                                        $clock_in = $value->clock_in;

                                        if($clock_in != ''){
                                            $time1 = strtotime($clock_in);
                                            $grace = strtotime($graceTime2);

                                            if($time1 > $grace):
                                                $diff = abs($time1 - $grace);
                                                //$diff2 += abs($time1 - $GraceTime);
                                                $tmins = $diff/60;

                                                $hours = floor($tmins/60);

                                                $mins = $tmins%60;

                                                $totalHoursWorked[] = $hours.":".$mins;
                                                $totalLateHoursCount = $hours;

                                                if($mins > 15):
                                                    $totalLateHoursCount +=1;
                                                endif;
                                                // $totalLateHoursCount += $hours;
                                                // $totalLateMintsCount += $mins;
                                                "<b>".$hours.':'. $mins."</b>";
                                            endif;
                                        }
                                        ?>
                                        
                                            <tr>
                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                                <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                                                <td class="text-center">{{$value->day}}</td>
                                                <td class="text-center" style="background-color: #FFC0CB;">{{$value->clock_in}}</td>
                                                <td class="text-center">{{$value->clock_out}}</td>
                                                <td class="text-center"><?php echo "<b>".$hours.':'. $mins."</b>"; ?></td>
                                            
                                            </tr>
                                       
                                    @endforeach
                                   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function NeglectLateArrival(attendance_id,date)
    {
        $('#NeglectArea'+attendance_id).html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        if(confirm("Do you want to Neglect Late Arrival of "+date+" Date ? "))
        {
            $.ajax({
                url: '<?php echo url('/')?>/hedbac/NeglectEmployeeAttendance',
                type: "GET",
                data: {attendance_id:attendance_id,m:'<?=Input::get('m')?>'},
                success:function(data) {
                    viewAttendanceProgress();
                    $('#removeBtn'+attendance_id).remove();
                    $('#NeglectArea'+attendance_id).html('');
                    $('#NeglectArea'+attendance_id).html('Neglected');


                }
            });
        }
        else
        {
            $('#NeglectArea'+attendance_id).html('');
        }

    }
</script>