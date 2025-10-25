<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\SubDepartment;
$count=1;
$current_date = date('Y-m-d');
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
                <table class="table table-bordered table-striped table-hover">
                    <?php $SubDepartment = SubDepartment::select('id','sub_department_name')->where([['company_id','=', $companyData->id]])->get()->toArray();?>
                        <div class="row">


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                            <br>
                        </div>
                        </div>
                    @foreach($SubDepartment as $value)
                            <?php
                            CommonHelper::companyDatabaseConnection($companyData->id);
                            $allEmployees = Employee::select('emp_name','emp_id','provident_fund_id')->where([['provident_fund_id','!=',0],['emp_sub_department_id','=',$value['id']]])->get()->toArray();
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            <thead>
                            <tr style="background-color: #ddd;" >
                                <td colspan="28">
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <h4><b><?= $value['sub_department_name'] ?></b></h4>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            </thead>
                            <thead>
                            <tr>
                                <th class="text-center">S No.</th>
                                <th class="text-center">Acc No.</th>
                                <th class="text-center">Emp Name </th>
                                <th class="text-center">Total Provident Fund </th>
                                <th class="text-center">Fund Disbursed</th>
                                <th class="text-center">Remaining Provident Fund</th>
                                <th class="text-center">Action </th>

                            </tr>
                            </thead>
                            <tbody>
                                @foreach($allEmployees as $value3)
                                    <?php
                                    CommonHelper::companyDatabaseConnection($companyData->id);
                                    $total_pf_amount = DB::table("provident_fund_data")
                                        ->select(DB::raw("SUM(pf_amount) as pf_amount"))
                                        ->where([['emp_id','=',$value3['emp_id']],['amount_type','=','plus']])
                                        ->first();
                                    $total_pf_amount_disbursed = DB::table("provident_fund_data")
                                        ->select(DB::raw("SUM(pf_amount) as pf_amount_disbursed"))
                                        ->where([['emp_id','=',$value3['emp_id']],['amount_type','=','minus']])
                                        ->first();
                                    CommonHelper::reconnectMasterDatabase();
                                    ?>
                                    <tr class="text-center">
                                        <td>{{$count++}}</td>
                                        <td>{{$value3['emp_id']}}</td>
                                        <td>{{$value3['emp_name']}}</td>
                                        <td>{{number_format($total_pf_amount->pf_amount,0)}}</td>
                                        <td>{{number_format($total_pf_amount_disbursed->pf_amount_disbursed,0)}}</td>
                                        <td>{{number_format($total_pf_amount->pf_amount-$total_pf_amount_disbursed->pf_amount_disbursed,0)}}</td>
                                        <td><button type="button" onclick="showDetailModelFourParamerter('hdc/disburseProvidentFundForm','<?php echo $value3['emp_id']."|".$value3['emp_name']."|".$value3['provident_fund_id']."|".$companyData->id;?>','Provident Fund Disburse Form','<?php echo Input::get('m'); ?>')" class="btn btn-primary btn-sm">Fund Disburse </button></td>
                                    </tr>
                                @endforeach
                            </tbody>

                    @endforeach

                </table>
            @endforeach
            </div>
        </div>
    </div>
</div>





