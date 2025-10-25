<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\SubDepartment;
$count=1;
$current_date = date('Y-m-d');
$pf_opening_amount = 0;
?>
<style>
    td{ padding: 0px !important;}
    th{ padding: 0px !important;}
</style>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                @foreach($companiesList as $companyData)
                    <table class="table table-responsive table-bordered table-striped table-condensed">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                                <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo HrHelper::date_format($current_date);?></label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 30px !important; font-style: inherit;font-family: -webkit-body; font-weight: bold;">
                                        {{ $companyData->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                                <?php $nameOfDay = date('l', strtotime($current_date)); ?>
                                <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>
                            </div>
                            <div style="line-height:5px;">&nbsp;</div>
                        </div>
                        <?php
                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                        $pf_opening_balance = DB::table("provident_fund_data")
                            ->select('pf_amount','date')
                            ->where([['acc_no','=',$Employees->acc_no],['amount_type','=','plus'],['view','=','no']]);
                         if($pf_opening_balance->count() > 0):
                            $pfData = $pf_opening_balance->first();
                            $pf_opening_amount = $pfData->pf_amount;
                        ?>
                        <thead>
                            <tr>
                                <td>
                                    <?php
                                    echo "<b>Pf Opening Balance : ".$pf_opening_amount."</b>";
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    echo "<b>Pf Opening Balance Date : ".HrHelper::date_format($pfData->date)."</b>";
                                    ?>
                                </td>
                            </tr>
                        </thead>
                        <?php endif; ?>
                            <thead>
                            <tr style="background-color: #ddd;" >
                                <td colspan="28">
                                    <div class="row text-center">
                                        <h4><b><?= $SubDepartment->sub_department_name ?></b></h4>
                                    </div>
                                </td>
                            </tr>
                            </thead>
                            <thead>
                            <tr>
                                <th class="text-center">S No.</th>
                                <th class="text-center">Acc No.</th>
                                <th class="text-center">Emp Name </th>
                                <th class="text-center">Provident Funds </th>
                                <th class="text-center">Month </th>
                                <th class="text-center">Entry Date </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php

                                $total_pf_amount_disbursed = DB::table("provident_fund_data")
                                    ->select(DB::raw("SUM(pf_amount) as pf_amount_disbursed"),"date")
                                    ->where([['acc_no','=',$Employees->acc_no],['amount_type','=','minus'],['view', '=', 'yes']])
                                    ->first();

                                $total_pf_amount_disbursed_detail = DB::table("provident_fund_data")
                                    ->select('pf_amount','date','month')
                                    ->where([['acc_no','=',$Employees->acc_no],['amount_type','=','minus']])
                                    ->get();

                                $total_pf_amount_t = DB::table("provident_fund_data")
                                    ->select(DB::raw("SUM(pf_amount) as pf_amount_total"))
                                    ->where([['acc_no','=',$Employees->acc_no],['amount_type','=','plus']])
                                    ->first();
                                $total_pf_amount = DB::table("provident_fund_data")
                                    ->select('pf_amount','date','month')
                                    ->where([['acc_no','=',$Employees->acc_no],['amount_type','=','plus'],['view','=','yes']])
                                    ->orderBy('date','asc')
                                    ->get();

                                CommonHelper::reconnectMasterDatabase();
                                ?>

                                @if(count($total_pf_amount) > 0)
                                @foreach($total_pf_amount as $value4)
                                    <tr class="text-center">
                                        <td>{{$count++}}</td>
                                        <td>{{$Employees->acc_no}}</td>
                                        <td>{{$Employees->emp_name}}</td>
                                        <td>{{number_format($value4->pf_amount,0)}}</td>
                                        <td>{{$value4->month}}</td>
                                        <td><?php echo HrHelper::date_format($value4->date);?></td>
                                    </tr>

                                @endforeach

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right" style="font-weight: bold">Total (Including Opening Balance) </td>
                                    <td class="text-center" style="font-weight: bold">{{ $total_pf_amount_t->pf_amount_total}}</td>
                                    <td></td>
                                </tr>
                                <tr style="background-color: #ddd;" >
                                    <td colspan="28">
                                        <div class="row text-center">
                                            <h4><b>Provident Fund Disbursed</b></h4>
                                        </div>
                                    </td>
                                </tr>
                                <table class="table table-responsive table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S No.</th>
                                            <th class="text-center">Acc No.</th>
                                            <th class="text-center">Emp Name </th>
                                            <th class="text-center">Total Provident Fund </th>
                                            <th class="text-center">Fund Disbursed</th>
                                            <th class="text-center">Remaining Provident Fund</th>
                                            <th class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                          @if(count($total_pf_amount_disbursed_detail) > 0)
                                              @foreach($total_pf_amount_disbursed_detail as $value5)
                                                <tr class="text-center">
                                                    <td>{{$count++}}</td>
                                                    <td>{{$Employees->acc_no}}</td>
                                                    <td>{{$Employees->emp_name}}</td>
                                                    <td>{{$total_pf_amount_t->pf_amount_total}}</td>
                                                    <td>{{number_format($value5->pf_amount,0)}}</td>
                                                    <td>{{$total_pf_amount_t->pf_amount_total-$total_pf_amount_disbursed->pf_amount_disbursed}}</td>
                                                    <td>{{HrHelper::date_format($value5->date)}}</td>
                                                </tr>
                                              @endforeach
                                              <tr>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td class="text-center" style="font-size: 18px; font-weight: bold">Total : </td>
                                                  <td class="text-center" style="font-size: 18px; font-weight: bold">{{$total_pf_amount_disbursed->pf_amount_disbursed}}</td>
                                                  <td></td>
                                                  <td></td>
                                              </tr>
                                          @else
                                            <tr class="text-center">
                                                <td colspan="28">
                                                    <b style="color:red;" >Record Not Found</b>
                                                </td>
                                            </tr>
                                          @endif
                                        </tbody>
                                </table>
                                    @else
                                        <tr class="text-center">
                                            <td colspan="28">
                                                <b style="color:red;" >Record Not Found</b>
                                            </td>
                                        </tr>
                                    @endif
                                  </tbody>
                             </table>
                @endforeach
            </div>
        </div>
    </div>
</div>