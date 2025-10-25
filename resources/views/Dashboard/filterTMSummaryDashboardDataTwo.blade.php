@php
    use App\Helpers\CommonHelper;
@endphp
@foreach($projectList as $plRow)
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {{CommonHelper::getProjectWiseProgressBar($plRow->id,$m)}}
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{$plRow->customer_name}}</td>
                        </tr>
                        <tr>
                            <th>Project Name</th>
                            <td>{{$plRow->project_name}}</td>
                        </tr>
                        <tr>
                            <th>Project Type</th>
                            <td>@if ($plRow->project_type == 1) Local @else International @endif</td>
                        </tr>
                        <tr>
                            <th>Total Working Days</th>
                            <td>{{$plRow->total_working_days}}</td>
                        </tr>
                        <tr>
                            <th>Project Status</th>
                            <td>@if ($plRow->project_status == 1) Pending @elseif ($plRow->project_status == 2) Completed @else Hold @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            {{CommonHelper::getProjectWiseTaskSummaryPerformance($plRow->id,$m)}}
        </div>
    </div>
@endforeach