<link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
<div class="container">
    <div class="row">
        <div class="row" style="margin-top: 250px; margin-right: 35px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <p> {{ date_format(date_create(date('Y-m-d')),"F d, Y") }} </p>
            </div>
        </div>
        <div class="row"  style="margin-top: 30px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h3>To Whom It May Concern</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 30px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p style="text-align: justify;">
                    This is to certify that @if($employee->gender_id == '1') Mr. @elseif($employee->gender_id == '2') Ms. @endif {{ $employee->emp_name.' '.$employee->emp_father_name }} CNIC # {{ $employee->cnic }}
                    is currently employed with {{ env('APP_NAME') }} as {{ $designation }} since
                    {{ date_format(date_create($employee->joining_date),"F d, Y") }}. His current salary is Rs. {{ number_format($employee->new_salary) }}/-
                    and working in the capacity of {{ $employment_status }} employment.
                </p>
                <div style="margin-top: 100px;">
                    <p><span style="border-top: solid 1px #000; padding: 15px 10px 0px 10px">Manager Finance</span></p>
                </div>
            </div>
        </div>
    </div>
</div>