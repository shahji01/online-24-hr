<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\EmployeeBankData;
use App\Models\Overtime;
use App\Models\Fuel;
use App\Models\DriversAllowances;


$total_ot_claimed_hours = 0;
$total_ot_verified_hours = 0;
$total_ot_for_month = 0;

$total_km = 0;
$total_km_amount = 0;

$total_ot_labour_law = 0;
$total_convc_allowance_holiday = 0;
$total_convc_allowance_workday = 0;
$total_parking_charges = 0;
$total_out_of_city = 0;
$total_puncture = 0;
$total_mobile_charges = 0;
$total_allowance = 0;



?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}

</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
            <div class="">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="text-center"><h1 style="text-decoration: underline">Overtime</h1></div>
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered table-condensed" id="LeavesPolicyList">
                            <thead>
                            <tr>
                                <th class="text-center">Sno.</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Designation</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">EMR#</th>
                                <th class="text-center">Gross Salary</th>
                                <th class="text-center">O.T Claim(Hrs)</th>
                                <th class="text-center">O.T Verified(Hrs)</th>
                                <th class="text-center">P/H O.T rate</th>
                                <th class="text-center">O.T For Month</th>
                                <th class="text-center">Bank Acc No.</th>

                            </tr>

                            </thead>
                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $overtime = Overtime::where([['employee_category_id','=',$data['employee_category_id']],
                                    ['region_id', '=', $data['region_id']],["month","=",$month_year[1]],
                                    ["year","=",$month_year[0]]])->get()->toArray();
                            CommonHelper::reconnectMasterDatabase();
                            $counter  =1 ;
                            foreach($overtime as $value1):
                            $total_ot_claimed_hours += $value1['ot_claimed_hours'];
                            $total_ot_verified_hours += $value1['ot_verified_hours'];
                            $total_ot_for_month += $value1['ot_for_month'];
                            ?>
                            <tr class="text-center">
                                <td class="text-center"> {{$counter++}}</td>
                                <td class="text-center">
                                    <?php
                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $empdata = Employee::select('designation_id','emp_name','branch_id')->where([['emr_no','=',$value1['emr_no']]]);
                                    $accNo = EmployeeBankData::where([['status','=',1],['emr_no','=',$value1['emr_no']]])->value('account_no');
                                    CommonHelper::reconnectMasterDatabase();
                                    echo $empdata->value('emp_name');
                                    ?>
                                </td>
                                <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',($empdata->value('designation_id') != '') ? $empdata->value('designation_id'): '0')}}</td>
                                <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',($empdata->value('branch_id') != '') ? $empdata->value('branch_id'): '0')}}</td>
                                <td class="text-center">{{$value1['emr_no']}}</td>
                                <td class="text-right">{{number_format($value1['gross_salary'],0)}}</td>
                                <td class="text-right">{{$value1['ot_claimed_hours']}}</td>
                                <td class="text-right">{{$value1['ot_verified_hours']}}</td>
                                <td class="text-right">{{$value1['per_hour_ot_rate']}}</td>
                                <td class="text-right">{{number_format($value1['ot_for_month'],0)}}</td>
                                <td class="text-center">
                                    <?php echo $accNo;?>
                                </td>


                            </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="6" class="text-right"><b>Total</b></td>
                                <td class="text-right"><b>{{number_format($total_ot_claimed_hours,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_ot_verified_hours,0)}}</b></td>
                                <td class="text-right"></td>
                                <td class="text-right"><b>{{number_format($total_ot_for_month,0)}}</b></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-center"><h1 style="text-decoration: underline">Fuel</h1></div>
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered table-condensed" id="LeavesPolicyList">
                            <thead>
                            <tr>
                                <th colspan="7"></th>
                                <th colspan="3" class="text-center">Fuel</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th class="text-center">Sno.</th>
                                <th class="text-center">Emp Name</th>
                                <th class="text-center">Designation</th>
                                <th class="text-center">CNIC</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">EMR#</th>
                                <th class="text-center">Monthly Salary</th>
                                <th class="text-center">Km</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Bank Acc No.</th>

                            </tr>

                            </thead>
                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $fuel = fuel::where([['employee_category_id','=',$data['employee_category_id']],['region_id', '=', $data['region_id']],["month","=",$month_year[1]],["year","=",$month_year[0]]])->get()->toArray();
                            CommonHelper::reconnectMasterDatabase();
                            $counter2  =1 ;
                            foreach($fuel as $value2):
                            $total_km+=$value2['km'];
                            $total_km_amount+=$value2['amount'];
                            ?>
                            <tr class="text-center">
                                <td class="text-center"> {{$counter2++}}</td>
                                <td class="text-center">
                                    <?php
                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $empdata = Employee::select('emp_cnic','emp_name','branch_id','designation_id')->where([['emr_no','=',$value2['emr_no']]]);
                                    $accNo = EmployeeBankData::where([['status','=',1],['emr_no','=',$value2['emr_no']]])->value('account_no');
                                    CommonHelper::reconnectMasterDatabase();
                                    echo $empdata->value('emp_name');
                                    ?>
                                </td>
                                <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',($empdata->value('designation_id') != '') ? $empdata->value('designation_id'): '0')}}</td>
                                <td class="text-center">{{$empdata->value('emp_cnic')}}</td>
                                <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',($empdata->value('branch_id') != '') ? $empdata->value('branch_id'): '0')}}</td>
                                <td class="text-center">{{$value2['emr_no']}}</td>
                                <td class="text-right">{{number_format($value2['monthly_salary'],0)}}</td>
                                <td class="text-right">{{$value2['km']}}</td>
                                <td class="text-right">{{$value2['rate']}}</td>
                                <td class="text-right">{{number_format($value2['amount'],0)}}</td>
                                <td class="text-center"><?php echo $accNo;?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tfoot>
                            <tr>
                                <td colspan="7" class="text-right"><b>Total</b></td>
                                <td class="text-right"><b>{{ number_format($total_km,0) }}</b></td>
                                <td></td>
                                <td class="text-right"><b>{{ number_format($total_km_amount,0) }}</b></td>
                                <td></td>
                            </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center"><h1 style="text-decoration: underline">Driver</h1></div>
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered table-condensed" id="LeavesPolicyList">
                            <thead>
                            <tr>
                                <th class="text-center">Sno.</th>
                                <th class="text-center">Emp Name</th>
                                <th class="text-center">Designation</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">Cost Center</th>
                                <th class="text-center">Psgl</th>
                                <th class="text-center">Salary</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Hours</th>
                                <th class="text-center">O.T as per Law</th>
                                <th class="text-center">Convc. Allowance Holiday</th>
                                <th class="text-center">Convc. Allowance W.day</th>
                                <th class="text-center">Parking Charges</th>
                                <th class="text-center">Out Of City</th>
                                <th class="text-center">Puncture</th>
                                <th class="text-center">Mobile Charges</th>
                                <th class="text-center">Total Allowance</th>
                                <th class="text-center">Bank Acc No.</th>

                            </tr>

                            </thead>
                            <tbody>
                            <?php

                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $drivers_allowances = DriversAllowances::where([['employee_category_id','=',$data['employee_category_id']],['region_id', '=', $data['region_id']],["month","=",$month_year[1]],["year","=",$month_year[0]]])->get();
                            CommonHelper::reconnectMasterDatabase();
                            $counter3  =1 ;
                            foreach($drivers_allowances as $value3):
                            $total_ot_labour_law += $value3['ot_labour_law'];
                            $total_convc_allowance_holiday += $value3['allowance_on_holiday'];
                            $total_convc_allowance_workday += $value3['allowance_on_workingday'];
                            $total_parking_charges += $value3['parking_charges'];
                            $total_out_of_city += $value3['out_of_city'];
                            $total_puncture += $value3['puncture'];
                            $total_mobile_charges += $value3['mobile_charges'];
                            $total_allowance += $value3['total_allowance'];
                            ?>
                            <tr class="text-center">
                                <td class="text-center">{{$counter3++}}</td>
                                <td class="text-center">{{$value3['emp_name']}}</td>
                                <td class="text-center">{{$value3['designation']}}</td>
                                <td class="text-center">{{$value3['location']}}</td>
                                <td class="text-right">{{$value3['cost_center']}}</td>
                                <td class="text-right">{{$value3['psgl']}}</td>
                                <td class="text-right">{{number_format($value3['salary'],0)}}</td>
                                <td class="text-right">{{$value3['rate']}}</td>
                                <td class="text-right">{{$value3['hours']}}</td>
                                <td class="text-right">{{number_format($value3['ot_labour_law'],0)}}</td>
                                <td class="text-right">{{number_format($value3['allowance_on_holiday'],0)}}</td>
                                <td class="text-right">{{number_format($value3['allowance_on_workingday'],0)}}</td>
                                <td class="text-right">{{number_format($value3['parking_charges'],0)}}</td>
                                <td class="text-right">{{number_format($value3['out_of_city'],0)}}</td>
                                <td class="text-right">{{number_format($value3['puncture'],0)}}</td>
                                <td class="text-right">{{number_format($value3['mobile_charges'],0)}}</td>
                                <td class="text-right">{{number_format($value3['total_allowance'],0)}}</td>
                                <td class="text-center">{{$value3['bank_account_no']}}</td>
                            </tr>
                            <?php endforeach; ?>
                            <tfoot>
                            <tr>
                                <td class="text-right" colspan="9"><b>Total</b></td>
                                <td class="text-right"><b>{{number_format($total_ot_labour_law,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_convc_allowance_holiday,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_convc_allowance_workday,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_parking_charges,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_out_of_city,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_puncture,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_mobile_charges,0)}}</b></td>
                                <td class="text-right"><b>{{number_format($total_allowance,0)}}</b></td>
                                <td></td>
                            </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
