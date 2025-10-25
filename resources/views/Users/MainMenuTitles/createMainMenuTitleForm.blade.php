@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Add Main Menu Title</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'uad/addMainMenuTitleDetail','id'=>'addMainMenuTitleForm')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="m" value="{{ Input::get('m') }}">
                        <input type="hidden" name="formSection[]" value="1">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Main Navigation Name:</label>
                                <input type="text" name="main_menu_name" id="main_menu_name" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Sub Navigation Title Name:</label>
                                <input type="text" name="title_name" id="title_name" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Menu Type:</label>
                                <select name="menu_type" id="menu_type" class="form-control requiredField" >
                                    <option value="1">Company</option>
                                    <option value="2">Master</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Main Navigation</th>
                                    <th class="text-center">Sub Navigation Title</th>
                                    <th class="text-center">Action</th>
                                    </thead>
                                    <tbody id="viewMainMenuTitleList"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $(".btn-success").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='formSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val in employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }
            });

            function viewMainMenuTitleList(){
                $('#viewMainMenuTitleList').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/udc/viewMainMenuTitleList',
                    type: "GET",
                    success:function(data) {
                        setTimeout(function(){
                            $('#viewMainMenuTitleList').html(data);
                        },1000);
                    }
                });
            }
            viewMainMenuTitleList();

            $('#addMainMenuTitleForm').on('submit',function(e){
                $.ajaxSetup({
                    header:$('meta[name="_token"]').attr('content')
                });
                e.preventDefault(e);
                $.ajax({
                    type : 'POST',
                    url: baseUrl+'/uad/addMainMenuTitleDetail',
                    data: $(this).serialize(),
                    success: function(data){
                        $.notify({
                            icon: "fa fa-check-circle",
                            message: "<b>Successfully Saved</b>."
                        }, {
                            type: 'success',
                            timer: 3000
                        });
                    },
                    error: function(data){
                        swalError();
                    }
                });
                $("#reset").click();
                viewMainMenuTitleList();
            });
        });
    </script>
@endsection