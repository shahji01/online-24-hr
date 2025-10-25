<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
		
$m = Input::get('m');
$type = Input::get('type');


?>
<link rel="stylesheet" href="{{ URL::asset('assets/custom/css/print.css') }}">
<link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
<script src="{{ URL::asset('assets/custom/js/customMainFunction.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<br><br>

<?php

 	if($hr_warning_letter->count() == '0'){
    	?>
    		<h3 class="text-center text-danger">No Letter Found</h3>
    	<?php

    }
    else{
        $hr_warning_letter=$hr_warning_letter->first();
 ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
			@if($type != 'log')
				@if(in_array('print', $operation_rights2))
					<?php echo CommonHelper::displayPrintButtonInBlade('PrintHrWarningLetter','','1');?>
				@endif
			@endif
		</div>
	</div>
</div>
<br><br>
<div class="container" id="PrintHrWarningLetter">
	<div class="footer"></div>
	<div class="row warning-top-mar letter-head">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding1">
			<p>{{date('F d, Y')}}</p>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<p><b class="warning-right">EMP ID {{$hr_warning_letter->emp_id}}</b></p>
		</div>
	</div>
	<div class="row letter-head">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 padding1">
			<p class="warning-line-hei"><b>Mr. {{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name', $hr_warning_letter->emp_id, 'emp_id') }}</b></br>
				<b>S/o {{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_father_name', $hr_warning_letter->emp_id, 'emp_id') }}</b></br>
				{{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','residential_address', $hr_warning_letter->emp_id, 'emp_id') }}
			</p>
		</div>
	</div>
	<div class="row letter-head before-subject-margin">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 padding1">
			<p><b>Subject:</b><b class="bor-bot">Warning Letter</b></p>
		</div>
	</div>
	<div class="row letter-head">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 padding1">
			<p><b>Dear Mr. {{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name', $hr_warning_letter->emp_id, 'emp_id') }},</b></p>
		</div>
	</div>
	<div class="row letter-head">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
			<p class="warning-line-hei war-margin1" >
				<?php echo nl2br($hr_warning_letter->letter_content1) ?>
			</p>
		</div>
	</div>
	<?php if($hr_warning_letter->letter_content2 != '<p><br></p>') { ?>
		<div class="row page-break2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
				<p class="warning-line-hei war-margin1">
					<?php echo nl2br($hr_warning_letter->letter_content2);?>
				</p>
			</div>
		</div>
	<?php }?>
	<div class="row before-regards-margin after-regards-margin">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
			<p>Best Regards,</p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-1	2 col-sm-12 col-xs-12 warning-mar2">
			<p>Human Resource Manager</br>
				HR Department
			</p>
		</div>
	</div>
</div>
<?php
}
 ?>