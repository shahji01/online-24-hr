<a  href="{{ url('hr/viewLeaveApplicationRequestList?m='.Input::get('m')) }}" target="_blank" >
    <div class="alert alert-secondary" role="alert" style="color:#383d41;">
        <i class="fa fa-tasks fa-fw" style="color:#ffa500"></i>
        Leave Requests
        <span class="badge badge-pill badge-warning" style="font-size: 16px;color: white;"><?=$leaves?></span>
    </div>
</a>


<a  href="{{ url('hr/viewLoanRequestList?m='.Input::get('m')) }}" target="_blank">
    <div class="alert alert-secondary" role="alert" style="color:#383d41;">
        <i class="fa fa-tasks fa-fw" style="color:rgb(240,173,78);color:#ffa500;"></i>
        Loan Requests
        <span class="badge badge-pill badge-warning" style="font-size: 16px;color: white;"><?=$loan?></span>

    </div>
</a>
