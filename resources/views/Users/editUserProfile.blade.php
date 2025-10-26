@extends('layouts.default')
@section('content')
    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'uad/editUserPasswordDetail')) }}
                        <input type="hidden" name="m" value="{{ Input::get('m') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="container">
                                    <h3>Password Rules</h3>
                                </div>
                                <ul style="color:Red;font-size: 13px;">
                                    <li>Should have At least one Uppercase letter.</li>
                                    <li>At least one Lower case letter.</li>
                                    <li>Also,At least one numeric value.</li>
                                    <li>And, At least one special character.</li>
                                    <li>Must be more than 6 characters long.</li>
                                </ul>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" class="form-control" placeholder="Old password" name="old_password" id="old_password">
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" placeholder="New password" name="password" id="password" onkeyup="checkPassword()">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password </label>
                                    <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" id="password_confirmation" onkeyup="checkPassword()" value="">
                                    <lable id="pass_message"></lable>
                                </div>
                                <div class="form-actions">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div>
                                    @if($errors->all())
                                        <div class="container">
                                            <h3>Validation Messages</h3>
                                        </div>
                                        <ul style="color:red;font-size: 13px;">
                                            @foreach($errors->all() as $error)
                                                <li>{{$error}}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkPassword() {
            var password = $("#password").val();
            var confirm_password = $("#password_confirmation").val();

            if(confirm_password != '' && password != '')
            {
                if(password != confirm_password)
                {
                    $("#pass_message").html('<span style="color:red;font-size: 13px;font-weight: bold;">Password Don,t Match ! </span>');
                    $(".btn-success").attr("disabled","disabled");
                }
                else
                {
                    $("#pass_message").html('<span style="color:green;font-size: 13px;font-weight: bold;">Password Matched ! </span>');
                    $(".btn-success").removeAttr("disabled")
                }
            }
            else{
                $("#pass_message").html('');

            }
        }
    </script>

@endsection