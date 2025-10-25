<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>


<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="HrReport">
                        @if($trainingsData->count() > 0)
                            <thead>
                            <th class="text-center col-sm-1">S.No</th>
                            <th class="text-center">Region</th>
                            <th class="text-center">Category</th>
                            <th>Trainer Name</th>
                            <th>Certificate Number</th>
                            <th class="text-center">Participants</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Topic</th>
                            <th class="text-center">Training Date</th>
                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($trainingsData->get() as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$value->region_id) }}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$value->employee_category_id) }}</td>
                                    <td class="text-center">{{$value->trainer_name}}</td>
                                    <td class="text-center">{{$value->certificate_number}}</td>
                                    <td class="text-center">
                                        <?php
                                        if($value->participant_type == '1'):
                                            if($value->participants != 'All'):
                                                $emp_Array = explode(',',$value->participants);

                                                foreach($emp_Array as $value2):
                                                    echo HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value2,'emr_no').",";
                                                endforeach;
                                                else:
                                                    echo $value->participants;
                                                endif;
                                        else:
                                            echo $value->participants;
                                        endif;

                                        ?>
                                    </td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$value->location_id) }}</td>
                                    <td class="text-center">{{ $value->topic_name }}</td>
                                    <td class="text-center">{{ HrHelper::date_format($value->training_date) }}</td>

                                </tr>
                            @endforeach
                            @else
                                <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="14">Record Not Found !</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

