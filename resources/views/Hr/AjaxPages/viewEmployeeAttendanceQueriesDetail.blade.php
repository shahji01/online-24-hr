<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>

<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
                    <thead>
                    <tr>    
                        <th class="text-center">S No.</th>
                        <th class="text-center" >Emp Id</th>
                        <th class="text-center" >Emp Name</th>
                        <th class="text-center" >Attendance Date</th>
                        <th class="text-center" >Clock Inn Request</th>
                        <th class="text-center" >Clock Out Request</th>
                        <th class="text-center">Action</th>
                    </tr>
                   </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if($attendance_queries->count() > 0)
                    @foreach($attendance_queries->get() as $value)
                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                            <td class="text-center">{{$value->emp_id}}</td>
                            @php CommonHelper::companyDatabaseConnection(Input::get('m')); @endphp
                            <td class="text-center">{{ DB::table('employee')->select('emp_name')->where('status',1)->where('id',$value->employee_id)->value('emp_name') }}</td>
                            @php CommonHelper::reconnectMasterDatabase(); @endphp
                            <td class="text-center">{{HrHelper::date_format($value->date) }}</td>
                            <td class="text-center">
                                @if($value->clock_in!='')
                                {{date("g:ia", strtotime($value->clock_in))}}
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($value->clock_out!='')
                                 {{date("g:ia", strtotime($value->clock_out))}}
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center">
                                <?php echo Form::open(array('url' => 'had/approveOrRejectAttendanceQuery','id'=>'employeeForm'));?>
                                @if($value->approval_status == 1)
                                    <input type="hidden" value="{{ $value->emp_id }}" name="emp_id" id="emp_id" />
                                     <input type="hidden" value="{{ $value->employee_id }}" name="employee_id" id="employee_id" />
                                    <input type="hidden" value="{{ $value->id }}" name="id" id="id" />
                                    <span style="cursor:pointer" onclick="Approve()" class="badge badge-sm badge-success">Resovle</span>
                                    <span style="cursor:pointer" onclick="Reject()" class="badge badge-sm badge-danger">Reject</span>
                                @endif
                                <?php echo Form::close();?>
                            </td>
                        </tr>
                        <tr>
                             <th>Query</th>
                            <td colspan="6" class="text-center">{{$value->query}}</td>
                        </tr>
                    @endforeach
                     @else
                        <tr class="text-danger">
                            <td class="text-center" colspan="7" style="color:red;">Record Not Found !</td>
                        </tr>
                     @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    
    var m=$('#m').val();
    var baseUrl=$('#baseUrl').val();
    
    function Approve(){
        var status=2;
        var id=$('#id').val();
        var emp_id=$('#emp_id').val();
        $.ajax({
        url: baseUrl+"/hdc/approveOrRejectAttendanceQuery",
        type: 'GET',
        data: {status:status,id:id,emp_id:emp_id},
        success: function (response){
            location.reload();
            
        }
    });
        
    }
    function Reject(){
        var status=3;
        var id=$('#id').val();
        $.ajax({
        url: baseUrl+"/hdc/approveOrRejectAttendanceQuery",
        type: 'GET',
        data: {status:status,id:id},
        success: function (response){
            location.reload();
            
        }
    });
        
    }
    
</script>
