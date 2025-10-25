<?php
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$m = $_GET['m'];
$current_date = date('Y-m-d');

CommonHelper::companyDatabaseConnection(\Illuminate\Support\Facades\Input::get('m'));
$emp_name=DB::table('employee')->where('emp_id',$pf_profit->emp_id)->first()->emp_name;
CommonHelper::reconnectMasterDatabase();

?>






<div class="well">
    <div class="container-fluid">
        <?php echo Form::open(array('url' => 'had/editPfProfitDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="<?= Input::get('m') ?>">
        <input type="hidden" name="emp_id" value="<?= $pf_profit->emp_id ?>">
        <input type="hidden" name="id" value="<?= $pf_profit->id ?>">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Emp Name:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" readonly class="form-control" value="{{ $emp_name }}" name="emp_name" id="emp_name">

            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Profit Amount:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" class="form-control requiredField" value="{{ $pf_profit->profit }}" name="pf_profit_amount" id="pf_profit_amount">

            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Year From:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" class="form-control requiredField" value="{{ $pf_profit->year_from }}" name="year_from"  placeholder="YYYY" pattern="\d*" maxlength="4" minlength="4">
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Year To:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" class="form-control requiredField" value="{{ $pf_profit->year_to }}" name="year_to"  placeholder="YYYY" pattern="\d*" maxlength="4" minlength="4">
            </div>


        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                <br>
                <!--<input style="width: 24px;height: 20px;margin-top: 8px;" type="checkbox" id="show_all" name="show_all" value="show_all">-->
                <!--<label  for="show_all"> Show All </label>&nbsp;&nbsp;-->
                <button type="submit" class="btn btn-sm btn-success" style="margin-top: 12px;">Submit</button>
            </div>
        </div>
    </div>
    <?php echo Form::close();?>
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





