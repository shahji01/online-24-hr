<?php
use App\Helpers\HrHelper;
?>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 class="text-center" style="color:green">
            <b>Half Day Time : <?=date('H:i', $halfdayTime)?></b>
        </h3>
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">HalfDay Date</th>
                    <th class="text-center">Day</th>
                    <th class="text-center">Check In</th>
                    <th class="text-center">Check Out</th>
                    <th class="text-center">Working Hours</th>
                </thead>
                <tbody>
                <?php $counter = 0; ?>
                @foreach($total_halfDay as $value)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ ++$counter }}</span></td>
                            <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                            <td class="text-center">{{$value->day}}</td>
                            <td class="text-center" style="background-color: #FFC0CB;">{{$value->clock_in}}</td>
                            <td class="text-center">{{$value->clock_out}}</td>
                            <td class="text-center"><?php

                                $clock_in = $value->clock_in;

                                if($clock_in != '' && $value->clock_out != ''){
                                    $time1 = strtotime($clock_in);
                                    $time2 = strtotime($value->clock_out);


                                        $diff = abs($time2 - $time1);
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
                                       echo  "<b>".$hours.':'. $mins."</b>";

                                }else{
                                    echo "--";
                                }
                                ?></td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>