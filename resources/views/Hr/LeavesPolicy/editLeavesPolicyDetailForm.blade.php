<?php
$c = count($leave_data);
$count = 0;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            {{ Form::open(array('url' => 'had/editLeavesPolicyDetail')) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="formSection[]" id="formSection" value="1" />
            <input type="hidden" name="id" id="id" value="{{ Input::get('id') }}" class="form-control requiredField" />
            <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Leaves Policy Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input name="leaves_policy_name" type="text" value="{{ $leave_policy->leaves_policy_name }}" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Policy Date from:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" name="PolicyDateFrom" value="{{ $leave_policy->policy_date_from }}"  class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Policy Date till:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" name="PolicyDateTill" value="{{ $leave_policy->policy_date_till }}" class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Full Day Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="full_day_deduction_rate" class="form-control requiredField" readonly>
                                <option value="">select</option>
                                <option @if($leave_policy->fullday_deduction_rate == '1') selected @endif value="1">1 (Day)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Half Day Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="half_day_deduction_rate" class="form-control requiredField" readonly>
                                <option value="">select</option>
                                <option @if($leave_policy->halfday_deduction_rate == '0.5') selected @endif value="0.5">0.5 (Day)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Per Hour Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="per_hour_deduction_rate" class="form-control requiredField" readonly>
                                <option value="">select</option>
                                <option @if($leave_policy->per_hour_deduction_rate == '0.25') selected @endif value="0.25"> 0.25 (Days)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                <thead>
                                <th>Leaves Type</th>
                                <th>No. of Leaves</th>
                                <th>Action</th>
                                </thead>
                                <tbody id="append_area">
                                @foreach($leave_data as $value)
                                    <tr>
                                        <td>
                                            <select name="leaves_type_id[]" class="form-control requiredField" required>
                                                <option value="">Select</option>
                                                @foreach($leave_type as $value2)
                                                    <option @if($value->leave_type_id == $value2->id) selected @endif value="{{ $value2->id }}">{{ $value2->leave_type_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="no_of_leaves[]" id="no_of_leaves" value="{{ $value->no_of_leaves }}" class="form-control requiredField" required />
                                        </td>
                                        <td class="text-center"><button onclick="removeEmployeeSection('{{ $count }}')" type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash fa"></i></button></td>
                                    </tr>
                                @endforeach
                                <input type="hidden" id="count" value="{{ $c }}">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>