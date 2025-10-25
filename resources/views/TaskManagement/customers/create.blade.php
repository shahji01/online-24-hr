<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')
@section('content')
    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="card-title">Create Customer Form</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'tms/customers/store')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Customer Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Customer Type</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="customer_type" id="customer_type" class="form-control">
                                            <option value="1">Local</option>
                                            <option value="2">International</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Mobile No:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Email:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="email" id="email" class="form-control requiredField" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Password</label>
                                        <input type="text" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <button type="button" style="margin-top:37px;color:white;" class="icon btn btn-sm btn-warning" onclick="password_generator()" >Generate</button>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="text-dark sf-label">Roles</label>
                                        <select style="width: 100%;" class="form-control" name="role_id" id="role_id">
                                            <option value="">Select</option>
                                            @foreach($menu_privileges as $key => $val)
                                                <option value="{{$val->id}}">{{$val->role_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function password_generator( len ) {
            var length = (len)?(len):(10);
            var string = "abcdefghijklmnopqrstuvwxyz"; //to upper
            var numeric = '0123456789';
            var punctuation = '!@#$%^&*()_+~`|}{[]\:;?><,./-=';
            var password = "";
            var character = "";
            var crunch = true;
            while( password.length<length ) {
                entity1 = Math.ceil(string.length * Math.random()*Math.random());
                entity2 = Math.ceil(numeric.length * Math.random()*Math.random());
                entity3 = Math.ceil(punctuation.length * Math.random()*Math.random());
                hold = string.charAt( entity1 );
                hold = (password.length%2==0)?(hold.toUpperCase()):(hold);
                character += hold;
                character += numeric.charAt( entity2 );
                character += punctuation.charAt( entity3 );
                password = character;
            }
            password=password.split('').sort(function(){return 0.5-Math.random()}).join('');
            document.getElementById("password").value = password.substr(0,len);
        }
    </script>
@endsection