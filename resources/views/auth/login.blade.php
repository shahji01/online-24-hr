<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow" />
    <title>{{ env('APP_NAME') }}</title>

    <link rel="icon" href="{{ URL::asset('assets/images/logoTab.PNG') }}">
    <link href="{{ URL::asset('assets/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/custom/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/animate.min.css') }}">
    <script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>

    <style>
        :root {
            --primary-color: #29abe2;
            --dark-color: #1c1e21;
            --text-color: #333;
            --light-gray: #f5f7fa;
        }

        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #29abe2 0%, #0076b9 100%);
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            max-width: 880px;
            width: 100%;
            display: flex;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-in-out;
        }

        .auth-image {
            background: url('{{ url("assets/images/big/3.jpg") }}') center/cover no-repeat;
            width: 50%;
            display: none;
        }

        @media (min-width: 992px) {
            .auth-image {
                display: block;
            }
        }

        .auth-form {
            flex: 1;
            background: #fff;
            padding: 50px 40px;
            position: relative;
        }

        .logo img {
            height: 70px;
            margin-bottom: 20px;
            animation: fadeIn 1s ease;
        }

        h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 10px;
        }

        p {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #d6d6d6;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(41, 171, 226, 0.3);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: #fff;
            padding: 12px;
            border-radius: 10px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1b94c8;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(41, 171, 226, 0.3);
        }

        label.text-dark {
            font-weight: 500;
            color: var(--text-color);
        }

        #forgotPswd, #backToLogin {
            cursor: pointer;
            color: var(--primary-color);
            text-decoration: underline;
            font-size: 14px;
            font-weight: 500;
        }

        .footer-note {
            text-align: center;
            font-size: 13px;
            color: #aaa;
            margin-top: 25px;
        }

        @keyframes fadeInUp {
            from {transform: translateY(30px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }
    </style>
</head>

<body>
<div class="auth-container shadow-lg">
    <div class="auth-image"></div>

    <div class="auth-form">
        <div class="text-center logo mb-4">
            <img src="{{ url('assets/images/big/icon.png') }}" alt="logo">
        </div>

        {{-- Login Form --}}
        <span id="loginForm">
            <h2 class="text-center">Welcome Back</h2>
            <p class="text-center">Sign in to access your {{ env('APP_NAME') }} account.</p>

            <form id="formLogin" action="{{ url('/login') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group mb-3">
                    <label for="email" class="text-dark">Email</label>
                    <input class="form-control" id="email" type="text" name="email" value="{{ old('email') }}" placeholder="Enter your company email">
                    @if ($errors->has('email'))
                        <span class="text-danger"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label for="pwd" class="text-dark">Password</label>
                    <input class="form-control" id="pwd" type="password" name="password" placeholder="Enter your password">
                    @if ($errors->has('password'))
                        <span class="text-danger"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>

                <div class="text-right mb-3">
                    <label id="forgotPswd">Forgot your password?</label>
                </div>

                <button type="button" onclick="encryptPassword()" class="btn btn-primary">Sign In</button>
            </form>

            @if(Session::has('msg'))
                <script>
                    $.notify({
                        icon: "fa fa-check-circle",
                        message: "<b>{!! Session::get('msg') !!}</b>."
                    }, { type: 'success', timer: 4500 });
                </script>
            @endif
        </span>

        {{-- Forgot Password Form --}}
        <span id="forgotForm" style="display: none;">
            <h3 class="text-center mb-2" style="color: var(--primary-color);">Forgot Password</h3>
            <p class="text-center">Enter your email to receive password reset instructions.</p>

            <form action="{{ url('/emailCheck') }}" method="GET">
                {{ csrf_field() }}
                <div class="form-group mb-3">
                    <label class="text-dark" for="forgotEmail">Email</label>
                    <input class="form-control" required id="forgotEmail" type="text" name="email" placeholder="Enter your registered email">
                </div>

                <div class="text-right mb-3">
                    <label id="backToLogin">Back to Login</label>
                </div>

                <button type="button" onclick="sendPswd()" class="btn btn-primary">Send Reset Link</button>
            </form>
        </span>

        <div class="footer-note">
            © {{ date('Y') }} {{ env('APP_NAME') }}. All Rights Reserved.
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ URL::asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

<script>
    var encryptionKey = "{{ env('LOGIN_ENCRYPTION_KEY') }}";

    function encryptPassword() {
        var password = document.getElementById('pwd').value;
        if (password) {
            document.getElementById('formLogin').submit();
        }
    }

    $(".preloader").fadeOut();

    var baseUrl = '{{ url("/") }}';
    $('#forgotPswd').click(() => $('#loginForm').fadeOut(300, () => $('#forgotForm').fadeIn(300)));
    $('#backToLogin').click(() => $('#forgotForm').fadeOut(300, () => $('#loginForm').fadeIn(300)));

    function sendPswd(){
        var email = $('#forgotEmail').val();
        $.ajax({
            type: 'get',
            url: baseUrl+'/emailCheck',
            data: {email: email},
            success: function (res) {
                if(res.success){
                    $.notify({ icon: "fa fa-check-circle", message: "<b>Password has been sent to your email.</b>" },
                        { type: 'success', timer: 4500 });
                } else {
                    $.notify({ icon: "fa fa-times-circle", message: "<b>Email address not found.</b>" },
                        { type: 'danger', timer: 4500 });
                }
            }
        })
    }
</script>
</body>
</html>
