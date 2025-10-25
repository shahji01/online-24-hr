<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/editLocationsDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                    <input type="hidden" name="id" id="id" value="{{ $locations->id }}" class="form-control requiredField" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Location Name</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" class="form-control requiredField" name="location_name" id="location_name" value="{{ $locations->location_name }}" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Location Code</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" class="form-control requiredField" name="location_code" id="location_code" value="{{ $locations->location_code }}" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">City</label>
                                    <select name="city_id" id="city_id" class="form-control">
                                        <option value="">Select City</option>
                                        @foreach($cities as $key => $val)
                                            <option @if($locations->city_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Fuel Amount</label>
                                    <input type="number" step="any" class="form-control" name="fuel_amount" id="fuel_amount" value="{{ $locations->fuel_amount }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Latitude</label>
                                    <input type="number" step="any" class="form-control" name="latitude" id="latitude" value="{{ $locations->latitude }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Longitude</label>
                                    <input type="number" step="any" class="form-control" name="longitude" id="longitude" value="{{ $locations->longitude }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Address</label>
                                    <textarea class="form-control" name="address" id="address">{{ $locations->address }}</textarea>
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
