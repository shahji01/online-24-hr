<?php
    use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
    $implode_days_one = explode('|',$leaves_policy[0]->fromToDaysone);
    $implode_days_two = $leaves_policy[0]->fromToDaystwo;

    $m = $_GET['m'];
    $employeeId = $_GET['employeeId'];
    CommonHelper::companyDatabaseConnection(Input::get('m'));
    $employeeDetail = DB::table('employee')->where('id','=',$employeeId)->first();
    CommonHelper::reconnectMasterDatabase();
    $employeeFileNo = $employeeDetail->acc_no;
    $employeeNo = $employeeDetail->emp_no;
    $employeeName = $employeeDetail->emp_name;
    
?>
<style>
    tr td{
        padding: 2px !important;
    }
    tr th{
        padding: 2px !important;
    }
</style>
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/addTaxesDetail','id'=>'EOBIform'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <table class="table table-bordered sf-table-list">
                                        <thead>
                                        <tr>
                                            <th style="background-color: rgb(42, 110, 207);padding: 2px;">
                                                <div style="">
                                                    <div style="display:block;float:left;width:50%; margin-top: 7px;">&nbsp;&nbsp;<span style="color:white;">LEAVES </span></div>
                                                    <div class="text-right">
                                                        <?php
                                                        $total_leaves = round($total_leaves->total_leaves,1);
                                                        $taken_leaves = $taken_leaves->taken_leaves;?>
                                                        <span class="btn btn-success btn-sm" style="cursor: default">Taken Leaves = <?=$taken_leaves?></span>
                                                        <span class="btn btn-danger btn-sm" style="cursor: default">Remaining Leaves= <?=($total_leaves-$taken_leaves)?></span>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-bordered sf-table-list">
                                        <thead>
                                        <tr>
                                            <th class="text-center">S No#</th>
                                            <th class="text-center">Leaves Name</th>
                                            <th class="text-center">No of leaves</th>
                                            <th class="text-center">Taken Leaves</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $count = 1; $count_leaves = '';?>
                                        @foreach($leaves_policy as $val)
                                            <?php
                                                $count_leaves += $val->no_of_leaves;
                                            ?>
                                            <tr>
                                                <td style="color: green;"  class="text-center"><b>{{ $count++ }}</b></td>
                                                <td style="color: green;"><b>{{ HrHelper::getMasterTableValueById(Input::get('m'),'leave_type','leave_type_name',$val->leave_type_id )}}</b></td>
                                                <td class="text-center" style="color: green;"><b>{{ $val->no_of_leaves }}</b></td>
                                                <td class="text-center"><?php echo HrHelper::takenLeavesLeaveTypeWise()?></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th class="text-center" style="color: #fff;background-color: #2a6ecf; "colspan="2"><b>Total</b></th>
                                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf;"><b>{{ $count_leaves }}</b></th>
                                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf;"><b>{{ $count_leaves }}</b></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="TaxesSection[]" class="form-control" id="sectionEOBI" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Emp Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="" id="" value="{{ $emp_data->emp_name }}" disabled class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Emp Designation:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="" id="" value="{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$emp_data->designation_id)}}" disabled class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Select Leave Type</label>
                                        <select name="selectLeaveType" id="selectLeaveType" class="form-control">
                                            @foreach($leaves_policy as $abcVal)
                                                <option value="{{ $abcVal->leave_type_id }}">{{ HrHelper::getMasterTableValueById(Input::get('m'),'leave_type','leave_type_name',$abcVal->leave_type_id)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>Full Day Leave  :
                                            <input type="radio" name="leave_day_type" id="leave_day_type" value="full_day_leave" onclick="leaves_day_type(this.value)">
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>Half Day Leave :
                                            <input type="radio" name="leave_day_type" id="leave_day_type" value="half_day_leave" onclick="leaves_day_type(this.value)">
                                        </h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>Short Leave :
                                            <input type="radio" name="leave_day_type" id="leave_day_type" value="short_leave" onclick="leaves_day_type(this.value)">
                                        </h4>
                                    </div>

                                </div>
                                <div class="row" id="leave_days_area"></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Reason</label>
                                        <textarea id="reason" class="form-control requiredField"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 style="text-decoration: underline;"> <b>Terms & Condtions</b></h4>
                            <?php print_r($leaves_policy[0]->terms_conditions); ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <button type="button" onclick="check_days()" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    function leaves_day_type(type)
    {
        var current_date  = '<?= date("Y-m-d") ?>';
        if(type == 'full_day_leave')
        {


            $("#leave_days_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> No. of Days</label><input type="number" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                '<span id="warning_message" style="color:red"></span></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> To </label><input type="date" class="form-control requiredField" name="to_date" id="to_date"></div>');

        }
        else if(type == 'half_day_leave')
        {

            $("#leave_days_area").html('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div>');
        }
        else if(type == 'short_leave')
        {
            $("#leave_days_area").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div>');

        }

    }
    function check_days()
    {

        if(jqueryValidationCustom() == 1 || jqueryValidationCustom() == 0){

            var leaves_day_type = $("input[id='leave_day_type']:checked").val();

            if(leaves_day_type == 'full_day_leave'){

                var days_limit_one_para1 = '<?= $implode_days_one[1] ?>';
                var days_limit_para2 = '<?= $implode_days_one[0] ?>';
                var days_limit_two = '<?= $implode_days_two ?>';
                var inform_days_one = '<?= $leaves_policy[0]->informLeavesBeforeOne ?>';
                var inform_days_two = '<?= $leaves_policy[0]->informLeavesBeforeTwo ?>';
                var from_date = $('#from_date').val();
                var no_of_days = $('#no_of_days').val();
                //  alert(from_date);

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



                /*if(parseInt(no_of_days) >= days_limit_para2 && parseInt(no_of_days) <= days_limit_one_para1)
                {
                    if((inform_days_one-1) >   timeDifferenceInDays )
                    {
                        $('#warning_message').html('For Leaves Between ('+days_limit_para2+'-'+days_limit_one_para1+') then Inform Administration Before '+inform_days_one+' days !');
                        var check = false;
                    }
                    else
                    {
                        $('#warning_message').html('');
                        var check = true;
                    }

                }
                else if(parseInt(no_of_days) >= days_limit_two){

                    if((inform_days_two-1) > timeDifferenceInDays  )
                    {
                        $('#warning_message').html('For More then '+days_limit_two+' Leaves Inform Administration Before '+inform_days_two+' days !');
                        var check = false;
                    }
                    else
                    {
                        $('#warning_message').html('');
                        var check = true;
                    }



                }*/
                var check = true;
                if(check == true){

                    var emp_id = '<?= $employeeId ?>';
                    var emp_file_no = '<?= $employeeFileNo ?>';
                    var emp_no = '<?= $employeeNo ?>';
                    var selectLeaveType = $('#selectLeaveType').val();
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();

                    var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                    var from_date =  $("#from_date").val();
                    var to_date   = $("#to_date").val();
                    var leave_day_type = 1
                    var data = { company_id:company_id,selectLeaveType:selectLeaveType,emp_id:emp_id,emp_file_no:emp_file_no,emp_no:emp_no,leave_day_type:leave_day_type,no_of_days:no_of_days,from_date:from_date,to_date:to_date,reason:reason};


                }


            }
            else if(leaves_day_type == 'half_day_leave')
            {

                var emp_id = '<?= $employeeId ?>';
                var emp_file_no = '<?= $employeeFileNo ?>';
                var emp_no = '<?= $employeeNo ?>';
                var selectLeaveType = $('#selectLeaveType').val();
                var company_id = '<?= Input::get('m') ?>';
                var reason   = $("#reason").val();
                var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                var first_second_half = $("input[id='first_second_half']:checked").val();
                var no_of_days = (1*half_day_deduction_rate);
                var first_second_half_date =  $("#first_second_half_date").val();
                var leave_day_type = 2
                var data = {company_id:company_id,selectLeaveType:selectLeaveType,emp_id:emp_id,emp_file_no:emp_file_no,emp_no:emp_no,leave_day_type:leave_day_type,no_of_days:no_of_days,first_second_half:first_second_half,first_second_half_date:first_second_half_date,reason:reason};

            }
            else if(leaves_day_type == 'short_leave')
            {
                var emp_id = '<?= $employeeId ?>';
                var emp_file_no = '<?= $employeeFileNo ?>';
                var emp_no = '<?= $employeeNo ?>';
                var selectLeaveType = $('#selectLeaveType').val();
                var company_id = '<?= Input::get('m') ?>';
                var reason   = $("#reason").val();
                var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                var short_leave_time_from = $("#short_leave_time_from").val();
                var short_leave_time_to = $("#short_leave_time_to").val();
                var short_leave_date = $("#short_leave_date").val();
                var no_of_days = (1*per_hour_deduction_rate);
                var first_second_half_date =  $("#first_second_half_date").val();
                var leave_day_type = 3;
                var data = { company_id:company_id,selectLeaveType:selectLeaveType,emp_id:emp_id,emp_file_no:emp_file_no,emp_no:emp_no,leave_day_type:leave_day_type,no_of_days:no_of_days,short_leave_time_from:short_leave_time_from,short_leave_time_to:short_leave_time_to,short_leave_date:short_leave_date,reason:reason};

            }
            else
            {
                alert('Error ! Select Leave Day');
                return false;
            }

            var company_id = '<?= Input::get('m') ?>';
            $.ajax({
                url: '<?php echo url('/')?>/hadbac/addLeaveApplicationDetail',
                type: "GET",
                data: data,
                success:function(data) {

                    // var baseUrl = $('#url').val();
                    var url = $('#url').val()+'/hr/viewLeaveApplicationList?pageType=viewlist&&parentCode=21&&m='+company_id+'';
                    window.location.href =url;
                }
            });

        }
        else{ alert(jqueryValidationCustom());}
    }
</script>