<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Tax;
$data ='';
$data3 ='';



foreach ($allsubDeparments as $value1):
    CommonHelper::companyDatabaseConnection(Input::get('m'));
    $allEmployees= Employee::where([['emp_sub_department_id','=',$value1->id],['status','=','1']])->get();

    $data.= '<div class="col-xs-12 col-sm-12 col-md-12 text-center" style="text-decoration: underline;">
            <h4><b>'.HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$value1->department_id)."&nbsp;&nbsp;==>&nbsp;&nbsp;".$value1->sub_department_name.'</b></h4>
        </div>';

    foreach ($allEmployees as $value2):

       // $check_criteria = CarPolicy::select('id','policy_name','start_salary_range','end_salary_range')->where([['designation_id','=',$value2->designation_id]]);
         CommonHelper::companyDatabaseConnection(Input::get('m'));
         $emp_data = Employee::select('id','emp_salary')->where([['id','=',$value2->id]])->first();
         CommonHelper::reconnectMasterDatabase();
        $check_criteria = Tax::select('id','tax_name','salary_range_from','salary_range_to')->where([['salary_range_from','<=',$emp_data->emp_salary],['salary_range_to','>=',$emp_data->emp_salary]]);


        if($check_criteria->count() > 0):
            $condition1 = 1;
            $check_criteria1 = $check_criteria->first();
            $start_salary = $check_criteria1->salary_range_from;
            $end_salary = $check_criteria1->salary_range_to;
            $tax_id = $check_criteria1->id;

           // $check_criteria2 = Employee::select('id')->where([['id','=',$value2->id]])->whereBetween('emp_salary', [$start_salary, $end_salary])->count();

        else:
            $tax_id=0;
            $condition1 = 0;
            $condition2 = 0;
            $designation_id  = 0;
            $start_salary = 0;
            $end_salary = 0;

        endif;


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $tax_user = Employee::select('id')->where([['id','=',$value2->id],['tax_id','>','0']])->count();
        CommonHelper::reconnectMasterDatabase();

        if($tax_user > '0'):
            $data3 = '<small id="assign'.$value2->id.'"> Tax Status: <span class="label label-success">Assigned</span></small>';
            $assign_or_cancel ='<li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="cancelTax('.$value2->id.','.$tax_id.')">Cancel</a>
                       </li>';

        else:
            $assign_or_cancel ='<li role="presentation text-center">
                       <a style="cursor:pointer;" class="edit-modal" onclick="assignTax('.$value2->id.','.$tax_id.')">Assign</a>
                       </li>';
            $data3 ='';

        endif;

        if($condition1 == '1'):
            $data2 = ' <span class="label label-success">Matched</span>';
            $buttonData ='
        <div class="dropdown text-center" >
                    <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" id="btn'.$value2->id.'">'.$assign_or_cancel.'</ul>
         </div>';
            $tax_salary = '<small> Tax Apply at : '.$start_salary."--".$end_salary.'</small><br>';
            $tax_name_data = '<small>Tax Name: <a id="label'.$value2->id.'" style="cursor:pointer;" onclick="viewTax('.$tax_id.')">'.$check_criteria1->tax_name.'</a><br></small>';

        else:

            $data2 = '<span class="label label-warning">Not Matched</span>';
            $buttonData = ' <div class="dropdown text-center"></div>';
            $tax_salary= '';
            $tax_name_data = '';
        endif;


        $data .= '

<div class="col-xs-6 col-sm-6 col-md-6">
    <div class="well well-sm">
        <div class="row">
            <div class="col-sm-4 col-md-4">
                <img style="width: 140px;height: 170px;" src="'.url($value2->img_path).'" alt="" class="img-rounded img-responsive" />
                '.$buttonData.'
            </div>
            <div class="col-sm-8 col-md-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">'.$data2.'</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="assign_status">
                        <h5>Name : '.$value2->emp_name.'</h5>
                        <small> Emp Salary : '.$value2->emp_salary.'</small><br>
                         '.$tax_name_data.'
                          '.$tax_salary.'
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
function assignTax(id,tax_id)
        {

         var url =  "'.url('/').'/hedbac/EditEmployeeTaxDetail";
         var m =  "'.Input::get('m').'";
             $.getJSON(url, { id:id,m:m,tax_id:tax_id} ,function(result){
                $.each(result, function(i, field){

                 $("#assign"+id).html(field.label);
                  $("#btn"+id).html(field.cancel_assign_btn);
                });
        })

        }
        function cancelTax(id,assign_id)
        {
         var policy_id = 0;
         var url =  "'.url('/').'/hedbac/EditEmployeeTaxDetail";
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