<?php
use App\Models\Attendance;
use App\Helpers\CommonHelper;
$m = Input::get('m');
$counter = 1;
$i = 0;
 ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-sm mb-0 table-bordered table-striped">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Attendance Date</th>
                                    <th class="text-center">Days</th>
                                    <th class="text-center">Clock In</th>
                                    <th class="text-center">Clock Out</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Absent</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($employees as $key => $value)
                                        @foreach($dates as $date)
                                            <?php
                                            $i++;
                                            $LoopingDate = $date;
                                            $month_year = explode('-',$date);
                                            $year = $month_year[0];
                                            $month = $month_year[1];
                                            $clock_in = '';
                                            $clock_out = '';

                                            CommonHelper::companyDatabaseConnection($m);

                                            $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
                                                ->where('status',1)->where('employee_id',$value->id)->where('from_date','<=',$LoopingDate)
                                                ->where('to_date','>=',$LoopingDate)->orderBy('id','desc')->value('working_hours_policy_id');

                                            $getAttendance = Attendance::select('clock_in','clock_out')->where([['employee_id','=',$value->id],['attendance_date','=', $LoopingDate]]);
                                            if($getAttendance->count() > 0 ) {
                                                $getAttendanceDetail =  $getAttendance->first();
                                                $clock_in = $getAttendanceDetail->clock_in;
                                                $clock_out = $getAttendanceDetail->clock_out;
                                            } else {
                                                $clock_in = '';
                                                $clock_out = '';
                                            }

                                            CommonHelper::reconnectMasterDatabase();

                                            $day_off = DB::table('working_hours_policy')->select('days_off')->where('id',$working_hours_policy_id)->value('days_off');
                                            $day_off = explode("=>",$day_off);
                                            $dating = date('D',strtotime($LoopingDate));
                                            ?>

                                            <tr style="@if(in_array($dating,$day_off)){{"background-color: #FFC0CB"}}@endif">
                                                <td class="text-center">{{ $counter++ }}
                                                    <input type="hidden" name="employee_id[]" value="{{ $value->id }}" />
                                                    <input type="hidden" name="emp_name[]" value="{{ $value->id }}" />
                                                    <input type="hidden" name="month[]" value="{{ $month }}" />
                                                    <input type="hidden" name="to_date" value="{{ Input::get('to_date') }}" />
                                                    <input type="hidden" name="year[]" value="{{ $year }}" />
                                                </td>
                                                <td class="text-center">{{ $value->emp_id }}</td>
                                                <td>{{ $value->emp_name }}</td>
                                                <td>
                                                    @if(in_array($dating,$day_off)) {{ date('d-m-Y',strtotime($LoopingDate)) }} @endif
                                                    <input name="attendance_date[]" id="attendance_date_{{ $i }}" type="@if(in_array($dating,$day_off)){{"hidden"}}@else{{"date"}}@endif" value="{{ $LoopingDate }}" class="form-control" readonly />
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="day[]" value="{{ date('D',strtotime($LoopingDate)) }}">{{ date('D',strtotime($LoopingDate)) }}
                                                </td>
                                                <td>@if(in_array($dating,$day_off)) {{ "DAY OFF" }} @endif
                                                    <input type="time" class="form-control clockIn" value="09:00" name="clock_in[]" id="clock_in_{{ $i }}" />
                                                </td>
                                                <td>@if(in_array($dating,$day_off)) {{ "DAY OFF" }} @endif
                                                    <input type="time" class="form-control clockOut" value="18:00" name="clock_out[]" id="clock_out_{{ $i }}" />
                                                </td>
                                                <td class="text-center">--</td>
                                                <td>
                                                    @if(in_array($dating,$day_off)){{"DAY OFF"}}
                                                        <input type="hidden" name="absent[]" value="1">
                                                        <input type="hidden" name="day_off[]" value="1">
                                                    @else
                                                        <input type="hidden" name="day_off[]" value="2">
                                                        <select name="absent[]" id="absent_{{ $i }}" class="form-control" onchange="absemtStatus('{{ $i }}')">
                                                            <option value="1">No</option>
                                                            <option value="2">YES</option>
                                                        </select>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                                <button class="btn btn-sm btn-success" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function absemtStatus(rowId) {

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

    function setAsDefault(param1,param2,param3){
        var formdataa = new Array();
        var val;
        var param = param1;

        formdataa.push($(this.target).val());

        for (val in formdataa) {
            fillAllFields(param,param2,param3);
            // alert('Sucess');

        }
    }

    function fillAllFields(param,param2,param3) {

        var requiredField = document.getElementsByClassName(param2);
        for (i = 0; i < requiredField.length; i++) {

            var rf = requiredField[i].id;
            console.log(rf);
            var checkType = requiredField[i].type;

            if(checkType !== 'hidden') {
                $('.' + rf).val(param);
            }
        }
    }

</script>