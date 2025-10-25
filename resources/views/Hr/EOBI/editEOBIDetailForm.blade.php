<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            {{ Form::open(array('url' => 'had/editEOBIDetail')) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="formSection[]" id="formSection" value="1" />
            <input type="hidden" name="id" id="id" value="{{ $eobi->id }}" class="form-control requiredField" />
            <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>EOBI Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="EOBI_name" id="EOBI_name" value="{{ $eobi->EOBI_name }}" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>EOBI Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="number" name="EOBI_amount" id="EOBI_amount" value="{{ $eobi->EOBI_amount }}" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Month & Year:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="month" name="month_year" id="month_year" value="{{ $eobi->month_year }}" class="form-control requiredField" />
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