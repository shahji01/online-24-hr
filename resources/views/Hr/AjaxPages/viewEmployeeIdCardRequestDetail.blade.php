<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

//$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}

$m = Input::get('m');

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$id = $employee_card_request->id;
$approval_status = $employee_card_request->approval_status;
$card_status = $employee_card_request->card_status

?>

<style>

    hr{border-top: 1px solid cadetblue}

    .img-circle {
        width: 150px;
        height: 150px;
        border: 2px solid #ccc;
        padding: 4px;
        border-radius: 50%;
        margin-bottom: 32px;
        margin-top: -78px;
        z-index: 10000000;
    }

    input[type="radio"]{
        width:30px;
        height:20px;
    }

</style>

<div class="panel-body">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div style="float: right;">
                            <!--@if ($card_status == 1)
                                <button type="button" class="btn btn-info btn-xs" onclick="printAndDeliverIdCard('<?php echo $m ?>','<?php echo $id;?>', '2', 'employee_card_request')"> Printed </button>
                            @endif

                            @if (($card_status == 1 || $card_status == 2) || $card_status != 3)
                                <button type="button" class="btn btn-success btn-xs" onclick="printAndDeliverIdCard('<?php echo $m ?>','<?php echo $id;?>', '3', 'employee_card_request')"> Delivered </button>
                            @endif -->

                        </div>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                        <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                        <div class="gudia-gap">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="hr-border" style="border: 1px solid #e5e5e5b0; margin-top: 89px;"></div>
                                    <img id="img_file_1" class="img-circle" src="<?= Storage::url($employee['img_path'])?>">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row table-responsive">
                                    <table style="table-layout: fixed;" class="table table-bordered sf-table-list ">
                                        <thead>
                                            <th class="text-center"> EMR-No </th>
                                            <th class="text-center"> Employee Name </th>
                                            <th class="text-center"> Department </th>
                                            <th class="text-center"> Designation </th>
                                        </thead>

                                        <tbody>
                                            <td class="text-center"><?=$employee_card_request['emr_no']?></td>
                                            <td class="text-center"><?=$employee['emp_name']?></td>
                                            <td class="text-center"><?=$sub_department['sub_department_name']?></td>
                                            <td class="text-center"><?=$designation['designation_name']?></td>
                                        </tbody>

                                        <thead>
                                            <th class="text-center"> CNIC </th>
                                            <th class="text-center"> Joining Date </th>
                                            <th class="text-center"> Posted At </th>
                                            <th class="text-center">-------</th>
                                        </thead>

                                        <tbody>
                                            <td class="text-center"><?=$employee['emp_cnic']?></td>
                                            <td class="text-center"><?=$employee['emp_joining_date']?></td>
                                            <td class="text-center"><?=$employee_card_request['posted_at']?></td>
                                            <td class="text-center">-------</td>
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <th colspan="4" class="text-center">FIR Copy</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div class="iframe-loading" style="background: url('<?=url('/assets/img/icon-loader.gif')?>') center center no-repeat;background-size: 200px 200px">
                                                        <?php $url = url('/').Storage::url($employee_card_request['fir_copy_path']); ?>
                                                        @if($employee_card_request['fir_copy_extension']== 'doc' || $employee_card_request['fir_copy_extension'] == 'docx')
                                                            <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                                        @elseif($employee_card_request['fir_copy_extension'] == 'pdf')
                                                            <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                                        @elseif($employee_card_request['fir_copy_extension'] == 'jpeg' || $employee_card_request['fir_copy_extension'] == 'jpg'
                                                         || $employee_card_request['fir_copy_extension'] == 'png' ||  $employee_card_request['fir_copy_extension']== 'gif')
                                                            <img style="width: 100%;" height="789" src="<?=$url?>">
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-center">Card Image</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div class="iframe-loading" style="background: url('<?=url('/assets/img/icon-loader.gif')?>') center center no-repeat;background-size: 200px 200px">
                                                        <?php  $url = url('/').Storage::url($employee_card_request['card_image_path']); ?>
                                                        @if($employee_card_request['card_image_extension']== 'doc' || $employee_card_request['card_image_extension'] == 'docx')
                                                            <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                                        @elseif($employee_card_request['card_image_extension'] == 'pdf')
                                                            <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                                         @elseif($employee_card_request['card_image_extension'] == 'jpeg' || $employee_card_request['card_image_extension'] == 'jpg'
                                                                 || $employee_card_request['card_image_extension'] == 'png' ||  $employee_card_request['card_image_extension']== 'gif')
                                                            <img style="width: 100%;" height="789" src="<?=$url?>">
                                                        @endif
                                                    </div>
                                                </td>
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
</div>

<script>

    function printAndDeliverIdCard(companyId, recordId, card_status, tableName){
        var companyId;
        var recordId;
        var tableName;
        var card_status;

        $.ajax({
            url: '<?php echo url('/')?>/cdOne/printAndDeliverIdCard',
            type: "GET",
            data: {companyId:companyId,recordId:recordId,tableName:tableName,card_status:card_status},
            success:function(data) {
                console.log(data);
                location.reload();
            },
            error: function() {
                console.log("error");
            }
        });

    }
</script>
