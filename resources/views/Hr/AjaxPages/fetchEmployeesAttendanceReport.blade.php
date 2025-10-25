<style>
    .panel-heading {
        padding: 0px 15px;}
    .field_width {width: 120px;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }


</style>
<?php

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holidays;

$employeeArray = [];
$recordNotFound = [];

$result=[];


CommonHelper::reconnectMasterDatabase();
$end_att_date='';
$current_date= date("Y-m-d", time());
$explode_current_date=explode('-',$current_date);
if($to > $current_date){
    $end_att_date=$current_date;
}else{
     $end_att_date=$to;
}
$start_att_date=$from;
CommonHelper::companyDatabaseConnection(Input::get('m'));

$holidays=DB::table('holidays')->where([['status','=',1]])
->whereBetween('holiday_date',[$start_att_date,$end_att_date])
->get()->toArray();

$attendance_dates=DB::table('attendance')->whereBetween('attendance_date',[$start_att_date,$end_att_date])
->whereIn('employee_id',$emr_array)
->groupBy('attendance_date')->get();

$attendances=DB::table('attendance')
->select('attendance.*','employee.emp_id','employee.emp_name','employee.sub_department_id')
->join('employee','employee.id','=','attendance.employee_id')
->whereBetween('attendance.attendance_date',[$start_att_date,$end_att_date])
->whereIn('attendance.employee_id',$emr_array)
->groupBy('attendance.employee_id')->get();

CommonHelper::reconnectMasterDatabase();
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" id="TaxesList">
                        <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Department</th>
                            @foreach($attendance_dates as $val)
                                <th class="text-center" >{{ HrHelper::date_format($val->attendance_date) }}</th>
                            @endforeach
                            <th class="text-center">Total Days</th>
                            <th class="text-center">Total Present</th>
                            <th class="text-center">Total Absent</th>
                             <th class="text-center">Total Leaves</th>
                            <th class="text-center">Total Off Days</th>
                            <th class="text-center">Total Holidays</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php 
                            $count=1;  
                           
                            @endphp
                            @foreach($attendances as $val)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $val->emp_id }}</td>
                                <td>{{ $val->emp_name }}</td>
                                <td>@php    
                                        if($val->sub_department_id != ''){
                                         echo  HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$val->sub_department_id);
                                        } else{
                                            echo '--';
                                        }
                                    @endphp    
                                </td>
                                @php 
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $emp_attendances=DB::table('attendance')
                                    ->whereBetween('attendance_date',[$start_att_date,$end_att_date])
                                    ->where('employee_id',$val->employee_id)->get();
                                 
                                 $day=0;
                                 $totalOffDates=[];
                                 
                                @endphp
                                
                                @php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));    
                                $total_days_off = Attendance::select('attendance_date')->whereBetween('attendance_date',[$start_att_date,$end_att_date])
                                ->where('attendance_status','day_off')->where('employee_id','=',$val->employee_id);
                                if($total_days_off->count() > 0):
                                    foreach($total_days_off->get() as $value2):
                                        $public_holidays[]=$value2['attendance_date'];
                                    endforeach;

                                else:
                                    $public_holidays = array();
                                endif;

                               
                                $get_holidays = Holidays::select('holiday_date')->where([['status','=',1]])
                                ->whereBetween('holiday_date',[$start_att_date,$end_att_date]);
                                if($get_holidays->count() > 0):
                                    foreach($get_holidays->get() as $value2):

                                        $monthly_holidays[]=$value2['holiday_date'];
                                        
                                    endforeach;

                                else:
                                    $monthly_holidays =array();
                                    
                                endif;

                                $monthly_holidays = array_merge($monthly_holidays,$public_holidays);
								
								$absent_attendances=DB::table('attendance')
                                ->whereBetween('attendance_date',[$start_att_date,$end_att_date])
                                ->where('employee_id',$val->employee_id)
                                ->whereNotIn('attendance_date',$monthly_holidays)
                                ->where('clock_in','')
                                ->where('clock_out','');
								
								
								CommonHelper::reconnectMasterDatabase();
                                
                                $leave_application = DB::table('leave_application')
                                ->join('leave_application_data','leave_application_data.leave_application_id','=','leave_application.id')
                                ->whereBetween('leave_application_data.from_date',[$start_att_date,$end_att_date])
                            ->where([['leave_application.employee_id', '=', $val->employee_id],['leave_application.status', '=', 1]])
                            ->orWhere(function($nest) use($val,$start_att_date,$end_att_date) {
                                $nest->where('leave_application.employee_id',$val->employee_id)
                                    ->where('leave_application.status',1)
                                    ->whereBetween('leave_application_data.to_date',[$start_att_date,$end_att_date]);
                            })->orWhere(function($nest) use($val,$start_att_date,$end_att_date) {
                                $nest->where('leave_application.employee_id',$val->employee_id)
                                    ->where('leave_application.status',1)
                                    ->whereBetween('leave_application_data.first_second_half_date',[$start_att_date,$end_att_date]);
                            });
                                
                               @endphp
                                
                                @foreach($emp_attendances as $val1)
                                    
                                    <td >@php
                                    if(in_array($val1->attendance_date,$monthly_holidays)){
                                        echo "Holiday ";
                                    }elseif(in_array($val1->attendance_date,$public_holidays)){
                                         echo "Off Day ";
                                    }elseif($val1->clock_in == '' && $val1->clock_out == ''){
                                        echo "Absent";
                                    }
                                    if($val1->clock_in != '' && $val1->clock_out != ''){
                                        $day++;
                                        echo 'In '. date('h:i:a', strtotime($val1->clock_in));
                                        echo " Out ".date('h:i:a', strtotime($val1->clock_out));
                                    }elseif($val1->clock_in != '' && $val1->clock_out == ''){
                                         $day++;
                                         echo 'In '. date('h:i:a', strtotime($val1->clock_in));
                                         echo " Out Missing";
                                    }
                                      
                                       
                                    @endphp</td>
                                @endforeach
                                <td>{{ count($attendance_dates) }}</td>
                                <td>{{ $day }}
                                <td>{{ $absent_attendances->count() }}</td>
                                <td>{{ $leave_application->sum('leave_application_data.no_of_days') }}</td>
                                <td>{{ $total_days_off->count() }}</td>
                                <td>{{ $get_holidays->count() }}</td>
                            </tr>
                             @endforeach
                        </tbody>
                    </table>

                    

                </div>
            </div>



        </div>
       

    </div>`
</div>






