<?php
use App\Helpers\HrHelper;
?>
<style>
    p {
        font-size: 18px;
    }
        b{
            text-decoration: underline;
        }

</style>
<div class="row">
    <div class="col-sm-12 text-center">
        <h2><b>JOINING REPORT</b></h2>
    </div>
</div>
<br><br>
<?php $timestamp = strtotime($date);
$day = date('d', $timestamp);
$month = date('F', $timestamp);
$year = date('Y', $timestamp);

$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($day %100) >= 11 && ($day%100) <= 13)
    $abbreviation = (int) $day. 'th';
else
    $abbreviation = (int) $day. $ends[(int) $day % 10];
?>
<div class="row">
    <div class="col-sm-12">
       <p>Dated: <b> {{$month." ".(int) $day.", ".$year }}</b></p>
        <p>The Deputy CEO</p>
        <p>VIS Credit Rating Company Limited</p>
        <p>Karachi,</p>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col-sm-12">
        <p>This is with reference to my appointment in VIS Credit Rating Company Limited in th position of <b>{{ $designation_name }}</b></p>

        <p>I am joining the company with effect from <b>{{ $abbreviation }} day of {{ $month }} {{ $year }} </b></p>

        <p>Yours sincerely,</p>

        <p>Name: <b>{{ $emp_name }}</b></p>

        <p>Signed on this <b>{{ $abbreviation }} day of {{ $month }} {{ $year }} </b> at Karachi.</p>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col-sm-10"></div>
    <div class="col-sm-2">
        <p style="border-top: 1px solid black;">&nbsp;&nbsp;&nbsp;Deputy CEO</p>
    </div>
</div>