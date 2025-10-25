<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/editAllowanceTypeDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                    <input type="hidden" name="id" id="id" value="{{ $allowance_type->id }}" class="form-control requiredField" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Allowance Type:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="allowance_type" id="allowance_type" class="form-control requiredField" value="{{ $allowance_type->allowance_type }}" />
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Is Taxable:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select name="taxable" id="taxable" class="form-control requiredField">
                                        <option @if($allowance_type->taxable == 1) selected @endif value="1">Taxable</option>
                                        <option @if($allowance_type->taxable == 2) selected @endif value="2">Non Taxable</option>
                                    </select>
                                </div>
                            </div>
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