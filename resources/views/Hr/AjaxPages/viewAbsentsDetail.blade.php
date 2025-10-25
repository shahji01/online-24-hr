<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
$date=date("Y-m-d");
?>

<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp Id</th>
                    <th class="text-center">Emp Name</th>
                    <th class="text-center">Attendance Date</th>
                    <th class="text-center">Clock In</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if($absents_detail->count() > 0)
                    @foreach($absents_detail->get() as $value)
                    @php
                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                    
                     $isAbsent= DB::table('attendance')->where('attendance_date',$date)->where('employee_id',$value->id)->where('clock_in','!=','');  
                    
                    CommonHelper::reconnectMasterDatabase();
                    @endphp
                    @if($isAbsent->count()>0)
                        @else
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value->emp_id}}</td>
                                <td class="text-center">{{ $value->emp_name }}</td>
                                <td class="text-center">{{ HrHelper::date_format($date) }}</td>
                                <td class="text-center" style="background-color:#ffd3d3;">--</td>
                            </tr>
                        @endif    
                    @endforeach
                         @else
                            <tr class="text-danger">
                                <td class="text-center" colspan="5" style="color:red;">Record Not Found !</td>
                            </tr>
                         @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>