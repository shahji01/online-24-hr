<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\CarPolicy;
$data ='';
$data3 ='';




    foreach ($allsubDeparments as $value1):
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allEmployees= Employee::where([['emp_sub_department_id','=',$value1->id],['status','=','1']])->get();

        $data.= '<div class="col-xs-12 col-sm-12 col-md-12 text-center" style="text-decoration: underline;">
            <h4><b>'.HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$value1->department_id)."&nbsp;&nbsp;==>&nbsp;&nbsp;".$value1->sub_department_name.'</b></h4>
        </div>';

    foreach ($allEmployees as $value2):
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $check_criteria = CarPolicy::select('id','policy_name','start_salary_range','end_salary_range')->where([['designation_id','=',$value2->designation_id]]);

        if($check_criteria->count() > 0):
            $condition1 = 1;
            $designation_id  = $value2->designation_id;
            $check_criteria1 = $check_criteria->first();
            $start_salary = $check_criteria1->start_salary_range;
            $end_salary = $check_criteria1->end_salary_range;
            $policy_id = $check_criteria1->id;
            $check_criteria2 = Employee::select('id')->where([['id','=',$value2->id]])->whereBetween('emp_salary', [$start_salary, $end_salary])->count();

        else:
            $policy_id=0;
            $condition1 = 0;
            $condition2 = 0;
            $designation_id  = 0;
            $start_salary = 0;
            $end_salary = 0;
            $policy_name = '';
        endif;



        $policy_user = Employee::select('id')->where([['id','=',$value2->id],['car_policy_id','>','0']])->count();

        if($policy_user > '0'):
            $data3 = '<small id="assign'.$value2->id.'"> Policy Status: <span class="label label-success">Assigned</span></small>';
            $assign_or_cancel ='<li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="cancelCarPolicy('.$value2->id.','.$policy_id.')">Cancel</a>
                       </li>';

        else:
            $assign_or_cancel ='<li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="assignCarPolicy('.$value2->id.','.$policy_id.')">Assign</a>
                       </li>';
            $data3 ='';

        endif;

        if($condition1 == '1' && $check_criteria2 == '1'):
        $data2 = ' <span class="label label-success">Matched</span>';
        $policy_name = '<a id="label'.$value2->id.'" style="cursor:pointer;" onclick="viewCarPolicy('.$policy_id.')">'.$check_criteria1->policy_name.'</a>';
        $buttonData ='
        <div class="dropdown text-center" >
                    <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" id="btn'.$value2->id.'">'.$assign_or_cancel.'</ul>
         </div>';

        else:

        $data2 = '<span class="label label-warning">Not Matched</span>';
        $policy_name = '-';
        $buttonData = ' <div class="dropdown text-center"></div>';
        endif;


$data .= '

<div class="col-xs-6 col-sm-6 col-md-6">
    <div class="well well-sm">
        <div class="row">
            <div class="col-sm-4 col-md-4">
                <img src="'.url($value2->img_path).'" alt="" class="img-rounded img-responsive" />
                '.$buttonData.'
            </div>
            <div class="col-sm-8 col-md-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">'.$data2.'</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="assign_status">
                        <h5>Name : '.$value2->emp_name.'</h5>
                        <small> Salary : '.$value2->emp_salary.'</small><br>
                        <small> Designation: '.HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$value2->designation_id).'</small><br>
                        <small> Policy Name : '.$policy_name.'</small><br>
                        '.$data3.'
                        <small id="assign'.$value2->id.'"></small>

                    </div>
                </div>
                <!-- Split button -->
            </div>
        </div>
    </div>
</div>';
endforeach;
endforeach;

$data .= '<script>
function assignCarPolicy(id,policy_id)
        {

         var url =  "'.url('/').'/hedbac/EditEmployeeCarPolicyDetail";
         var m =  "'.Input::get('m').'";
             $.getJSON(url, { id:id,m:m,policy_id:policy_id} ,function(result){
                $.each(result, function(i, field){

                 $("#assign"+id).html(field.label);
                  $("#btn"+id).html(field.cancel_assign_btn);
                });
        })

        }
        function cancelCarPolicy(id,assign_id)
        {
         var policy_id = 0;
         var url =  "'.url('/').'/hedbac/EditEmployeeCarPolicyDetail";
         var m =  "'.Input::get('m').'";

             $.getJSON(url, { id:id,m:m,policy_id:policy_id,assign_id:assign_id} ,function(result){
                $.each(result, function(i, field){
                  $("#assign"+id).html(field.label);
                  $("#btn"+id).html(field.cancel_assign_btn);
                });
        })

        }
</script>';

echo json_encode(array('data'=>$data));

?>