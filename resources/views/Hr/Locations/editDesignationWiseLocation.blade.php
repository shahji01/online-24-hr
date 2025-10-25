<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/editDesignationWiseLocationDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                    <input type="hidden" name="id" id="id" value="{{ $data->id }}" class="form-control requiredField" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Location Name</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="location_name" id="location_name_1" >
                                    <option>Select Location</option>
                                    @foreach(DB::table('locations')->where('status',1)->get() as $val)
                                        <option @if($data->location_id==$val->id) selected @endif value="{{ $val->id }}"> {{ $val->location_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Designation Name</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="designation_name" id="designation_name_1" >
                                    <option>Select Designation</option>
                                    @foreach(DB::table('designation')->where('status',1)->get() as $val)
                                        <option @if($data->designation_id==$val->id) selected @endif value="{{ $val->id }}"> {{ $val->designation_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Fuel Amount</label>
                                    <input type="number" step="any" class="form-control" name="fuel_amount" id="fuel_amount" value="{{ $data->fuel_amount }}" />
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
