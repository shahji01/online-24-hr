<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$m = Input::get('m');
$type = Input::get('type');

$designation_id = HrHelper::getcompanyTableValueByIdAndColumn($m,'employee','designation_id', $hr_mfm_south_without_increment_letter->emr_no, 'emr_no');
$region = HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','region_id', $hr_mfm_south_without_increment_letter->emr_no, 'emr_no');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/custom/css/print.css') }}">
<link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
<script src="{{ URL::asset('assets/custom/js/customMainFunction.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<br><br>
<div class="container">
    <div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
			@if($type != 'log')
				@if(in_array('print', $operation_rights2))
					<?php echo CommonHelper::displayPrintButtonInBlade('PrintHrMfmSouthWithoutIncrementLetter','','1');?>
				@endif
			@endif
		</div>
    </div>
</div>
<br><br>
<div class="container" id="PrintHrMfmSouthWithoutIncrementLetter">
	<div class="footer">Footer</div>
	<div class="row warning-top-mar">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 letter-head padding1">
			<p>{{ HrHelper::hr_date_format($hr_mfm_south_without_increment_letter->date) }}</p>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 letter-head">
			<p><b class="warning-right">EMR #  {{$hr_mfm_south_without_increment_letter->emr_no}}</b></p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 letter-head padding1">
			<p class="warning-line-hei"><b>Mr. {{ HrHelper::getcompanyTableValueByIdAndColumn($m,'employee','emp_name', $hr_mfm_south_without_increment_letter->emr_no, 'emr_no') }}</b></br>
				<b>{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region', $region) }} Region</b>
			</p>
		</div>
	</div>
	<div class="row before-subject-margin">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 letter-head padding1">
			<p><b>Subject:</b>  <b class="bor-bot">Confirmation Letter </b></p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 letter-head padding1">
			<p><b>Dear Mr. {{ HrHelper::getcompanyTableValueByIdAndColumn($m,'employee','emp_name',$hr_mfm_south_without_increment_letter->emr_no, 'emr_no') }},</b></p>
		</div>
	</div>
	<div class="row letter-head">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2 letter-head">
			<p class="warning-line-hei war-margin1" >
				<?php echo nl2br($hr_mfm_south_without_increment_letter->letter_content1) ?>
			</p>
		</div>
	</div>
	<?php if($hr_mfm_south_without_increment_letter->letter_content2 != '<p><br></p>') { ?>
	<div class="row page-break2">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
			<p class="warning-line-hei war-margin1">
				<?php echo nl2br($hr_mfm_south_without_increment_letter->letter_content2);?>
			</p>
		</div>
	</div>
	<?php }?>
	<div class="row before-regards-margin after-regards-margin">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2 last-margin">
			<p>Best Regards,</p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
			<p class="warning-line-hei"><b>Hira Nasir</b></br>
				Human Resource Manager
			</p>
		</div>
	</div>
	<div class="row before-signature-margin after-hr-margin">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2 ">
			<p class="warning-line-hei letter-head">I accept the company's terms and conditions and confirm my taking up the position of {{ HrHelper::getMasterTableValueById($m,'designation','designation_name', $designation_id) }}.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
			<p class="warning-line-hei">Employee Name & Signature: <b>{{ HrHelper::getcompanyTableValueByIdAndColumn($m,'employee','emp_name', $hr_mfm_south_without_increment_letter->emr_no, 'emr_no') }}</b></br>
				Date:
			</p>
		</div>
	</div>
</div>


