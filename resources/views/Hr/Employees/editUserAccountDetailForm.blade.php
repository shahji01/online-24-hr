<?php
use App\Models\Employee;
use App\User;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Storage;
?>

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-sm-12">
                {{ Form::open(array('url' => 'had/editUserAccountDetail')) }}
                <input type="hidden" name="emp_id" value="{{ $users->emp_id }}">
                <input type="hidden" name="employee_id" value="{{ $users->employee_id }}">
                <input type="hidden" name="id" value="{{ $users->id }}">
                <input type="hidden" name="company_id" value="{{ Input::get('m') }}">
                <div class="row" id="credential_area_1">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Account type</label>
                        <select class="form-control" name="account_type" id="account_type">
                            <option @if($users->acc_type == 'user') selected @endif value="user">User</option>
                            <option @if($users->acc_type == 'admin') selected @endif value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Roles</label>
                        <select class="form-control" name="role_id" id="role_id">
                            @foreach($roles as $role)
                                <option @if($users->role_id ==  $role['id']) selected @endif value="{{ $role['id'] }}">{{ $role['role_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Password</label>
                        <input type="text" class="requiredField form-control" id="password" name="password">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <button style="margin-top:35px;color:white" class="btn btn-sm btn-warning" type="button" onclick="password_generator()" >Generate</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success btn-md']) }}
                        <button type="reset" id="reset" class="btn btn-sm btn-primary btn-md">Clear Form</button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#account_type').select2();
        $('#role_id').select2();
    });
</script>