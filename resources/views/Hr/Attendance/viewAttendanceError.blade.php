<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\employees;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;

$accType = Auth::user()->acc_type;
$count = 1;
$total_ot_days = 0;
$leave_application_request_list = [];
$totalHoursWorked4 = 0;
$dates = [];
$absentDays = 0;
$leaves = array();
$totalLateHoursCount = 0;
$totalLateMintsCount = 0;
$diff2 = 0;
$total_late_mins=0;
$total_early_going_mins=0;

$totals = [
    'total_present_days' => 0,
    'total_absent_days' => 0,
    'total_leaves' => 0,
    'total_holidays' => 0,
    'total_off_days' => 0,
    'total_late_minutes' => 0,
    'total_half_days' => 0,
];
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" id="printList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                        <thead>
                                        <th class="">S.No</th>
                                        <th class="">Name</th>
                                        <th class="">Attendance Date</th>
                                        <th class="">Current Working Hour Policy</th>
                                        <th class="">Select Working Hour Policy</th>
                                        <th class="">Clock In</th>
                                        <th class="">Clock Out</th>
                                        <th class="">Action</th>
                                        
                                        </thead>
                                        <tbody>
                                        
                                        @if(count($attendanceErrorData) > 0)
                                                @foreach($attendanceErrorData as $value)

                                                    <tr>
                                                       <td>
                                                            <input type="hidden" value="{{$value->id}}" >    
                                                            <input type="hidden" value="{{$value->att_id}}" >    
                                                            <input type="hidden" value="{{$value->attendance_date}}" >    
                                                       
                                                       <?php echo $count++ ?>
                                                       </td> 
                                                       <td><?php echo $value->emp_name ?></td>
                                                       <td><?php echo $value->attendance_date ?></td> 
                                                       <td><?php echo $value->working_hours_policy ?></td> 
                                                       <td>
                                                       <select style="width: 100%;" class="form-control" name="working_hours_policy_id" id="working_hours_policy_id">
                                                            <option value="">Select Policy</option>
                                                            @foreach($workingHourPolicies as $key => $y)
                                                                <option  @if($value->working_hours_policy_id == $y->id) selected @endif  value="{{ $y->id }}">{{ $y->working_hours_policy}}</option>
                                                            @endforeach
                                                        </select> 
                                                       </td> 
                                                      <td>
                                                        <input type="time" name="clock_in" class="form-control" id="clock_in" value="{{ $value->clock_in }}" />
                                                      </td> 
                                                      <td>
                                                        <input type="time" name="clock_out" class="form-control" id="clock_out" value="{{ $value->clock_out }}"  />
                                                      </td>
                                                      <td>
                                                        <input type="button" class="btn btn-sm btn-primary" onclick="updateAttendanceError(event)" value="Update Error" style="" />
                                                      </td> 
                                                    </tr>
                                                @endforeach

                                            @else   
                                            <tr class="text-center">
                                                <td colspan="11" style="color:red;">
                                                    Record Not Found
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

<script>
        $('#working_hours_policy_id').select2();
       function updateAttendanceError(e) {
            let emp_id = e.target.parentNode.parentNode.querySelectorAll('input')[0].value;
            let att_id = e.target.parentNode.parentNode.querySelectorAll('input')[1].value;
            let attendance_date = e.target.parentNode.parentNode.querySelectorAll('input')[2].value;
            let clock_in = e.target.parentNode.parentNode.querySelectorAll('input')[3].value;
            let clock_out = e.target.parentNode.parentNode.querySelectorAll('input')[4].value;
            let working_hours_policy_id = e.target.parentNode.parentNode.querySelector('select').value;

            if(working_hours_policy_id)
            {

                $.ajax({
                    url: baseUrl+'/had/updateAttendanceError',
                    type: "GET",
                    data: {emp_id,att_id,attendance_date,clock_in,clock_out,working_hours_policy_id,m:m},
                    success:function(data) {
                        if(data=='success'){
                            $.notify({
                                icon: "fa fa-check-circle",
                                message: "<b> Successfully Updated</b>.",
                            }, {
                                type: 'success',
                                timer: 50
                            });

                            viewAttendanceError();
                        }
                        else
                        {
                            $.notify({
                                icon: "fa fa-check-circle",
                                message: "<b> something went wrong</b>.",
                            }, {
                                type: 'error',
                                timer: 50
                            });
                        }
                    }
                    
                });
        }
        else
        {
            alert("please select working hour policy")
        }
          
        }
    $('.select_att_status').on('change', function() {
        var status=this.value;
        var myArr = status.split("*");
        var c=myArr[3];
        c--;
        if(myArr[0] == "absent"){
            $('#att_tr_'+c).css( "background-color", "#e76e6ed9" );
        }
        else{
            $('#att_tr_'+c).css('background-color','');
        }

        var att_status=myArr[0];
        var emp_id=myArr[2];
        var att_date=myArr[1];
        var m='<?php echo Input::get("m") ?>';

        $.ajax({
            url: baseUrl+'/had/editAttendanceDetail',
            type: "GET",
            data: {att_status:att_status,emp_id:emp_id,att_date:att_date,m:m},
            success:function(data) {
                if(data=='success'){
                    $.notify({
                        icon: "fa fa-check-circle",
                        message: "<b> Successfully Updated</b>.",
                    }, {
                        type: 'success',
                        timer: 50
                    });
                }
            }

        });
    });
</script>