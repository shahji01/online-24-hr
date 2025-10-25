@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Add Sub Menu</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'uad/addSubMenuDetail','id'=>'addSubMenuForm')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Main Navigation Name:</label>
                                <select class="form-control requiredField" name="main_navigation_name" id="main_navigation_name">
                                    <option value="">Select Main Navigation</option>
                                    @foreach($MainMenuTitles as $key => $y)
                                        <option value="<?php echo $y->id.'_'.$y->title_id?>">{{ $y->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Sub Navigation Title Name:</label>
                                <input type="text" name="sub_navigation_title_name" id="sub_navigation_title_name" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Sub Navigation Url:</label>
                                <input type="text" name="sub_navigation_url" id="sub_navigation_url" class="form-control requiredField" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Js:</label>
                                <input type="text" name="js" id="js" value="" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Page Type:</label>
                                <select class="form-control" name="page_type" id="page_type">
                                    <option value="1">Outer Page</option>
                                    <option value="2">Inner Page</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Main Navigation</th>
                                        <th class="text-center">Sub Navigation</th>

                                        <th class="text-center">Action</th>
                                        </thead>
                                        <tbody id="viewSubMenuList">
                                        </tbody>
                                    </table>
                                </div>
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
            $('#main_navigation_name').select2();
            function viewSubMenuList(){
                $('#viewSubMenuList').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/udc/viewSubMenuList',
                    type: "GET",
                    success:function(data) {
                        setTimeout(function(){
                            $('#viewSubMenuList').html(data);
                        },1000);
                    }
                });
            }
            viewSubMenuList();
            $(function(){
                $('#addSubMenuForm').on('submit',function(e){
                    jqueryValidationCustom();
                    if(validate==0){
                        $.ajaxSetup({
                            header:$('meta[name="_token"]').attr('content')
                        });
                        e.preventDefault(e);
                        $.ajax({
                            type:"POST",
                            url:baseUrl+'/uad/addSubMenuDetail',
                            data:$(this).serialize(),
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
                        viewSubMenuList();
                    }
                    else{
                        return false;
                    }
                });
            });
        });

    </script>
@endsection