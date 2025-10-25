<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="<?=Input::get('m')?>">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row table-responsive">
                                    <table style="table-layout: fixed;" class="table table-bordered sf-table-list table-hover">
                                        <thead>
                                        <th class="text-center"> EMR-No </th>
                                        <th class="text-center"> Employee Name </th>
                                        </thead>

                                        <tbody>
                                        @if($trainingsData->participant_type == '1')
                                            <?php $emp_Array = explode(',',$trainingsData->participants); ?>
                                            @foreach($emp_Array as $value2)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $value2 }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value2,'emr_no') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        @if($trainingsData->participant_type == '2')
                                            <tr>
                                                <td class="text-center">--</td>
                                                <td class="text-center">
                                                    {{ $trainingsData->participants }}
                                                </td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="&nbsp;"></div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row table-responsive">
                                    <table style="table-layout: fixed;" class="table table-bordered sf-table-list table-hover">
                                        <thead>
                                        <th class="text-center">Regions</th>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$trainingsData->region_id) }}</td>
                                        <th class="text-center">Category</th>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$trainingsData->employee_category_id) }}</td>
                                        <th class="text-center">Location</th>
                                        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$trainingsData->location_id) }}</td>
                                        </thead>

                                        <thead>
                                        <th class="text-center">Training Date</th>
                                        <td class="text-center">{{ HrHelper::date_format($trainingsData->training_date) }}</td>
                                        <th class="text-center" colspan="2">Topic Name</th>
                                        <td class="text-center" colspan="2">{{ $trainingsData->topic_name }}</td>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php foreach ($TrainingCertificate as $value) { ?>
                                    <?php $url = url('/').Storage::url($value->certificate_uploading);?>
                                    <h4 class="text-center"><label>Training Certificate</label></h4>
                                    @if($value->file_type == 'doc' || $value->file_type == 'docx')
                                        <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                    @elseif($value->file_type == 'pdf')
                                        <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                    @elseif($value->file_type == 'jpeg' || $value->file_type == 'jpg' || $value->file_type == 'png' ||  $value->document_extension == 'gif')
                                        <img style="width: 100%;" src="{{$url}}">
                                    @endif
                                    <?php } ?>
                                </div>
                            </div>



                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
