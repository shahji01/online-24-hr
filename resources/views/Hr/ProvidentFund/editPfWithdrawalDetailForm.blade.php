<?php
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$m = $_GET['m'];
$current_date = date('Y-m-d');

CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
$emp_name=DB::table('employee')->where('id',$pf_withdrawal->employee_id)->first()->emp_name;
CommonHelper::reconnectMasterDatabase();

?>






<div class="well">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/editPfWithrawalDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                <input type="hidden" name="employee_id" value="<?= $pf_withdrawal->employee_id ?>">
                <input type="hidden" name="id" value="<?= $pf_withdrawal->id ?>">

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Emp Name:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" readonly class="form-control" value="{{ $emp_name }}" name="emp_name" id="emp_name">

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Withdrawal Amount:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" class="form-control requiredField" value="{{ $pf_withdrawal->amount }}" name="pf_withdrawal_amount" id="pf_withdrawal_amount">

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Month Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" class="form-control requiredField" value="{{ $pf_withdrawal->month_year }}" name="month_year" id="month_year">

                            </div>


                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right">

                                <!--<input style="width: 24px;height: 20px;margin-top: 8px;" type="checkbox" id="show_all" name="show_all" value="show_all">-->
                                <!--<label  for="show_all"> Show All </label>&nbsp;&nbsp;-->
                                <button type="submit" class="btn btn-sm btn-success" style="margin-top: 12px;">Submit</button>
                            </div>
                        </div>

                </div>

            </div>
        </div>
</div>






    <?php echo Form::close();?>
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




