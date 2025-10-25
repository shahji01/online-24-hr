<?php

$m = Input::get('m');
$counter = 1;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Role;
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
                                <h4 class="card-title">User Accounts List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="{{ $m }}">
                                </div>
                            </div>
                            <div class="col-sm-8 text-right">
                                @if(in_array('print', $operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export', $operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                                <thead>
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp Id</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Acc Type</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center hidden-print">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($users) > 0)
                                    @foreach($users as $key => $y)
                                        <tr>
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td class="text-center">{{ $y->emp_id}}</td>
                                            <td>{{ $y->name ?? '--'}}</td>
                                            <td>{{ $y->username ?? '--'}}</td>
                                            <td>{{ $y->email ?? '--'}}</td>
                                            <td>{{ $y->acc_type ?? '--'}}</td>
                                            <td>{{ $y->role_name ?? "--"}}</td>
                                            <input type="hidden" value="{{$y->email}}" id="email">
                                            <td class="text-center hidden-print">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                                        <i data-feather="chevron-down"
                                                           class="svg-icon"></i></button>
                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                        @if(in_array('view', $operation_rights))
                                                                <!--<li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hdc/viewUserAccountDetail','{{ $y->id }}','View User Account Detail','{{ $m }}')">
                                                                <a class="delete-modal btn">
                                                                    View
                                                                </a>
                                                            </li>-->
                                                        @endif
                                                        @if(in_array('edit', $operation_rights))
                                                            <li role="presentation" class="actionsLink">
                                                                <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editUserAccountDetailForm','{{ $y->id }}','Edit User Account Detail','{{ $m }}')">
                                                                    Edit
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(in_array('repost', $operation_rights))
                                                            @if($y->status == 2)
                                                                <li role="presentation" class="actionsLink" onclick="repostCompanyTableRecord('{{ $m }}','{{ $y->id }}','employee')">
                                                                    <a class="delete-modal btn">
                                                                        Repost
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                        @if(in_array('view', $operation_rights))
                                                            <!--<li role="presentation" class="actionsLink" onclick="sendPswd('<?php echo $y->email ?>')">
                                                                <a class="delete-modal btn">
                                                                    Send Password
                                                                </a>
                                                            </li>-->
                                                        @endif
                                                        @if(in_array('delete', $operation_rights))
                                                            @if($y->status == 1)
                                                                <li role="presentation" class="actionsLink" onclick="deleteCompanyMasterTableRecord('/deleteUserAccount','{{ $y->id }}','users','{{ $m }}','')">
                                                                    <a class="delete-modal btn" >
                                                                        Delete
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-danger text-center">No Record Found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    
        $(document).ready(function(){
            var table = $('#UserAccountsList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false
            });
        
            $('#emp_id_search').keyup( function() {
                table.search(this.value).draw();
        
            });
        });
    
        var baseUrl=$('#baseUrl').val();
        function sendPswd(email){

            $.ajax({
                type:'get',
                url: baseUrl+'/emailCheck',
                type: "GET",
                data: {email: email},
                success: function (res) {
                    if(res.success==true){
                        $.notify({
                            icon: "fa fa-check-circle",
                            message: "<b> Password has been sent on his/her email </b>.",
                        }, {
                            type: 'success',
                            timer: 4500
                        });
                    }
                    else{
                        $.notify({
                            icon: "fa fa-times-circle",
                            message: "<b> Your Email Is Not Matched</b>.",
                        }, {
                            type: 'danger',
                            timer: 4500
                        });
                    }
                }
            })
        }
    </script>
@endsection