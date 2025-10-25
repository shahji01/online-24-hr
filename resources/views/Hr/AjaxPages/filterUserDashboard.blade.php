<?php
$id=Input::get('emp_id');
if(Input::get('filter') != 'user'):

    $emp_id = Auth::user()->emp_id;
else:

    $emp_id = Input::get('emp_id');
endif;
$accType = Auth::user()->acc_type;



?>

<?php
use App\Models\Employee;
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;
use App\Models\EmployeeEducationalData;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\EmployeePromotion;
use Carbon\Carbon;

use App\Models\Policies;





$counter = 1;
$countersss = 1;
$c=1;
$counting = 0;
$countUsedLeavess = 0;
$countRemainingLeaves = 0;
$countRemainingCasualLeaves=0;
$countRemainingSickLeaves=0;
$salarys = array();
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$reasons=DB::table('reasons')->where('status',1)->get();
if(Input::get('m') == ''):
?>


<style>


    /*btn icons css start*/


    /*btn icons css end*/

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {

        color: rgb(79, 84, 103) !important;
        background-color: rgb(137, 113, 234)!important;
        border-color: rgb(137, 113, 234)!important;
        color: white!important;
    }
    html *{
        -webkit-font-smoothing: antialiased;
    }
    h3{
        font-size: 25px !important;
        margin-top: 20px;
        margin-bottom: 10px;
        line-height: 1.4em !important;
    }


    p {
        font-size: 14px;
        margin: 0 0 10px !important;
        font-weight: 300;
    }

    small {
        font-size: 75%;
        color: #777;
        font-weight: 400;
    }

    .container .title{
        color: #3c4858;
        text-decoration: none;
        margin-top: 30px;
        margin-bottom: 25px;
        min-height: 32px;
    }

    .container .title h3{
        font-size: 25px;
        font-weight: 300;
    }

    div.card {

        border: 0;
        margin-bottom: 30px;
        margin-top: 30px;
        border-radius: 6px;
        color: rgba(0,0,0,.87);
        background: #fff;
        width: 100%;
        box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    }

    div.card.card-plain {

        background: transparent;
        box-shadow: none;
    }
    div.card .card-header {

        border-radius: 3px;
        padding: 1rem 15px;
        margin-left: 15px;
        margin-right: 15px;
        margin-top: -30px;
        border: 0;
        background: linear-gradient(60deg,#eee,#bdbdbd);
    }

    .card-plain .card-header:not(.card-avatar) {

        margin-left: 0;
        margin-right: 0;
    }

    .div.card .card-body{

        padding: 15px 30px;
    }

    div.card .card-header-primary {

        background: linear-gradient(60deg,#ab47bc,#7b1fa2);
        box-shadow: 0 5px 20px 0 rgba(0,0,0,.2), 0 13px 24px -11px rgba(156,39,176,.6);
    }

    div.card .card-header-danger {

        background: linear-gradient(60deg,#ef5350,#d32f2f);
        box-shadow: 0 5px 20px 0 rgba(0,0,0,.2), 0 13px 24px -11px rgba(244,67,54,.6);
    }


    .card-nav-tabs .card-header {
        margin-top: -30px!important;
    }

    .card .card-header .nav-tabs {

        padding: 0;
    }

    .nav-tabs {

        border: 0;
        border-radius: 3px;
        padding: 0 15px;
    }

    .nav {

        display: flex;
        flex-wrap: wrap;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
    }

    .nav-tabs .nav-item {

        margin-bottom: -1px;
    }

    .nav-tabs .nav-item .nav-link.active {

        background-color: hsla(0,10%,100%,.2);
        transition: background-color .3s .2s;
    }

    .nav-tabs .nav-item .nav-link{

        border: 0!important;
        color: #fff!important;
        font-weight: 500;
    }

    .nav-tabs .nav-item .nav-link {

        color: #fff;
        border: 0;
        margin: 0;
        border-radius: 3px;
        line-height: 24px;
        text-transform: uppercase;
        font-size: 12px;
        padding: 10px 15px;
        background-color: transparent;
        transition: background-color .5s 2s;
    }

    .nav-link{

        display: block;
    }

    .nav-tabs .nav-item .material-icons {

        margin: -1px 5px 0 0;
        vertical-align: middle;
    }

    .nav .nav-item {

        position: relative;
    }


</style>



<div class="text-center">
    <br/><br/><br/><br/>
    <br/><br/><br/><br/><br/><br/>
    <h2> Please Select Company From C.List Menu !</h2>
    <br/><br/><br/><br/><br/><br/>
    <br/><br/><br/><br/><br/>
</div>




<?php else:
CommonHelper::companyDatabaseConnection(Input::get('m'));
$employee = Employee::where([['emp_id','=',$emp_id],['status','=',1]]);

$employeeName = Employee::where([['emp_id','=',$employee->value('reporting_manager')],['status','=',1],['reporting_manager','!=','0']]);
$employee_education = EmployeeEducationalData::where([['emp_id','=',$emp_id],['status','=',1]])->get();

if($employeeName->value('emp_name') != ''){
    $reporting_manager = $employeeName->value('emp_name');
}
else{
    $reporting_manager = '--';
}

$EmployeePromotion = EmployeePromotion::where([['emp_id','=',$emp_id],['status','=',1]])->orderBy('emp_id')->orderBy('id','desc');
$team_lead_exists = Employee::where([['reporting_manager','=',Input::get('emp_id')],['status','=',1]]);





$trainings=DB::table('trainings')->where('status',1)->get();

CommonHelper::reconnectMasterDatabase();

?>

<?php

CommonHelper::companyDatabaseConnection(Input::get('m'));
$holidays=DB::table('holidays')->where('status',1)->get();
CommonHelper::reconnectMasterDatabase();

?>



<div class="row">
    <div class="col-sm-12" id="userinfo">
        <div class="text-center">
            <?php if($employee->value('img_path') != ''){ ?>
            <img src="<?php echo url('storage/'.$employee->value('img_path')) ?>" class="avatar img-circle img-thumbnail img-responsive" style="width:20%;" alt="avatar">
            <?php } else{ ?>
            <img id="img_file_1" class="avatar img-circle img-thumbnail" style="width:20%;height:auto;" src="<?= url('storage/app/uploads/employee_images/user-dummy.png')?>">
            <?php } ?>

        </div>
    </div>
    <div class="col-sm-12">&nbsp;
        <div class="text-center">
            <h3 id="username"><strong><?php echo $employee->value('emp_name') ?></strong></h3>
            <pre><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$employee->value('emp_sub_department_id')); ?> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee->value('designation_id'))?></pre>
            <pre><?php echo $employee->value('professional_email') ?></pre>
        </div>
    </div>
</div>





<div class="row">
    <div class="col-sm-12">
        <!-- Tabs on Plain Card -->
        <div class="card card-nav-tabs card-plain">

            <div class="card-header card-header-danger" style="padding-left: 7%;">
                <!-- colors: "header-primary", "header-info", "header-success", "header-warning", "header-danger" -->
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs" data-tabs="tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#UserInfo" data-toggle="tab">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#Info" data-toggle="tab">Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#Leaves" data-toggle="tab">Leaves</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#mineLeaves" data-toggle="tab">Leaves (Mine)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#pendingLeaves" data-toggle="tab">Leaves (Team)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#Education" data-toggle="tab">Education</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#Trainings" data-toggle="tab">Trainings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#JobHistory" data-toggle="tab">History</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#Policies" data-toggle="tab">Policies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#team" data-toggle="tab">Team</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content container-fluid">
                <div class="tab-pane  fade in active show" id="UserInfo">
                    <br>
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="row">

                                <div class="col-sm-12">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center shadow" style="padding:7px;">
                                                    {{--<i class="fa fa-bell fa-fw" style="color:red"></i>--}}
                                                    <b style="font-size: 17px;"> Upcoming Birthday's</b>
                                                </div>
                                                <hr>
                                                <div class="panel-body">
                                                    <div class="list-group">
                                                        @if(count($upcoming_birthdays_detail) > 0)
                                                            @foreach($upcoming_birthdays_detail as $upcoming_birthday)
                                                                <p>
                                                                    <b><span style="color: red;" class="fas fa-gift fa"></span> {{$upcoming_birthday->emp_name}} <small><b>( {{ CommonHelper::changeDateFormatWithoutYear($upcoming_birthday->emp_date_of_birth) }} ) </b></small></b>
                                                                </p>
                                                            @endforeach
                                                        @else
                                                            <p class="text-center"  style="padding: 20px;">
                                                                <b><span style="color: red;">No record found</span></b>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-sm-4">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center shadow" style="padding:7px;">

                                                    <b style="font-size: 17px">Work Anniversary's</b>
                                                </div>
                                                <hr>
                                                <div class="panel-body">
                                                    <div class="list-group">
                                                        @if(count($empWorkAnvs) > 0)
                                                            @foreach($empWorkAnvs as $empWorkAnv)
                                                               <?php

                                                             $emp_join_date = Carbon::parse($empWorkAnv->emp_joining_date);

                                                             $date = Carbon::today()->addDays(10);
                                                             $Date = Carbon::parse($date->format('Y-m-d'));
                                                             $y = $emp_join_date->diffInYears($Date);
                                                             //$m = $emp_join_date->diffInMonths($Date);
                                                                ?>
                                                                <p>
                                                                    <b><span style="color: red;" class="fas fa-gift fa"></span>  {{$empWorkAnv->emp_name}} <small><b>( {{ CommonHelper::changeDateFormatWithoutYear($empWorkAnv->emp_joining_date) }} ) </b></small></b><small><?php echo $y!="1"? $y." years":$y." year" ?>  </small>
                                                                </p>

                                                            @endforeach
                                                        @else
                                                            <p class="text-center" style="padding: 20px;">
                                                                <b><span style="color: red;">No record found</span></b>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-4">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center shadow" style="padding:7px;">

                                                    <b style="font-size: 17px">Employees Of The Month</b>
                                                </div>
                                                <hr>
                                                <div class="panel-body">
                                                    <div class="list-group">
                                                        @if($EmployeeOfTheMonth->count() > 0)
                                                            @foreach($EmployeeOfTheMonth->get() as $Eom)
                                                                <p style="padding: 5px;">
                                                                    <span style="color: red;" class="fas fa-gift fa"></span>
                                                                    Emp Name: <b>{{HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$Eom->emp_id) }}</b>
                                                                    Remarks : <b>{{$Eom->remarks}}</b>
                                                                </p>
                                                            @endforeach
                                                        @else
                                                            <p class="text-center"  style="padding: 20px;">
                                                                <b><span style="color: red;">No record found</span></b>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                </div>



                            </div>
                        </div>
                    </div>


                </div>
                <div class="tab-pane fade in" id="Info">
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-sm mb-0 table-bordered table-striped" id="TaxesList">
                                <tbody>

                                <tr>
                                    <td  class="">1</td>
                                    <th class="">Emp ID </th>
                                    <th class=""><?php echo $employee->value('emp_id') ?></th>
                                </tr>
                                <tr>
                                    <td  class="">2</td>
                                    <th class="">Emp Name</th>
                                    <th class="emp_name_td"><?php echo $employee->value('emp_name') ?></td>
                                </tr>

                                <tr>
                                    <td  class="">3</td>
                                    <th class="">Department</th>
                                    <th class="emp_dep_td"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$employee->value('emp_sub_department_id')); ?></th>
                                </tr>
                                <tr>
                                    <td class="">4</td>
                                    <th class="">Designation</th>
                                    <?php
                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $promoted_designation = EmployeePromotion::select('designation_id','emp_id')->where([['emp_id','=',$employee->value('emp_id')],['status','=',1]])->orderBy('id', 'desc');
                                    if($promoted_designation->count() > 0):
                                        $emp_designation_id = $promoted_designation->value('designation_id');
                                    else:
                                        $emp_designation_id = $employee->value('designation_id');
                                    endif;
                                    CommonHelper::reconnectMasterDatabase();
                                    ?>
                                    <th class="emp_des_td"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$emp_designation_id)?></th>
                                </tr>
                                <tr>
                                    <td  class="">5</td>
                                    <th class="">Reporting To</th>
                                    <th class="emp_lm_td"><?php echo $reporting_manager ?></th>
                                </tr>
                                <tr>
                                    <td  class="">6</td>
                                    <th class="">Cell # </th>
                                    <th class="emp_cell_td">
                                        <?php
                                            substr($employee->value('emp_contact_no'), 0, 1);
                                            if($employee->value('emp_contact_no')[0] != '0'){
                                                echo '0'.$employee->value('emp_contact_no');
                                            }
                                            else{
                                                $employee->value('emp_contact_no');
                                            }

                                        ?>
                                    </th>

                                </tr>
                                <tr>
                                    <td  class="">7</td>
                                    <th class="">Official Email</th>
                                    <th class="emp_email_td"><?php echo $employee->value('professional_email') ?></th>
                                </tr>
                                <tr>
                                    <td  class="">8</td>
                                    <th class="">DOJ</th>
                                    <th class=""><?php echo  date("d-M-Y", strtotime($employee->value('emp_joining_date'))); ?></th>
                                </tr>
                                <tr>
                                    <td  class="">9</td>
                                    <th class="">DOB</th>
                                    <th class="emp_dob_td"><?php echo  date("d-M-Y", strtotime($employee->value('emp_date_of_birth'))) ?></th>
                                </tr>

                                </tbody>
                            </table>

                        </div>
                        <div class="col-sm-6">
                            <p class="bg-success text-center" id="response"></p>
                            <p class="bg-danger text-white text-center" id="error_cnic"></p>
                            <form class="form" id="basic_info_form">
                                <div id="ajax_form"></div>
                                <div id="without_ajax_form">

                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label for="name"><h4>Name</h4></label>
                                            <input type="text" class="form-control requiredField" name="name" id="name" placeholder="Name" value="<?php echo $employee->value('emp_name') ?>" >
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="p_email"><h4>Personal Email</h4></label>
                                            <input type="email" class="form-control requiredField" name="p_email" id="p_email" placeholder="Personal Email" value="<?php echo $employee->value('professional_email') ?>">
                                        </div>
                                    </div>


                                    <div class="row form-group">

                                        <div class="col-sm-6">
                                            <label for="cnic"><h4>Cnic</h4></label>
                                            <input type="text" class="form-control requiredField cnicExistMessage" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" maxlength="15" placeholder="CNIC Number" name="cnic" id="cnic" value="<?php echo $employee->value('emp_cnic') ?>" />
                                            <span style="font-size:13px;color:red;font-weight: bold;" id="cnicExistMessage"></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="phone"><h4>Phone</h4></label>
                                            <span class="input-group-prepend">
                                                    <span class="input-group-text">+92</span>
                                                    <span class="input-group-area" style="width:100%;">
                                                        <?php substr($employee->value('emp_contact_no'), 0, 1);
                                                        $contactNo=ltrim($employee->value('emp_contact_no'), '0');
                                                        ?>
                                                        <input type="text" id="phone" value="<?php echo $contactNo ?>" name="phone" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorlandlineNumberOne','1','e')" class="form-control" placeholder="3xxxxxxxxx" maxlength="10" onkeypress="return isNumber(event)" />
                                                    </span>
                                                    </span>

                                            <span style="color:red;font-size:13px;font-weight: bold;" class="errorlandlineNumberOne" ></span>
                                        </div>
                                    </div>


                                    <div class="row form-group">

                                        <div class="col-sm-12">
                                            <label for="email"><h4>Dob</h4></label>
                                            <input type="date" class="form-control" name="dob" id="dob" placeholder="DOB" value="<?php echo $employee->value('emp_date_of_birth') ?>">
                                        </div>
                                        <div class="col-sm-12 text-right" style="margin-top: 15px;">
                                            <br>
                                            <button type="submit" class="btn btn-md btn-success" id="btn_disable" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Loading...">Save</button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>


                </div>
                <div class="tab-pane fade in" id="Leaves">
                    <br>

                    <?php
                    if($WithoutLeavePolicy[0] == 'Select Leave Policy'){
                    ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                </button>
                                <span class="glyphicon glyphicon-record"></span> <strong>Warning Message</strong>
                                <hr class="message-inner-separator">
                                <p>
                                    Please Select Leave Policy.</p>
                            </div>
                        </div>
                    </div>
                    <?php

                    }
                    else{
                    ?>


                    <?php echo Form::open(array('url' => 'had/addTaxesDetail','id'=>'EOBIform'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="company_id" value="<?php echo Input::get('m'); ?>">
                    <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $employee->value('emp_id'); ?>">


                    <div class="panel">
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-12">


                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th style="background-color: #6a75e9;">
                                                <div style="">
                                                    <div style="display:block;float:left;width:50%; margin-top: 6px;">&nbsp;&nbsp;<span style="color:white;">LEAVES BALANCE</span></div>
                                                    <div class="text-right">
                                                        <?php
                                                        $total_leaves = $total_leaves->total_leaves;
                                                        $taken_leaves = $taken_leaves->taken_leaves;?>
                                                        <span class="btn btn-success btn-sm" style="cursor: default">Taken Leaves = <?= ($taken_leaves == '')? '0': $taken_leaves ?></span>
                                                        <span class="btn btn-danger btn-sm" style="cursor: default">Remaining Leaves= <?=($total_leaves-$taken_leaves)?></span>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>S No#</th>
                                            <th>Leaves Name</th>
                                            <th class="text-center">No of leaves</th>
                                            <th class="text-center">Used</th>
                                            <th class="text-center">Remaining</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count =0 ;
                                        $count_leaves = '0';
                                        ?>
                                        @foreach($leaves_policy as $val)
                                            <?php
                                            $count_leaves+=$val->no_of_leaves ;
                                            $count++;
                                            ?>
                                            <tr>
                                                <td style="color: green;"><b>{{ $count }}</b></td>
                                                <td style="color: green;"><b>{{ HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )}}</b></td>
                                                <td class="text-center" style="color: green;"><b>
                                                        <?php

                                                        if($val->leave_type_id == 1):
                                                            echo $val->no_of_leaves;
                                                        elseif($val->leave_type_id == 3):
                                                            echo $val->no_of_leaves;
                                                        else:
                                                            echo $val->no_of_leaves;
                                                        endif;
                                                        ?>
                                                    </b>
                                                </td>
                                                <td class="text-center">
                                                    <?php

                                                    //CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                    $getUsedLeaves =DB::table('leave_application_data')
                                                        ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                        ->where([['leave_application.emp_id','=',$emp_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
                                                            ['leave_application.status', '=', '1'],
                                                            ['leave_application.approval_status_lm', '=', '2'],
                                                            ['leave_application.approval_status', '=', '2']])
                                                        ->sum('no_of_days');
                                                    $countUsedLeavess +=$getUsedLeaves;
                                                    echo $getUsedLeaves;
                                                    //CommonHelper::reconnectMasterDatabase();

                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if($val->leave_type_id == 1):
                                                        $remaining = $val->no_of_leaves-$getUsedLeaves;

                                                    elseif($val->leave_type_id == 3):
                                                        $remaining = $val->no_of_leaves-$getUsedLeaves;
                                                        $countRemainingCasualLeaves=$val->no_of_leaves -$getUsedLeaves;
                                                    else:
                                                        $remaining = $val->no_of_leaves-$getUsedLeaves;
                                                        $countRemainingSickLeaves=$val->no_of_leaves -$getUsedLeaves;
                                                    endif;



                                                    if($remaining < 0):
                                                        echo "<span style='color:red;'>$remaining</span>";
                                                    else:
                                                        $countRemainingLeaves +=$remaining;
                                                        echo $remaining;
                                                    endif;
                                                    ?>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th class="text-right"  style="color: #fff;background-color: #6a75e9;" colspan="2"><b>Total</b></th>
                                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;"><b>{{ $count_leaves }}</b></th>
                                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;"><?php print_r($countUsedLeavess)?></th>
                                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;"><?=$countRemainingLeaves?></th>


                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div style="color: #fff;background-color: #6a75e9;">
                                        <b>SELECT LEAVE TYPE</b>
                                        &ensp;
                                        <span class="glyphicon glyphicon-arrow-down"></span>
                                    </div>
                                    <div class="btn-group" data-toggle="buttons" style="padding: 4px;">
                                        @foreach($leaves_policy as $val)
                                            <?php $leaveName = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )?>

                                            <label style="border:1px solid #fff;" class="btn btn-success" onclick="viewEmployeeLeavesDetail('<?=$val->id?>','{{ $val->no_of_leaves }}','<?php echo $val->leave_type_id ?>')">
                                                <input required="required" autocomplete="off" type="radio" name="leave_type" id="leave_type" class="requiredField" value="<?=$val->leave_type_id?>">
                                                {{ $leaveName }}
                                                <span class="glyphicon glyphicon-ok"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="" id="leavesData"></div>
                            <div class="" id="leave_days_area"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Reason For Leave</label>
                                    <select class="form-control reasonsList" name="reason" id="reason" onchange="check_reason(this.value)">
                                        <option value="">Select</option>
                                        @foreach($reasons as $r)
                                            <option value="{{ $r->id }}">{{ $r->reason }}</option>
                                        @endforeach
                                    </select>
                                    <span id="reason_area"></span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Address While on Leaave</label>
                                    <textarea id="leave_address" class="form-control requiredField">-</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <span id="errorMesg" style="color:red"></span>
                            <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-sm btn-success">Submit</button>
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                        </div>
                    </div>
                    <?php echo Form::close();?>
                    <?php
                    }
                    ?>
                </div>
                <div class="tab-pane fade in" id="mineLeaves">
                    <br>
                    <?php

                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                    //$empId = Employee::select('emp_id')->where([['reporting_manager','=',$emp_id],['status','=',1]])->get()->value('emp_id');

                    CommonHelper::reconnectMasterDatabase();

                    $leave_application_request_list = DB::table('leave_application')
                        ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                        ->select('leave_application.*')
                        ->where('leave_application.emp_id','=',$id)
                        ->where('leave_application.view','=','yes')
                        ->orderBy('leave_application.approval_status')
                        ->get();
                    $m = Input::get('m');
                    $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
                    $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
                    $companies = DB::table('company')->where('status',1)->get();
                    ?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">

                                                <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                                                <div class="lineHeight">&nbsp;</div>
                                                <div class="panel">
                                                    <div class="panel-body" id="PrintLeaveApplicationRequestList">

                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive LeavesData">

                                                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                                                                        <thead>
                                                                        <th class="text-center">S No.</th>
                                                                        <th class="text-center">Emp ID</th>
                                                                        <th class="text-center">Emp Name</th>
                                                                        <th class="text-center">Leave Type</th>
                                                                        <th class="text-center">Day Type</th>
                                                                        <th class="text-center">Date</th>
                                                                        <th class="text-center">Approval Status(HR)</th>
                                                                        <th class="text-center">Approval Status(GM)</th>
                                                                        <th class="text-center">Status</th>
                                                                        <th class="text-center hidden-print">Action</th>

                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if(count($leave_application_request_list) != '0'){ ?>
                                                                        @foreach($leave_application_request_list as $value)
                                                                            <?php
                                                                            CommonHelper::companyDatabaseConnection($m);
                                                                            $emp_data =  Employee::where([['emp_id','=',$value->emp_id],['status','=',1]]);
                                                                            CommonHelper::reconnectMasterDatabase();
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $c++ }}</span></td>
                                                                                <td class="text-center"><?php echo $emp_data->value('emp_id') ?></td>
                                                                                <td class="text-center"><?php echo $emp_data->value('emp_name') ?></td>
                                                                                <td class="text-center" style="color:green">{{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->leave_type)}}</td>
                                                                                <td class="text-center" style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</td>
                                                                                <td class="text-center" >{{ HrHelper::date_format($value->date) }}</td>
                                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>
                                                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                                                <td class="text-center hidden-print">
                                                                                    <div class="dropdown">
                                                                                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                                                                            <i class="fa fa-chevron-down" ></i></button>
                                                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                                            @if($accType=='client' || $emp_data->value('emp_id') == Auth::user()->emp_id)
                                                                                            <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?=$m?>')">
                                                                                                <a class="delete-modal btn" >
                                                                                                    Edit
                                                                                                </a>
                                                                                            </li>
                                                                                            @endif
                                                                                            <li role="presentation" class="actionsLink" onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_id?>','<?=$m?>')">
                                                                                                <a class="delete-modal btn" >
                                                                                                    View
                                                                                                </a>
                                                                                            </li>
                                                                                            @if($value->status == 2)
                                                                                                <li role="presentation" class="actionsLink" onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                                                                                    <a class="delete-modal btn" >
                                                                                                        Refresh
                                                                                                    </a>
                                                                                                </li>
                                                                                            @else
                                                                                                <li role="presentation" class="actionsLink" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" >
                                                                                                    <a class="delete-modal btn" >
                                                                                                        Delete
                                                                                                    </a>
                                                                                                </li>
                                                                                            @endif

                                                                                        </ul>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="10">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                                        <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>

                                                                        @endforeach
                                                                        <?php } else { ?>
                                                                        <tr>
                                                                            <td colspan="9" class="text-center text-danger"><strong>No Reord Found</strong></td>
                                                                        </tr>
                                                                        <?php } ?>
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
                            </div>
                        </div>
                    </div>


                </div>
                <div class="tab-pane fade in" id="pendingLeaves">
                    <br>
                    <?php
                    $line_manager_employees = [];
                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                    $line_manager_emp = Employee::select('emp_id')->where([['reporting_manager','=',$emp_id],['status','=',1]])->get();
                    foreach($line_manager_emp as $value){
                        $line_manager_employees[] = $value->emp_id;
                    }
                    CommonHelper::reconnectMasterDatabase();

                    $leave_application_request_list = DB::table('leave_application')
                        ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                        ->select('leave_application.*')
                        ->whereIn('leave_application.emp_id',$line_manager_employees)
                        ->where('leave_application.view','=','yes')
                        ->orderBy('leave_application.approval_status')
                        ->get();
                    $m = Input::get('m');
                    $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
                    $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
                    $companies = DB::table('company')->where('status',1)->get();
                    ?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">

                                                <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                                                <div class="lineHeight">&nbsp;</div>
                                                <div class="panel">
                                                    <div class="panel-body" id="PrintLeaveApplicationRequestList">

                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive LeavesData">

                                                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                                                                        <thead>
                                                                        
                                                                        <th class="text-center">S No. </th>
                                                                        <th class="text-center">Emp ID</th>
                                                                        <th class="text-center">Emp Name</th>
                                                                        <th class="text-center">Leave Type</th>
                                                                        <th class="text-center">Day Type</th>
                                                                        <th class="text-center">Date</th>
                                                                        <th class="text-center">Approval Status(HR)</th>
                                                                        <th class="text-center">Approval Status(GM)</th>
                                                                        <th class="text-center">Status</th>
                                                                        <th class="text-center hidden-print">Action</th>

                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if(count($leave_application_request_list) != '0'){ ?>
                                                                        @foreach($leave_application_request_list as $value)
                                                                            <?php
                                                                            CommonHelper::companyDatabaseConnection($m);
                                                                            $emp_data =  Employee::where([['emp_id','=',$value->emp_id],['status','=',1]]);
                                                                            CommonHelper::reconnectMasterDatabase();
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $countersss++ }}</span></td>
                                                                                <td class="text-center"><?php echo $emp_data->value('emp_id') ?></td>
                                                                                <td class="text-center"><?php echo $emp_data->value('emp_name') ?></td>
                                                                                <td class="text-center" style="color:green">{{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->leave_type)}}</td>
                                                                                <td class="text-center" style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</td>
                                                                                <td class="text-center" >{{ HrHelper::date_format($value->date) }}</td>
                                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>
                                                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                                                <td class="text-center hidden-print">
                                                                                    <div class="dropdown">
                                                                                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                                                                            <i class="fa fa-chevron-down" ></i></button>
                                                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                                            @if($accType=='client')
                                                                                            <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?=$m?>')">
                                                                                                <a class="delete-modal btn" >
                                                                                                    Edit
                                                                                                </a>
                                                                                            </li>
                                                                                            @endif
                                                                                            <li role="presentation" class="actionsLink" onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_id?>','<?=$m?>')">
                                                                                                <a class="delete-modal btn" >
                                                                                                    View
                                                                                                </a>
                                                                                            </li>
                                                                                            @if($value->status == 2)
                                                                                                <li role="presentation" class="actionsLink" onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                                                                                    <a class="delete-modal btn" >
                                                                                                        Refresh
                                                                                                    </a>
                                                                                                </li>
                                                                                            @else
                                                                                                <li role="presentation" class="actionsLink" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" >
                                                                                                    <a class="delete-modal btn" >
                                                                                                        Delete
                                                                                                    </a>
                                                                                                </li>
                                                                                            @endif

                                                                                        </ul>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="10">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                                        <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>

                                                                        @endforeach
                                                                        <?php } else { ?>
                                                                        <tr>
                                                                            <td colspan="9" class="text-center text-danger"><strong>No Reord Found</strong></td>
                                                                        </tr>
                                                                        <?php } ?>
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
                            </div>
                        </div>
                    </div>


                </div>




                <div class="tab-pane fade in" id="Education">
                    <br>
                    {{--<div class="row">--}}
                    {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">--}}
                    {{--<input type="button" name="add_edu" id="add_edu" value="Add Education" class="btn btn-success"	/>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    <p class="icons">
                        <a class="icon btn btn-sm btn-success" name="add_edu" id="add_edu" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1"><span style="color:white;" class="avion fas fa-plus"> Add Education </span> </a>
                    </p>
                    <div class="row">
                        <div class="col">
                            <div class="collapse multi-collapse" id="multiCollapseExample1">

                                <div class="line-height"> </div>

                                <form id="checking_edu">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="hidden" name="check_emp_id" id="check_emp_id" value="<?php echo $emp_id ?>"/>
                                            <input type="hidden" name="m" id="m" value="<?php echo $m ?>"/><div class="table-responsive">
                                                <input type="hidden" name="education_data[]" id="education_data[]" value="1">
                                                <table class="table table-sm mb-0 table-bordered table-striped">
                                                    <thead>

                                                    <th class="text-center">Name Of Institution</th>
                                                    <th class="text-center">From</th>
                                                    <th class="text-center">To</th>
                                                    <th class="text-center" >Degree / Diploma</th>
                                                    <th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-success"><i class="fas fa-plus"></i></button></th>
                                                    </thead>
                                                    <tbody id="insert_clone">
                                                    <tr class="get_rows">

                                                        <td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>
                                                        <td class="text-center"><input style="width: 100%;" name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1" value=""></td>
                                                        <td class="text-center"><input style="width: 100%;" name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>
                                                        <td class=""><input type="hidden" name="qualificationSection[]">
                                                            <select style="width: 100%;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1" ><option value="">Select</option>
                                                                @foreach($DegreeType as $DegreeTypeValue)
                                                                    <option value="{{ $DegreeTypeValue->id }}" >{{ $DegreeTypeValue->degree_type_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span id="other_option_1"></span>
                                                        </td>
                                                        <td class="text-center"><span class="badge badge-pill badge-secondary"></span></td>
                                                    </tr>
                                                    </tbody>
                                                    <tfoot>

                                                    <td colspan="5" class="text-right">

                                                        <input type="button" name="addSubmit" id="addSubmit" class="btn btn-sm btn-primary" value="Submit" />
                                                    </td>

                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </form>


                            </div>

                        </div>

                        <!--Table-->
                        <table class="table table-sm mb-0 table-bordered table-striped table-fixed">
                            <!--Table head-->
                            <thead>
                            <tr>
                                <th class="text-center">S.no</th>
                                <th class="text-center">Institute Name</th>
                                <th class="text-center">Degree Type</th>
                                <th class="text-center">Year Of Admission</th>
                                <th class="text-center">Year Of Passing</th>
                            </tr>
                            </thead>
                            <!--Table head-->

                            <!--Table body-->
                            <tbody>
                            <?php if(count($employee_education) != 0){ ?>

                            <?php foreach($employee_education as $value){ ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++ ?></td>
                                <td class="text-center"><?php echo $value->institute_name ?></td>
                                <td class="text-center"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'degree_type','degree_type_name',$value->degree_type) ?></td>
                                <td class="text-center"><?php echo date("d-m-Y", strtotime($value->year_of_admission)); ?></td>
                                <td class="text-center"><?php echo date("d-m-Y", strtotime($value->year_of_passing)); ?></td>
                            </tr>
                            <?php } ?>

                            <?php } else{ ?>
                            <tr>
                                <td colspan="6" class="text-danger text-center"><strong><h4>No Record Found</h4></strong></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                            <!--Table body-->

                        </table>
                        <!--Table-->
                    </div>



                </div>
                
                
                
                
                
                
                
                <div class="tab-pane fade in" id="Trainings">
                    <br>
                   
                    

                        <!--Table-->
                        <table class="table table-sm mb-0 table-bordered table-striped table-fixed">
                            <!--Table head-->
                            <thead>
                            <tr>
                                 <th class="text-center">S.No</th>
                                 <th class="text-center">Training Topic</th>
                                 <th class="text-center">Training Date</th>
                                 <th class="text-center">Hours</th>
                                 <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <!--Table head-->

                            <!--Table body-->
                            <tbody>
                            <?php if(count($trainings) != 0){ ?>

                            <?php foreach($trainings as $value){
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $employee_training=DB::table('employee_training')->select('training_id')->where('status',1)->where('training_id',$value->id)->where('emp_id',$emp_id)->count();
                                CommonHelper::reconnectMasterDatabase();
                                
                                if($employee_training != 0){
                                    $status="Taken";
                                    $cls="badge-success";
                                }else{
                                    $status="Not Taken";
                                    $cls="badge-danger";
                                }
                                
                            ?>
                            <tr>
                                <td class="text-center"> <span class="badge badge-pill badge-secondary"><?php echo $counter++ ?></span></td>
                                <td class="text-center"><?php echo $value->topic_name ?></td>
                                <td class="text-center"><?php echo HrHelper::date_format($value->training_date)?></td>
                                <td class="text-center"><?php echo $value->training_hours." Hours" ?></td>
                                <td class="text-center"> <span class="badge badge-pill {{ $cls }}"><?php echo $status ?></span></td>
                            </tr>
                            <?php } ?>

                            <?php } else{ ?>
                            <tr>
                                <td colspan="5" class="text-danger text-center"><strong><h4>No Record Found</h4></strong></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                            <!--Table body-->

                        </table>
                        <!--Table-->
                    </div>
                
                
                <div class="tab-pane fade in" id="JobHistory">
                    <br>
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12" id="pass_eye" style="margin-top: 19px">
                            <input type="password" class="form-control" name="p_user" id="p_user" placeholder="password" style="margin-left: 20px;" >
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 checkSubmit ">
                            <button type="button" class="icon btn btn-success btn-sm" id="check_pass" style="margin-top: 23px;margin-right: -3px;">
                                <span class="fas fa-eye fa-lg"></span>
                            </button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <br>
                            <ul class="timeline">
                                <?php if($EmployeePromotion->count() > 0){

                                ?>

                                <?php foreach($EmployeePromotion->get() as $value){
                                $salarys[] = number_format($value->salary,0);

                                ?>
                                <li>
                                    <a target="_blank" href="#"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$value->designation_id) ?></a>
                                    <a href="#" class="float-right"><?php echo date("F-d-Y", strtotime($value->date)) ?></a>
                                    <h5>Salary: <span class="salary_hidden demi" id="check_<?= $counting++ ?>" ><?php echo number_format($value->salary) ?></span></h5>
                                </li>
                                <?php } ?>
                                <?php } ?>
                                <li>
                                    <a target="_blank" href="#"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee->value('designation_id')) ?></a>
                                    <a href="#" class="float-right"><?php echo date("F-d-Y", strtotime($employee->value('date'))) ?></a>
                                    <h5>Salary: <span class="salary_hidden demi" id="check_<?php echo $counting++ ?>" ><?php echo number_format($employee->value('emp_salary'));    ?></span></h5>
                                    <?php $salarys[] = number_format($employee->value('emp_salary'),0); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="bg-danger text-center" id="error_password" style="width: 100%;color:white;" ></p>
                        </div>
                    </div>


                    <br>



                </div>
                <div class="tab-pane fade in" id="Policies">
                    <br>
                    <?php
                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                    $policies = Policies::where([['status', '=', 1],['category_id', '=', 1]])->get();
                    $forms = Policies::where([['status', '=', 1],['category_id', '=', 2]])->get();
                    CommonHelper::reconnectMasterDatabase();
                    ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h3 class="tabsHeading">Policies</h3>
                            <div class="row">
                                <ul>
                                    @foreach($policies as $key => $val)
                                        <li><h6><a class="policyLinks" target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h6></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h3 class="tabsHeading" >Forms</h3>
                            <div class="row">
                                <ul>
                                    @foreach($forms as $key => $val)
                                        <li><h6><a class="policyLinks" target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h6></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="tab-pane fade in" id="team">
                    <br>

                    <div class="row">
                        @if($team_lead_exists->exists())

                            @foreach($team_lead_exists->get() as $value)
                                <div class="col-sm-5 text-center teaminfo" id="userinfo">
                                    <div class="row card">
                                    <div class="col-sm-12 card-body" style="border-top: none;border-bottom: 2px solid rgba(151,138,237,0.83);">
                                        <img src="<?= url($value->img_path != '' ? 'storage/'.$value->img_path  : 'storage/app/uploads/employee_images/user-dummy.png')?> " class="avatar img-circle img-thumbnail" style="width:30%;" alt="avatar">

                                    <br>

                                        <h5 id="username"><strong> {{$value->emp_name  }}</strong></h5>
                                        <pre style="font-size: 12px;">{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$value->emp_sub_department_id) }}  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; {{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$value->designation_id) }} </pre>
                                        <pre style="font-size: 12px;">{{ $value->professional_email ?? "--"}}</pre>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-1"></div>
                            @endforeach

                        @else

                                <div class="col-sm-12 text-center">
                                    <p style="color:red;font-weight: bold;">No record found</p>
                                </div>

                        @endif


                     </div>
                </div>

            </div>
        </div>
        <!-- End Tabs on plain Card -->
    </div>
</div>




<div class="panel-body">
    <div class="row">
        <div class="modal fade" id="myModalss" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content custom_width">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <div id="education_area_1">

                        </div>
                        <input type="button" name="addSubmit" id="addSubmit"  class="btn btn-primary" value="Submit" />

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <?php endif; ?>
        <script>

            function check_reason(val){
                if(val==7){
                    $('.reasonsList').removeAttr('id');
                    $('#reason_area').append('<div class="row">'+
                        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+
                        '<label>Enter Your Reason</label>'+
                        '<textarea id="reason" class="form-control requiredField">-</textarea>'+
                        '</div>'+
                        '</div>');
                }else{
                    $('.reasonsList').attr('id', 'reason');
                    $('#reason_area').html('');
                }
            }



            $("#basic_info_form").submit(function(e){
                e.preventDefault();
                var name = $("#name").val();
                var p_email = $("#p_email").val();
                var cnic = $("#cnic").val();
                var phone = $("#phone").val();
                var dob = $("#dob").val();
                var url = '<?php echo url("/") ?>/ddc/basicInfo';
                var emp_id = '<?php echo $employee->value("emp_id"); ?>';
                var m = '<?php echo Input::get("m") ?>';
                jqueryValidationCustom()
                if(validate == '0'){
                    $.ajax({
                        type:'GET',
                        url:url,
                        data:{name:name,p_email:p_email,cnic:cnic,phone:phone,dob:dob,emp_id:emp_id,m:m},
                        success:function(res){
                            if(res == 1){
                                $('#error_cnic').hide().html('<strong>Cnic Already Exists !!</strong>').fadeIn('slow').delay(6000).hide(1);
                            }
                            else{
                                $("#name").val(res[0]);
                                $("#p_email").val(res[1]);
                                $("#cnic").val(res[2]);
                                $("#phone").val(res[3]);
                                $("#dob").val(res[4]);

                                $("#username").html(res[0]);
                                $("#p_email_1").html(res[1]);
                                $("#cell_1").html(res[3]);
                                $("#dob_1").html(res[4]);
                                $("#name_1").html(res[0]);
//                                $('#response').hide().html('<strong>Successfully Updated !!</strong>').fadeIn('slow').delay(3000).hide(1);
                                $.notify({
                                    icon: "fa fa-check-circle",
                                    message: "<b>Successfully Updated</b>.",
                                }, {
                                    type: 'success',
                                    timer: 3000
                                });
                                $(".emp_name_td").html(res[0]);
                                $(".emp_cell_td").html('0'+res[3]);
                                $(".emp_email_td").html(res[1]);
                                $(".emp_dob_td").html(res[4]);
                            }
                        }
                    });
                }
            });



//            function jqueryValidationCustom(){
//                var requiredField = document.getElementsByClassName('requiredField');
//                for (i = 0; i < requiredField.length; i++){
//                    var rf = requiredField[i].id;
//                    var checkType = requiredField[i].type;
//                    /*if(checkType == 'text'){
//                     alert('Please type text');
//                     }else if(checkType == 'select-one'){
//                     alert('Please select one option');
//                     }else if(checkType == 'number'){
//                     alert('Please type number');
//                     }else if(checkType == 'date'){
//                     alert('Please type date');
//                     }*/
//                    if($('#'+rf).val() == ''){
//                        $('#'+rf).css('border-color', 'red');
//                        $('#'+rf).focus();
//                        validate = 1;
//                        return false;
//                    }else{
//                        $('#'+rf).css('border-color', '#ccc');
//                        validate = 0;
//                    }
//                }
//
//
//                /*var requiredField1 = document.getElementsByClassName('requiredField');
//                 for (i = 0; i < requiredField1.length; i++){
//                 var rf1 = requiredField[i].id;
//                 if($('#'+rf1+'').val() == ''){
//                 validate = 1;
//                 }else{
//                 validate = 0;
//                 }
//                 }*/
//                return validate;
//            }

            $("#reset_btn").click(function(){
                var name = $("#name").val();
                var p_email = $("#p_email").val();
                var cnic = $("#cnic").val();
                var phone = $("#phone").val();
                var dob = $("#dob").val();
                var url = '<?php echo url("/") ?>/ddc/basicInfo';
                var emp_id = '<?php echo $employee->value("emp_id"); ?>';
                var m = '<?php echo Input::get("m") ?>';

                if(name != ''){
                    $("#name").val('');
                    $('#name').attr('placeholder', 'Name');
                }
                if(p_email != ''){
                    $("#p_email").val('');
                    $('#p_email').attr('placeholder', 'Personal Email');
                }
                if(cnic != ''){
                    $("#cnic").val('');
                    $('#cnic').attr('placeholder', 'Cnic');
                }
                if(phone != ''){
                    $("#phone").val('');
                    $('#phone').attr('placeholder', 'phone');
                }
                if(dob != ''){
                    $("#dob").val('');
                    $('#dob').attr('placeholder', 'DOB');
                }


            });

            $('#btn_check').on('click', function() {
                var $this = $(this);
                $this.button('loading');
                setTimeout(function() {
                    $this.button('reset');
                }, 3000);
            });

            $("#salaryPrivacy").click(function(){
                $("#pass_eye").removeClass("hidden");
                $(".checkSubmit").removeClass('hidden');
            });

            $("#check_pass").click(function(e){
                e.preventDefault();
                var p_user = $("#p_user").val();
                var url = '<?php echo url("/") ?>/ddc/checkingPassword';
                var emp_id = '<?php echo $employee->value("emp_id"); ?>';
                var passedArrays = <?php echo json_encode($salarys); ?>;
                $.ajax({
                    type:'GET',
                    url:url,
                    data:{p_user:p_user,emp_id:emp_id},
                    success:function(res){
                        if(res == 'matched'){
                            $(".demi").removeClass('salary_hidden');
                            //$('#password_matched').html('<strong>Password Matched !!</strong>');
                            $.notify({
                                icon: "fas fa-check-circle",
                                message: "<b>Passwprd matched</b>."
                            }, {
                                type: 'success',
                                timer: 3000
                            });
                            for(var i = 0; i < passedArrays.length; i++){
                                $("#check_"+i).html(passedArrays[i]);
                            }
                        }
                        else{
                            //$('#error_password').hide().html('<strong>Password Not Matched !!</strong>').fadeIn('slow').delay(3000).hide(1);
                            $.notify({
                                icon: "fa fa-times-circle",
                                message: "<b>Passwprd not matched!</b>."
                            }, {
                                type: 'danger',
                                timer: 3000
                            });
                            $(".demi").addClass('salary_hidden');
                            $(".salary_hidden").html('******');
                        }
                    }
                });
            });

            $(document).ready(function(){
                $('#add_edu span').html(" Add Education");
                $('#degree_type_1').select2();
                $(".salary_hidden").html('******');
                $("#institute_name_1").removeClass('requiredField');
                $("#year_of_admission_1").removeClass('requiredField');
                $("#year_of_passing_1").removeClass('requiredField');
                $("#degree_type_1").removeClass('requiredField');
            });



            @if($WithoutLeavePolicy[0] != 'Select Leave Policy')
            @if($leaves_policy_validatity == 0)
setInterval(function () {
                $(".policy_expire_mesg").css("color","#fff");
                $(".policy_expire_mesg").css("background-color","#a94442");
                $(".policy_expire_mesg").css("border-color","#a94442");
                setTimeout(function () {
                    $(".policy_expire_mesg").removeAttr("style");

                },500)
            },900);
            @endif

            function leaves_day_type(type)
            {

                var current_date  = '<?= date("Y-m-d") ?>';
                var leave_type = $("input[id='leave_type']:checked").val();

                if(leave_type == 4)
                {
                    if(type == 'full_day_leave')
                    {

                        $("#leave_days_area").html('<div class="row">'+
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> To </label><input type="date" class="form-control requiredField" onchange="checkCasualLeavesDifference(<?php echo $countRemainingSickLeaves ?>)" name="to_date" id="to_date"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> No. of Days</label><input type="number" readonly onclick="checkCasualLeave()" onchange="checkCasualLeave()" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                            '<span id="warning_message" style="color:red"></span></div>' +
                            '</div> <br>');

                    }
                    else if(type == 'half_day_leave')
                    {

                        $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                            '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                            '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                            '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                            '<label> Date </label><input type="date" class="form-control requiredField" onchange="checkCasualLeaves(<?php echo $countRemainingSickLeaves ?>)" id="first_second_half_date" name="first_second_half_date"> </div></div>');
                    }
                    else if(type == 'short_leave')
                    {

                        $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>');

                    }
                }

                if(leave_type == 2)
                {
                    if(type == 'full_day_leave')
                    {

                        $("#leave_days_area").html('<div class="row">'+
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> To </label><input type="date" class="form-control requiredField" name="to_date" id="to_date"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> No. of Days</label><input type="number" readonly class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                            '<span id="warning_message" style="color:red"></span></div>' +
                            '</div>');
                    }
                    else if(type == 'half_day_leave')
                    {

                        $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                            '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                            '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                            '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                            '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>');
                    }
                    else if(type == 'short_leave')
                    {
                        $("#leave_days_area").html('');
                        $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>');

                    }
                }
                else if(leave_type == 3)
                {
                    if(type == 'full_day_leave')
                    {

                        $("#leave_days_area").html('<div class="row">'+
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> To </label><input type="date" class="form-control requiredField" onchange="checkCasualLeavesDifference(<?php echo $countRemainingCasualLeaves ?>)" name="to_date" id="to_date"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> No. of Days</label><input type="number" readonly onclick="checkCasualLeave()" onchange="checkCasualLeave()" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                            '<span id="warning_message" style="color:red"></span></div>' +
                            '</div> <br>');

                    }
                    else if(type == 'half_day_leave')
                    {

                        $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                            '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                            '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                            '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                            '<label> Date </label><input type="date" class="form-control requiredField" onchange="checkCasualLeaves(<?php echo $countRemainingCasualLeaves ?>)" id="first_second_half_date" name="first_second_half_date"> </div></div>');
                    }
                    else if(type == 'short_leave')
                    {

                        $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                            '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>');

                    }
                }


            }
            
            
            function calcBusinessDays(start, end) {

                // Copy date objects so don't modify originals
                
                
                
                var s = new Date(+start);
                var e = new Date(+end);
                
                
                
                // Set time to midday to avoid dalight saving and browser quirks
                s.setHours(12,0,0,0);
                e.setHours(12,0,0,0);

                // Get the difference in whole days
                var totalDays = Math.round((e - s) / 8.64e7);

                // Get the difference in whole weeks
                var wholeWeeks = totalDays / 7 | 0;

                // Estimate business days as number of whole weeks * 5
                var days = wholeWeeks * 5;

                // If not even number of weeks, calc remaining weekend days
                if (totalDays % 7) {
                    s.setDate(s.getDate() + wholeWeeks * 7);

                    while (s < e) {
                        s.setDate(s.getDate() + 1);

                        // If day isn't a Sunday or Saturday, add to business days
                        if (s.getDay() != 0 && s.getDay() != 6) {
                            ++days;
                        }
                    }
                }
                
                
                var new_start_date=myDateFormatter(start);
                var new_end_date=myDateFormatter(end);
                
                var holidays = <?php echo json_encode($holidays); ?>;
                    holidays.forEach(function(item) {
                        var date=item.holiday_date;
                        if((date <= new_end_date && date >= new_start_date)) {
                            --days;
                        }
                    });
                
                
                
                
                return days;
            }
            
            
            
            function myDateFormatter (date) {
                var d = new Date(date);
                var day = d.getDate();
                var month = d.getMonth() + 1;
                var year = d.getFullYear();
                if (day < 10) {
                    day = "0" + day;
                }
                if (month < 10) {
                    month = "0" + month;
                }
                var date = year + "-" + month + "-" + day;
        
                return date;
            }; 
            
            
            function check_days()
            {
                //alert('working');

                var leave_type = $("input[id='leave_type']:checked").val();
                var leaves_day_type = $("input[id='leave_day_type']:checked").val();
                var leave_policy_id = '<?=$leaves_policy[0]->leaves_policy_id?>';
                var emp_id = $("#emp_id").val();

//                $('.leaveAppLoader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');



                    if(leave_type == 4)
                    {
                        if(leaves_day_type == 'full_day_leave'){

                            var from_date = $('#from_date').val();
                            var no_of_days = $('#no_of_days').val();

                            var emp_id = emp_id;
                            var company_id = '<?= Input::get('m') ?>';
                            var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';


                            var from_date =  $("#from_date").val();
                            var to_date   = $("#to_date").val();
                            var leave_type = $("input[id='leave_type']:checked").val();
                            var leave_day_type = 1
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var leave_address =  $("#leave_address").val();
                            var data = {
                                leave_type:leave_type,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                company_id:company_id,
                                full_day_deduction_rate:full_day_deduction_rate,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                from_date:from_date,
                                to_date:to_date,
                                reason:reason,
                                leave_address:leave_address,
                                backup_contact:backup_contact
                            };


                        }
                        else if(leaves_day_type == 'half_day_leave')
                        {

                            var emp_id = emp_id;
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var company_id = '<?= Input::get('m') ?>';
                            var leave_address =  $("#leave_address").val();
                            var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                            var first_second_half = $("input[id='first_second_half']:checked").val();
                            var no_of_days = 0.5;
                            var first_second_half_date =  $("#first_second_half_date").val();
                            var leave_day_type = 2
                            var leave_type = $("input[id='leave_type']:checked").val();
                            var data = {
                                leave_type:leave_type,
                                company_id:company_id,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                first_second_half:first_second_half,
                                first_second_half_date:first_second_half_date,
                                leave_address:leave_address,
                                reason:reason,
                                first_second_half_date:first_second_half_date,
                                backup_contact:backup_contact,
                            };

                        }
                        else
                        {
                            alert('Error ! Select Full/Half Leave !');
                            return false;
                        }



                    }

                    else if(leave_type == 1)
                    {

                        if(leaves_day_type == 'full_day_leave'){


                            var inform_days_two = '29';
                            var from_date = $('#from_date').val();
                            var to_date   = $("#to_date").val();
                            var no_of_days = $('#no_of_days').val();
                            var current_date = '<?= date("Y-m-d"); ?>';
                            var date1 = current_date;
                            var date2 = from_date;
                            date1 = date1.split('-');
                            date2 = date2.split('-');
                            date1 = new Date(date1[0], date1[1], date1[2]);
                            date2 = new Date(date2[0], date2[1], date2[2]);
                            date1_unixtime = parseInt(date1.getTime() / 1000);
                            date2_unixtime = parseInt(date2.getTime() / 1000);
                            var timeDifference = date2_unixtime - date1_unixtime;
                            var timeDifferenceInHours = timeDifference / 60 / 60;
                            var timeDifferenceInDays = timeDifferenceInHours  / 24;

                            /*   var check1= checkLeavesDifference();
                             var check2= checkAnnualLeaveDays();

                             if((inform_days_two-1) > timeDifferenceInDays)
                             {

                             $('#warning_message').html('For Anuual Leaves , Inform Administration Before '+inform_days_two+' days !');
                             var check = false;

                             }
                             else
                             {
                             $('#warning_message').html('');
                             var check = true;
                             }


                             if(check == true && check1 == true && check2 == true){*/

                            var emp_id = emp_id;
                            var company_id = '<?= Input::get('m') ?>';
                            var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                            //var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                            var from_date =  $("#from_date").val();
                            var to_date   = $("#to_date").val();
                            var leave_type = $("input[id='leave_type']:checked").val();
                            var leave_day_type = 1
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var leave_address =  $("#leave_address").val();
                            var data = {
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                company_id:company_id,
                                full_day_deduction_rate:full_day_deduction_rate,
                                leave_type:leave_type,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                from_date:from_date,
                                to_date:to_date,
                                reason:reason,
                                leave_address:leave_address,
                                backup_contact:backup_contact,
                            };

                            //   }
                            // else
                            // {
                            //      return false;
                            // }


                        }

                    }
                    else if(leave_type == 2)
                    {
                        if(leaves_day_type == 'full_day_leave'){


                            var from_date = $('#from_date').val();
                            var no_of_days = $('#no_of_days').val();
                            var company_id = '<?= Input::get('m') ?>';
                            var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                            var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                            var from_date =  $("#from_date").val();
                            var to_date   = $("#to_date").val();
                            var leave_type = $("input[id='leave_type']:checked").val();
                            var leave_day_type = 1
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var leave_address =  $("#leave_address").val();
                            var data = {

                                leave_type:leave_type,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                company_id:company_id,
                                full_day_deduction_rate:full_day_deduction_rate,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                from_date:from_date,
                                to_date:to_date,
                                reason:reason,
                                leave_address:leave_address,
                                backup_contact:backup_contact,
                            };



                        }
                        else if(leaves_day_type == 'half_day_leave')
                        {

                            var emp_id = emp_id;
                            var company_id = '<?= Input::get('m') ?>';
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var leave_address =  $("#leave_address").val();
                            var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                            var first_second_half = $("input[id='first_second_half']:checked").val();
                            var no_of_days = (1*half_day_deduction_rate);
                            var first_second_half_date =  $("#first_second_half_date").val();
                            var leave_day_type = 2
                            var leave_type = $("input[id='leave_type']:checked").val();
                            var data = {
                                leave_type:leave_type,
                                company_id:company_id,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                first_second_half:first_second_half,
                                first_second_half_date:first_second_half_date,
                                leave_address:leave_address,
                                reason:reason,
                                first_second_half_date:first_second_half_date,
                                backup_contact:backup_contact,
                            };



                        }
                        else if(leaves_day_type == 'short_leave')
                        {

                            var emp_id = emp_id;
                            var company_id = '<?= Input::get('m') ?>';
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var leave_address =  $("#leave_address").val();
                            var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                            var short_leave_time_from = $("#short_leave_time_from").val();
                            var short_leave_time_to = $("#short_leave_time_to").val();
                            var short_leave_date = $("#short_leave_date").val();
                            var no_of_days = (1*per_hour_deduction_rate);
                            var first_second_half_date =  $("#first_second_half_date").val();
                            var leave_day_type = 3;
                            var leave_type = $("input[id='leave_type']:checked").val();

                            var data = {
                                leave_type:leave_type,
                                company_id:company_id,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                short_leave_time_from:short_leave_time_from,
                                short_leave_time_to:short_leave_time_to,
                                short_leave_date:short_leave_date,
                                leave_address:leave_address,
                                reason:reason,
                                backup_contact:backup_contact,
                            };

                        }
                        else
                        {
                            alert('Error ! Select Full/Half/Short Leave Type !');
                            return false;
                        }
                    }
                    else if(leave_type == 3)
                    {
                        if(leaves_day_type == 'full_day_leave'){

                            var from_date = $('#from_date').val();
                            var no_of_days = $('#no_of_days').val();

                            var emp_id = emp_id;
                            var company_id = '<?= Input::get('m') ?>';
                            var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';


                            var from_date =  $("#from_date").val();
                            var to_date   = $("#to_date").val();
                            var leave_type = $("input[id='leave_type']:checked").val();
                            var leave_day_type = 1
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var leave_address =  $("#leave_address").val();
                            var data = {
                                leave_type:leave_type,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                company_id:company_id,
                                full_day_deduction_rate:full_day_deduction_rate,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                from_date:from_date,
                                to_date:to_date,
                                reason:reason,
                                leave_address:leave_address,
                                backup_contact:backup_contact
                            };


                        }
                        else if(leaves_day_type == 'half_day_leave')
                        {

                            var emp_id = emp_id;
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var company_id = '<?= Input::get('m') ?>';
                            var leave_address =  $("#leave_address").val();
                            var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                            var first_second_half = $("input[id='first_second_half']:checked").val();
                            var no_of_days = 0.5;
                            var first_second_half_date =  $("#first_second_half_date").val();
                            var leave_day_type = 2
                            var leave_type = $("input[id='leave_type']:checked").val();
                            var data = {
                                leave_type:leave_type,
                                company_id:company_id,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                first_second_half:first_second_half,
                                first_second_half_date:first_second_half_date,
                                leave_address:leave_address,
                                reason:reason,
                                first_second_half_date:first_second_half_date,
                                backup_contact:backup_contact,
                            };

                        }
                        else if(leaves_day_type == 'short_leave')
                        {

                            var emp_id = emp_id;
                            var company_id = '<?= Input::get('m') ?>';
                            var reason   = $("#reason").val();
                            if(reason >=1 && reason<=6){
                                reason   = $("#reason option:selected").text();
                            }
                            var backup_contact   = $("#backup_contact").val();
                            var leave_address =  $("#leave_address").val();
                            var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                            var short_leave_time_from = $("#short_leave_time_from").val();
                            var short_leave_time_to = $("#short_leave_time_to").val();
                            var short_leave_date = $("#short_leave_date").val();
                            var no_of_days = (1*per_hour_deduction_rate);
                            var first_second_half_date =  $("#first_second_half_date").val();
                            var leave_day_type = 3;
                            var leave_type = $("input[id='leave_type']:checked").val();

                            var data = {
                                leave_type:leave_type,
                                company_id:company_id,
                                emp_id:emp_id,
                                leave_policy_id:leave_policy_id,
                                leave_day_type:leave_day_type,
                                no_of_days:no_of_days,
                                short_leave_time_from:short_leave_time_from,
                                short_leave_time_to:short_leave_time_to,
                                short_leave_date:short_leave_date,
                                leave_address:leave_address,
                                reason:reason,
                                backup_contact:backup_contact,
                            };

                        }
                        else
                        {
                            alert('Error ! Select Full/Half/Short Leave Type !');
                            return false;
                        }
                    }
                    else
                    {
                        alert('Please Select Leaves Type !')
                    }
                    var company_id = '<?= Input::get('m') ?>';
                    $.ajax({
                        url: '<?php echo url('/')?>/hadbac/addLeaveApplicationDetail',
                        type: "GET",
                        data: data,
                        success:function(data) {

                            if(data == 1) {
                                sessionStorage.setItem('successMsg', 'Application submitted');
                                location.reload();
                            }
                            else {
                                alert(data);
                                $(".leaveAppLoader").html("");
                            }
                        }
                    });


            }


            function viewEmployeeLeavesDetail(id,leavesCount,leaveType) {

                //alert(leaveType); return false;
                var current_date = '<?= date("Y-m-d") ?>';
                $('#leavesData').append('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $("#leave_days_area").html('');

                var url = '<?php echo url('/')?>/hdc/viewEmployeeLeaveDetail';
                var data = {
                    company_id: '<?= Input::get('m') ?>',
                    emp_id: '<?=$attendance_machine_id?>',
                    leave_id: id,
                    leavesCount: leavesCount,
                    leaveType: leaveType,
                };
                $.ajax({
                    url: url,
                    data :data,
                    success:function(result) {
                        $('#leavesData').html(result);
                    }
                });

            }

             function checkAllowedLeaveDays(remainingLeaves,check)
             {
             var no_of_days = $("#no_of_days").val();

             if(check == 1)
             {

             if(no_of_days > remainingLeaves)
             {
             $("#no_of_days").val('');
             $("#warning_message").html('You cannot carry Leaves , More Then '+remainingLeaves+' In 2nd segment !');

             }
             else if(no_of_days < remainingLeaves)
             {
             $("#no_of_days").val('');
             $("#warning_message").html('You cannot carry Less Leaves , You Have to Carry '+remainingLeaves+' Leaves In 2nd segment !');

             }
             else
             {
             $("#warning_message").html('');
             }
             }
             else if(check == 0)
             {
             if(no_of_days > remainingLeaves)
             {
             $("#no_of_days").val('');
             $("#warning_message").html('You cannot carry Leaves , More Then '+remainingLeaves+' In 1st segment !');

             }
             else
             {
             $("#warning_message").html('');
             }
             }
             }

             function checkCasualLeave()
             {
             var no_of_days =  $("#no_of_days").val();

             if(no_of_days > 3)
             {
             $("#no_of_days").val('');
             $("#warning_message").html('You cannot carry More then 3 Casual Leaves !');
             $("#submitBtn").attr('disabled','disabled');
             $("#errorMesg").html('Please Remove All Errors First !');




             }
             else
             {
             $("#warning_message").html('');
             $("#errorMesg").html('');
             $("#submitBtn").removeAttr('disabled');
             }

             }

             function checkCasualLeavesDifference(leavesCount)
             {

             var from_date = $('#from_date').val();
             var to_date   = $("#to_date").val();
             var date1 = new Date(from_date);
             var date2 = new Date(to_date);

             
             var diffDays = calcBusinessDays(date1, date2);
            
             $("#no_of_days").val(diffDays+1);
             var no_of_days = $("#no_of_days").val();

                 if(to_date < from_date){
                     $("#warning_message").html('Please Correct Date !');
                     $("#submitBtn").attr('disabled','disabled');
                     $("#errorMesg").html('Please Remove All Errors First !');
                     return false;
                 }

             if(no_of_days <= leavesCount)
             {
             $("#warning_message").html('');
             $("#errorMesg").html('');
             $("#submitBtn").removeAttr('disabled');

             return true;
             }
             else
             {
             $("#warning_message").html('Please Correct Date Difference !');
             $("#submitBtn").attr('disabled','disabled');
             $("#errorMesg").html('Please Remove All Errors First !');

             }


             }
             function checkAnnualLeaveDays(leavesCount)
             {


             var no_of_days =  $("#no_of_days").val();



             if(no_of_days > leavesCount)
             {

                 $("#warning_message").html('You cannot take More Then '+leavesCount+' Annual Leaves !');
                 $("#submitBtn").attr('disabled','disabled');
                 $("#errorMesg").html('Please Remove All Errors First !');
             }
             else
             {

             $("#warning_message").html('');
             $("#errorMesg").html('');
             $("#submitBtn").removeAttr('disabled');
             return true;
             }



             }


             function checkLeavesDifference(leavesCount)
             {
                check();
             var from_date = $('#from_date').val();
             var to_date   = $("#to_date").val();
             var date1 = new Date(from_date);
             var date2 = new Date(to_date);
             var no_of_days = $("#no_of_days").val();
             var diffDays = calcBusinessDays(date1, date2);

                 $("#no_of_days").val(diffDays+1);
                 var no_of_days = diffDays+1;

                 if(to_date < from_date){
                     $("#submitBtn").attr('disabled','disabled');
                     $("#errorMesg").html('Please Remove All Errors First !');
                     $("#warning_message").append('Please Enter Correct Date !');
                     return false;
                 }

             if(no_of_days  > leavesCount)
             {
                 $("#submitBtn").attr('disabled','disabled');
                 $("#errorMesg").html('Please Remove All Errors First !');
                 //$("#warning_message").html('You cannot take less then 4 and More Then '+leavesCount+' Annual Leaves !');
                 //$("#warning_message").append('<br>');
                 $("#warning_message").append('Please Enter Correct Date !');
             }
             else
             {
                 $("#warning_message").html('');
                 $("#errorMesg").html('');
                 $("#submitBtn").removeAttr('disabled');
                 return true;



             }


             }
            @endif


            function viewRangeWiseLeaveApplicationsRequests()
            {
                jqueryValidationCustom();
                if(validate == 0) {
                    $('#leavesLoader').append('<div class="row">&nbsp;</div><div class="loader"></div>');
                    var data = '';
                    var gm_Approvals;
                    var fromDate = $("#fromDate").val();
                    var toDate = $("#toDate").val();
                    var LeavesStatus = $("#LeavesStatus").val();
                    var department_id = $("#department_id").val();
                    var sub_department_id_1 = $("#sub_department_id_1").val();
                    var employee_id = $("#emp_id").val();
                    var company_id = $("#company_id").val();
                    var m = '<?= Input::get('m') ?>';
                    var url = '<?php echo url('/')?>/hdc/viewRangeWiseLeaveApplicationsRequests';
                    if($("#gm_Approval").prop("checked") == true){
                        gm_Approvals = 1;
                        data = {fromDate: fromDate, toDate: toDate, LeavesStatus: LeavesStatus,employee_id:employee_id,m:m,company_id:company_id,department_id:department_id,sub_department_id_1:sub_department_id_1,gm_Approvals:gm_Approvals}
                    }
                    else{
                        data = {fromDate: fromDate, toDate: toDate, LeavesStatus: LeavesStatus,employee_id:employee_id,m:m,company_id:company_id,department_id:department_id,sub_department_id_1:sub_department_id_1}
                    }
                    $.ajax({
                        url: url,
                        type: "GET",
                        data:data,
                        success: function (data) {
                            $('#leavesLoader').html("");
                            $('.LeavesData').html(data);


                        }
                    })
                }

            }
            function LeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id,company_id)
            {

                $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
                var m = '<?= Input::get('m'); ?>';
                var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id,m:company_id},
                    success: function (data) {

                        jQuery('#showDetailModelTwoParamerter').modal('show', {backdrop: 'false'});
                        jQuery('#showDetailModelTwoParamerter .modalTitle').html('View Leave Application Detail');
                        jQuery('#showDetailModelTwoParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                        jQuery('#showDetailModelTwoParamerter .modal-body').html(data);


                    }
                })


            }
            function editLeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id,company_id)
            {
                // alert();
                $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
                var m = '<?= Input::get('m'); ?>';
                var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';
                var data = {id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id,m:company_id};
                $.getJSON(url, data ,function(result){
                    $.each(result, function(i, field){
                        $('#leave_area'+id).html('<hr>' +
                            '<div class="row text-center" style="background-color: gainsboro">' +
                            '<h4><b>Leave Application Details</b></h4>' +
                            '</div>' +
                            '<div class="row">&nbsp;</div>'+field);

                    });
                })

            }

            function approveAndRejectLeaveApplication(recordId,approval_status,leave_day_type)
            {
                // alert('hello');
                // return;
                var check = (approval_status == 2) ? "Approve":"Reject";

                var companyId = '<?= Input::get('m'); ?>';

                if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
                {

                    $.ajax({
                        url: '<?php echo url("/")?>/hdc/approveAndRejectLeaveApplication',
                        type: "GET",
                        data: {companyId:companyId,recordId:recordId,approval_status:approval_status},
                        success:function(data) {
                            // alert(data);
                            location.reload();
                        }
                    });
                }
            }

            function approveAndRejectLeaveApplication2(recordId,approval_status_lm,leave_day_type)
            {
                var check = (approval_status_lm == 2) ? "Approve":"Reject";

                var companyId = '<?= Input::get('m'); ?>';

                if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
                {

                    $.ajax({
                        url: '<?php echo url("/")?>/hdc/approveAndRejectLeaveApplication2',
                        type: "GET",
                        data: {companyId:companyId,recordId:recordId,approval_status_lm:approval_status_lm},
                        success:function(data) {
                            location.reload();
                            //getPendingLeaveApplicationDetail('approval_status_lm',leave_day_type);
                        }
                    });
                }
            }

            function approveAndRejectLeaveApplication3(recordId,approval_status_hd,leave_day_type)
            {

                var check = (approval_status_hd == 2) ? "Approve":"Reject";
                var url= '<?php echo url('/')?>/cdOne/approveAndRejectLeaveApplication3';
                var companyId = '<?= Input::get('m'); ?>';

                if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
                {

                    $.ajax({
                        url: url,
                        type: "GET",
                        data: {companyId:companyId,recordId:recordId,approval_status_hd:approval_status_hd},
                        success:function(data) {
                            getPendingLeaveApplicationDetail('approval_status_hd',approval_status_hd,leave_day_type);
                        }
                    });
                }
            }

            function RepostLeaveApplicationData(companyId,recordId)
            {
                if(confirm('Do you want to Repost Leave Applicaiton ?'))
                {
                    repostMasterTableRecords(recordId,'leave_application');

                }

            }

            $(function(){
                $('select[name="department_id"]').on('change', function() {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    var department_id = $(this).val();
                    if($(this).val() == '') { $('#emp_loader').html('');}
                    var m = '<?= Input::get('m'); ?>';
                    if(department_id) {
                        $.ajax({
                            url: '<?php echo url('/')?>/slal/MachineEmployeeListDeptWise',
                            type: "GET",
                            data: { department_id:department_id,m:m},
                            success:function(data) {
                                $('#emp_loader').html('');
                                $('select[name="employee_id"]').empty();
                                $('select[name="employee_id"]').html(data);
                                // $('select[name="employee_id"]').find('option').get(0).remove();
                            }
                        });
                    }else{
                        $('select[name="employee_id"]').empty();
                    }
                });
            });
            function getPendingLeaveApplicationDetail(type,leave_day_type){
                var companyId = '<?= Input::get('m'); ?>';
                jQuery('#showDetailModelTwoParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/hdc/getPendingLeaveApplicationDetail',
                    type: "GET",
                    data: {m:companyId,type:type,leave_day_type:leave_day_type},
                    success:function(data) {
                        if(data == 0)
                        {
                            location.reload();
                        }else{
                            jQuery('#showDetailModelTwoParamerter .modal-body').html(data);
                        }

                    }
                });
            }




            function getEmployee(){
                var m = '<?php echo $m ?>';
                var department = $("#department_id_").val();
                var employee_project_id = $("#employee_project_id").val();
                var line_managers = 'line_manager';

                if(department == '' && employee_project_id != ''){
                    alert('Please Select Department');
                }
                if(department != '' && employee_project_id == ''){
                    data = {department:department,employee_project_id:'0',m:m,line_managers:'line_managers'}
                }
                else if(department != '' && employee_project_id != ''){
                    data = {department:department,employee_project_id:employee_project_id,m:m,line_managers:'line_managers'}
                }
                if(department != ''){
                    $('#emp_loader_1').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    $.ajax({
                        type:'GET',
                        url:'<?php echo url('/') ?>/slal/getSubDepartment',
                        data:data,
                        success:function(res){
                            $('#emp_loader_1').html('');
                            $('select[name="emp_id"]').empty();
                            $('select[name="emp_id"]').html(res);
                            $("#emp_id").prepend("<option value='' selected='selected'>Select Employee</option>");
                        }
                    })
                }
                else{

                    $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
                    $('select[name="sub_department_id_1"]').empty();
                    $('select[name="emr_no"]').empty();
                }
            }
            $("#add_edu1").click(function(){
                $('#myModalss').modal('toggle');

                $("#education_area_1").html('<form id="checking_edu"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" name="check_emp_id" id="check_emp_id" value="<?php echo $emp_id ?>"/><input type="hidden" name="m" id="m" value="<?php echo $m ?>"/><div class="table-responsive">' +
                    '<input type="hidden" name="education_data[]" id="education_data[]" value="1"><table class="table table-sm mb-0 table-bordered table-striped"><thead><th class="text-center col-sm-1">S.No</th>' +
                    '<th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>' +
                    '<th class="text-center">Degree / Diploma</th>' +
                    '<th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-success"><i class="fas fa-plus"></i></button></th>' +
                    '</thead><tbody id="insert_clone"><tr class="get_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                    '<td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>' +
                    '<td class="text-center"><input name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1" value=""></td>' +
                    '<td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>' +
                    '<td class="text-center"><input type="hidden" name="qualificationSection[]">' +
                    '<select style="width:100% !important;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1"><option value="">Select</option>'+
                    '@foreach($DegreeType as $DegreeTypeValue)<option value="{{ $DegreeTypeValue->id }}">{{ $DegreeTypeValue->degree_type_name }}</option>@endforeach</select><span id="other_option_1"></span></td>'+
                    '<td class="text-center">-</td></tr></tbody></table></div></div></form>');


                $("#addMoreQualification").click(function(e){
                    var clone = $(".get_clone_1").html();

                    var form_rows_count = $(".get_rows").length;
                    form_rows_count++;
                    $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                        "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                        "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                        "<td class='text-center'><input style='width: 100%;' name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                        "<td class='text-center'><input style='width: 100%;' name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                        "<td><input type='hidden' name='education_data[]' id='education_datas[]' value="+form_rows_count+">" +
                        "<select style='width: 100%;' id='degree_type_"+form_rows_count+"' class='form-control degree_type_ requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                        "<span style='width: 100%;' id='other_option_"+form_rows_count+"'></span></td>" +
                        "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='icon btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                        "</td>" +
                        "</tr>");
                    $('#degree_type_'+form_rows_count+'').select2();

                });


            });



            $("#addMoreQualification").click(function(e){
                var clone = $(".get_clone_1").html();

                var form_rows_count = $(".get_rows").length;
                form_rows_count++;
                $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' > <td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>"
                    +"<td class='text-center'><input style='width: 100%;' name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                    "<td class='text-center'><input style='width: 100%;' name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                    "<td><input type='hidden' name='education_data[]' id='education_datas[]' value="+form_rows_count+">" +
                    "<select style='width: 100%;' id='degree_type_"+form_rows_count+"' class='form-control degree_type_ requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                    "<span id='other_option_"+form_rows_count+"'></span></td>" +
                    "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='icon btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                    "</td> </tr>");
                $('#degree_type_'+form_rows_count+'').select2();

            });


            function removeQualificationSection(id){
                $('#remove_area_'+id).remove();
            }


            $('#btn_edu').click(function(){

            });
            function addEducation(){


            }
            $("#addSubmit").click(function(e){
                e.preventDefault();
                jqueryValidationCustom()

                if(validate == '0'){
                    $.ajax({
                        type:'GET',
                        url:'<?php echo url("/")?>/ddc/addEducationDetails',
                        data:$("#checking_edu").serialize(),
                        success:function(res){
                            location.reload();
                        }
                    });
                }
            });
            $('#add_edu').click(function(){
                var a=$('#add_edu span').html();
                if(a == " Add Education"){
                    $("#add_edu .avion").removeClass("fa-plux").fadeIn(1000);
                    $("#add_edu").removeClass("btn-success").fadeIn(1000);
                    $("#add_edu").addClass("btn-warning").fadeIn(1000);
                    $("#add_edu .avion").addClass("fa-times").fadeIn(1000);
                    $("#add_edu .avion").html(' Close').fadeIn(1000);
                    $("#institute_name_1").addClass('requiredField');
                    $("#year_of_admission_1").addClass('requiredField');
                    $("#year_of_passing_1").addClass('requiredField');
                    $("#degree_type_1").addClass('requiredField');
                }
                else{
                    $("#add_edu .avion").removeClass("fa-times").fadeIn(1000);
                    $("#add_edu").removeClass("btn-warning").fadeIn(1000);
                    $("#add_edu").addClass("btn-success").fadeIn(1000);
                    $("#add_edu .avion").addClass("fa-plus").fadeIn(1000);
                    $("#add_edu .avion").html(' Add Education').fadeIn(1000);
                    $("#institute_name_1").removeClass('requiredField');
                    $("#year_of_admission_1").removeClass('requiredField');
                    $("#year_of_passing_1").removeClass('requiredField');
                    $("#degree_type_1").removeClass('requiredField');

                }


            });



            /*
             * Custom variables
             * -------------------
             * Here you can change animation that
             * triggers after you click on icon.
             * We have used animate.css for CSS animations.
             * Visit https://daneden.github.io/animate.css/
             * for more info.
             *
             * Animations you can choose from are:
             * bounce, flash, pulse, rubberBand, shake,
             * swing, tada, wobble, jello, flip, hinge
             *
             *
             */

            var animation = 'rubberBand';



            /*
             * Icon
             * --------
             * This code toggle classes for
             * our icon.
             *
             */



