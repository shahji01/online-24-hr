<?php
$accType = Auth::user()->acc_type;
$m = $_GET['m'];
$current_date = date('Y-m-d');
use App\Helpers\CommonHelper;
use App\Models\Employee;
?>

<div>



        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/editIncomeTaxDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">



                        <div class="row">


                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="company_id" value="<?=$m?>">
                                <input type="hidden" name="id" value="{{ $income_tax->id }}">
                                <label class="sf-label">Employee Name:</label>
                                <?php CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m')); ?>
                                <input type="text" class="form-control" readonly value="{{ Employee::where('emp_id',$income_tax->emp_id)->where('status',1)->first()->emp_name }}" >
                                <?php CommonHelper::reconnectMasterDatabase(); ?>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Amount:</label>
                                <input type="number" name="amount" id="amount" value="{{ $income_tax->amount }}" class="form-control">
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <br>

                                <button type="submit" class="btn btn-sm btn-success" >Update</button>
                            </div>
                        </div>

                        <div class="text-center ajax-loader"></div>




    <?php echo Form::close(); ?>
</div>


