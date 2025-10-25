<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$id = Input::get('id');
$sub_department_id = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee','emp_sub_department_id', $emr_no, 'emp_id');
$location_id = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee','emp_department_id', $emr_no, 'emp_id')

?>
<style>

    input[type="radio"],input[type="checkbox"]{ width:30px;
        height:20px;
    }


</style>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                        <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">EMR No:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input readonly name="emr_no" id="emr_no" type="text" value="{{ $emr_no }}" class="form-control requiredField">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Employee Name:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input readonly name="emp_name" id="emp_name" type="text" value="{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $emr_no, 'emp_id') }}" class="form-control requiredField">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input readonly name="emp_name" id="emp_name" type="text" value="{{ HrHelper::getMasterTableValueById($m, 'sub_department','sub_department_name', $sub_department_id ) }}" class="form-control requiredField">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Location:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input readonly name="location" id="location" type="text" value="{{ HrHelper::getMasterTableValueById($m, 'locations','employee_location', $location_id ) }}" class="form-control requiredField" required>
                            </div>
                        </div>

                        <br>

                            <div class="row">
                                <div class="col-sm-12">


                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Equipment</th>
                                            <th class="text-center">Action</th>
                                            <th class="text-center">Details</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($equipment as $value)
                                            <tr>
                                                <td class="text-center">{{ $value->equipment_name }}</td>
                                                <td class="text-center"><input {{in_array($value->id,$employeeEquipment)?'checked':''}} type="checkbox" name="equipment_id[]" value="{{ $value->id }} " /></td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-center">Insurance Card</td>
                                            <td class="text-center">
                                                <input {{in_array(9,$employeeEquipment)?'checked':''}} type="checkbox" class="insurance" onclick="insuranceCheck()" name="equipment_id[]" value="9" />
                                            </td>
                                            <td class="text-center">
                                                <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Insurance Number</label>
                                                    <input type="text" class="form-control" name="insurance_number" id="insurance_number" value="{{ $employee->insurance_number }}" disabled>
                                                </div>
                                                @if($employee_insurance_copy->count() > 0)
                                                    <div id="insurance_div_hide">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>&nbsp</label><br>
                                                            <label class="pointer" id="insurance_check" style="color: green">Change / Edit Insurance copy:</label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <a onclick="showMasterTableEditModel('hdc/viewEmployeeInsuranceCopy','{{ $employee->id.'|'.$emr_no }}','View Employee Insurance Copy','{{ $m }}')" class=" btn btn-info btn-sm" style="color: white;cursor: pointer;">View</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: none" id="insurance_div_show">
                                                        <label class="sf-label">Insurance Upload</label>
                                                        <input type="file" class="form-control" name="insurance_path" id="insurance_path" disabled>
                                                    </div>
                                                @else
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Insurance Upload</label>
                                                        <input type="file" class="form-control" name="insurance_path" id="insurance_path" disabled>
                                                    </div>
                                                @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">EOBI Card</td>
                                            <td class="text-center">
                                                <input {{in_array(10,$employeeEquipment)?'checked':''}} type="checkbox" class="eobi" onclick="eobiCheck()" name="equipment_id[]" value="10" />
                                            </td>
                                            <td class="text-center">
                                                <div class="row">


                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">EOBI Number</label>
                                                    <input type="text" class="form-control" name="eobi_number" id="eobi_number" value="{{ $employee->eobi_number }}" disabled>
                                                </div>
                                                @if($employee_eobi_copy->count() > 0)
                                                    <div id="eobi_div_hide">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>&nbsp</label><br>
                                                            <label class="pointer" id="eobi_check" style="color: green">Change / Edit EOBI copy:</label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>&nbsp</label><br>
                                                            <a onclick="showMasterTableEditModel('hdc/viewEmployeeEobiCopy','{{ $employee->id.'|'. $emr_no }}','View Employee Insurance Copy','{{ $m }}')" class=" btn btn-info btn-xs">View</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: none" id="eobi_div_show">
                                                        <label class="sf-label">EOBI Upload</label>
                                                        <input type="file" class="form-control" name="eobi_path" id="eobi_path" disabled>
                                                    </div>
                                                @else
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">EOBI Upload</label>
                                                        <input type="file" class="form-control" name="eobi_path" id="eobi_path" disabled>
                                                    </div>
                                                @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Mobile & Sim</td>
                                            <td class="text-center">
                                                <input {{in_array(11,$employeeEquipment)?'checked':''}} type="checkbox" class="mobile" onclick="mobileCheck()" name="equipment_id[]" value="11" />
                                            </td>
                                            <td class="text-center">
                                                <div class="row">


                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Model Number.</label>
                                                    <input type="text" class="form-control" name="model_number" id="model_number" value="{{ $equipment_detail['model_number'] }}" disabled>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Mobile Number</label>
                                                    <input type="number" class="form-control" name="mobile_number" id="mobile_number" value="{{ $equipment_detail['mobile_number'] }}" disabled>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Sim Number</label>
                                                    <input type="text" class="form-control" name="sim_number" id="sim_number" value="{{ $equipment_detail['sim_number'] }}" disabled>
                                                </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        if ($('.insurance').is( ":checked" )) {
            $('#insurance_number').prop("disabled", false);
            $('#insurance_path').prop("disabled", false);
        }
        else {
            $('#insurance_number').prop("disabled", true);
            $('#insurance_path').prop("disabled", true);
        }

        if ($('.eobi').is( ":checked" )) {
            $('#eobi_number').prop("disabled", false);
            $('#eobi_path').prop("disabled", false);
        }
        else {
            $('#eobi_number').prop("disabled", true);
            $('#eobi_path').prop("disabled", true);
        }

        if ($('.mobile').is( ":checked" )) {
            $('#model_number').prop("disabled", false);
            $('#mobile_number').prop("disabled", false);
            $('#sim_number').prop("disabled", false);
        }
        else {
            $('#model_number').prop("disabled", true);
            $('#mobile_number').prop("disabled", true);
            $('#sim_number').prop("disabled", true);
        }
    });


    function insuranceCheck()
    {
        if ($('.insurance').is( ":checked" )) {
            $('#insurance_number').prop("disabled", false);
            $('#insurance_path').prop("disabled", false);
        }
        else {
            $('#insurance_number').prop("disabled", true);
            $('#insurance_path').prop("disabled", true);
        }

    }

    function eobiCheck()
    {
        if ($('.eobi').is( ":checked" )) {
            $('#eobi_number').prop("disabled", false);
            $('#eobi_path').prop("disabled", false);
        }
        else {
            $('#eobi_number').prop("disabled", true);
            $('#eobi_path').prop("disabled", true);
        }

    }

    function mobileCheck()
    {
        if ($('.mobile').is( ":checked" )) {
            $('#model_number').prop("disabled", false);
            $('#mobile_number').prop("disabled", false);
            $('#sim_number').prop("disabled", false);
        }
        else {
            $('#model_number').prop("disabled", true);
            $('#mobile_number').prop("disabled", true);
            $('#sim_number').prop("disabled", true);
        }

    }

    $('#eobi_check').click(function() {
        $('#eobi_div_hide').hide();
        $('#eobi_div_show').show();
    });

    $('#insurance_check').click(function() {
        $('#insurance_div_hide').hide();
        $('#insurance_div_show').show();
    });

</script>
