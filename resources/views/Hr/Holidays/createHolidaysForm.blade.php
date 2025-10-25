<?php
$m = Input::get('m');
$month_year = explode('-', date('Y-m-d'));
use App\Helpers\HrHelper;
?>

@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Holidays Calender</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Calender Year:</label>
                                        <select name="year" id="year" class="form-control requiredField" onchange="viewHolidayCalender();">
                                            @for($i = date("Y")-1; $i <= date("Y")+3; $i++)
                                                <option @if($month_year[0] == $i) selected @endif value={{ $i }}>Year {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <div id="calender"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection