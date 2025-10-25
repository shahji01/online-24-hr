<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="{{ Input::get('m') }}">
                <input type="hidden" name="old_password" value="{{ $user_account_detail->password }}">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="UserAccountsList">
                                        <tbody>
                                        <tr>
                                            <th class="text-center">Employee ID </th>
                                            <td class="text-center">{{ $user_account_detail['emp_id'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Employee Name </th>
                                            <td class="text-center">{{ $user_account_detail['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Username</th>
                                            <td class="text-center">{{ $user_account_detail['username'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Email</th>
                                            <td class="text-center">{{ $user_account_detail['email'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Role</th>
                                            <td class="text-center">{{ $user_account_detail['acc_type'] }}</td>
                                        </tr>
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
</div>