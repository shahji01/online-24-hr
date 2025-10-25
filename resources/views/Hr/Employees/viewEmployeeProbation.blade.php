<?php
use App\Helpers\CommonHelper;

?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="{{ Input::get('m') }}">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employees Probation / Intern Period Expiry List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export', $operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper" id="printList">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Emp ID#</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">Joining Date</th>
                                    <th class="text-center">Action</th>
                                    </thead>
                                    <tbody id="tableData"></tbody>
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

        $(document).ready(function(){

            $('#tableData').html('<div class="loader"></div>');
            $.ajax({
                url: baseUrl + '/hdc/viewEmployeeProbationPeriodOverDetail',
                type: "GET",
                data: {m:m},
                success: function (data) {
                    $('#tableData').html(data);
                },
                error: function () {
                    swalError();
                    $('#tableData').html('');
                }
            });
        });

    </script>
@endsection