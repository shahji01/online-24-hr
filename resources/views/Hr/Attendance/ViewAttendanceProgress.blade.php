<?php
use App\Helpers\CommonHelper;
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;

$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employee Attendance Progress</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                {{ CommonHelper::displayPrintButtonInBlade('PrintAttendanceList','','1') }}
                                <button type="button" class="btn btn-sm btn-warning" style="color: white" onclick="ExportToExcel('AttendanceList')">
                                    <span class="glyphicon glyphicon-print"></span> Export XLS</button>
                            </div>
                        </div>
                        <hr>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    @include('includes.allFilters')
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Employment Status</label>
                                        <select style="width: 100%" class="form-control  employee_status" name="employment_status_id" id="employment_status_id" >
                                            <option value="">Select Employment Status</option>
                                            @foreach($job_type as $key3 => $value)
                                                <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control requiredField" value="{{Session::get('fromDate')}}">
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control requiredField" value="{{Session::get('toDate')}}">
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <button style="margin-top:40px;" type="button" class="btn btn-sm btn-primary btn_search" onclick="viewAttendanceProgress('')"><i class="fas fa-search"> </i> Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="formSection"></div>
    </div>
@endsection

@section('script')
    <script>

        $('#employment_status_id').select2();
        function ExportToExcel(tableid) {
            var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
            var textRange; var j = 0;
            tab = document.getElementById(tableid);//.getElementsByTagName('table'); // id of table
            if (tab==null) {
                return false;
            }
            if (tab.rows.length == 0) {
                return false;
            }

            for (j = 0 ; j < tab.rows.length ; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                //tab_text=tab_text+"</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            document.getElementsByClassName('show_data').removeClass;

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "download.xls");
            }
            else                 //other browser not tested on IE 11
                //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
                try {
                    var blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
                    window.URL = window.URL || window.webkitURL;
                    link = window.URL.createObjectURL(blob);
                    a = document.createElement("a");
                    if (document.getElementById("caption")!=null) {
                        a.download=document.getElementById("caption").innerText;
                    }
                    else
                    {
                        a.download = 'download';
                    }

                    a.href = link;

                    document.body.appendChild(a);

                    a.click();

                    document.body.removeChild(a);
                } catch (e) {
                }


            return false;
            //return (sa);
        }

        function viewAttendanceProgress(flag){

            var department_id = $('#department_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var month_year = $('#month_year').val();
            var job_type = $('#employment_status_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            jqueryValidationCustom();
            if(validate == 0){
                $('#formSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewAttendanceProgress',
                    type: "GET",
                    data: {month_year:month_year,job_type:job_type,m:m,employee_id:employee_id,category_id:category_id,location_id:location_id,
                        department_id:department_id,from_date:from_date,to_date:to_date,project_id:project_id, flag: flag},
                    success:function(data) {
                        $('#formSection').html(data);
                    },
                    error: function() {
                        $('#formSection').html('');
                        swalError();
                    }
                });
            }
        }

    </script>
@endsection