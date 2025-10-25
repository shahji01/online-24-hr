<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow" />

    <link rel="icon" href="{{ URL::asset('assets/images/logoTab.PNG') }}">
    <title>{{ env('APP_NAME') }}</title>

    <link href="{{ URL::asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/custom/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/animate.min.css') }}">

    <script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>
</head>

<body>
<div class="main-wrapper">
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
         style="background:url({{url('assets/images/big/auth-bg.jpg')}}) no-repeat center center;">
        <input type="hidden" id="baseUrl" value="{{ url('/') }}">
        <div class="auth-box row">
            <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url({{url('assets/images/big/3.jpg')}});">
            </div>
            <div class="col-lg-5 col-md-7 bg-white">
                <div class="p-3">
                    <div class="text-center">
                        <img src="{{url('assets/images/big/icon.png')}}" alt="wrapkit">
                    </div>
                    <span id="loginForm">
                        <h2 class="mt-3 text-center">Sign In Test</h2>
                        <p class="text-center">Enter your email address and password to access admin panel.</p>

                        <form id="formLogin" action="{{ url("/login") }}" method="POST" class="mt-4">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">Username</label>
                                        <input class="form-control" id="email" type="text"
                                               name="email" value="{{ old('email') }}" placeholder="Company email">
                                        @if ($errors->has('email'))
                                            <span class="text-danger" style="font-size: 13px;">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Password</label>
                                        <input class="form-control" id="pwd" type="password" name="password"
                                               placeholder="enter your password">
                                        @if ($errors->has('password'))
                                            <span class="text-danger" style="font-size: 13px;">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 text-right">
                                    <label id="forgotPswd" style="cursor: pointer;color: #0e3179;font-size: 14px;">
                                        Forgot your password?
                                    </label>
                                </div>

                                <div class="col-lg-12 text-center">
                                    <button type="button" onclick="encryptPassword()" class="btn btn-block btn-dark">Sign In</button>
                                </div>
                            </div>
                            <div style="padding-top: 20px;"></div><div>
                                @if(Session::has('msg'))
                                    <script>
                                        $.notify({
                                            icon: "fa fa-check-circle",
                                            message: "<b> {!! Session::get('msg')  !!}</b>.",
                                        }, {
                                            type: 'success',
                                            timer: 4500
                                        });
                                    </script>
                                @endif
                            </div>

                            <div style="padding-top: 65px;">

                            </div>
                        </form>
                    </span>
                    <span id="forgotForm" style="display: none;">
                        <h3 class="mt-3 text-center">Forgot Form</h3>
                        <p class="text-center">Enter your email address where you recieve your password.</p>

                        <form action="{{ url('/emailCheck') }}" method="GET" class="mt-4">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">Email</label>
                                        <input class="form-control" required id="forgotEmail" type="text"
                                               name="email" value="{{ old('email') }}" placeholder="Company email">
                                    </div>
                                </div>

                                <div class="col-lg-12 text-right">
                                    <label id="backToLogin" style="cursor: pointer;color: #0e3179;font-size: 14px;">
                                        Back to login form?
                                    </label>
                                </div>

                                <div class="col-lg-12 text-center">
                                    <button type="button" onclick="sendPswd()" class="btn btn-block btn-dark">Send</button>
                                </div>
                            </div>

                            <div style="padding-top: 20px;"></div><div>
                                @if(Session::has('msg'))
                                    <script>
                                        $.notify({
                                            icon: "fa fa-check-circle",
                                            message: "<b> {!! Session::get('msg')  !!}</b>.",
                                        }, {
                                            type: 'success',
                                            timer: 4500
                                        });
                                    </script>
                                @endif
                            </div>

                            <div style="padding-top: 65px;"></div>
                        </form>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>
<script src="{{ URL::asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }} "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

<script>
    var encryptionKey = "{{ env('LOGIN_ENCRYPTION_KEY') }}";

    function encryptPassword() {
        var password = document.getElementById('pwd').value;
        if (password) {
            // // Use a strong encryption key
            // const secretKey = CryptoJS.enc.Utf8.parse(encryptionKey);

            // // Encrypt the password using AES encryption
            // const encryptedPassword = CryptoJS.AES.encrypt(password, secretKey, {
            //     mode: CryptoJS.mode.ECB,
            //     padding: CryptoJS.pad.Pkcs7
            // });

            // document.getElementById('pwd').value = encryptedPassword.toString();
            document.getElementById('formLogin').submit();
        }
    }

    //    // Example usage
    //    const userPassword = "user123";
    //    const encryptionKey = "secureEncryptionKey";
    //    const encryptedPassword = encryptPassword(userPassword, encryptionKey);
    //    console.log("Encrypted Password:", encryptedPassword);
    //
    //
    //    function encryptPassword() {
    //        var password = document.getElementById('pwd').value;
    //        if (password) {
    //            // Generate a random IV (Initialization Vector)
    //            var iv = CryptoJS.lib.WordArray.random(16);
    //
    //            // Encrypt the password
    //            var encrypted = CryptoJS.AES.encrypt(password, CryptoJS.enc.Utf8.parse(encryptionKey), { iv: iv });
    //
    //            // Combine the IV and encrypted data
    //            var cipherText = iv.concat(encrypted.ciphertext);
    //
    //            // Encode the combined data in base64 for safe transmission
    //            var encryptedBase64 = CryptoJS.enc.Base64.stringify(cipherText);
    //
    //            document.getElementById('pwd').value = encryptedBase64;
    //            document.getElementById('formLogin').submit();
    //        }
    //    }

    $(".preloader ").fadeOut();
    var baseUrl=$('#baseUrl').val();
    $('#forgotPswd').click(function(){
        $('#loginForm').css({'display':'none'}).fadeOut(1000);
        $('#forgotForm').css({'display':'block'}).fadeIn(1000);
//        $('#pwd').css({'display':'none'}).fadeOut(1000);
        $('#forgotEmail').css({'width':'100%'});
    });
    $('#backToLogin').click(function(){
        $('#forgotForm').css({'display':'none'}).fadeOut(1000);
        $('#loginForm').css({'display':'block'}).fadeIn(1000);
    });

    function sendPswd(){
        var email=$('#forgotEmail').val();

        $.ajax({
            type:'get',
            url: baseUrl+'/emailCheck',
            type: "GET",
            data: {email: email},
            success: function (res) {
                if(res.success==true){
                    $.notify({
                        icon: "fa fa-check-circle",
                        message: "<b> Password has been sent on your email </b>.",
                    }, {
                        type: 'success',
                        timer: 4500
                    });
                }
                else{
                    $.notify({
                        icon: "fa fa-times-circle",
                        message: "<b>Email Address Is Not Found</b>.",
                    }, {
                        type: 'danger',
                        timer: 4500
                    });
                }
            }
        })
    }

</script>
</body>

</html>