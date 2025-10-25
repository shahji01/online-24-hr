<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
$m = $_GET['m'];
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="employeeAttendanceList">
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">EMR-No</th>
                <th class="text-center">Employee Name</th>
                <th class="text-center">Attendace Date</th>
                <th class="text-center">Days</th>
                <th class="text-center">Clock In</th>
                <th class="text-center">Clock Out</th>
                <th class="text-center">Late</th>
                <th class="text-center">Absent</th>
                </thead>
                <tbody id="">
                <?php
                //echo $getData['month'];
                $totalDays = cal_days_in_month(CAL_GREGORIAN,$getData['month'],$getData['year']);
                $FilterDates = '';
                for($i = 1; $i <= $totalDays; $i++)
                {
                if(strlen($i)== 1)
                {
                    $FilterDates = '0'.$i;
                }
                else
                {
                    $FilterDates = $i;
                }

                $LoopingDate = $getData['year'].'-'.$getData['month'].'-'.$FilterDates;
                ?>

                <tr style="<?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "background-color: #FFC0CB";}?>">
                    <td><?php echo $i;?></td>
                    <td><input type="hidden" name="emr_no[]" value="<?php echo $getData['emr_no'];?>"><?php echo $getData['emr_no']?></td>
                    <td><input type="hidden" name="emp_name[]" value="<?php echo $getData['emp_name'];?>"><?php echo $getData['emp_name'];?></td>
                    <input type="hidden" name="month[]" value="<?php echo $getData['month'];?>">
                    <input type="hidden" name="year[]" value="<?php echo $getData['year'];?>">

                    <td>
                        <?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo $LoopingDate;}?>
                        <input name="attendance_date[]" id="attendance_date_<?php echo $i;?>" type="<?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "hidden";}else{echo "date";}?>" value="<?php echo $LoopingDate;?>" class="form-control" readonly >
                    </td>

                    <td><input type="hidden" name="day[]" value="<?php echo date('D',strtotime($LoopingDate));?>"><?php echo date('D',strtotime($LoopingDate));?></td>


                    <td><?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "DAY OFF";}?>
                        <input type="<?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "hidden";}else{echo "time";}?>"
                        class="form-control" value="<?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "";}else{echo "10:00";}?>"
                        name="clock_in[]" id="clock_in_<?php echo $i;?>">
                    </td>
                    <td><?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "DAY OFF";}?>
                        <input type="<?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "hidden";}else{echo "time";}?>" class="form-control"
                        value="<?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "";}else{echo "18:00";}?>"
                        name="clock_out[]" id="clock_out_<?php echo $i;?>">
                    </td>
                    <td>--</td>
                    <td><?php if($getData['day_off'] == date('D',strtotime($LoopingDate))){echo "DAY OFF";}else{?>
                        <select name="absent[]" id="absent_<?php echo $i;?>" class="form-control" onchange="AbsentStatus('<?php echo $i;?>')">
                            <option value="1" selected>No</option>
                            <option value="2">YES</option>
                        </select>
                        <?php } ?>
                    </td>



                </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<script>
    function AbsentStatus(rowId) {
        var absentStatusVal  = $('#absent_'+rowId).val();
        if(absentStatusVal == 2)
        {
            $('#clock_in_' + rowId).attr('type', 'hidden');
            $('#clock_in_' + rowId).attr('value', '');
            $('#clock_out_' + rowId).attr('type', 'hidden');
            $('#clock_out_' + rowId).attr('value', '');
        }
        else
        {
            $('#clock_in_' + rowId).attr('type', 'time');
            $('#clock_in_' + rowId).attr('value', '10:00');
            $('#clock_out_' + rowId).attr('type', 'time');
            $('#clock_out_' + rowId).attr('value', '18:00');
        }

    }

</script>