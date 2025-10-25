
<?php

$accType = Auth::user()->acc_type;
/*if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}*/
//$parentCode = $_GET['parentCode'];
$m = Input::get('m');

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\EmployeePromotion;


$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Income Tax List</h4>
                            </div>
                            <div class="col-sm-4 text-right">

                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeList','','1')?>
                                @endif


                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-6"></div>

                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <span id="PrintEmployeeList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                            <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="EmployeeList">
                                        <thead>
                                        <tr class="text-center">
                                            <th scope="col">S.No</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Emp Name</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Status</th>
                                            <th id="hide-table-row" class="hide-table hidden-print" scope="col" >Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php if(count($list) != '0'){ ?>
                                        <?php $counter = 1;?>
                                        @foreach($list as $key => $y)
                                            <tr class="text-center">
													<td class="text-center counterId" id="<?php echo $counter;?>">
                                                        <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
													</td>
													<td class="">{{ $y->emp_id}}</td>
                                                <?php CommonHelper::companyDatabaseConnection(Input::get('m')); ?>
                                                    <td class="">{{ Employee::where('emp_id',$y->emp_id)->where('status',1)->first()->emp_name }}</td>
                                                <?php CommonHelper::reconnectMasterDatabase(); ?>


                                                <td class="text-right"><?php echo number_format($y->amount,0);?></td>


                                                <td class="">{{ HrHelper::getStatusLabel($y->status)}}</td>
													<td id="hide-table-row" class="hide-table text-center hidden-print">
														 <div class="dropdown">
															<button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
																<i data-feather="chevron-down"
                                                                   class="svg-icon"></i></button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">


                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation" class="actionsLink">
                                                                    <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editIncomeTaxDetailForm','<?php echo $y->id;?>','Edit Income Tax Detail','<?php echo $m; ?>')">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                                @endif

                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($y->status == 1)
                                                                        <li role="presentation" class="actionsLink" onclick="deleteEmployee('<?php echo $m ?>','<?php echo $y->id ?>','income_tax','<?php echo $y->emp_id ?>')">
																			<a class="delete-modal btn" >
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
                                        <?php } else{ ?>
                                        <tr>
											 <td colspan="13" class="text-danger text-center"><h3 class="text-danger"><strong>No Record Found</strong></h3></td>
											</tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                                </span>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebart -->
        <!-- ============================================================== -->
    </div>

    <script>
        $(document).ready(function() {

            var table = $('#EmployeeList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false,


            });

            $('#emp_id_search').keyup( function() {
                table.search(this.value).draw();

            });





        });

        function deleteEmployee(companyId,recordId,tableName,emp_id){
            var companyId;
            var recordId;
            var tableName;
            var emp_id;
            if(confirm("Do you want to delete this record ?") == true){
                $.ajax({
                    url: baseUrl+'/cdOne/deleteIncomeTax',
                    type: "GET",
                    data: {'request_type':'delete',companyId:companyId,recordId:recordId,tableName:tableName,'emp_id':emp_id},
                    success:function(data) {
                        location.reload();
                    }
                });
            }
            else{
                return false;
            }
        }

    </script>








@endsection

