<?php
use App\Helpers\HrHelper;
?>
<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table table-sm mb-0 table-bordered">
            <tbody>

            <tr>
                <th>Emp ID </th>
                <td>{{ $emp_data->emp_id }}</td>

                <th>Emp Name</th>
                <td>{{ $emp_data->emp_name }}</td>

                <th>Category</th>
                <td>{{ $emp_data->old_category }}</td>
            </tr>
            <tr>
                <th>Project</th>
                <td>{{ $emp_data->old_project }}</td>

                <th>Grade</th>
                <td>{{ $emp_data->new_grade }}</td>

                <th>Reporting To</th>
                <td>{{ $emp_data->reporting_manager }}</td>
            </tr>

            <tr>
                <th>Cell No.</th>
                <td>
                    <?php
                    substr($emp_data->contact_no, 0, 1);
                    if(!empty($emp_data->contact_no) && $emp_data->contact_no[0] != '0'){
                        echo '0'.$emp_data->contact_no;
                    }
                    else{
                        echo $emp_data->contact_no;
                    }

                    ?>
                </td>

                <th>Employment Status</th>
                <td>{{ $job_types[$emp_data->employment_status_id]->job_type_name }}</td>

                <th>Marital Status</th>
                <td>{{ $marital_status[$emp_data->marital_status_id]->marital_status_name }}</td>
            </tr>


            <tr>
                <th>Birth Date</th>
                <td>{{ HrHelper::date_format($emp_data->date_of_birth) }}</td>

                <th>Joining date</th>
                <td>{{ HrHelper::date_format($emp_data->joining_date) }}</td>

                <th>Confirmation date</th>
                <td>{{ HrHelper::date_format($emp_data->confirmation_date) }}</td>
            </tr>

            <tr>
                <th>Gender</th>
                <td>{{ $emp_data->gender_id == 1 ? 'Male' : 'Female' }}</td>

                <th>Nationality</th>
                <td>{{ $emp_data->nationality }}</td>

                <th>Religion</th>
                <td>{{ $emp_data->religion }}</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
{{--<div class="tab-pane  fade in active show" id="Info"></div>--}}