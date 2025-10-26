<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" >
                        <div class="row">
                            <div class="col-sm-8">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="searchId" name="searchId" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="{{ $m }}">
                                </div>
                            </div>
                            <div class="col-sm-8 text-right">
                                @if(in_array('print',$operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export',$operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" id="printList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <!--<th class="text-center">Emp. No.</th>-->
                                        <th class="text-center">Policy Name</th>
                                        <th class="text-center">Start Time</th>
                                        <th class="text-center">End Time</th>
                                        <th class="text-center">Grace Time</th>
                                        <th class="text-center">Half Day Time</th>
                                        <th class="text-center">Days Off</th>
                                        <th class="text-center">created By</th>
                                        <th class="text-center  hidden-print">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1; ?>
                                        @foreach($workingHoursPolicyList as $value)
                                            <tr>
                                                <td class="text-center">{{$counter++}}</td>
                                                <td>{{$value->working_hours_policy}}</td>
                                                <td class="text-center">{{$value->start_working_hours_time}}</td>
                                                <td class="text-center">{{$value->end_working_hours_time}}</td>
                                                <td class="text-center">{{$value->working_hours_grace_time}}</td>
                                                <td class="text-center">{{$value->half_day_time}}</td>
                                                <td class="text-center">{{ rtrim(str_replace("=>",", ",$value->days_off), ", ") }}</td>
                                                <td class="text-center">{{$value->username}}</td>
                                                <td class="text-center">{{HrHelper::getStatusLabel($value->status)}}</td>
                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                                            <i data-feather="chevron-down"
                                                               class="svg-icon"></i></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            @if($value->status == 1)
                                                                <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editWorkingOurPolicyList','{{$value->id}}','Edit working Policy Detail','{{$value->company_id}}')">
                                                                    <a class="delete-modal btn">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                                <li role="presentation" class="actionsLink" onclick="change_status('{{$value->company_id}}','{{$value->id}}','2','working_hours_policy')" >
                                                                    <a class="delete-modal btn">
                                                                        Delete
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if($value->status == 2)
                                                                <li role="presentation" class="actionsLink" onclick="change_status('{{$value->company_id}}','{{$value->id}}','1','working_hours_policy')">
                                                                    <a class="delete-modal btn">
                                                                        Refresh
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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

            var table = $('#exportList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false

            });

            $('#searchId').keyup( function() {
                table.search(this.value).draw();
            });
        });

        function change_status(m,id,status,tableName){
            $.ajax({
                type:'GET',
                url:baseUrl+'/cdOne/change_status',
                data:{m:m,id:id,status:status},
                success:function(res){
                    location.reload()
                }
            });
        }
    </script>
@endsection