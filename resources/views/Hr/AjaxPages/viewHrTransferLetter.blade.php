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
            <p><b class="warning-right">EMR # {{$hr_transfer_letter->emr_no}}</b></p>
        </div>
    </div>
    <div class="row letter-head">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 padding1">
            <p class="warning-line-hei"><b>Mr. {{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name', $hr_transfer_letter->emr_no, 'emr_no') }}</b></br>
                <b>S/o {{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_father_name', $hr_transfer_letter->emr_no, 'emr_no') }}</b></br>
                {{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','residential_address', $hr_transfer_letter->emr_no, 'emr_no') }}
            </p>
        </div>
    </div>
    <div class="row letter-head before-subject-margin">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 padding1">
            <p><b>Subject:</b><b class="bor-bot">Transfer Letter</b></p>
        </div>
    </div>
    <div class="row letter-head">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 war-margin1 padding1">
            <p><b>Dear Mr. {{ HrHelper::getcompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name', $hr_transfer_letter->emr_no, 'emr_no') }},</b></p>
        </div>
    </div>
    <div class="row letter-head">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
            <p class="warning-line-hei war-margin1" >
                <?php echo nl2br($hr_transfer_letter->letter_content1) ?>
            </p>
        </div>
    </div>
    <?php if($hr_transfer_letter->letter_content2 != '<p><br></p>') { ?>
    <div class="row page-break2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
            <p class="warning-line-hei war-margin1">
                <?php echo nl2br($hr_transfer_letter->letter_content2);?>
            </p>
        </div>
    </div>
    <?php }?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2 ">
            <p class="warning-line-hei letter-head">All other terms and conditions as detailed in your appointment letter shall remain unchanged.</p>

            <p class="warning-line-hei war-margin1 letter-head">We look forward to your valuable contributions and wish you all the very best for a rewarding career with the organization.</p>
        </div>
    </div>
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
    <div class="row before-signature-margin after-hr-margin">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2 ">
            <p class="warning-line-hei letter-head">I accept the company's terms and conditions and confirm my taking up the position of {{ HrHelper::getMasterTableValueById($m,'designation','designation_name', $designation_id) }} with revised salary.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 warning-mar2">
            <p class="warning-line-hei">Employee Name & Signature: <b>{{ HrHelper::getcompanyTableValueByIdAndColumn($m,'employee','emp_name', $hr_transfer_letter->emr_no, 'emr_no') }}</b></br>
                Date:
            </p>
        </div>
    </div>
</div>