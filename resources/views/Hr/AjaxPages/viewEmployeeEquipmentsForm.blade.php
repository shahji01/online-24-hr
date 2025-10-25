<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
use App\Helpers\CommonHelper;
?>
<div class="panel-body">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th class="text-center">Equipment</th>
                    <th class="text-center">Action</th>
                    <th class="text-center">Details</th>
                </tr>
                </thead>

                <tbody>
                @foreach($employeeEquipment as $value)
                    <tr>
                        <td class="text-center">{{ $value->equipment_name }}</td>
                        <td class="text-center"><input type="checkbox" name="equipment_id[]" value="{{ $value->id }} " /></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td class="text-center">Insurance Card</td>
                    <td class="text-center">
                       <?php if($employee !== ''){ ?>
                        <input @if($employee->insurance_path != null || $employee->insurance_number != null) checked @endif type="checkbox" class="insurance" onclick="insuranceCheck()" name="equipment_id[]" value="9" />
                        <?php } ?>
                    </td>

                    <td class="text-center">
                        <div class="row">


                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Insurance Number</label>
                            <input type="text" class="form-control" name="insurance_number" id="insurance_number" value="{{ $employee->insurance_number }}" disabled>
                        </div>
                        @if($employee->insurance_path == null)
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Insurance Upload</label>
                                <input type="file" class="form-control" name="insurance_path" id="insurance_path" disabled>
                            </div>
                        @else
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <br>
                                <p style="color: green" class="text-center">Insurance file already submitted</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <br>
                                <a onclick="showMasterTableEditModel('hdc/viewEmployeeInsuranceCopy','{{ $employee->id.'|'.$employee->emp_id }}','View Employee Insurance Copy','{{ $m }}')" class=" btn btn-info btn-xs">View</a>
                            </div>
                        @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">EOBI Card</td>
                    <td class="text-center">
                        <input @if($employee->eobi_path != null || $employee->eobi_number != null) checked @endif type="checkbox" class="eobi" onclick="eobiCheck()" name="equipment_id[]" value="10" />
                    </td>
                    <td class="text-center">
                        <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">EOBI Number</label>
                            <input type="text" class="form-control" name="eobi_number" id="eobi_number" value="{{ $employee->eobi_number }}" disabled>
                        </div>
                        @if($employee->eobi_path == null)
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <label class="sf-label">EOBI Upload</label>
                                <input type="file" class="form-control" name="eobi_path" id="eobi_path" disabled>
                            </div>
                        @else
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <br>
                                <p style="color: green" class="text-center">EOBI file already submitted</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <br>
                                <a onclick="showMasterTableEditModel('hdc/viewEmployeeEobiCopy','{{ $employee->id.'|'. $employee->emp_id }}','View Employee Insurance Copy','{{ $m }}')" class=" btn btn-info btn-xs">View</a>
                            </div>
                        @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">Mobile & Sim</td>
                    <td class="text-center">
                        <input type="checkbox" class="mobile" onclick="mobileCheck()" name="equipment_id[]" value="11" />
                    </td>
                    <td class="text-center">
                        <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Model Number.</label>
                            <input type="text" class="form-control" name="model_number" id="model_number" disabled>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Mobile Number</label>
                            <input type="number" class="form-control" name="mobile_number" id="mobile_number" disabled>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Sim Number</label>
                            <input type="text" class="form-control" name="sim_number" id="sim_number" disabled>
                        </div>
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
</div>

<br>
<div style="float: right;">
    <button style="text-align: center" class="btn btn-success btn-sm" type="submit" value="Submit">Submit</button>
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
    });


</script>