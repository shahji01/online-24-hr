<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="{{ URL::asset('assets/custom/js/customMainFunction.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('assets/custom/css/taxCertificate.css') }}">
    <title>Tax Certificate</title>
</head>

<body>
<p class="headings"><strong>Certificate of Collection Deduction of Income <span class="font-small-heading">(including salary)</span> </strong></p>
<div class="top-margin-main"></div>
<div class="center-text">
    <span class="span1">S.No <span class="sno">@if(array_key_exists($employee_id, $employees)) {{ $employees[$employee_id]->emp_id }} @endif</span></span>
    <span class="span1">&nbsp;</span>
    <span class="span1">Original/Duplicate</span>
    <span style="font-size:11px;">Date of issue</span><span class="date-issue">{{ HrHelper::date_format(date('Y-m-d')) }}</span>
</div>

<div class="top-margin-main"></div>

<div class="center-text">
    <span class="span2">Certified that the sum of Rupees</span>
    <span class="span2">&nbsp;</span>
    <span class="span2 bold">{{ number_format($income_tax->tax_paid, 0) }}</span>
    <span class="span2">&nbsp;</span>
    <span class="span2" >(Amount of tax collected/deducted in figures)</span>
</div>

<div class="top-margin-main"></div>

<div class="center-text">
    <div class="line center-text" style="font-size: 12px">{{ CommonHelper::convertNumberToWords($income_tax->tax_paid).' Only' }}</div>
    <div class="line center-text">&nbsp;</div>
    <span class="span-amount">(amount in words)</span>
</div>

<div class="top-margin-main"></div>

<table style="width:100%;text-align: left;border-collapse: collapse;">
    <tr>
        <td class="para1 td-width">on account of income tax has been deducted / collected from (Name and Address of the person from whom tax collected/deducted)</td>
        <td class="line2 td-width center-text"><b>@if(array_key_exists($employee_id, $employees)) {{ $employees[$employee_id]->emp_name }} @endif</b></td>
    </tr>
    <tr>
        <td class="td-width">&nbsp;</td>
        <td class="line2 td-width center-text"></td>
    </tr>
    <tr>
        <td class="td-width">&nbsp;</td>
        <td class="line2 td-width center-text"> TENPEARLS (PVT) LTD </td>
    </tr>
    <tr>
        <td class="td-width">&nbsp;</td>
        <td class="para1 td-width">in case of an individual, his/her name in full and in case of an association of persons / company, name and style of the association of persons/company</td>
    </tr>
</table>

<div>
    <table style="width:100%;text-align: left;border-collapse: collapse;">
        <tr>
            <td class="td1">having National Tax Number </td>
            <td class="line7"></td>
            <td class="td2"> (if any) and</td>
        </tr>
        <tr>
            <td class="td1">holder of CNIC No. </td>
            <td class="line7 center-text">@if(array_key_exists($employee_id, $employees)) {{ $employees[$employee_id]->cnic }} @endif</td>
            <td class="td2">  (in case of an individual) </td>
        </tr>
        <tr>
            <td class="td1">on.</td>
            <td class="line7"></td>
            <td class="td2"> (Date of collection / deduction)</td>
        </tr>
    </table>

    <table style="width:100%;text-align: left;border-collapse: collapse;">
        <tr>
            <td class="td3">Or during the period. </td>
            <td class="td4-date">From</td>
            <td class="td6"><span class="date">01.07.{{ $explode_year[0] }}</span></td>
            <td class="td4">To </td>
            <td class="td7"><span class="date">30.06.{{ $explode_year[1] }}</span></td>
            <td class="td5"> (Period of collection / deduction)</td>
        </tr>
    </table>

    <table style="width:100%;text-align: left;border-collapse: collapse;">
        <tr>
            <td class="td1">under section </td>
            <td class="line7 center-text">149</td>
            <td class="td2">(specify the section of Income Tax Ordinance,2001)</td>
        </tr>

        <tr>
            <td class="td1">On account of. </td>
            <td class="line7 center-text">Salary</td>
            <td class="td2">(specify nature)</td>
        </tr>

        <tr>
            <td class="td1">vide.</td>
            <td class="line7"></td>
            <td class="td2">(particulars of LC, Contract etc.)</td>
        </tr>

        <tr>
            <td class="td1">on the value/amount of Rupee </td>
            <td class="line7 center-text">{{ number_format($income_tax->annual_salary) }}</td>
            <td class="td2">(Gross amount on which tax deducted/collected in figures) </td>
        </tr>

        <tr>
            <td class="td1">Taxable Salary</td>
            <td class="line7 center-text">{{ number_format($income_tax->taxable_income) }}</td>
        </tr>
    </table>
