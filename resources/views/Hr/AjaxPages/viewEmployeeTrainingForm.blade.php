<style>
    .panel-heading {
        padding: 0px 15px;}
    .field_width {width: 120px;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  {  }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

    div.wrapper {
        overflow: scroll;
        max-height: 630px;

    }



</style>
<?php

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Helpers\HrHelper;
use App\Models\EmployeePromotion;

$m=Input::get('m');

$employee_training=DB::table('employee_training')->where([['training_id'],'=',$training_data])

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" id="TaxesList">
                        <thead>

                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Training Topic</th>
                            <th class="text-center">Hours</th>
                            <th  class="text-center">Training Date</th>
                            <th  class="text-center">Check <input id="check_all" type="checkbox" class="check_all"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 0;?>
                        <?php foreach($employees as $row1){?>
                        <?php
                        $counter++;
                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                        $employee_training=DB::table('employee_training')->where([['training_id','=',$training_data->id],['emp_id','=',$row1['emp_id']]])->count();
                        ?>
                        <tr>
                            <td class="text-center ">{{$counter}}</td>
                            <td class="hidden-print">
                                {{ $row1['emp_id'] }}
                                <input type="hidden" value="{{ $row1['emp_id'] }}" name="emp_id[]">
                            </td>
                            <td class="text-center">{{ $row1['emp_name'] }}
                                <input type="hidden" value="{{ $training_data->id }}" name="training_id[]">
                            </td>
                            <td class="text-center">
                                <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                     $promoted_designation = EmployeePromotion::select('designation_id','emp_id')->where([['emp_id','=',$row1['emp_id']],['status','=',1]])->orderBy('id', 'desc');
                                     if($promoted_designation->count() > 0):
                                       $emp_designation_id = $promoted_designation->value('designation_id');
                                       echo HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $emp_designation_id, 'id');
                                     else:
                                       $emp_designation_id = $row1['designation_id'];
                                       echo HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $emp_designation_id, 'id');
                                     endif;
                                    CommonHelper::reconnectMasterDatabase();
                                ?>
                                <input type="hidden" class="form-control" value="{{ $emp_designation_id }}" name="designation[]">
                            </td>
                            <td class="text-center">
                                {{ $training_data->topic_name }}
                                
                            </td>
                            <td class="text-center">
                                {{ $training_data->training_hours }}
                            </td>

                            <td class="text-center">
                                {{  HrHelper::date_format($training_data->training_date) }}
                            </td>
                            <td class="text-center">
                                <input @if($employee_training != 0) checked @endif type="checkbox" class="check" value='{{ $row1['emp_id'] }}_{{ $training_data->id }}' name="check[]">
                            </td>
                            
                           

                        </tr>

                        <?php

                        CommonHelper::reconnectMasterDatabase();

                        ?>

                        <?php } ?>
                        </tbody>
                    </table>

                   

                </div>
            </div>



        </div>
        <div class="row text-right">&nbsp;
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="submit" name="submit" class="btn btn-sm btn-success" />
            </div>
        </div>

    </div>`
</div>

<script>
    $("#check_all").change(function(){
        
       if(this.checked) {
           
            $('.check').prop('checked', true);
        
      
    } else {
      
      $('.check').prop('checked', false);
    }
    });
   
</script>



