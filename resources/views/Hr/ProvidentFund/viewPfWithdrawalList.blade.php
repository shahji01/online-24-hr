<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View PF Withdrawal List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintAllownceList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('AllowanceList','','1')?>
                                @endif
                            </div>

                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4">
                                <div class="form-group has-search text-right">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id_search" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>

                        </div>
                        <span id="PrintAllownceList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped" id="TaxesList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">EMP ID</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">Department / Sub Department</th>
                                        <th class="text-center">Withdrawal Amount</th>
                                        <th class="text-center">Month-Year</th>
                                        <th class="text-center hidden-print">Actions</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($pf_withdrawal as $key => $value)
                                            <?php
                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $sub_department = Employee::select('sub_department_id','department_id')->where([['id', '=', $value->employee_id]])->first();
                                            CommonHelper::reconnectMasterDatabase();
                                            $monthName="";
                                            if($value->month > 0){
                                                $monthName = date('M', mktime(0, 0, 0, $value->month=(int)$value->month, 10));
                                            }
                                            ?>

                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $value->emp_id }}</td>
                                                <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->employee_id,'id') }}</td>
                                                 <td class="text-center">{{ HrHelper::getMasterTableValueByIdAndColumn($m,'department','department_name',$sub_department->department_id,'id') }}
                                                     / <small>{{ HrHelper::getMasterTableValueByIdAndColumn($m,'sub_department','sub_department_name',$sub_department->sub_department_id,'id') }}</small>
                                                </td>
                                                <td class="text-center">{{ number_format($value->amount,0) }}</td>
                                                <td class="text-center">{{ $monthName."-".$value->year }}</td>



                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle  btn-rounded btn-sm" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down" class="svg-icon" ></i></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            @if(in_array('edit', $operation_rights))
                                                                <li role="presentation" class="actionsLink">
                                                                    <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editPfWithdrawalDetailForm','<?php echo $value->id;?>','Edit PF Withdrawal Detail','<?php echo $m; ?>')">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($value->status == 1)
                                                                    <li role="presentation" class="actionsLink">
                                                                        <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','pf_withdrawal')">
                                                                            Delete
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif

                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                            </span>
                        <div class="text-center ajax-loader"></div>

                    </div>


                </div>

            </div>
        </div>

    </div>



    </div>


@endsection

