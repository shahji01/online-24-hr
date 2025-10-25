<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\LeaveApplicationData;
use App\Models\Employee;
?>

@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title">Attendance Report</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                @if(in_array('print',$operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export',$operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">From Date:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type='date' name="from" id="from" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">To Date:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type='date' name="to" id="to" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <button style="margin-top:40px;" type="button" class="btn btn-sm btn-primary btn_search" onclick="viewAttendanceReport()"><i class="fas fa-search"> </i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="addMoreSection"></div>
        <div class="row">&nbsp;</div>
    </div>

@endsection

@section('script')
    <script>

        function viewAttendanceReport(){

            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var location_id = $('#location_id').val();
            var from_date = $('#from').val();
            var to_date = $('#to').val();

            jqueryValidationCustom();
            if(validate == 0){
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/fetchAttendanceReport',
                    type: "GET",
                    data: {
                            from_date:from_date,
                            to_date:to_date,
                            m:m,
                            employee_id:employee_id,
                            department_id:department_id,
                            sub_department_id:sub_department_id,
                            location_id:location_id
                          },
                    success:function(data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function(error) {
                        $('#addMoreSection').html('');
                        swalError();
                    }
                });
            }else{
                return false;
            }
        }

//        $(document).ready(function () {
//            $(document).bind('ajaxStart', function () {
//            }).bind('ajaxStop', function () {
//                $("select[name='emp_id'] option[value='all']").remove();
//            });
//        });

    </script>
@endsection