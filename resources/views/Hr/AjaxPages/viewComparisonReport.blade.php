<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\EmployeeCategory;
use App\Models\Regions;
use App\Models\EmployeePromotion;




if($explodeMonthYearFrom[1] > 0){
    $monthNameFrom = date('M', mktime(0, 0, 0, $explodeMonthYearFrom[1]=(int)$explodeMonthYearFrom[1], 10));
}



if($explodeMonthYearTo[1] > 0){
    $monthNameTo = date('M', mktime(0, 0, 0, $explodeMonthYearTo[1]=(int)$explodeMonthYearTo[1], 10));
}



?>

<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }
    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

</style>



<div class="row">
    <div class="col-sm-12">
        <h2 class="text-center"><b>Employees Count</b></h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="3" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Emp Name</b></th>
                <th class=""><b style="font-size:18px;">Joining Date</b></th>
                </thead>
                <tbody>
                @if($employeesFrom->count() >0)
                    <?php $counter = 1;?>
                    @foreach($employeesFrom as $emp_from)
                    <tr>
                        <td class="text-center counterId" id="<?php echo $counter;?>">
                            <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                        </td>
                        <td class=""><b>{{ $emp_from->emp_name }}</b></td>
                        <td class=""><b>{{ HrHelper::date_format($emp_from->emp_joining_date) }}</b></td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="3" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>


            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="3" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Emp Name</b></th>
                <th class=""><b style="font-size:18px;">Joining Date</b></th>
                </thead>
                <tbody>
                @if($employeesTo->count() >0)
                    <?php $counter1 = 1;?>
                    @foreach($employeesTo as $emp_to)
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter1;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter1++;?></span>
                            </td>
                            <td class=""><b>{{ $emp_to->emp_name }}</b></td>
                            <td class=""><b>{{ HrHelper::date_format($emp_to->emp_joining_date) }}</b></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="3" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>


            </table>
        </div>

    </div>
</div>

<hr><br>

<div class="row">
    <div class="col-sm-12">
        <h2 class="text-center"><b>Allowance Count</b></h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>

                </thead>
                <tbody>
                @if($allowance_from->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($allowance_from as $allow_from)
                        @if($allow_from->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $allow_from->month=(int)$allow_from->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$allow_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ HrHelper::getMasterTableValueById(Input::get('m'),'allowance_types','allowance_type', $allow_from->allowance_type_id) }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$allow_from->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($allow_from->allowance_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($allowance_from->count() >0)
                <tfoot>
                <tr>
                    <th class="text-right" colspan="4"><b>Total Amount</b></th>
                   <td class="text-right"><b>{{ number_format($allowance_from->sum('allowance_amount'),0) }}</b></td>
                </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>


                </thead>
                <tbody>
                @if($allowance_to->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($allowance_to as $allow_to)
                        @if($allow_to->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $allow_to->month=(int)$allow_to->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$allow_to->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ HrHelper::getMasterTableValueById(Input::get('m'),'allowance_types','allowance_type', $allow_to->allowance_type_id) }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$allow_to->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($allow_to->allowance_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($allowance_to->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($allowance_to->sum('allowance_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>

    </div>
</div>


<hr><br>

<div class="row">
    <div class="col-sm-12">
        <h2 class="text-center"><b>Deduction Count</b></h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Deduction</b></th>


                </thead>
                <tbody>
                @if($deduction_from->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($deduction_from as $deduct_from)
                        @if($deduct_from->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $deduct_from->month=(int)$deduct_from->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$deduct_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $deduct_from->deduction_type}}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$deduct_from->year }}</b></td>
                            <td class="text-right"><b>{{ $deduct_from->deduction_type=="LWP"? $deduct_from->deduction_amount.' Day':number_format($deduct_from->deduction_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($deduction_from->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($deduction_from->sum('deduction_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">Type</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Deduction</b></th>


                </thead>
                <tbody>
                @if($deduction_to->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($deduction_to as $deduct_to)
                        @if($deduct_to->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $deduct_to->month=(int)$deduct_to->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$deduct_to->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $deduct_to->deduction_type }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$deduct_to->year }}</b></td>
                            <td class="text-right"><b>{{ $deduct_to->deduction_type=="LWP"? $deduct_to->deduction_amount.' Day':number_format($deduct_to->deduction_amount,0) }}</b></td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($deduction_to->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="4"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($deduction_to->sum('deduction_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>

    </div>
</div>




<hr><br>

<div class="row">
    <div class="col-sm-12">
        <h2 class="text-center"><b>Loan Count</b></h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameFrom.' '.$explodeMonthYearFrom[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>


                </thead>
                <tbody>
                @if($loan_from->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($loan_from as $l_from)
                        @if($l_from->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $l_from->month=(int)$l_from->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$l_from->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$l_from->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($l_from->loan_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($loan_from->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="3"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($loan_from->sum('loan_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif



            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="">
            <table class="table table-sm mb-0 table-bordered table-striped">
                <thead>
                <th class="text-center" colspan="5" ><b style="font-size:18px;">{{ $monthNameTo.' '.$explodeMonthYearTo[0] }}  </b></th>
                </thead>
                <thead>
                <th class=""><b style="font-size:18px;">S.No</b></th>
                <th class=""><b style="font-size:18px;">Name</b></th>
                <th class=""><b style="font-size:18px;">M/Y</b></th>
                <th class=""><b style="font-size:18px;">Amount</b></th>

                </thead>

                <tbody>

                @if($loan_to->count() >0)
                    <?php $counter = 1;
                    $monthName;
                    ?>
                    @foreach($loan_to as $l_to)
                        @if($l_to->month > 0)
                            <?php $monthName = date('M', mktime(0, 0, 0, $l_to->month=(int)$l_to->month, 10)); ?>
                        @endif
                        <tr>
                            <td class="text-center counterId" id="<?php echo $counter;?>">
                                <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                            </td>
                            <td class=""><b>{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$l_to->emp_id,'emp_id') }}</b></td>
                            <td class=""><b>{{ $monthName.'-'.$l_to->year }}</b></td>
                            <td class="text-right"><b>{{ number_format($l_to->loan_amount,0) }}</b></td>


                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5" style="color:red;"><b>No Record Found</b></td>
                    </tr>
                @endif
                </tbody>
                @if($loan_to->count() >0)
                    <tfoot>
                    <tr>
                        <td class="text-right" colspan="3"><b>Total Amount</b></td>
                        <td class="text-right" colspan="1"><b>{{ number_format($loan_to->sum('loan_amount'),0) }}</b></td>
                    </tr>
                    </tfoot>
                @endif

            </table>
        </div>

    </div>
</div>


















