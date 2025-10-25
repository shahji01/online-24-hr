
<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$data='';
$leaves_data = '';
$btn = '';
$remainingLeaves = '';
$total_leaves = $total_leaves->total_leaves;
$taken_leaves = ($taken_leaves->taken_leaves == '')? '0':$taken_leaves->taken_leaves;
$count = 1; 
$count_leaves = '0';
 $leaves_loop ='';
$no_of_leaves = '0';
$countUsedLeavess='0';
$countRemainingLeaves='0';
$leaveArray=['Annual','Sick','Casual','Maternity'];
$leave_type=$_GET['leave_type'];

foreach($leaves_policy as $val):
    if($val->leave_type_id == 1):
        $no_of_leaves = $val->no_of_leaves;
    elseif($val->leave_type_id == 3):
        $no_of_leaves=  $val->no_of_leaves;
    else:
        $no_of_leaves = $val->no_of_leaves;
    endif;
    $count_leaves+=$val->no_of_leaves ;

  
    $getUsedLeaves =DB::table('leave_application_data')
        ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
        ->where([['leave_application.emp_id','=',$leave_application_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
            ['leave_application.status', '=', '1'],
            ['leave_application.approval_status', '=', '2'],
			['leave_application.approval_status_lm', '=', '2']])
        ->sum('no_of_days');

    $countUsedLeavess +=$getUsedLeaves;

    if($val->leave_type_id == 1):
        $remaining = $val->no_of_leaves -$getUsedLeaves ;

    elseif($val->leave_type_id == 3):
        $remaining = $val->no_of_leaves - $getUsedLeaves;
    else:
        $remaining = $val->no_of_leaves-$getUsedLeaves;
    endif;
    
    if($remaining < 0):
        $remainingLeaves = "<span style='color:red;'>$remaining</span>";
    else:
        $countRemainingLeaves +=$remaining;
        $remainingLeaves=$remaining;
    endif;


    $leaves_data.= '<tr>
        <td class="text-center" style="color: green;padding: 0px;"><b>'.$count++.'</b></td>
        <td class="text-center" style="color: green;padding: 0px;"><b>'.HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id ).'</b></td>
        <td class="text-center" style="color: green;padding: 0px;"><b>'.$no_of_leaves.'</b></td>
        <td class="text-center" style="color: green;padding: 0px;"><b>'.$getUsedLeaves.'</b></td>
        <td class="text-center" style="color: green;padding: 0px;"><b>'.$remainingLeaves.'</b></td>
    </tr>';
endforeach;
$data.= '
        <div class="row">
            <table class="table table-bordered sf-table-list">
                <thead>
                    <tr>
                        <th style="background-color: #6a75e9;padding: 2px;">
                            <div style="display:block;float:left;width:50%; margin-top: 7px;">&nbsp;&nbsp;<span style="color:white;">Employee Leaves Detail</span></div>
                                <div class="text-right">
                                <span class="btn btn-success btn-sm" style="cursor: default">Taken Leaves ='.$taken_leaves.'</span>
                                <span class="btn btn-danger btn-sm" style="cursor: default">Remaining Leaves='.($total_leaves-$taken_leaves).'</span>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>';

$data.= '   <table class="table table-bordered sf-table-list">
            <thead>
                <tr>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Leaves Name</th>
                    <th class="text-center">No of leaves</th>
                    <th class="text-center">Used</th>
                    <th class="text-center">Remaining</th>
                  </tr>
             </thead>
              <tbody>'.$leaves_data.'</tbody>
             <tfoot>
                <tr>
                  <th class="text-center" style="color: #fff;background-color: #6a75e9;padding: 0px; "colspan="2"><b>Total</b></th>
                  <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;padding: 0px;"><b>'.$count_leaves.'</b></th>
                  <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;padding: 0px;"><b>'.$countUsedLeavess.'</b></th>
                  <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;padding: 0px;"><b>'.$countRemainingLeaves.'</b></th>

                  </tr>
             </tfoot>
    </table>';

if($leave_day_type == 1):
    $data.='
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive" style="min-height: 100px;">
                <table class="table table-bordered sf-table-list">
                     <thead>
                         <th>Employee Name</th>
                         <td>'.Hrhelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$leave_application_data->emp_id).'</td>
                     </thead>
                      <thead>
                         <th>Leave Type</th>
                         <td>'.$leaveArray[$leave_type-1].'</td>
                     </thead>
                     <thead>
                         <th>Day Type</th>
                         <td>'.$leave_day_type_label.'</td>
                     </thead>
                     <thead>
                         <th>No.of Days</th>
                         <td>'.$leave_application_data->no_of_days.'</td>
                     </thead>
                     <thead>
                         <th>Approved By</th>
                         <td>-</td>
                     </thead>

                </table>
            </div>
        </div>
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive" style="min-height: 100px;" >
                <table class="table table-bordered sf-table-list">
                     <thead>
                         <th>Employee Designation</th>
                         <td>'.$designation_name.'</td>
                     </thead>
                    <thead>
                        <th>Leave From </th>
                        <td>'.date("d-m-Y", strtotime($leave_application_data->from_date)).'</td>
                    </thead>
                    <thead>
                         <th>Leave Till </th>
                         <td>'.date("d-m-Y", strtotime($leave_application_data->to_date)).'</td>
                    </thead>
                      <thead>
                         <th>Created On</th>
                         <td>'.date("d-m-Y", strtotime($leave_application_data->date)).'</td>
                     </thead>
                     <thead>
                         <th>Approval Status</th>
                         <td>'.$approval_status.'</td>
                     </thead>
                </table>
            </div>
         </div>
         </div>
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                 <label>Reason :</label><br>
          '.$leave_application_data->reason.'
           <br><label>Address While On Leave :</label><br>
          '.$leave_application_data->leave_address.'
            </div>
        </div>

      </div>
      </div>';

elseif($leave_day_type == 2):
    $data.='
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive" style="min-height: 100px;">
                <table class="table table-bordered sf-table-list">
                     <thead>
                         <th>Employee Name</th>
                         <td>'.Hrhelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$leave_application_data->emp_id).'</td>
                     </thead>
                      <thead>
                         <th>Leave Type</th>
                         <td>'.$leave_type_name.'</td>
                     </thead>
                     <thead>
                         <th>Day Type</th>
                         <td>'.$leave_day_type_label.'</td>
                     </thead>
                     <thead>
                        <th>First / Second Half</th>
                        <td style="color:green;">'.ucfirst($leave_application_data->first_second_half).'</td>
                     </thead>
                     <thead>
                         <th>Approved By</th>
                         <td>-</td>
                     </thead>
                </table>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive" style="min-height: 100px;">
                <table class="table table-bordered sf-table-list">
                     <thead>
                         <th>Employee Designation</th>
                         <td>'.$designation_name.'</td>
                     </thead>
                    <thead>
                        <th>Leave Date </th>
                        <td>'.$leave_application_data->first_second_half_date.'</td>
                    </thead>
                    <thead>
                         <th>Created On</th>
                         <td>'.$leave_application_data->date.'</td>
                    </thead>
                     <thead>
                         <th>Approval Status</th>
                         <td>'.$approval_status.'</td>
                     </thead>
                     <thead>
                         <th>-</th>
                         <td>-</td>
                     </thead>
                </table>
            </div>
          </div>
         <label>Reason :</label><br>
          '.$leave_application_data->reason.'
           <br><label>Address While On Leave :</label><br>
          '.$leave_application_data->leave_address.'
      </div>
      </div>';

else:


    $data.='
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive" style="min-height: 100px;">
                <table class="table table-bordered sf-table-list">
                     <thead>
                         <th>Employee Name</th>
                         <td>'.Hrhelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$leave_application_data->emp_id).'</td>
                     </thead>
                      <thead>
                         <th>Leave Type</th>
                         <td>'.$leave_type_name.'</td>
                     </thead>
                     <thead>
                         <th>Day Type</th>
                         <td>'.$leave_day_type_label.'</td>
                     </thead>
                     <thead>
                         <th>Created On</th>
                         <td>'.$leave_application_data->date.'</td>
                     </thead>
                     <thead>
                         <th>Approved By</th>
                         <td>-</td>
                     </thead>

                </table>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive" style="min-height: 100px;">
                <table class="table table-bordered sf-table-list">
                    <thead>
                         <th>Employee Designation</th>
                         <td>'.$designation_name.'</td>
                    </thead>
                    <thead>
                        <th>Leave Time From </th>
                        <td>'.date('H : A', strtotime($leave_application_data->short_leave_time_from)).'</td>
                    </thead>
                    <thead>
                         <th>Leave Time Till </th>
                         <td>'.date('H : A', strtotime($leave_application_data->short_leave_time_to)).'</td>
                    </thead>
                      <thead>
                         <th>Leave Date</th>
                         <td>'.$leave_application_data->short_leave_date.'</td>
                     </thead>
                     <thead>
                         <th>Approval Status</th>
                         <td>'.$approval_status.'</td>
                     </thead>
                </table>
            </div>
          </div>
         <label>Reason :</label><br>
          '.$leave_application_data->reason.'
           <br><label>Address While On Leave :</label><br>
          '.$leave_application_data->leave_address.'
      </div>
      </div>';

endif;

echo json_encode(array('data'=>$data));



?>
