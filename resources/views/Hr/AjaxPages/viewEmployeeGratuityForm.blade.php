<?php
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\EmployeePromotion;
use App\Models\Gratuity;

$emr_no = array();
$total_gratuity = 0;

?>
<div class="panel">
    <div class="panel-body" id="PrintGratuity">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="Gratuity">
                        <thead>
                        <th class="text-center">S.No</th>
                        <th class="text-center">EMR NO</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Emp Project</th>
                        <th class="text-center">From Date</th>
                        <th class="text-center">Till Date</th>
                        <th class="text-center">Salary</th>
                        <th class="text-center col-sm-1">Months</th>
                        <th class="text-center">Gratuity Amount</th>
                        <th class="text-center">Status</th>
                        </thead>
                        <tbody>
                        <?php $counter = 1; $counter2 = 0;?>
                        @foreach($employee as $value)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $gratuity = 0;
                            $from_date = $value['emp_joining_date'];
                            $till_date = Input::get('till_date');
                            $salary = $value['emp_salary'];

                            $lastGratuity = Gratuity::where([['emr_no', '=', $value['emr_no']]])->orderBy('id', 'desc');
                            if($lastGratuity->count() > 0):
                                $lastGratuityDetail = $lastGratuity->first();
                                $from_date = date('Y-m-d', strtotime($lastGratuityDetail->to_date. ' + 1 day'));
                            endif;

                            $employeeCurrentPositions = EmployeePromotion::where([['emr_no','=',$value['emr_no']],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
                            if($employeeCurrentPositions->count() > 0):
                                $salary = $employeeCurrentPositions->value('salary');
                            endif;

                            $diff = abs(strtotime($till_date) - strtotime($from_date));

                            $years = floor($diff / (365*60*60*24));

                            $months = floor(($diff - $years * 365*60*60*24)
                                / (30*60*60*24));
                            $months = $months + ($years * 12);

                            if($from_date != 0000-00-00):
                                if($months >= 12):
                                    $gratuity = round(($salary) / 12 * ($months));
                                    $emr_no[] = $value['emr_no'];
                                    $total_gratuity+=$gratuity;
                                endif;
                            endif;
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center">{{ $value['emr_no']}}</td>
                                <td class="text-center">{{ $value['emp_name']}}</td>
                                <?php if($value['employee_project_id'] !== '0'){ ?>
                                 <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_projects','project_name',$value['employee_project_id'])}}</td>
                                <?php } else{?>
                                 <td class="text-center">-</td>
                                <?php } ?>
                                <td class="text-center">{{ $from_date }}
                                    <input readonly type="hidden" class="form-control" name="from_date_<?=$value['emr_no']?>" value="{{ $from_date }}">
                                    <input readonly type="hidden" class="form-control" name="emp_category_id_<?=$value['emr_no']?>" value="{{ $value['employee_category_id'] }}">
                                    <input readonly type="hidden" class="form-control" name="region_id_<?=$value['emr_no']?>" value="{{ $value['region_id'] }}">
                                </td>

                                <td class="text-center">{{ $till_date }}
                                    <input readonly type="hidden" class="form-control" name="till_date_<?=$value['emr_no']?>" value="{{ $till_date }}">
                                </td>
                                <td class="text-right">{{ number_format($salary,0) }}
                                    <input readonly type="hidden" class="form-control" name="emp_salary_<?=$value['emr_no']?>" value="{{ $salary }}">
                                </td>
                                <td class="text-center"><input @if($months < 12) readonly @endif   @if($from_date == 0000-00-00) readonly @endif type="text" onkeyup="calculateGratuity('{{ $salary }}', '{{ $value['emr_no'] }}', this.value);" class="form-control" name="year_month_<?=$value['emr_no']?>" value="{{ $months }}"></td>
                                <td class="text-right">{{ number_format($gratuity,0) }}
                                    <input @if($months < 12) readonly @endif  @if($from_date == 0000-00-00) readonly @endif type="hidden" class="form-control total_gratuity2 gratuity2_<?=$value['emr_no']?>" name="gratuity_<?=$value['emr_no']?>" value="{{ $gratuity }}">
                                </td>
                                <td class="text-center">@if($months < 12)<?php $counter2++?><p style="color:red">Not Applicable</p>@endif @if($from_date == 0000-00-00) <p style="color:red">DOJ not found</p> @endif</td>
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            <td colspan="7" class="text-right" style="font-size: 16px"><b>Total</b></td>
                            <td colspan="1" class="text-right"><b class="set_total_gratuity" style="font-size: 16px">{{ number_format($total_gratuity,2) }}</b></td>
                            <td colspan="1"></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-right"><b>Employee Less then 1 year (Non Calculated) </b></td>
                            <td colspan="1" class="text-right"><b>{{ number_format($counter2,0) }}</b></td>
                            <td colspan="1"></td>
                        </tr>
                        </tfoot>
                        </tbody>
                    </table>

                    <div style="float: right;">
                        <input type="hidden" name="emr_no" value="<?php print base64_encode(serialize($emr_no)) ?>">;
                        <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>



    function calculateGratuity(salary, id, month)
    {
        var totalPoints = 0;
        if(month >= 12)
        {
            $(".gratuity2_"+id).val(parseInt((salary / 12)  * month));

            $('.total_gratuity2').each(function(){
                totalPoints += parseFloat($(this).val());
            });
            $('.set_total_gratuity').html(totalPoints);
        }

        else
        {
            $(".gratuity2_"+id).val('');
        }

    }







</script>
