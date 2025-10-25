<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
//$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
CommonHelper::companyDatabaseConnection(Input::get('m'));
$emp_name=DB::table('employee')->select('emp_name')->where('id',Auth::user()->employee_id)->value('emp_name');
CommonHelper::reconnectMasterDatabase();
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">



        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="card-title">Attendance Request Queries</h4>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintLoanReport','','1');?>
                                    <?php echo CommonHelper::displayExportButton('LoanReport','','1')?>
                                </div>
                            </div>

                            <hr>
                            <div class="panel">
                                <div class="panel-body">
                                    <?php echo Form::open(array('url' => 'had/addAttendanceRequestQueriesDetail','id'=>'employeeForm'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="m" value="<?php echo Input::get('m')?>">
                                    <input type="hidden" name="emp_id" value="{{ Auth::user()->emp_id }}">
                                    <input type="hidden" name="employee_id" value="{{ Auth::user()->employee_id }}">
                                    <div class="row">
                                         
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                            <label class="sf-label">Attendance Date:</label>
                                            <span class="rflabelsteric">*</span>
                                            <input type="date" name="attendance_date" required id="attendance_date" onchange="onAttendanceDateChange(this.value,{{ Auth::user()->employee_id }})" class="form-control requiredField" />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                            <label class="sf-label">Clock Inn Time Request To Change:</label>
                                            <span class="rflabelsteric">*</span>
                                            <input type="time" name="clock_in"  id="clock_in" class="form-control requiredField" />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                            <label class="sf-label">Clock Out Time Request To Change:</label>
                                            <span class="rflabelsteric">*</span>
                                            <input type="time" name="clock_out"  id="clock_out" class="form-control requiredField" />
                                        </div>
                                    </div>    
                                    <div class="row">
                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
                                            <label class="sf-label">Query :</label>
                                            <span class="rflabelsteric">*</span>
                                            <textarea name="query" id="query" required class="form-control requiredField"></textarea>
                                            <br>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success','id'=>'btn_add']) }}
                                        </div>
                                    </div>
                                    <?php echo Form::close();?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintLoanReport">
                        <div class="row">
                                    <div class="col-sm-2">

                                    </div>
                                    <div class="col-sm-6"></div>

                                    <!--<div class="col-sm-4 text-right">-->
                                    <!--    <div class="form-group has-search">-->
                                    <!--        <span class="fa fa-search form-control-feedback"></span>-->
                                    <!--        <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />-->
                                    <!--        <input type="hidden" id="company_id" value="<?= $m ?>">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>

                        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                        <div class="table-responsive" id="LoanReport">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportLateArrivals">
                                <thead>
                                <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Emp ID.</th>
                                <th scope="col">Emp Name</th>
                                <th scope="col">Att Date</th>
                                <th scope="col">Clock Inn Request</th>
                                <th scope="col">Clock Out Request</th>
                                <th scope="col">Status</th>
                                <th scope="col">Query</th>
                                <th scope="col" class="hidden-print">Action</th>
                                </tr>
                                </thead>
                                <tbody >
                                <?php $count=1;
                                    $badge='';
                                    $status=['1'=>'Pending','2'=>'Resolved','3'=>'Rejected'];
                                ?>
                                @if(count($attendance_queries) > 0)
                                @foreach($attendance_queries as $value)
                                @php
                                    if($value->approval_status==1){
                                         $badge='badge-warning';
                                    }elseif($value->approval_status==2){
                                        $badge='badge-success';
                                    }elseif($value->approval_status==3){
                                        $badge='badge-danger';
                                    }
                                @endphp
                                
                                    <tr>
                                        <td class="text-center"><span class="badge badge-pill badge-secondary">{{$count++}}</span></td>
                                        <td class="text-center">{{ $value->emp_id}}</td>
                                        @if($value->employee_id != '')
                                        <td class="text-center">{{\App\Helpers\HrHelper::getCompanyTableValueByIdAndColumn(Input::get("m"),'employee','emp_name',$value->employee_id,'id')}}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td class="text-center">
                                            @if($value->approval_status==1)
                                                 <input type="date"  name="attendance_date" id="attendance_date_{{$value->id}}" value="{{ $value->attendance_date }}" class="form-control requiredField" />
                                            @else
                                                {{\App\Helpers\HrHelper::date_format($value->attendance_date) }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->approval_status==1)
                                                  <input type="time" value="{{ $value->clock_in }}" name="clock_in" id="clock_in_{{$value->id}}" class="form-control " />
                                            @else
                                            @if($value->clock_in!='')
                                                {{date("g:ia", strtotime($value->clock_in))}}
                                            @else
                                                -
                                            @endif
                                            @endif
                                            
                                        </td>
                                        <td class="text-center">
                                            @if($value->approval_status==1)
                                                  <input type="time" value="{{ $value->clock_out }}" name="clock_out" id="clock_out_{{$value->id}}" class="form-control " />
                                            @else
                                             @if($value->clock_out!='')
                                                {{date("g:ia", strtotime($value->clock_out))}}
                                            @else
                                                -
                                            @endif
                                                
                                            @endif
                                            
                                        </td>
                                        <td class="text-center"><span style="color:white;" class="badge badge-sm {{ $badge }}"> {{$status[$value->approval_status]}} </span></td>
                                        <td class="">
                                            @if($value->approval_status==1)
                                                   <textarea name="query" id="query_{{$value->id}}" required class="form-control requiredField">{{$value->query}}</textarea>
                                            @else
                                               {{$value->query}}
                                            @endif
                                        </td>
                                         <td class="hidden-print">
                                           @if($value->approval_status==1)
                                           <span style="color:white;cursor:pointer;" onclick="remove({{ $value->id }},{{ $value->employee_id }})" class="badge badge-sm badge-danger">Remove</span>
                                           <span style="color:white;cursor:pointer;" onclick="update({{ $value->id }},{{ $value->employee_id }})" class="badge badge-sm badge-success">Update</span>
                                           @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr >
                                         <td colspan="7" class="text-center text-danger">No record found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>


                    </div>


                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebart -->
        <!-- ============================================================== -->
    </div>

<script>
    
    function remove(id,employee_id){
        var status=0;
        $.ajax({
            url: baseUrl+"/hdc/approveOrRejectAttendanceQuery",
            type: 'GET',
            data: {status:status,id:id,employee_id:employee_id},
            success: function (response){
                location.reload();
                
            }
        });
    }
    
    function update(param1,param2){
        var id=param1;
        var employee_id=param2;
        var query=$('#query_'+id).val();
        var attendance_date=$('#attendance_date_'+id).val();
        var clock_out=$('#clock_out_'+id).val();
        var clock_in=$('#clock_in_'+id).val();
        $.ajax({
            url: baseUrl+"/had/updateAttendanceRequestQueriesDetail",
            type: 'GET',
            data: {id:id,employee_id:employee_id,query:query,attendance_date:attendance_date,clock_out:clock_out,clock_in:clock_in},
            success: function (response){
                
               $.notify({
                    icon: "fa fa-check-circle",
                    message: "<b>Attendance request updated successfully</b>.",
                }, {
                    type: 'success',
                    timer: 3000
                });
                
            }
        });
    }
    
    function onAttendanceDateChange(date,employee_id){
        $.ajax({
            url: baseUrl+"/hdc/fetchAttendance",
            type: 'GET',
            data: {attendance_date:date,employee_id:employee_id},
            success: function (response){
                $('#clock_in').val(response.clock_in);
               $('#clock_out').val(response.clock_out);
            }
        });
    }
    
</script>



@endsection