</div>

<div class="top-margin"></div>
<p class="para3">This is to further certify that the tax collected/deducted has been deposited in the Fedral Government Account as per the following details:</p>

<div class="center-text">
    <table style="width:100%;text-align: left;border-collapse: collapse;">
        <tr>
            <td class="font1">Date of deposit</td>
            <td class="font1">SBP/NBP/Treasury</td>
            <td class="font1" >Branch/City</td>
            <td class="font1">Account (Rupees)</td>
            <td class="font1">Challan/Treasury No/ CPR No.</td>
        </tr>
    </table>

    <table style="width:100%;text-align: left;border-collapse: collapse;">
        <tr>
            <td class="font3">VARIOUS</td>
            <td class="font3">VARIOUS</td>
            <td class="font3">Karachi</td>
            <td class="font3">{{ number_format($income_tax->tax_paid) }}</td>
            <td class="font3">VARIOUS</td>
        </tr>

    </table>
    <table style="width:100%;text-align: left;border-collapse: collapse;">
        <tr>
            <td class="line5">_______________</td>
            <td class="line5">_______________</td>
            <td class="line5">_______________</td>
            <td class="line5">_______________</td>
            <td class="line5">_______________</td>
        </tr>
    </table>
</div>

<div class="top-margin"></div>

<P class="para3">Company / office etc. collecting / deducting the tax:</P>
<div class="top-margin"></div>
<table style="width:100%;text-align: left;border-collapse: collapse;">
    <tr>
        <td class="space1">Name </td>
        <td class="line6 center-text">{{ env('APP_NAME') }}</td>
        <td class="space2"> Signature </td>
        <td class="line6 center-text"><img src="http://finance.tenpearls.com/assets/img/SignatureStemp0001.jpg" style="width: 90px;margin-top: -60px; margin-bottom: -10px"></td>
    </tr>

    <tr>
        <td></td>
        <td class="line6"></td>
        <td class="space2">  Name</td>
        <td class="line6 center-text">Manager Finance</td>
    </tr>

    <tr>
        <td class="space1">Address </td>
        <td class="line6 center-text">C69-71, 12th Commercial Street Phase 2 Ext,</td>
        <td class="space2">Designation </td>
        <td class="line6 center-text"></td>
    </tr>

    <tr>
        <td></td>
        <td class="line6 center-text">DHA, Karachi, 75500, Pakistan</td>
        <td class="space2"> Seal </td>
        <td class="line6-noborder"></td>
    </tr>

    <tr>
        <td class="space1">NTN (if any) </td>
        <td class="line6 center-text"></td>
        <td class="space1"></td>
        <td class="line6-noborder"></td>
    </tr>

    <tr>
        <td class="space1">Date</td>
        <td class="line6 center-text">{{ HrHelper::date_format(date('Y-m-d')) }}</td>
        <td class="space1"></td>
        <td class="line6-noborder center-text">
            <img src="http://finance.tenpearls.com/assets/img/SignatureStemp0002.jpg" style="width: 90px;margin-top: -60px; margin-bottom: -10px">
        </td>
    </tr>
</table>

</body>
</html>