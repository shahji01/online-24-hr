<style>
    .floatLeft{
        width: 40%;
        float: left;
    }
    .floatRight{
        width: 40%;
        float: right;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="floatLeft">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Project Name</th>
                                    <td>{{$getProjectDetail->project_name}}</td>
                                </tr>
                                <tr>
                                    <th>Project Type</th>
                                    <td>@if ($getProjectDetail->project_type == 1) Local @else International @endif</td>
                                </tr>
                                <tr>
                                    <th>Project Owner Name</th>
                                    <td>{{$getProjectDetail->project_owner_name}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="floatRight">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="2">Privious Assign Employees</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        @php
                                            $empIdsArray = [];
                                        @endphp
                                        @foreach($getPriviousAssignEmployees as $gpaeRow)
                                            @php
                                                $empIdsArray[] = $gpaeRow->id;
                                            @endphp
                                            <span class="btn btn-success btn-xs">{{$gpaeRow->emp_name}}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">New Assign Employees</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <select name="assign_employees[]" class="form-control" multiple id="assign_employees">
                                            @foreach($employees as $eRow)
                                                <option value='{{$eRow->id}}' @if(in_array($eRow->id, $empIdsArray)) selected @endif>{{$eRow->emp_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right"><a class="btn btn-xs btn-success" onclick="updateAssignEmployeesDetail()">Update Detail</a></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function updateAssignEmployeesDetail(){
        var m = $('#m').val();
        var baseUrl = $('#baseUrl').val();
        var assignEmployees = $('#assign_employees').val();
        $.ajax({
            url: ''+baseUrl+'/tms/company-projects/updateAssignEmployeesDetail',
            method: 'GET',
            data: {
                m: m,
                assignEmployees: assignEmployees,
                projectId: '{{$getProjectDetail->id}}'
            },
            error: function() {
                alert('error');
            },
            success: function(response) {
                location.reload();
            }
        });
    }
</script>