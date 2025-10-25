@php
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $endDate = date('Y-m-d');
@endphp
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <label>Project Name</label>
        <select name="dfProjectId" id="dfProjectId" class="form-control select2" onchange="filterTMSummaryDashboardData()">
            <option value="">Select Project</option>
            @foreach($projectList as $plRow)
                <option value="{{$plRow->id}}">{{$plRow->project_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
        <label>&nbsp;&nbsp;</label>
        <input type="button" class="btn btn-xs btn-success" value="View" onclick="filterTMSummaryDashboardData()" style="margin-top: 39px;" />
    </div>
</div>
<br />
<div id="filterTMSummaryDashboardDataTwo"></div>
