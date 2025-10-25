<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
?>

<style>
    input[type="radio"],
    input[type="checkbox"] {
        width: 30px;
        height: 20px;
    }
</style>
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="panel">
                <div class="panel-body">
                    {{ Form::open(['url' => 'had/addIncrementsDetail']) }}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" value="{{ $company_id }}" name="company_id">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">S.No.</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Salary</th>
                                    <th class="text-center col-sm-2">Designation</th>
                                    <th class="text-center col-sm-1">Grade</th>
                                    <th class="text-center col-sm-1">Increment Date</th>
                                    @if ($increment_type == 1)
                                        <th class="text-center col-sm-1">Amount <br>
                                            <input type="number" step="any" id="apply_all" class="form-control" onkeyup="applyToAll(this.value,'{{ $increment_type }}')">
                                        </th>
                                    @elseif($increment_type == 2)
                                        <th class="text-center col-sm-1">Percentage <br>
                                            <input type="number" step="any" id="apply_all" class="form-control" onkeyup="applyToAll(this.value,'{{ $increment_type }}')">
                                        </th>
                                    @endif
                                    <th class="text-center col-sm-1">New Salary</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter = 1;
                                CommonHelper::companyDatabaseConnection($company_id);
                                ?>
                                @if (!empty($employees))
                                    @foreach ($employees as $key => $val)
                                        <?php
                                        $employee_designation = EmployeePromotion::where([['employee_id', '=', $val->id], ['status', '=', 1], ['approval_status', '=', 2], ['designation_id', '!=', '']])->orderBy('id', 'desc');

                                        if ($employee_designation->count() > 0):
                                            $designation_id = $employee_designation->value('designation_id');
                                        else:
                                            $designation_id = $val->designation_id;
                                        endif;

                                        $employee_promotion = EmployeePromotion::where([['employee_id', '=', $val->id], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');
                                        if ($employee_promotion->count() > 0):
                                            $salary = $employee_promotion->value('salary');
                                        else:
                                            $salary = $val->salary;
                                        endif;

                                        ?>
                                        <tr>
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td class="text-center">{{ $val->emp_id }}
                                                <input type="hidden" name="employee_id[]" id="employee_id" value="{{ $val->id }}">
                                            </td>
                                            <td>{{ $val->emp_name }}</td>
                                            <td class="text-right">{{ number_format($val->salary, 0) }}</td>
                                            <td>
                                                <select name="designation_id_{{ $val->id }}" id="designation_id_{{ $val->id }}" class="form-control designation_id" onchange="getGradeByDesignationSingle('{{ $val->id }}')">
                                                    <option value="">Select Designation</option>
                                                    @foreach($designations as $key2 => $val2)
                                                        <option @if($val->designation_id == $val2->id) selected @endif value="{{ $val2->id }}" data-value="{{ $val2->grade_id }}">{{ $val2->designation_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="grade_id_{{ $val->id }}" id="grade_id_{{ $val->id }}" class="form-control grade_id">
                                                    <option value="">Select Grade</option>
                                                    @foreach($grades as $key3 => $val3)
                                                        <option @if($val->grade_id == $val3->id) selected @endif value="{{ $val3->id }}" >{{ $val3->employee_grade_type }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" name="promotion_date_{{ $val->id }}" id="promotion_date_{{ $val->id }}" value="{{ date('Y-m-01') }}" class="form-control requiredField">
                                            </td>
                                            <td>
                                                @if ($increment_type == 1)
                                                    <input type="number" step="any" name="increment_{{ $val->id }}" id="increment_{{ $val->id }}" class="form-control increment" onkeyup="calculateIncrementAmount(this.value,'{{ $val->id }}','{{ $salary }}','{{ $increment_type }}')">
                                                @elseif($increment_type == 2)
                                                    <input type="number" step="any" name="percentage_{{ $val->id }}" id="percentage_{{ $val->id }}" class="form-control increment" onkeyup="calculateIncrementAmount(this.value,'{{ $val->id }}','{{ $salary }}','{{ $increment_type }}')">
                                                    <input type="hidden" name="increment_{{ $val->id }}" id="increment_{{ $val->id }}">
                                                @endif
                                            </td>
                                            <td>
                                                <input readonly type="number" name="salary_{{ $val->id }}" id="salary_{{ $val->id }}" class="form-control" value="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                <?php CommonHelper::reconnectMasterDatabase(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success btn-sm']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $('.designation_id').select2();
    $('.grade_id').select2();

    function calculateIncrementAmount(value, id, salary, increment_type) {
        var old_salary = parseInt(salary);
        if (increment_type == 1) {
            $('#salary_' + id).val(parseInt(value) + old_salary);
        } else if (increment_type == 2) {
            var increment = Math.round((old_salary * value) / 100);
            $('#increment_' + id).val(increment);
            $('#salary_' + id).val(increment + old_salary);
        }
    }

    function applyToAll(value, increment_type) {
        if (increment_type == 1) {
            $('.increment').val(value).keyup();
        } else if (increment_type == 2) {
            $('.increment').val(value).keyup();
        }
    }

    function getGradeByDesignationSingle(id)
    {
        var grade_id = 0;
        grade_id = $("#designation_id_"+id+" option:selected").attr("data-value");
        $('#grade_id_'+id).val(parseInt(grade_id)).change();
    }
</script>