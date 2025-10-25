

<div class="">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <?php echo Form::open(array('url' => 'had/editQualificationDetail?m='.Input::get('m').'&&d='.Input::get('id').'','id'=>'qualificationForm'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="qualificationSection[]" class="form-control" id="qualificationSection" value="1" />
                                    </div>
                                </div>
                                <input type="hidden" name="qualification_id_1" id="qualification_id_1" value="<?php echo $qualificationDetail->id?>" class="form-control requiredField" />
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Qualification Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="qualification_name_1" id="qualification_name_1" placeholder="Qualification Name" value="<?php echo $qualificationDetail->qualification_name?>" class="form-control requiredField" />
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Institute Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="institute_id_1" class="form-control requiredField">
                                            @foreach($institutes as $i)
                                                <option @if($qualificationDetail->institute_id == $i->id){{ 'selected' }} @endif value="{{ $i->id}}">{{ $i->institute_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Country</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="country_1" id="country_1" class="form-control requiredField" >
                                            <option value="">Select Country</option>
                                            @foreach($countries as $key => $y)
                                                <option @if($qualificationDetail->country_id == $y->id){{ 'selected' }} @endif value="{{ $y->id}}">{{ $y->nicename}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>State</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <span id="state_area_1">
												<select name="state_1" id="state_1" class="form-control requiredField" onchange="changeCity(this.id)">
													@foreach($states as $value)
                                                        <option @if($qualificationDetail->state_id == $value->id){{ 'selected' }} @endif value="{{ $value->id}}">{{ $value->name}}</option>
                                                    @endforeach
												</select>
												</span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>City</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <span id="city_area_1">
												<select name="city_1" id="city_1" class="form-control requiredField">
													@foreach($cities as $value2)
                                                        <option @if($qualificationDetail->city_id == $value2->id){{ 'selected' }} @endif value="{{ $value2->id}}">{{ $value2->name}}</option>
                                                    @endforeach
												</select>
												</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="qualificationSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