//            $('.icon').on('click', function(){
//
//                $(this).toggleClass('icon--active');
//            })
//
//            /* If you want to disable animation just comment out the code bellow */
//
//            $('.icon').on('click', function(){
//
//                $(this).addClass('animated ' + animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
//                    $(this).removeClass('animated ' + animation);
//                })
//            });


            $('.cnicExistMessage').on('keydown',function(evt){
                var cnicLength = $(this).val().length;
                if (cnicLength <= 15) {
                    $('#cnicExistMessage').text('Not less than 13').css("color", "red");
                    $('#btn_disable').attr('disabled', 'disabled');
                }
                else{
                    $('#cnicExistMessage').text('');
                    $("#btn_disable").removeAttr("disabled");
                }
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode == 8 || charCode == 9
                    || charCode == 27 || charCode == 13
                    || (charCode == 65 && evt.ctrlKey === true) )

                    return;
                if ((charCode < 48 || (charCode > 57 && charCode < 96 || charCode > 105 ))) {

                     return false;

                }
                else{

                    if( cnicLength < 13){
                        $('#cnicExistMessage').text('Not less than 13').css("color", "red");
                        $('#btn_disable').attr('disabled', 'disabled');
                    }
                    if (cnicLength >= 13) {
                        $('#cnicExistMessage').text('');
                        $("#btn_disable").removeAttr("disabled");
                    }
                    if(cnicLength == 5 || cnicLength == 13){
                        $(this).val($(this).val()+'-');
                    }
                }

            });
            function stopKeyPressedSpace(){


                var key = event.keyCode || event.charCode || event.which ;
                return key;
            }
            function checkMobileNumber(paramOne,paramTwo,paramThree,e){

                var mobileNumbers  = paramOne;
                var phoneno = /^[2-9]\d{9}$/;
                var charCode = (e.which) ? e.which : event.keyCode;


                if(paramThree == '1'){
                    if(phoneno.test(mobileNumbers)) {
                        $('.'+paramTwo+'').html('');
                        $("#btn_disable").removeAttr("disabled");
                        return true;
                    }else {
                        if(charCode == 48 && mobileNumbers.length  ==1 ){
                            $('#btn_disable').attr('disabled', 'disabled');
                            $('.'+paramTwo+'').html('zero is not allowed').css("color", "red");
                        }else{
                            if (mobileNumbers.length  <10 ) {
                                $('#btn_disable').attr('disabled', 'disabled');
                                $('.'+paramTwo+'').html('Only 10 digits Allowed').css("color", "red");
                                return false;
                            }else if(mobileNumbers.length  >10) {
                                $('#btn_disable').attr('disabled', 'disabled');
                                $('.'+paramTwo+'').html('Only 10 digits Allowed ').css("color", "red");
                                return false;
                            }else{
                                $('.'+paramTwo+'').html('');
                                $("#btn_disable").removeAttr("disabled");
                                return true;
                            }
                        }

                    }
                }else if(paramThree == '2'){
                    if(phoneno.test(mobileNumbers)) {
                        $('.'+paramTwo+'').html('');
                        $("#btn_disable").removeAttr("disabled");
                        return true;
                    }else {
                        if(charCode == 48 && mobileNumbers.length  ==1){

                            $('.'+paramTwo+'').html('zero is not allowed').css("color", "red");
                            $('#btn_disable').attr('disabled', 'disabled');
                        }else{
                            if (mobileNumbers.length  <10 ) {
                                $('.'+paramTwo+'').html('Only 10 digits Mobile Number  Not Less Than 10').css("color", "red");
                                $('#btn_disable').attr('disabled', 'disabled');
                                return false;
                            }else if(mobileNumbers.length  >10) {
                                $('.'+paramTwo+'').html('Only 10 digits Mobile Number Not Greater Than 10 ').css("color", "red");
                                $('#btn_disable').attr('disabled', 'disabled');
                                return false;
                            }else{
                                $('.'+paramTwo+'').html('');
                                $("#btn_disable").removeAttr("disabled");
                                return true;
                            }
                        }
                    }
                }


            }

            function isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }

        </script>

    </div>