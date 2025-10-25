<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h3 class="tabsHeading">Policies</h3>
        <div class="row">
            <ul>
                @foreach($policies as $key => $val)
                    <li><h6><a class="policyLinks" target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h6></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h3 class="tabsHeading" >Forms</h3>
        <div class="row">
            <ul>
                @foreach($forms as $key => $val)
                    <li><h6><a class="policyLinks" target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h6></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{--<div class="tab-pane fade in" id="Policies"></div>--}}