<?php
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Helpers\CommonHelper;
// echo '<pre>';
// print_r($total_ot_hours_count);
// die;
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
                        <h3 class="text-center" style="text-decoration: underline;">Duty Start Time {{$dutyStartTime}} - Duty End Time {{$dutyEndTime}}</h3>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="text-center">Normal Days</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Late Arrival Date</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Check In</th>
                                    <th class="text-center">Check Out</th>
                                    <th class="text-center" style="background-color: #FFC0CB;">Duration</th>
                                    <th class="text-center" style="background-color: #FFC0CB;">Overtime Hours</th>
                                    <th class="text-center">Count</th>
                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;
                                    $total_ott = 0;
                                    $total_ott1 = 0;


                                    ?>
                                    @foreach($total_ot_hours_count as $value)
                                        @if(!in_array($value->attendance_date,$id_array))
                                            <?php

                                            $minsOT =0;
                                            $hoursOT =0;
                                            $mins2 = 0;
                                            $hours2 = 0;
                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $working_hour_policy_id = Employee::select('working_hours_policy_id')->where([['emp_id','=',$value->emp_id]])->value('working_hours_policy_id');
                                            CommonHelper::reconnectMasterDatabase();

                                            $end_time =CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','end_working_hours_time',$working_hour_policy_id);
                                            $start_time = CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','start_working_hours_time',$working_hour_policy_id);
                                            $dutyStartTime =  CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','start_working_hours_time',$working_hour_policy_id);

                                            $start = strtotime($start_time);
                                            $end = strtotime($end_time);
                                            $elapsed1 = $end - $start;

                                            if($value->clock_in == '' || $value->clock_out == ''):

                                                $elapsed2 = 0;
                                            else:
                                                $start = strtotime($value->clock_in);
                                                $end = strtotime($value->clock_out);

                                                $elapsed2 = $end - $start;
                                            endif;

                                            if(!in_array($value->attendance_date,$id_array)):
                                                //Normal Days OVerTime
                                                if($value->clock_in != '' && $value->clock_out != ''):
                                                    if(strtotime($value->clock_out) > strtotime($end_time)):

                                                        $start = strtotime($end_time);
                                                        $end = strtotime($value->clock_out);

                                                        $diffOT = abs($end - $start);

                                                        $tminsOT = $diffOT/60;

                                                        $hoursOT = floor($tminsOT/60);

                                                        $minsOT = $tminsOT%60;

                                                        $totalHoursOT[] = $hoursOT.":".$minsOT;

                                                        //echo "<p><b>$hoursOT : $minsOT</p></b>";
                                                    endif;

                                                endif;
                                                if($value->clock_in != "00:00"):
                                                    $clock_in2 = $value->clock_in;
                                                    if($clock_in2 != ''){
                                                        $time2 = strtotime($value->clock_in);
                                                        $duty_time = strtotime($dutyStartTime);

                                                        if($duty_time > $time2):
                                                            $diff2 = abs($duty_time - $time2);

                                                            $tmins2 = $diff2/60;

                                                            $hours2 = floor($tmins2/60);

                                                            $mins2 = $tmins2%60;

                                                            $totalHoursWorked2[] = $hours2.":".$mins2;

                                                        endif;
                                                    }
                                                endif;

                                            endif;
                                            ?>
                                            @if($minsOT > 0 || $hoursOT > 0 || $mins2 > 0 || $hours2 > 0)
                                                <tr>
                                                    <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                                    <td class="text-center">{{ HrHelper::date_format($value->attendance_date) }}</td>
                                                    <td class="text-center">{{$value->day}}</td>
                                                    <td class="text-center" >{{$value->clock_in}}</td>
                                                    <td class="text-center">{{$value->clock_out}}</td>
                                                    @if($minsOT > 0 || $hoursOT > 0)
                                                    <td class="text-center" style="background-color: #FFC0CB;">
                                                        <?php  echo '<p><b>'.$hoursOT.":".$minsOT.'</b></p>'; ?>
                                                    </td>
                                                    @elseif( $mins2 > 0 || $hours2 > 0)
                                                        <td class="text-center" style="background-color: #FFC0CB;">
                                                        <?php  echo '<p><b>'.$hours2.":".$mins2.'</b></p>'; ?>
                                                        </td>
                                                    @endif
                                                    @if($minsOT > 0 || $hoursOT > 0)
                                                        <td class="text-center" style="background-color: #FFC0CB;">
                                                            <?php  echo '<p><b>'.$hoursOT.":".$minsOT.'</b></p>'; ?>
                                                        </td>
                                                    @elseif( $mins2 > 0 || $hours2 > 0)
                                                        <td class="text-center" style="background-color: #FFC0CB;">
                                                            <?php  echo '<p><b>'.$hours2.":".$mins2.'</b></p>'; ?>
                                                        </td>
                                                    @endif
                                                    <td class="text-center">
                                                        <?php

                                                        $total_ott = $hoursOT;
                                                        $total_ott += $hours2;

                                                        if($mins2 > 40):
                                                            $total_ott += 1;
                                                        endif;

                                                        if($minsOT > 40):
                                                            $total_ott += 1;
                                                        endif;
                                                        echo '<p><b>'.$total_ott.'</b></p>';
                                                        $total_ott1  += $total_ott;
                                                        ?>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3  class="text-center">Holidays Days</h3>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Late Arrival Date</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Check In</th>
                                    <th class="text-center">Check Out</th>
                                    <th class="text-center" style="background-color: #FFC0CB;">Duration</th>
                                    <th class="text-center" style="background-color: #FFC0CB;">Overtime Hours</th>
                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;


                                    ?>

                                    @foreach($total_ot_hours_holidays_count as $value)
                                        @if(in_array($value->attendance_date,$id_array))
                                            <?php
                                            $ot_days='';
                                            $minsOT ='';
                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $working_hour_policy_id = Employee::select('working_policy_id')->where([['emp_id','=',$value->emp_id]])->value('working_policy_id');
                                            CommonHelper::reconnectMasterDatabase();

                                            $end_time =CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','end_working_hours_time',$working_hour_policy_id);
                                            $start_time = CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','start_working_hours_time',$working_hour_policy_id);

                                            $start = strtotime($start_time);
                                            $end = strtotime($end_time);
                                            $elapsed1 = $end - $start;

                                            if($value->clock_in == '' || $value->clock_out == ''):

                                                $elapsed2 = 0;
                                            else:
                                                $start = strtotime($value->clock_in);
                                                $end = strtotime($value->clock_out);

                                                $elapsed2 = $end - $start;
                                            endif;


                                            if(in_array($value->attendance_date,$id_array)):

                                                if($value->clock_in != '' && $value->clock_out != ''):
                                                    //HOliday OverTime
                                                    $ottdaysCalculate = 0;

                                                    $ottdaysCalculate =  round(abs(strtotime($value->clock_out) - strtotime($value->clock_in))/60);

                                                    $ot_days = floor($ottdaysCalculate/60);
                                                    //$total_ott += floor($ottdaysCalculate/60)*1.5;
                                                endif;

                                                // echo '<p><b>'.$ot_days.'</b></p>';

                                            endif;
                                            ?>
                                            @if($ot_days > 0)
                                                <tr>
                                                    <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                                    <td class="text-center">{{ HrHelper::date_format($value->attendance_date) }}</td>
                                                    <td class="text-center">{{$value->day}}</td>
                                                    <td class="text-center" >{{$value->clock_in}}</td>
                                                    <td class="text-center">{{$value->clock_out}}</td>
                                                    <td class="text-center" style="background-color: #FFC0CB;">
                                                        <?php  echo '<p><b>'.$ot_days.'</b></p>'; ?>
                                                    </td>
                                                    <td class="text-center" style="background-color: #FFC0CB;">
                                                        <?php
                                                        //$total_ott += $ot_days*1.5;
                                                        $total_ott1  += $ot_days*1.5;
                                                        echo '<p><b>'.$ot_days*1.5.'</b></p>'; ?>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach

                                    @if($total_ott1 > 0)
                                        <tr>
                                            <td class='text-right' colspan="5">
                                            </td>
                                            <td class='text-right'>
                                                <b>Total Hours</b>
                                            </td>
                                            <td class='text-center'>
                                                <b><?php echo $total_ott1; ?></b>
                                            </td>
                                        </tr>
                                    @endif


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
