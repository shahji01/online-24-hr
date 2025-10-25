<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$month_year = explode('-', date('Y-m-d'));
$getYearName = Input::get('month_year');
$getCounterId = 12;
$firstCounterId = 0;
$currentDate = date('Y-m-d');

$m = Input::get('m');
if(empty($bookingRecordSetting)){

}else{
    $displayStartDays = $bookingRecordSetting->display_start_day;
    $displayEndDays = $bookingRecordSetting->display_end_day;
    $displayStartDate = date('Y-m-d', strtotime($currentDate. ' - '.$displayStartDays.' days'));
    $displayEndDate = date('Y-m-d', strtotime($currentDate. ' + '.$displayEndDays.' days'));
}

?>
<style type="text/css">
    .monthneme {
        background: #000;
        color: #fff;
    }

    .mouse-hover:hover{
        cursor: pointer;
        background-color: lightgrey;
    }
</style>


<table class="table table-responsive table-bordered table-condensed">
    <tbody>
<?php
for($ti=$firstCounterId; $ti<$getCounterId; $ti++) {
    if($ti < 9) {
        $month = '0';
        $month .= $ti+1;
    } else {
        $month = $ti+1;
    }
    $fontfamily = isset($fontfamily) ? $fontfamily : "Verdana";
    $defaultfontcolor = isset($defaultfontcolor) ? $defaultfontcolor : "#000000";
    $defaultbgcolor = isset($defaultbgcolor) ? $defaultbgcolor : "#E0E0E0";
    $defaultwbgcolor = isset($defaultwbgcolor) ? $defaultwbgcolor : "#F5F4D3";
    $todayfontcolor = isset($todayfontcolor) ? $todayfontcolor : "#000000";
    $todaybgcolor = isset($todaybgcolor) ? $todaybgcolor : "#F2BFBF";
    $monthcolor = isset($monthcolor) ? $monthcolor : "#000000";
    $relfontsize = isset($relfontsize) ? $relfontsize : "1";
    $cssfontsize = isset($cssfontsize) ? $cssfontsize : "7pt";
    // obtain month, today date etc
    $month = (isset($month)) ? $month : date("n",time());
    $monthnames = array("January","February","March","April","May","June","July","August","September","October","November",	"December");
    $textmonth = $monthnames[$month - 1];
    $getYearName = (isset($getYearName)) ? $getYearName : date("Y",time());
    $today = (isset($today))? $today : date("j", time());
    $today = ($month == date("n",time())) ? $today : 32;
    // The Names of Hijri months
    $mname = array("Muharram","Safar","Rabi'ul Awal","Rabi'ul Akhir","Jamadil Awal","Jamadil Akhir","Rajab","Sha'ban","Ramadhan","Shawwal","Zul Qida","Zul Hijja");
    // End of the names of Hijri months

    // Setting how many days each month has
    if ( (($month < 8) && ($month % 2 == 1)) || (($month > 7) && ($month % 2 ==
                            0)) ) $days = 31;
    if ( (($month < 8) && ($month % 2 == 0)) || (($month > 7) && ($month % 2 ==
                            1)) )
        $days = 30;

    //checking leap year to adjust february days
    if ($month == 2)
        $days = (date("L",time())) ? 29 : 28;

    $dayone = date("w",mktime(1,1,1,$month,1,$getYearName));
    $daylast = date("w",mktime(1,1,1,$month,$days,$getYearName));
    $middleday = intval(($days-1)/2);

    //checking the hijri month on beginning of gregorian calendar
    $date_hijri = date("$getYearName-$month-1");
    list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
    $smon_hijridone = $mname[$HMonths-1];
    $syear_hijridone = $HYear;

    //checking the hijri month on end of gregorian calendar
    $date_hijri = date("$getYearName-$month-$days");
    list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
    $smon_hijridlast = $mname[$HMonths-1];
    $syear_hijridlast = $HYear;

    //checking the hijri month on middle of gregorian calendar
    $date_hijri = date("$getYearName-$month-$middleday");
    list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
    $smon_hijridmiddle = $mname[$HMonths-1];
    $syear_hijridmiddle = $HYear;

    // checking if there's a span of a year
    if ($syear_hijridone == $syear_hijridlast) {
        $syear_hijridone = "";
    }

    //checking if span of month is only one or two or three hijri months

    if (($smon_hijridone == $smon_hijridmiddle) AND ($smon_hijridmiddle == $smon_hijridlast)) {
        $smon_hijri = "<font style='font-size: 12px !important; color: red; font-weight: bold;'>".$smon_hijridone."&nbsp;".$syear_hijridlast."</font>";
    }

    if (($smon_hijridone == $smon_hijridmiddle) AND ($smon_hijridmiddle != $smon_hijridlast)) {
        $smon_hijri = "<font style='font-size: 12px !important; color: red; font-weight: bold;'>".$smon_hijridone."&nbsp;".$syear_hijridone."&nbsp;-&nbsp;".$smon_hijridlast."&nbsp;".$syear_hijridlast."</font>";
    }

    if (($smon_hijridone != $smon_hijridmiddle) AND ($smon_hijridmiddle == $smon_hijridlast)) {
        $smon_hijri = "<font style='font-size: 12px !important; color: red; font-weight: bold;'>".$smon_hijridone."&nbsp;".$syear_hijridone."&nbsp;-&nbsp;".$smon_hijridlast."&nbsp;".$syear_hijridlast."</font>";
    }

    if (($smon_hijridone != $smon_hijridmiddle) AND ($smon_hijridmiddle != $smon_hijridlast)) {
        $smon_hijri = "<font style='font-size: 12px !important; color: red; font-weight: bold;'>".$smon_hijridone."&nbsp;".$syear_hijridone."&nbsp;-&nbsp;"."&nbsp;-&nbsp;".$smon_hijridmiddle."&nbsp;-&nbsp;".$smon_hijridlast."&nbsp;".$syear_hijridlast."</font>";
    }

    if($ti%3==0) echo "<tr>";
    echo "<td valign='top' class='text-center'><span><b><h5 id='heading'><a class='btn btn-xs btn-warning'>"?><?php echo $textmonth."&nbsp;".$getYearName."<br />".$smon_hijri."</a></h5></b></span><div class='table-responsive'><table border=0 class='table table-responsive table-bordered'><tbody><tr><td class='monthneme'>Sun</td><td class='monthneme'>Mon</td><td class='monthneme'>Tue</td><td class='monthneme'>Wed</td><td class='monthneme'>Thu</td><td class='monthneme'>Fri</td><td class='monthneme'>Sat</td></tr>";
    $timestamp = mktime(0,0,0,$month,1,$getYearName);
    $maxday = date("t",$timestamp);
    $thismonth = getdate ($timestamp);
    $startday = $thismonth['wday'];
    $ab = 0;
    for($i=0; $i<($maxday+$startday); $i++) {
        $dayinnumber=$i - $startday + 1;
        if($dayinnumber <= 9){
            $dayinnumber='0';
            $dayinnumber.=$i - $startday + 1 .'-'.$month.'-'.$getYearName;
        }else{
            $dayinnumber=$i - $startday + 1 .'-'.$month.'-'.$getYearName;
        }
        if(($i % 7) == 0 ) {
            echo "<tr>\n";
            $style="style=''";
        } else $style = "";
        if($i < $startday) echo "<td></td>\n";
        else{
            $ab++;
            $date_hijri = date("$getYearName-$month-$ab");
            list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
            $date_hijri = date("$getYearName-$month-$ab");
            list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
            if ($HDays == 30) {
                $ab = $ab + 1;
                $date_hijri = date("$getYearName-$month-$ab");
                list ($HDays, $HMonths, $HYear) = Hijri($date_hijri);
                if ($HDays == 2) {
                    $HDays = 1;
                }
                else {
                    $HDays = 30;
                }
                $ab = $ab - 1;
            }
            $sday_hijri = "<font style='font-size: 13px !important; color: red; font-weight: bold;'>".$HDays."</font>";



            $checkVars['bookings'] = array();
            if(in_array($dayinnumber, $checkVars['bookings'])){
                // Value is found.
                echo "<td  data-original-title='";?>
                <?php
            } else {
                $echoDay = ($i - $startday + 1);
                $dayinnumber;
                if(empty($bookingRecordSetting)){
                    $displayDatesRights = '2';
                }else{
                    $displayStartDays = $bookingRecordSetting->display_start_day;
                    $displayEndDays = $bookingRecordSetting->display_end_day;
                    $displayStartDate = date('Y-m-d', strtotime($currentDate. ' - '.$displayStartDays.' days'));
                    $displayEndDate = date('Y-m-d', strtotime($currentDate. ' + '.$displayEndDays.' days'));
                    $displayDatesRights = check_in_range($displayStartDate, $displayEndDate, date("Y-m-d", strtotime($dayinnumber)));
                }
                if($displayDatesRights == ''){

                }else{
                    //echo HrHelper::checkHolidayStatus($echoDay,$month,$getYearName,$sday_hijri,$m);
                    //echo "<td class='mouse-hover' onclick='addHolidaysDetail(".$echoDay.",".$month.",".$getYearName.")'>".$echoDay." ".$sday_hijri."</td>";
                    //echo BookingHelper::checkBookingStatus($m,$dayinnumber,$getLawnName,$sday_hijri,$echoDay,$checkLawnStatus->marge_lawn_status,$getYearName,$month);
                }
            }
        }
        if(($i % 7) == 6 ) echo "</tr>\n";
    }
    echo "</tbody></table></div></td>";
    if($ti!=0 && ($ti+1)%3==0) echo "</tr>";
}
echo "
    </tbody>
</table>";


function Hijri($GetDate){
    $TDays=round(strtotime($GetDate)/(60*60*24));
    $HYear=round($TDays/354.37419);
    $Remain=$TDays-($HYear*354.37419);
    $HMonths=round($Remain/29.531182);
    $HDays=$Remain-($HMonths*29.531182);
    $HYear=$HYear+1389;
    $HMonths=$HMonths+10;
    $HDays=$HDays+23;
    // If the days is over 29, then update month and reset days
    if ($HDays>29.531188 and round($HDays)!=30){
        $HMonths=$HMonths+1;
        $HDays=Round($HDays-29.531182);
    }else{
        $HDays=Round($HDays);
    }
    // If months is over 12, then add a year, and reset months
    if($HMonths>12){
        $HMonths=$HMonths-12;
        $HYear=$HYear+1;
    }
    return array ($HDays, $HMonths, $HYear);
}

function check_in_range($start_date, $end_date, $date_from_user){
    // Convert to timestamp
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($date_from_user);
    //echo $start_ts.' - '.$end_ts.' - '.$user_ts;
    // Check that user date is between start & end
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}
?>