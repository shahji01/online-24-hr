<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
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

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="text-center"><h3>View Lates Detail</h3></div>
                            <div class="table-responsive">

                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">Attendance Date</th>
                                    <th class="text-center">Clock in</th>
                                    <th class="text-center">Clock out</th>


                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;
                                    //print_r($leave_application_request_list);
                                    if(!empty($atendance)):?>

                                    @foreach($atendance as $value)
                                        <tr>
                                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                            <td class="text-center">{{ $value['emp_name'] }}</td>
                                            <td class="text-center" >{{ HrHelper::date_format($value['attendance_date']) }}</td>
                                            <td class="text-center" style="color:red" >{{ $value['clock_in'] }}</td>
                                            <td class="text-center" >{{ $value['clock_out'] }}</td>

                                        </tr>


                                    @endforeach

                                    <?php else: ?>

                                    <tr><td colspan="5" style="color:red" class="text-center">Record Not Found !</td></tr>

                                    <?php endif; ?>
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



    $('.btn-danger').click(function () {

    });
    function LeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id)
    {

        //alert(user_id);
        $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
        var m = '<?= Input::get('m'); ?>';
        var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';

        $.ajax({
            url: url,
            type: "GET",
            data: {m:m,id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id},
            success:function(data) {

                $('#leave_area'+id).html('<hr>' +
                    '<div class="row text-center" style="background-color: gainsboro">' +
                    '<h4><b>Leave Application Details</b></h4>' +
                    '</div>' +
                    '<div class="row">&nbsp;</div>'+data);
                $('.ClsHide').css('display','none');


            }
        });
    }



    function approveAndRejectLeaveApplication(recordId,approval_status)
    {

        var check = (approval_status == 2) ? "Approve":"Reject";
        var url= '<?php echo url('/')?>/cdOne/approveAndRejectLeaveApplication';
        var companyId = '<?= Input::get('m'); ?>';

        if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
        {

            $.ajax({
                url: url,
                type: "GET",
                data: {companyId:companyId,recordId:recordId,approval_status:approval_status},
                success:function(data) {
                    location.reload();
                }
            });
        }
    }


    function RepostLeaveApplicationData(companyId,recordId)
    {
        if(confirm('Do you want to Repost Leave Applicaiton ?'))
        {
            repostOneTableRecords(companyId,recordId,'leave_application','status');

        }

    }

</script>

<script type="text/javascript" src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>

