<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Bonus;
use App\Models\BonusIssue;
use App\Models\EmployeePromotion;
$counter = 1;
$data1 = '';
$bonus = 0;
$bonusAmount = 0;
$m = Input::get('m');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 style="text-align:center;font-weight:bold;">
                                    This list contains only those Employees who have been working here for a year or more
                                </h5>
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Joining Date</th>
                                    <th class="text-center">Salary</th>
                                    <th class="text-center">Bonus</th>
                                    <th class="text-center">Bonus Month & Year</th>
                                    <th class="text-center">Bonus Pay</th>
                                    </thead>
                                    <tbody>
                                    <?php foreach($all_employees as $key => $value):
                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $get_bonus_data = BonusIssue::where([['status','=',1],['bonus_id','=',Input::get('bonus_id')],['employee_id','=',$value->id],['bonus_month','=',$month_year[1]],['bonus_year','=',$month_year[0]]]);
                                    $get_promotion_date = EmployeePromotion::where([['status','=',1],['employee_id','=',$value->id]])->orderBy('id','desc');
                                    if($get_bonus_data->count() > 0){
                                        $get_bonus = $get_bonus_data->first();
                                    }
                                    else{
                                        $get_bonus=0;
                                    }
                                    CommonHelper::reconnectMasterDatabase();

                                    $date1 = $value->joining_date;
                                    $emp_date=explode('-',$date1);
                                    $date1=$emp_date[0].'-'.$emp_date[1];
                                    $date2 = $monthYearDay;
                                    $d1=new DateTime($date2);
                                    $d2=new DateTime($date1);
                                    $Months = $d2->diff($d1);

                                    $ts1 = strtotime($value->joining_date);
                                    $ts2 = strtotime($bonus_month);

                                    $empDay = date('d', $ts1);

                                    if($empDay <= 15){
                                        $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                                    }else{
                                        $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                                        $diff--;
                                        $diff;
                                    }
                                    ?>
                                    @if($diff >= 12)
                                        <?php
                                        $date1 = $value->joining_date;
                                        $emp_date=explode('-',$date1);
                                        $date1=$emp_date[0].'-'.$emp_date[1];
                                        $date2 = $bonus_month."-31";
                                        $d1=new DateTime($date2);
                                        $d2=new DateTime($date1);
                                        $Months = $d2->diff($d1);
                                        if($empDay <= 15){
                                            $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                                        }else{
                                            $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                                            $diff--;
                                            $diff;
                                        }
                                        ?>
                                        <tr id="bonusId{{ $value->id }}">
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td class="text-center">{{ $value->emp_id }}</td>
                                            <td>{{ $value->emp_name }}</td>
                                            <td class="text-center">{{ HrHelper::date_format($value->joining_date) }}</td>
                                            <td class="text-right">
                                                @if($get_promotion_date->count() > 0)
                                                    <?php
                                                    $get_promotion = $get_promotion_date->first();
                                                    ?>
                                                    <?=number_format($get_promotion->salary,0)?>
                                                @else
                                                    <?=number_format($value->salary,0)?>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <input type="hidden" name="emp_id[]" value="{{$value->emp_id}}">
                                                <input type="hidden" name="employee_id[]" value="{{$value->id}}">
                                                @if($get_percent->type =="Percent")
                                                    @if($get_promotion_date->count() > 0)
                                                        @if($diff >= 12)
                                                            <?php $bonus_amount=($get_percent->percent_of_salary/100)*$get_promotion->salary; ?>
                                                            <input type="text" name="check_list[]" class="form-control" value="<?=$get_bonus != '0' ? $get_bonus->bonus_amount: $bonus_amount; ?>">
                                                        @endif
                                                    @else
                                                        @if($diff >= 12)
                                                            <?php $bonus_amount=($get_percent->percent_of_salary/100)*$value->salary; ?>
                                                            <input type="text" name="check_list[]" class="form-control" value="<?=$get_bonus != '0'?$get_bonus->bonus_amount:$bonus_amount; ?>">
                                                        @endif

                                                    @endif
                                                @else
                                                    <input type="text" name="check_list[]" class="form-control" value="<?=$get_bonus != '0'?$get_bonus->bonus_amount:$get_percent->percent_of_salary?>">

                                                @endif
                                            </td>
                                            <?php
                                            $old_date_format = Input::get('bonus_month_year');
                                            $new_data_format = date("M-Y", strtotime($old_date_format));
                                            ?>
                                            <td class="text-center"><?=$new_data_format?></td>
                                            <td class="text-center">
                                                @if($bonus_pay == 1) With Salary @else Separately @endif
                                                <input type="hidden" name="bonus_pay[]" value="{{$bonus_pay}}">
                                            </td>
                                        </tr>
                                    @endif
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                                <button class="btn btn-sm btn-success" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
CommonHelper::reconnectMasterDatabase(); ?>
<script>

    $(".ads_Checkbox").change(function() {
        if(this.checked) {
            $('.btn-success').prop('disabled', false);
        }
        else{
            $('.btn-success').prop('disabled', true);
        }
    });


    $(function(){
        $("#check_all").click(function(){

            if($("#check_all").prop("checked") == true)
            {
                $(".ads_Checkbox").prop("checked",true);
                $('.btn-success').prop('disabled', false);
            }
            else
            {
                $(".ads_Checkbox").prop("checked",false);
                $('.btn-success').prop('disabled', true);
            }
        });
    });

    function removeBonus(id){
        var id;
        var m = '<?=Input::get('m')?>';
        var _token = $("meta[name=csrf-token]").attr("content");

        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url : ""+baseUrl+"/cdOne/deleteEmployeeBonus",
                type: "GET",
                data: {id:id, m:m, _token:_token},
                success: function (data) {
                    $("#bonusId"+id).fadeOut();
                },
                error: function () {
                    console.log("error");
                }
            });
        }
    }

</script>