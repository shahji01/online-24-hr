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
                        <h3 class="text-center" style="color:green">
                            <b>Grace Time : <?=$dutyEndTime2?></b>
                        </h3>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list table-hover" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Early Going Dates</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Check In</th>
                                    <th class="text-center">Check Out</th>
                                    <th class="text-center">Early</th>
                                    <th class="text-center">Count</th>
                                    <th class="text-center">Action</th>
                                    <th class="text-center">Reason (If Neglect)</th>

                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;
                                    $countTotaEarly = 0;
                                    $totalEarlyHoursCount = 0;
                                    ?>

                                    @foreach($ealryGoingData as $value)
                                        <?php

                                        if($value->clock_out != "00:00"):
                                            $clock_out = $value->clock_out;

                                            if($clock_out != ''){
                                                $time3 = strtotime($clock_out);
                                                $duty_end_time1 = strtotime($dutyEndTime2);
                                                if($duty_end_time1 > $time3):
                                                    $diff3 = abs($duty_end_time1 - $time3);

                                                    $tmins3 = $diff3/60;

                                                    $hours3 = floor($tmins3/60);

                                                    $mins3 = $tmins3%60;

                                                    $totalHoursWorked3[] = $hours3.":".$mins3;

                                                    $totalEarlyHoursCount = $hours3;

                                                    if($mins3 > 15):
                                                        $totalEarlyHoursCount +=1;
                                                    endif;

                                                     "<b>$hours3 : $mins3</b>";
                                                endif;
                                            }
                                        endif;
                                        ?>
                                    @if($totalEarlyHoursCount > 0)
                                        <tr>
                                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                            <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                                            <td class="text-center">{{$value->day}}</td>
                                            <td class="text-center" >{{$value->clock_in}}</td>
                                            <td class="text-center" style="background-color: #FFC0CB;">{{$value->clock_out}}</td>
                                            <td class="text-center"><?php echo "<b>$hours3 : $mins3</b>"; ?></td>
                                            <td class="text-center" style="color:red;">
                                                <?php
                                                if($value->neglect_early == 'no'):
                                                    $countTotaEarly +=  $totalEarlyHoursCount;
                                                endif;
                                                ?>
                                                {{$totalEarlyHoursCount}}
                                            </td>
                                            <td class="text-center">
                                                @if($value->neglect_early == 'yes')
                                                    <span style="color:green">Neglected </span>
                                                @else
                                                    <button id="removeBtn<?=$value->id?>" class="btn btn-xs btn-danger" onclick="NeglectEarlyGoing('<?=$value->id?>','<?=HrHelper::date_format($value->attendance_date)?>')">Neglect Early</button>
                                                    <span style="color:green;" id="NeglectArea<?=$value->id?>"></span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <?php CommonHelper::companyDatabaseConnection(Input::get('m')); ?>
                                                {{DB::table('user_query')->where([['emp_id','=',$value->emp_id],['query_date','=',$value->attendance_date],['status','=','1']])->value('remarks')}}
                                                <?php CommonHelper::reconnectMasterDatabase(); ?>
                                            </td>

                                        </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="5"></td>
                                        <td class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b style="color:red;">{{$countTotaEarly}}</b></td>
                                        <td colspan="2"></td>
                                    </tr>
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
    function NeglectEarlyGoing(attendance_id,date)
    {
        $('#NeglectArea'+attendance_id).html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        if(confirm("Do you want to Neglect Late Arrival of "+date+" Date ? "))
        {
            $.ajax({
                url: '<?php echo url('/')?>/hedbac/NeglectEarlyGoing',
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