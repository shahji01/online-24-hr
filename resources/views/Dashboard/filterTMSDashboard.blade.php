@php
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $endDate = date('Y-m-d');
@endphp
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <label>Project Name</label>
        <select name="dfProjectId" id="dfProjectId" class="form-control select2" onchange="loadTaskAgainstProjectId(),filterTMSDashboardData()">
            <option value="">Select Project</option>
            @foreach($projectList as $plRow)
                <option value="{{$plRow->id}}">{{$plRow->project_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <label>Project Task Name</label>
        <select name="dfTaskId" id="dfTaskId" class="form-control select2" onchange="filterTMSDashboardData()"></select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidden">
        <label>From Date</label>
        <input type="date" class="form-control" name="df_from_date" id="df_from_date" value="{{$fromDate}}" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 hidden">
        <label>&nbsp;&nbsp;</label>
        <input type="button" class="btn btn-xs btn-success" value="To" style="margin-top: 39px;" />
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidden">
        <label>From Date</label>
        <input type="date" class="form-control" name="df_to_date" id="df_to_date" value="{{$endDate}}" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
        <label>&nbsp;&nbsp;</label>
        <input type="button" class="btn btn-xs btn-success" value="View" onclick="filterTMSDashboardData()" style="margin-top: 39px;" />
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Total Assign Project</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dTotalAssignProject" onclick="loadTMSDashboardSummaryDetailOne(1,'Total Assign Project')"></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Total Assign Project Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dTotalAssignProjectTask" onclick="loadTMSDashboardSummaryDetailOne(2,'Total Assign Project Task')"></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Total Assign Project Sub Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dTotalAssignProjectSubTask" onclick="loadTMSDashboardSummaryDetailOne(3,'Total Assign Project Sub Task')"></div>
        </div>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <strong>Summary Project Wise</strong>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Attended Project Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dAttendedProjectTask" onclick="loadTMSDashboardSummaryProjectWiseDetail(1,'Attended Project Task')"></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Completed Project Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dCompletedProjectTask" onclick="loadTMSDashboardSummaryProjectWiseDetail(2,'Completed Project Task')"></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Pending Project Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dPendingProjectTask" onclick="loadTMSDashboardSummaryProjectWiseDetail(3,'Pending Project Task')"></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Inprogress Project Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dInprogressProjectTask" onclick="loadTMSDashboardSummaryProjectWiseDetail(4,'Inprogress Project Task')"></div>
        </div>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <strong>Summary Project Task Wise</strong>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Attended Project Sub Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dAttendedProjectSubTask" onclick="loadTMSDashboardSummaryProjectTaskWiseDetail(1,'Attended Project Sub Task')"></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Completed Project Sub Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dCompletedProjectSubTask" onclick="loadTMSDashboardSummaryProjectTaskWiseDetail(2,'Completed Project Sub Task')"></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Pending Project Sub Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dPendingProjectSubTask" onclick="loadTMSDashboardSummaryProjectTaskWiseDetail(3,'Pending Project Sub Task')"></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cardBody">
        <div class="row leftCardBorder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardHeading">Inprogress Project Sub Task</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cardContent" id="dInprogressProjectSubTask" onclick="loadTMSDashboardSummaryProjectTaskWiseDetail(4,'Inprogress Project Sub Task')"></div>
        </div>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <strong>Detail Project Wise</strong>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Project Name</th>
                                <th class="text-center">Project Type</th>
                                <th class="text-center">Task Name</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-center">Remaining Days</th>
                            </tr>
                        </thead>
                        <tbody id="getProjectTaskSummary"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <strong>Detail Project Task Wise</strong>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Project Name</th>
                                <th class="text-center">Project Type</th>
                                <th class="text-center">Task Name</th>
                                <th class="text-center">Sub Task Name</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-center">Remaining Days</th>
                            </tr>
                        </thead>
                        <tbody id="getProjectSubTaskSummary"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>