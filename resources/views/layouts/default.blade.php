<?php
use App\Models\Menu;
use App\Models\MenuPrivileges;

$routeName = Request::segment(1).'/'.Request::segment(2);
$js = DB::table('menu')->select(['js'])->where([['m_controller_name','=',$routeName],['page_type', '=', 1],['status','=',1]])->value('js');
$accType = Auth::user()->acc_type;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ URL::asset('assets/images/logoTab.PNG') }}">
    <title>{{ env('APP_NAME') }}</title>

    <link rel="stylesheet" href="{{ URL::asset('assets/extra-libs/c3/c3.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/custom/css/custom.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/summernote.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
    {{--<link rel="stylesheet" href="{{ URL::asset('assets/vanillaSelectBox.css') }}">--}}
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/bootstrap-multiselect.min.css') }}">

    <script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/summernote.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/swal.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/select2.min.js') }}"></script>
    {{--<script src=" {{ URL::asset('assets/vanillaSelectBox.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBY5p5e5PtJuJLl_nRpjefL0S094jdhEP8&libraries=places"></script>
    <script src="{{ URL::asset('assets/dist/js/bootstrap-multiselect.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.9/css/fixedHeader.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
    <script>
        function get_ajax_data_two(tableId,columns) {
            var form = $('#list_data');
            var actionUrl = form.attr('action');
            var tableElement = $(`#${tableId}`);
        
            // Destroy the existing DataTable instance if it exists
            if ($.fn.dataTable.isDataTable(tableElement)) {
                tableElement.DataTable().destroy();
                tableElement.empty(); // Clear table content
            }
            tableElement.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: actionUrl,
                type: 'GET',
                data: function(d) {
                    // Serialize form data and include it in the request
                    var formData = form.serializeArray();
                    $.each(formData, function(i, field) {
                        // Check if the field name already exists (e.g., for multi-select arrays)
                        if (d[field.name]) {
                            if (Array.isArray(d[field.name])) {
                                d[field.name].push(field.value);
                            } else {
                                d[field.name] = [d[field.name], field.value];
                            }
                        } else {
                            d[field.name] = field.value;
                        }
                    });
                },
                dataSrc: 'data' // Specify where the data is in the server response
            },
            columns: columns
            });
        }
    </script>
    <style>
        @media print {
            .hidden-print{
                display: none;
            }
        }
        .has-search .form-control {
            padding-left: 2.375rem;
        }

        .has-search .form-control-feedback {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
        .card-body {
            border-top: 4px solid #f7931e;
        }

        .animated {
            -moz-user-select: none;
            -ms-user-select: none;
            -webkit-user-select: none;
        }

        .yt-loader {
            -webkit-animation-name: horizontalProgressBar;
            animation-name: horizontalProgressBar;
            -webkit-animation-timing-function: ease;
            animation-timing-function: ease;
            background: #10589f;
            height: 3px;
            left: 0;
            top: 0;
            width: 0%;
            z-index: 9999;
            position:relative;
        }

        .yt-loader:after{
            display: block;
            position: absolute;
            content:'';
            height: 100%;
            box-shadow: #10589f 1px 0 6px 1px;
            opacity: 0.5;
        }
        @keyframes horizontalProgressBar
        {
            0%   {width: 0%;}
            20%  {width: 10%;}
            30%  {width: 15%;}
            40%  {width: 18%;}
            50%  {width: 20%;}
            60%  {width: 22%;}
            100% {width: 100%;}
        }
        #users-list li.selected {background:yellow}

        #users-list .selected {
            background: #CCD5DB;
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }

        .autocomplete {
            position: relative;
            display: inline-block;
        }

        input {
            border: 1px solid transparent;
            background-color: #f1f1f1;
            font-size: 13px;
        }

        input[type=text] {
            background-color: #f1f1f1;
            width: 100%;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 73%;
            left: 0;
            right: 0;

        }

        .autocomplete-items div {
            cursor: pointer;
            background-color: #fff;
            padding-left: 20px;
            height: 32px;
            line-height: 32px;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }

        .message-count {
            color: #fff;
            font-size: 10px;
            font-family: Arial, san-serif;
            font-weight: bold;
        }

        .badge-message {
            position: absolute;
            right: 0px;
            top: -5px;
            z-index: 10;
        }

        .badge-message {
            background: radial-gradient( 5px -15px, circle, white 8%, #ed2324 13px );
            background: -moz-radial-gradient( 5px -15px, circle, white 8%, #ed2324 13px );
            background: -ms-radial-gradient( 5px -15px, circle, white 8%, #ed2324 13px );
            background: -o-radial-gradient( 5px -15px, circle, white 8%, #ed2324 13px );
            background: -webkit-radial-gradient( 5px -15px, circle, white 8%, #ed2324 13px );
            background-color: #ed2324;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            padding: 5% 5%;
            position: relative;
            right: 0px;
            top: 18px;
            float: right;
            display: block;
        }

        .message-count {
            position: relative;
            display: block;
            font: 11px/13px Helvetica, Verdana, Tahoma;
            text-align: center;
            margin: 0;
            top: 3px;
        }
        .div-overflow {
            overflow-y: auto;height: 600px; width: 20rem
        }
        .notification-count{
            border-radius: 50%;
            background: #edebee;
            padding: 15px;
            display: inline-block;
            width: 55px !important;
            height: 55px !important;
            text-align: center;
        }
        .drop-down2 {
            padding: 0px 0px 0px 0px;
        }
        .notification-count2{
            border-radius: 50%;
            padding: 15px;
            display: inline-block;
            width: 55px !important;
            height: 55px !important;
            text-align: center;
            background: #fcac38;
            color: white;
        }

    </style>

    @yield('css')
</head>
<body onload="Loader()">

<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>

@include('includes._'.$accType.'Navigation')
<div class="lineHeight">&nbsp;</div>
<div class="container-fluid" id="mainSFContent">

    <?php

        if(Auth::user()->acc_type == 'user'){
            $menuPrivileges = MenuPrivileges::select('submenu_id')->where([['emp_id','=',Auth::user()->emp_id]])->value('submenu_id');

            $menuId= Menu::select('id')->where([['m_controller_name','=',Request::segment(1)."/".Request::segment(2)]])->value('id');
            $arrPrivileges = explode(",",$menuPrivileges);

            if(in_array($menuId, $arrPrivileges)){
    ?>
                @yield('content')
    <?php 
            }else{   $url=$_SERVER['REQUEST_URI'];
                if($url == "/users/editMyProfile?m=12" || $url == "/users/editUserProfile?m=12"){
    ?> 
                    @yield('content');
    <?php 
                }else{ 
    ?>
                    <div class="page-wrapper">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <br><br><br><div style='text-align:center'><h1>You have Insufficient Privileges to access this page !</h1></div>
                                                <div style='text-align:center'><h1><a href="{{ url('/') }}">Go Back  </a></h1></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    <?php 
                }
            }
        }else if(Auth::user()->acc_type == 'Customer'){
            $menuPrivileges = MenuPrivileges::select('submenu_id')->where([['customer_id','=',Auth::user()->customer_id]])->value('submenu_id');

            $menuId= Menu::select('id')->where([['m_controller_name','=',Request::segment(1)."/".Request::segment(2)]])->value('id');
            $arrPrivileges = explode(",",$menuPrivileges);

            if(in_array($menuId, $arrPrivileges)){
    ?>
                @yield('content')
    <?php 
            }else{   $url=$_SERVER['REQUEST_URI'];
                if($url == "/users/editMyProfile?m=12" || $url == "/users/editUserProfile?m=12"){
    ?> 
                    @yield('content');
    <?php 
                }else{ 
    ?>
                    <div class="page-wrapper">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <br><br><br><div style='text-align:center'><h1>You have Insufficient Privileges to access this page !</h1></div>
                                                <div style='text-align:center'><h1><a href="{{ url('/') }}">Go Back  </a></h1></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    <?php 
                }
            }
        }else{
    ?>
            @yield('content')
    <?php 
        } 
    ?>
    <input type="hidden" id="m" value="{{ Input::get('m') }}">
    <input type="hidden" id="baseUrl" value="{{ url('/') }}">
    <input type="hidden" id="employeeid" value="{{ \Illuminate\Support\Facades\Auth::user()->emp_id }}">
    <br>
        @include('includes._modal')
       <div id="footer"> @include('includes._footer')</div>

    <div class="loaderbody" id="loaderbody" style="display: none;">
        <div class="loader"></div>
    </div>

</div>

@yield('script')

<script>

    function swalAdd() {
        $.notify({
            icon: "fa fa-times-circle",
            message: "<b>Successfully Saved</b>."
        }, {
            type: 'success',
            timer: 3000
        });
    }

    function swalUpdate() {
        $.notify({
            icon: "fa fa-times-circle",
            message: "<b>Successfully Updated</b>."
        }, {
            type: 'info',
            timer: 3000
        });
    }

    function swalDelete() {
        $.notify({
            icon: "fa fa-times-circle",
            message: "<b>Successfully Deleted</b>."
        }, {
            type: 'danger',
            timer: 3000
        });
    }

    function swalError() {
        swal("Error !", "Something went wrong");
    }

    function swalAlert(title, text){
        swal(title, text);
    }

</script>

@if(Session::has('dataInsert'))
    <script>
        $.notify({
            icon: "fa fa-check-circle",
            message: "<b> {!! session('dataInsert') !!}</b>.",
        }, {
            type: 'success',
            timer: 3000
        });
    </script>
@endif
@if(Session::has('dataDelete'))
    <script>
        $.notify({
            icon: "fa fa-times-circle",
            message: "<b> {!! session('dataDelete') !!}</b>."
        }, {
            type: 'danger',
            timer: 3000
        });
    </script>
@endif
@if(Session::has('dataEdit'))
    <script>
        $.notify({
            icon: "fa fa-check-circle",
            message: "<b> {!! session('dataEdit') !!}</b>."
        }, {
            type: 'info',
            timer: 3000
        });
    </script>
@endif
<script>

    var activity_user  = '<?=Auth::user()->name?>';
    $(document).ready(function(){
        
        $(document).bind('ajaxStart', function () {
            $("#footer").css({"display": "none"});
            $("#loaderbody").css({"display": "block"});
        }).bind('ajaxStop', function () {
            $("#loaderbody").css({"display": "none"});
            $("#footer").css({"display": "block"});
        });

        
        var url = window.location.href;
        var today = new Date();
        var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var dateTime = date+' '+time;

    });


</script>

</body>