<div class="row">&nbsp;</div>
@if($team_lead_exists->exists())
    @foreach($team_lead_exists->get() as $value)
        <div class="col-sm-5 text-center teaminfo" id="userinfo">
            <div class="row card">
                <div class="col-sm-12 card-body" style="border-top: none;border-bottom: 2px solid rgba(151,138,237,0.83);">
                    <img src="<?= url($value->img_path != '' ? 'storage/'.$value->img_path  : 'storage/app/uploads/employee_images/user-dummy.png')?> " class="avatar img-circle img-thumbnail" style="width:30%;" alt="avatar">
                    <br>
                    <h5 id="username"><strong> {{$value->emp_name  }}</strong></h5>
                    <pre style="font-size: 12px;">{{ $departments[$value->department_id]->department_name }}  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; {{ $designations[$value->designation_id]->designation_name }} </pre>
                    <pre style="font-size: 12px;">{{ $value->official_email ?? "--"}}</pre>
                </div>
            </div>
        </div>
        <div class="col-sm-1"></div>
    @endforeach
@else
    <div class="col-sm-12 text-center">
        <p class="text-danger">No Record Found</p>
    </div>
@endif
{{--<div class="tab-pane fade in" id="team"></div>--}}