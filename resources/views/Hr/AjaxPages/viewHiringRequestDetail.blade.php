<?php
use App\Helpers\HrHelper;
$data='';
$data.='
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                        <th class="text-center col-sm-1">Job Title</th>
                        <td class="text-center col-sm-1">'.$hiringRequestDetail->RequestHiringTitle.'</td>
                    </thead>
                     <thead>
                         <th class="text-center col-sm-1">Sub Department </th>
                         <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$hiringRequestDetail->sub_department_id).'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Employment</th>
                         <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'job_type','job_type_name',$hiringRequestDetail->job_type_id).'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">Location</th>
                        <td class="text-center col-sm-1">'.$hiringRequestDetail->location.'</td>
                    </thead>
                     <thead>
                         <th class="text-center col-sm-1">Experience </th>
                         <td class="text-center col-sm-1">'.$hiringRequestDetail->experience.'</td>
                    </thead>
                    <thead>
                        <th class="text-center col-sm-1">Salary Range From</th>
                        <td class="text-center col-sm-1">'.$hiringRequestDetail->RequestHiringSalaryStart.'</td>
                    </thead>

                     <thead>
                         <th class="text-center col-sm-1">Age</th>
                         <td class="text-center col-sm-1">'.$hiringRequestDetail->RequestHiringAge.'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Gender</th>
                         <td class="text-center col-sm-1">'.($hiringRequestDetail->RequestHiringGender == 1 ? 'Male':'Female').'</td>
                   </thead>
                   <thead>
                        <th class="text-center col-sm-1">Approval Status</th>
                        <td class="text-center col-sm-1">'.$approval_status.'</td>
                    </thead>
                </table>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                        <th class="text-center col-sm-1">Designation</th>
                        <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$hiringRequestDetail->designation_id).'</td>
                    </thead>
                     <thead>
                         <th class="text-center col-sm-1">Qualification</th>
                         <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'qualification','qualification_name',$hiringRequestDetail->qualification_id).'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Career Level</th>
                         <td class="text-center col-sm-1">'.$hiringRequestDetail->career_level.'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Shift Type</th>
                         <td class="text-center col-sm-1">'.HrHelper::getMasterTableValueById(Input::get('m'),'shift_type','shift_type_name',$hiringRequestDetail->shift_type_id).'</td>
                    </thead>
                    <thead>
                         <th class="text-center col-sm-1">Salary Range End</th>
                         <td class="text-center col-sm-1">'.$hiringRequestDetail->RequestHiringSalaryEnd.'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">Apply Before Date</th>
                        <td class="text-center col-sm-1">'.HrHelper::date_format($hiringRequestDetail->apply_before_date).'</td>
                     </thead>
                     <thead>
                         <th class="text-center col-sm-1">Posted on Date</th>
                         <td class="text-center col-sm-1">'.HrHelper::date_format($hiringRequestDetail->date).'</td>
                    </thead>
                     <thead>
                         <th class="text-center col-sm-1">Created By</th>
                         <td class="text-center col-sm-1">'.$hiringRequestDetail->username.'</td>
                    </thead>
                     <thead>
                        <th class="text-center col-sm-1">Status</th>
                        <td class="text-center col-sm-1">'.$status.'</td>
                    </thead>

                </table>
            </div>
          </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table table-bordered sf-table-list">
                    <thead>
                         <th class="text-center">Job Description</th>
                    </thead>
                    <thead>
                        <td class="text-center">'.$hiringRequestDetail->RequestHiringDescription.'</td>
                    </thead>
        </table>
</div>
</div>';

$data.=' ';
echo json_encode(array('data'=>$data));



?>
