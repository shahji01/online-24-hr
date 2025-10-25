
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
                                <h4 class="card-title">PF For Employee List</h4>
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
                                            <th class="text-center hidden-print">EMP ID</th>
                                            <th class="text-center">Employee Name</th>
                                            <th  class="text-center">Provident Fund Amount</th>
                                            <th  class="text-center">Total Pf (Loan / Withdrawal)</th>
                                            <th  class="text-center">Pf Loan Paid</th>
                                            <!--<th  class="text-center">Profit</th>-->
                                            <!--<th  class="text-center">Profit Year From</th>-->
                                            <!--<th  class="text-center">Profit Year Till</th>-->
                                            <th  class="text-center">Withdrawal Type</th>
                                            <th class="text-center loan_fields_th">Deduction <br><small>(Per Month)</small></th>
                                            <th class="text-center loan_fields_th">Needed <br><small>(Month-Year)</small></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php if(count($list) != '0'){ ?>
                                        <?php $counter = 1;?>
                                        @foreach($list as $key => $y)
                                            <?php
                                            $profit_year_from='';
                                            $profit_year_till='';
                                            $profit=0;
                                            $pf_loan_amount=0;
                                            $pf_loan_paid=0;
                                            $per_month_deduction=0;
                                            $month_year='--';
                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $emp_name=Employee::select('emp_name')->where('id',$y->employee_id)->value('emp_name');
                                            $pf_profit=DB::table('pf_profit')->where([['employee_id','=',$y->employee_id],['pf_id','=',$y->id]]);
                                            if($pf_profit->count()>0){
                                                $pf_profit_first=$pf_profit->first();
                                                $profit_year_from=$pf_profit_first->year_from;
                                                $profit_year_till=$pf_profit_first->year_to;
                                                $profit=$pf_profit_first->profit;
                                            }
                                            $loan=DB::table('loan_request')->where([['employee_id','=',$y->employee_id],['pf_id','=',$y->id]]);
                                            if($loan->count()>0){
                                                $loan_first=$loan->first();
                                                $pf_loan_amount=$loan_first->loan_amount;
                                                $pf_loan_paid=$loan_first->pf_loan_paid;
                                                $monthNum  = $loan_first->month;
                                                $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                                $monthName = $dateObj->format('F');
                                                $month_year=$monthName."-".$loan_first->year;
                                                $per_month_deduction=$loan_first->per_month_deduction;

                                            }
                                            $pf_withdrawal=DB::table('pf_withdrawal')->select(DB::raw("SUM(amount) as amount"))
                                            ->where([['employee_id','=',$y->employee_id],['status','=',1]]);
                                             if($pf_withdrawal->count()>0){
                                                 $pf_withdrawal_first=$pf_withdrawal->first();
                                                  $pf_loan_amount=$pf_withdrawal_first->amount;
                                             }
                                            
                                            
                                            CommonHelper::reconnectMasterDatabase();
                                            ?>
                                            <tr class="text-center">
													<td class="text-center counterId" id="<?php echo $counter;?>">
                                                        <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
													</td>
													<td class="">{{ $y->emp_id}}</td>

                                                    <td class="">{{ $emp_name }}</td>

                                                <td class="text-center"><?php echo number_format($y->amount,0);?></td>
                                                <td class="text-center"><?php echo number_format($pf_loan_amount,0);?></td>
                                                <td class="text-center"><?php echo number_format($pf_loan_paid,0);?></td>
                                                <!--<td class="text-center"><?php echo number_format($profit,0);?></td>-->
                                                <!--<td class="text-center"><?php echo $profit_year_from;?></td>-->
                                                <!--<td class="text-center"><?php echo $profit_year_till;?></td>-->
                                                <td class="text-center"><?php echo $y->type;?></td>
                                                <td class="text-center"><?php echo number_format($per_month_deduction,0);?></td>
                                                <td class="text-center"><?php echo $month_year;?></td>

                                            </tr>

                                        @endforeach
                                        <?php } else{ ?>
                                        <tr>
											 <td colspan="22" class="text-danger text-center"><h4 class="text-danger"><strong>No Record Found</strong></h4></td>
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

