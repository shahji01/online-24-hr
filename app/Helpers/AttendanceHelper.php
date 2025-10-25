<?php
namespace App\Helpers;
use App\Models\Regions;
use DB;
use Config;
use Auth;
use Illuminate\Support\Facades\App;
use Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use App\Models\Log;


class AttendanceHelper{

    public static function addEmptyAttendanceRecords()
    {
        $firstDayOfPreviousMonth = date('Y-m-01', strtotime('first day of last month'));
        $lastDayOfNextMonth = date('Y-m-t', strtotime('last day of next month'));
        $previousAndFutureMonth = " INSERT IGNORE INTO attendance (employee_id,  attendance_date, day, month, year, date, time, username) 
            SELECT e.id AS employee_id,
            DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS monthdate,
            DATE_FORMAT(DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY, '%W') AS day_name,
            MONTH(DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS month,
            YEAR(DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS year,
            CURDATE() AS `date`,CURTIME() AS `time`,'auto_date' AS username
            FROM (
                SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
            ) AS a
            CROSS JOIN (
                SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
            ) AS b
            CROSS JOIN (
                SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
            ) AS c
            CROSS JOIN employee e
            WHERE NOT EXISTS (
                SELECT 1
                FROM attendance a
                WHERE a.employee_id = e.id
                    AND a.attendance_date = DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY
            )
            AND DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY <= '$lastDayOfNextMonth'
            AND (e.attendance_id IS NOT NULL AND e.attendance_id NOT IN ('', 'EXMPT', 'EXEMPT', '0'));";

        DB::select(DB::raw($previousAndFutureMonth));
    }
    public static function totalPresentDays($date_from,$date_to,$emr_array)
    {
        $totalPresentDay = [];
        
        $totaloffDayOfMonth = static::totaloffDayOfMonth($date_from,$date_to);
        $totaloff = static::totalHoliday($date_from,$date_to);
        // $totaloff =   array_merge($totaloffDayOfMonth , $totalHoliday) ; 
      
        $totaloff = $totaloff ? "'" . implode ( "', '", $totaloff ) . "'": 0 ;
        
        $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $totalPresentDayQuery = "select COUNT(a.id) presentDays ,a.employee_id
                                    FROM attendance a
                                    INNER JOIN employee e
                                    ON e.id = a.employee_id
                                    WHERE e.status = 1 AND e.status = 1 AND  employee_id IN ($emr_array)  
                                        AND attendance_date not in ($totaloff) 
                                        AND attendance_date  BETWEEN '$date_from' and '$date_to' 
                                        AND (clock_in  != '' or clock_out != '') 
                                        AND e.employment_status_id != 5 
                                        AND (attendance_status not IN ('day_off','holiday')  OR attendance_status IS NULL) 
                                        AND (
                                            a.attendance_type = 1
                                            OR (a.attendance_type = 2 AND NOT EXISTS (
                                                SELECT 1
                                                FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                                                WHERE
                                                    sub_att.employee_id = a.employee_id
                                                    AND sub_att.attendance_date = a.attendance_date
                                                    AND sub_att.attendance_type = 1
                                            ))
                                        )
                                        GROUP BY a.employee_id";


            //  echo "<pre>";
            //  print_r($totalPresentDayQuery);
            //  exit();
        $totalPresentDayQueryData = DB::select(DB::raw($totalPresentDayQuery));
        foreach ($totalPresentDayQueryData as $key => $value) {
            $totalPresentDay[$value->employee_id] = $value->presentDays;
        }

        return $totalPresentDay;
    }


    public static function totalPresentDaysDw($date_from,$date_to,$emr_array)
    {
        $totalPresentDay = [];
        
                $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        
        $totalPresentDayQuery = "select COUNT(a.id) presentDays ,a.employee_id
                                    FROM attendance a
                                    INNER JOIN employee e
                                    ON e.id = a.employee_id
                                    WHERE e.status = 1 AND e.status = 1 AND  employee_id IN ($emr_array) 
                                    AND attendance_date  BETWEEN '$date_from' and '$date_to'
                                    AND (clock_in  != '' or clock_out != '')
                                    AND e.employment_status_id = 5  
                                    AND (
                                        a.attendance_type = 1
                                        OR (a.attendance_type = 2 AND NOT EXISTS (
                                            SELECT 1
                                            FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                                            WHERE
                                                sub_att.employee_id = a.employee_id
                                                AND sub_att.attendance_date = a.attendance_date
                                                AND sub_att.attendance_type = 1
                                        ))
                                    )
                                    GROUP BY employee_id";

        $totalPresentDayQueryData = DB::select(DB::raw($totalPresentDayQuery));
        foreach ($totalPresentDayQueryData as $key => $value) {
            $totalPresentDay[$value->employee_id] = $value->presentDays;
        }

        return $totalPresentDay;
    }

    public static function totalSundays($date_from, $date_to, $emr_array)
    {
        $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $totalSundays = 0;
       
        $totalHolidays = static::totalHoliday($date_from, $date_to);
        
        $totalholicount = count($totalHolidays);
        
        
        $totalHoliday = $totalHolidays ? "'" . implode("', '", $totalHolidays) . "'" : 0;

        $totalSundaysQuery = "SELECT IFNULL(COUNT(a.id), 0) AS totalSunday, e.id
        FROM employee AS e
        LEFT JOIN attendance AS a ON a.employee_id = e.id AND
            a.status = 1 AND
            a.attendance_date NOT IN ($totalHoliday) AND
            a.attendance_date BETWEEN '$date_from' AND '$date_to' AND
            a.attendance_status IN ('day_off') AND (
                a.attendance_type = 1 OR (
                    a.attendance_type = 2 AND NOT EXISTS (
                        SELECT 1
                        FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                        WHERE sub_att.employee_id = a.employee_id AND
                            sub_att.attendance_date = a.attendance_date AND
                            sub_att.attendance_type = 1
                    )
                )
            )
        WHERE e.id IN ($emr_array)
        GROUP BY e.id;";

        $totalSundaysQueryData = DB::select(DB::raw($totalSundaysQuery));
        $maxTotalSunday = [];
                                           
        foreach ($totalSundaysQueryData as $key => $value) {
            
                // $maxTotalSunday = $value->totalSunday;
                $maxTotalSunday[$value->id]=$value->totalSunday+$totalholicount;
        
        }
        // echo "<pre>";
        // print_r($maxTotalSunday);
        // exit();
        return $maxTotalSunday;
    }



    public static function totaloffDayOfMonth($date_from,$date_to)
    {
        
        $totalSundays = [];

        $totalSundaysQuery = "select attendance_date totalSunday 
        from attendance
            where status = 1 AND
            attendance_date  BETWEEN '$date_from' and '$date_to' AND
            attendance_status IN ('day_off') GROUP BY attendance_date";


        $totalSundaysQueryData = DB::select(DB::raw($totalSundaysQuery));
        $totalHoliday = Count(static::totalHoliday($date_from,$date_to));


        foreach ($totalSundaysQueryData as $key => $value) {
          
                $totalSundays[] = $value->totalSunday ;
        }

        return $totalSundays ? $totalSundays : [] ;

    }

    public static function totalHoliday($date_from,$date_to)
    {
        $totalHoliday = [];

        $totalHolidayQuery = "select holiday_date totalHoliday  from holidays 
        where status = 1 and 
        holiday_date BETWEEN '$date_from' and '$date_to' ";


        $totalHolidayQueryData = DB::select(DB::raw($totalHolidayQuery));

        foreach ($totalHolidayQueryData as $key => $value) {
            if($value->totalHoliday > 0):
                $totalHoliday[] = $value->totalHoliday ;
            endif;
        }

        return $totalHoliday ? $totalHoliday : [] ;
    }

    public static function totalAbsentDays($date_from,$date_to,$emr_array)
    {
                $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
                
        $totalAbsentDays = [];
        // $totaloffDayOfMonth = static::totaloffDayOfMonth($date_from,$date_to);
        $totaloff = static::totalHoliday($date_from,$date_to);
        // $totaloff =   array_merge($totaloffDayOfMonth , $totalHoliday) ; 
      
        $totaloff = $totaloff ? "'" . implode ( "', '", $totaloff ) . "'": 0 ;

        $totalAbsentDaysQuery = "select COUNT(id) abesentDays ,employee_id
        from attendance
            where status = 1 and employee_id IN ($emr_array) AND
            attendance_date not in ($totaloff) AND
            attendance_date  BETWEEN '$date_from' and '$date_to' AND
            ((clock_in  = '' OR clock_in  is NULL) and (clock_out = '' OR clock_out is NULL))   AND 
            (attendance_status not IN ('day_off','holiday')OR attendance_status IS NULL) 
            AND (
                attendance_type = 1
                 OR (attendance_type = 2 AND NOT EXISTS (
                    SELECT 1
                    FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                    WHERE
                        sub_att.employee_id = employee_id
                        AND sub_att.attendance_date = attendance_date
                        AND sub_att.attendance_type = 1
                )) 
            )
            GROUP BY employee_id";

        $totalAbsentDaysQueryData = DB::select(DB::raw($totalAbsentDaysQuery));

  
        foreach ($totalAbsentDaysQueryData as $key => $value) {
            $totalAbsentDays[$value->employee_id] = $value->abesentDays ; // - $totalHoliday;
        }

        return $totalAbsentDays;
    }

    public static function cplLeave($date_from,$date_to,$emr_array)
    {
                $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $cplQuery = "
        SELECT a.employee_id , a.seconds totalOvertime ,SUM(a.cplleave) cpl FROM 
        ( 
            SELECT 
            a.employee_id , 
            a.attendance_status,
            1 cplleave,
            TIME_TO_SEC(0-TIMEDIFF(a.clock_in, a.clock_out))/60 AS seconds      
            FROM attendance a 
            INNER JOIN employee e
            ON  e.id = a.employee_id
            INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp
            ON a.working_hours_policy_id = whp.id
            inner JOIN ( 
            SELECT h.holiday_date FROM holidays h 
            WHERE h.status = 1 AND h.holiday_date BETWEEN '$date_from' AND '$date_to' ) 
            holiday ON holiday.holiday_date = a.attendance_date WHERE a.employee_id IN ($emr_array) AND a.status = 1 AND
            round(TIME_TO_SEC(0-TIMEDIFF(a.clock_in, a.clock_out))/60) >= 240 AND e.cpl_or_ot = 'cpl' AND  whp.ot_cpl IN ('holiday_and_offday','all_days') 
            
            UNION ALL
            SELECT 
            a.employee_id ,
            a.attendance_status,
            1 cplleave,
            TIME_TO_SEC(0-TIMEDIFF(a.clock_in, a.clock_out))/60 AS seconds
            FROM attendance a

            INNER JOIN employee e
            ON  e.id = a.employee_id
            INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp
            ON a.working_hours_policy_id = whp.id
            WHERE a.employee_id IN ($emr_array) AND a.attendance_status = 'day_off' AND
            a.attendance_date BETWEEN '$date_from' AND '$date_to' and round(TIME_TO_SEC(0-TIMEDIFF(a.clock_in, a.clock_out))/60) >= 240 AND e.cpl_or_ot = 'cpl' AND  whp.ot_cpl IN ('holiday_and_offday','all_days') 
            
            ) a GROUP BY a.employee_id";


        $cplQueryData = DB::select(DB::raw($cplQuery));
        $cplLeaveArray= [];
        foreach ($cplQueryData as $key => $value) {

            $cplLeaveArray[$value->employee_id] = $value->cpl;

        }

        return $cplLeaveArray;
    }

    public static function overTimeOffdayAndHoliday($date_from,$date_to,$emr_array)
    {
                $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $overTimeArray = [];

        $totalHoliday = static::totalHoliday($date_from,$date_to);
        $totalHoliday = $totalHoliday ? "'" . implode ( "', '", $totalHoliday ) . "'": 0 ;


        $overtimeQuery = "SELECT a.employee_id , ROUND(SUM(a.hours),0) totalOvertime FROM (
            SELECT 
            a.employee_id ,
            a.attendance_status,
            SUM(
                IF(
              a.clock_in > a.clock_out,
              IF(
                             
                  LPAD((TIME_TO_SEC(TIMEDIFF('23:59',a.clock_in)) % 3600) DIV 60, 2, '0') >= overtime_after_minutes,
                  CEIL(TIME_TO_SEC(TIMEDIFF('23:59',a.clock_in)) / 3600) ,
                  FLOOR(TIME_TO_SEC(TIMEDIFF('23:59',a.clock_in)) / 3600)
                ) 
                +
             IF(
                           
                LPAD((TIME_TO_SEC(TIMEDIFF( a.clock_out,'00:00')) % 3600) DIV 60, 2, '0') >= overtime_after_minutes,
                CEIL(TIME_TO_SEC(TIMEDIFF( a.clock_out,'00:00')) / 3600) ,
                FLOOR(TIME_TO_SEC(TIMEDIFF( a.clock_out,'00:00')) / 3600)
              ),
              IF(
                             
                  LPAD((TIME_TO_SEC(TIMEDIFF(a.clock_out, a.clock_in)) % 3600) DIV 60, 2, '0') >= overtime_after_minutes,
                  CEIL(TIME_TO_SEC(TIMEDIFF(a.clock_out, a.clock_in)) / 3600) ,
                  FLOOR(TIME_TO_SEC(TIMEDIFF(a.clock_out, a.clock_in)) / 3600)
                )
              )) hours
            FROM attendance a 
            INNER JOIN employee e
            ON  e.id = a.employee_id
            INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp
            ON a.working_hours_policy_id = whp.id
            WHERE a.employee_id IN ($emr_array)  AND 
            (a.clock_in !='' OR a.clock_out !='') AND 
            a.attendance_date in ($totalHoliday) AND
             a.status = 1 
             AND (
                a.attendance_type = 1
                
            )
             GROUP BY  a.employee_id 
            
            UNION all
            
            SELECT 
            a.employee_id ,
            a.attendance_status,
            SUM(
                IF(
              a.clock_in > a.clock_out,
              IF(
                             
                  LPAD((TIME_TO_SEC(TIMEDIFF('23:59',a.clock_in)) % 3600) DIV 60, 2, '0') >= overtime_after_minutes,
                  CEIL(TIME_TO_SEC(TIMEDIFF('23:59',a.clock_in)) / 3600) ,
                  FLOOR(TIME_TO_SEC(TIMEDIFF('23:59',a.clock_in)) / 3600)
                ) 
                +
             IF(
                           
                LPAD((TIME_TO_SEC(TIMEDIFF( a.clock_out,'00:00')) % 3600) DIV 60, 2, '0') >= overtime_after_minutes,
                CEIL(TIME_TO_SEC(TIMEDIFF( a.clock_out,'00:00')) / 3600),
                FLOOR(TIME_TO_SEC(TIMEDIFF( a.clock_out,'00:00')) / 3600)
              ),
              IF(
                             
                  LPAD((TIME_TO_SEC(TIMEDIFF(a.clock_out, a.clock_in)) % 3600) DIV 60, 2, '0') >= overtime_after_minutes,
                  CEIL(TIME_TO_SEC(TIMEDIFF(a.clock_out, a.clock_in)) / 3600) ,
                  FLOOR(TIME_TO_SEC(TIMEDIFF(a.clock_out, a.clock_in)) / 3600)
                )
              )) hours
            FROM attendance a 
            INNER JOIN employee e
            ON  e.id = a.employee_id
            INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp
            ON a.working_hours_policy_id = whp.id
            WHERE a.employee_id IN ($emr_array) AND 
            a.attendance_status = 'day_off' AND
            (a.clock_in !='' and a.clock_out !='') AND 
            a.attendance_date BETWEEN '$date_from' AND '$date_to' GROUP BY  a.employee_id  
            ) a
             GROUP BY a.employee_id;
            ";

          
        $overtimeQueryData = DB::select(DB::raw($overtimeQuery));

        foreach ($overtimeQueryData as $key => $value) {

            $overTimeArray[$value->employee_id] = (int)$value->totalOvertime;

        }

        return $overTimeArray;
    }

    public static function overTimeAllday($date_from,$date_to,$emr_array)
    {
        $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $overTimeArray = [];

        $totalHoliday = static::totalHoliday($date_from,$date_to);
        $totalHoliday = $totalHoliday ? "'" . implode ( "', '", $totalHoliday ) . "'": 0 ;


        $overtimeQuery = "SELECT a.employee_id ,ROUND(SUM(a.hours),0) totalOvertime FROM (
           
                        SELECT 
                        a.employee_id ,
                        a.attendance_date,

                        SUM(
                            IF(
                                        
                            LPAD((TIME_TO_SEC(TIMEDIFF(a.clock_out, end_working_hours_time)) % 3600) DIV 60, 2, '0') >= overtime_after_minutes,
                            CEIL(TIME_TO_SEC(TIMEDIFF(a.clock_out, end_working_hours_time)) / 3600) ,
                            FLOOR(TIME_TO_SEC(TIMEDIFF(a.clock_out, end_working_hours_time)) / 3600)
                            )
                        )   hours 
                        FROM attendance a 
                        INNER JOIN employee e
                        ON  e.id = a.employee_id
                        INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp
                        ON a.working_hours_policy_id = whp.id
                            WHERE a.employee_id IN ($emr_array) AND
                        (a.clock_in !='' and a.clock_out !='') AND 
                        (a.attendance_status != 'day_off' OR a.attendance_status IS NULL) AND
                        a.attendance_date BETWEEN '$date_from' AND '$date_to'
                        AND (
                            a.attendance_type = 1
                            
                        ) 
                         GROUP BY  a.employee_id  HAVING  hours > 0 

                        ) a
                        GROUP BY a.employee_id;
            ";

    
        $overtimeQueryData = DB::select(DB::raw($overtimeQuery));


        foreach ($overtimeQueryData as $key => $value) {

            $overTimeArray[$value->employee_id] = (int)$value->totalOvertime;

        }

        return $overTimeArray;
    }

    public static function totalFullDayLeave($date_from,$date_to,$emr_array)
    {

        $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        
        $yesterday = date("Y-m-d", strtotime($date_to . " -1 day"));

        $FullDayLeaveQuery = "
        
        SELECT la.employee_id,
           
            SUM(IFNULL(

            CASE
                WHEN lad.from_date BETWEEN '$date_from' AND '$yesterday' THEN  
                if(lad.to_date > '$date_to', DATEDIFF('$date_to' , lad.from_date) + 1 ,
                DATEDIFF(lad.to_date , lad.from_date) + 1) 

                WHEN lad.from_date = '$date_to' THEN 1

                WHEN lad.to_date BETWEEN '$date_from' AND '$date_to'  THEN   
                DATEDIFF(lad.to_date , '$date_from') + 1 

            END

            ,0)) totalleave

            from ".CONST_MASTER_DB.".leave_application_data lad 
            inner join ".CONST_MASTER_DB.".leave_application la 
            on la.id = lad.leave_application_id 
            where la.employee_id in ($emr_array) and 
            (la.status = 1 and la.approval_status = 2) AND 
            lad.from_date != '0000-00-00' AND 
            (lad.from_date BETWEEN '$date_from' AND '$date_to'
            OR
            lad.to_date BETWEEN '$date_from' AND '$date_to' )
            GROUP BY lad.employee_id
                        ";

        $FullDayLeaveQueryData = DB::select(DB::raw($FullDayLeaveQuery));


        // echo $FullDayLeaveQuery;
        // exit();
        $totalFullDayLeaveApproved = [];


        if (!empty($FullDayLeaveQueryData)):
            foreach ($FullDayLeaveQueryData as $val):

                $totalFullDayLeaveApproved[$val->employee_id] = (int)$val->totalleave;

            endforeach;
        endif;



        return $totalFullDayLeaveApproved;


    }

    public static function totalHalfDayLeave($date_from,$date_to,$emr_array)
    {
                $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;

        $HalfDayLeaveQuery = "SELECT
         la.employee_id,
         SUM( lad.no_of_days ) totalleave
         from ".CONST_MASTER_DB.".leave_application_data lad
         inner join ".CONST_MASTER_DB.".leave_application la
         on la.id = lad.leave_application_id 
         where la.employee_id IN ($emr_array) and (la.status = 1 and la.approval_status = 2)
         AND lad.first_second_half_date != '0000-00-00'
         AND first_second_half_date BETWEEN '$date_from' AND '$date_to' GROUP BY lad.employee_id
          ";

       
        $HalfDayLeaveQueryData = DB::select(DB::raw($HalfDayLeaveQuery));

        $totalHalfDayLeaveApproved = [];


        if (!empty($HalfDayLeaveQueryData)):
            foreach ($HalfDayLeaveQueryData as $val):

                $totalHalfDayLeaveApproved[$val->employee_id] = $val->totalleave;

            endforeach;
        endif;

        return $totalHalfDayLeaveApproved;
    }

    public static function totalLate($date_from,$date_to,$emr_array)
    {

        $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $totalLate = [];
        $totalHoliday = static::totalHoliday($date_from,$date_to);
        $totalHoliday = $totalHoliday ? "'" . implode ( "', '", $totalHoliday ) . "'": 0 ;

        $totalLateQuery = "SELECT a.employee_id,
        COUNT(a.attendance_date) totalLate
        
          FROM ".CONST_COMPANY_DB.".attendance a
        
        INNER JOIN 
        (
        SELECT id  ,
        ADDTIME(start_working_hours_time,SEC_TO_TIME(working_hours_grace_time*60)) late,
        ADDTIME(start_working_hours_time,SEC_TO_TIME(180*60)) halfday
        FROM ".CONST_MASTER_DB.".working_hours_policy 
        ) policy
        ON policy.id = a.working_hours_policy_id
        
        WHERE a.working_hours_policy_id = policy.id
         AND policy.late < a.clock_in and policy.halfday >= a.clock_in
         AND a.clock_in != '' 
         AND (a.attendance_status not IN ('day_off','holiday') or a.attendance_status is null or a.attendance_status = '')
         AND a.status = 1 
         AND employee_id IN ($emr_array) 
         AND attendance_date not in ($totalHoliday)
         AND attendance_date  BETWEEN '$date_from' and '$date_to'
         AND (
            attendance_type = 1
            OR (attendance_type = 2 AND NOT EXISTS (
                SELECT 1
                FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                WHERE
                    sub_att.employee_id = employee_id
                    AND sub_att.attendance_date = attendance_date
                    AND sub_att.attendance_type = 1
            ))
        )
        GROUP BY a.employee_id
        ";

        $totalLateQueryData = DB::select(DB::raw($totalLateQuery));

        foreach ($totalLateQueryData as $key => $value) {
            $totalLate[$value->employee_id] = $value->totalLate;
        }

        return $totalLate;




    }

    public static function totalHalfDays($date_from,$date_to,$emr_array)
    {
        $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $totalHalfDays = [];

        $totalHoliday = static::totalHoliday($date_from,$date_to);
        $totalHoliday = $totalHoliday ? "'" . implode ( "', '", $totalHoliday ) . "'": 0 ;

        $totalHalfDaysQuery = "
                            SELECT a.employee_id,
                            COUNT(a.attendance_date) totalhalfDays

                            FROM " . CONST_COMPANY_DB . ".attendance a

                            INNER JOIN 
                            (
                                SELECT id,
                                TIME_FORMAT(ADDTIME(start_working_hours_time, SEC_TO_TIME(180 * 60)), '%H:%i') halfday
                                FROM " . CONST_MASTER_DB . ".working_hours_policy 
                            ) policy
                            ON policy.id = a.working_hours_policy_id

                            WHERE a.working_hours_policy_id = policy.id
                            AND policy.halfday <= a.clock_in and policy.halfday >= a.clock_in
                            AND a.clock_in != '' 
                            AND ( a.attendance_status not IN ('day_off','holiday') or a.attendance_status is null or a.attendance_status = '')
                            AND a.status = 1 
                            AND attendance_date not in ($totalHoliday)
                            AND employee_id IN ($emr_array) 
                            AND attendance_date  BETWEEN '$date_from' and '$date_to'
                            AND (
                                attendance_type = 1
                                OR (attendance_type = 2 AND NOT EXISTS (
                                    SELECT 1
                                    FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                                    WHERE
                                        sub_att.employee_id = employee_id
                                        AND sub_att.attendance_date = attendance_date
                                        AND sub_att.attendance_type = 1
                                ))
                            )
                            GROUP BY a.employee_id
                        ";

        $totalHalfDaysQueryData = DB::select(DB::raw($totalHalfDaysQuery));

        foreach ($totalHalfDaysQueryData as $key => $value) {
            $totalHalfDays[$value->employee_id] = $value->totalhalfDays ;
        }

        return $totalHalfDays ? $totalHalfDays : $totalHalfDays = [];

    }

    public static function halfDaysAbsent($date_from,$date_to,$emr_array)
    {
        $emr_array = (empty($emr_array) || $emr_array == '') ? 0 : $emr_array ;
        $totalHalfDays = [];

        $totalHoliday = static::totalHoliday($date_from,$date_to);
        $totalHoliday = $totalHoliday ? "'" . implode ( "', '", $totalHoliday ) . "'": 0 ;

        $totalHalfDaysQuery = "
                            SELECT a.employee_id,
                            COUNT(a.attendance_date) totalhalfDays

                            FROM " . CONST_COMPANY_DB . ".attendance a

                            INNER JOIN 
                            (
                                SELECT id,
                                TIME_FORMAT(ADDTIME(start_working_hours_time, SEC_TO_TIME(181 * 60)), '%H:%i') halfday
                                FROM " . CONST_MASTER_DB . ".working_hours_policy 
                            ) policy
                            ON policy.id = a.working_hours_policy_id

                            WHERE a.working_hours_policy_id = policy.id
                            AND policy.halfday <= a.clock_in  and  policy.halfday >= a.clock_in
                            AND a.clock_in != '' 
                            AND ( a.attendance_status not IN ('day_off','holiday') or a.attendance_status is null or a.attendance_status = '')
                            AND a.status = 1 
                            AND attendance_date not in ($totalHoliday)
                            AND employee_id IN ($emr_array) 
                            AND attendance_date  BETWEEN '$date_from' and '$date_to'
                            AND (
                                attendance_type = 1
                                OR (attendance_type = 2 AND NOT EXISTS (
                                    SELECT 1
                                    FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                                    WHERE
                                        sub_att.employee_id = employee_id
                                        AND sub_att.attendance_date = attendance_date
                                        AND sub_att.attendance_type = 1
                                ))
                            )
                            GROUP BY a.employee_id
                        ";

                  
        $totalHalfDaysQueryData = DB::select(DB::raw($totalHalfDaysQuery));

        foreach ($totalHalfDaysQueryData as $key => $value) {
            $totalHalfDays[$value->employee_id] = $value->totalhalfDays ;
        }

        return $totalHalfDays ? $totalHalfDays : $totalHalfDays = [];

    }

    public static function totalDeductionDays($employee,$absent,$late,$half,$halfDaysAbsent, $totalFullDayLeave ,$totalHalfDayLeave )
    {
    
        $totalDeductions = [] ;
        foreach ($employee as $key => $value) {
            $ab = 0 ;
            $hf = 0 ;
            $fulldaylv = 0 ;
            $halfdaylv = 0 ;
            $lates = 0 ;
            $half_days = 0;
            $fulldayAbsent = 0;
            if(array_key_exists($value,$half)) {
                $half_days = $half[$value];
            }
            if(array_key_exists($value,$halfDaysAbsent)) {
                $fulldayAbsent = $halfDaysAbsent[$value];
            }
            if(array_key_exists($value,$late)) {
                $lates = $late[$value];
               
                if ($lates == 3) {
                    $lates = floor($lates / 3) ;
                }
                elseif($lates > 3 ) {
                    if ($lates % 3 == 0) {
                       
                        $lates = $lates  / 3 ;
                    
                    } else {
                        $lates = floor(($lates - 1)  / 3) ;
                    
                    }
                } elseif($lates < 3 ) {
                    $lates = 0;
                }
            }
            
            array_key_exists($value,$absent)? $ab = $absent[$value] : $ab = 0 ;
            array_key_exists($value,$half)? $hf = $half[$value] / 2 : $hf = 0 ;
            array_key_exists($value,$totalFullDayLeave)? $fulldaylv = $totalFullDayLeave[$value] : $fulldaylv = 0 ;
            array_key_exists($value,$totalHalfDayLeave)? $halfdaylv = $totalHalfDayLeave[$value] : $halfdaylv = 0 ;

            $t = $ab + $lates + $fulldayAbsent +  $hf - ($fulldaylv + $halfdaylv);
            
            $totalDeductions[$value] = $t >=0 ? $t : 0;
    
        }
        return $totalDeductions;
    }

    public static function totalLateDeduction($employee,$late)
    {
        $totalLateDeduction =[] ;
        foreach ($employee as $key => $value) {

            $lates = 0 ;


            if(array_key_exists($value,$late))
            {

                if ($late[$value] == 3) {

                    $lates = floor($late[$value] / 3) ;


                }
                elseif($late[$value] > 3 )
                {
                    if ($late[$value] % 2 == 0) {

                        $lates = floor(($late[$value] - 1)  / 2) ;

                    } else {
                        $lates = floor(($late[$value] - 1)  / 2) ;

                    }
                }

            }



            $totalLateDeduction[$value] = $lates;

        }
        return $totalLateDeduction;


    }


    public static function fullDayAbsent($emr_array,$from_date,$to_date)
    {
        $totaloffDayOfMonth = static::totaloffDayOfMonth($from_date,$to_date);
        $totalHoliday = static::totalHoliday($from_date,$to_date);
        $totaloff =   array_merge($totaloffDayOfMonth , $totalHoliday) ;       
        $totaloff = $totaloff ? "'" . implode ( "', '", $totaloff ) . "'": 0 ;
        
        $variableQuery = "SET @group_number := 0;";
        DB::select(DB::raw($variableQuery));

        $AttendanceReportQuery = "

                        SELECT 
                            grouped_dates.id,
                            grouped_dates.emp_name,
                            grouped_dates.emp_id,
                            grouped_dates.department_id,
                            grouped_dates.working_hours_policy_id,
                            grouped_dates.type,
                            MIN(grouped_dates.attendance_date) AS from_date,
                            MAX(grouped_dates.attendance_date) AS to_date,
                            DATEDIFF(MAX(grouped_dates.attendance_date), MIN(grouped_dates.attendance_date)) + 1 AS total_days
                        FROM (
                            SELECT
                                e.id,
                                e.emp_name,
                                e.emp_id,
                                e.department_id,
                                a.working_hours_policy_id,
                                a.attendance_date,
                                'full_day' AS type,
                                @group_number := IF(
                                    @prev_date + INTERVAL 1 DAY = a.attendance_date
                                    AND e.id = @prev_employee_id, 
                                    @group_number, 
                                    @group_number + 1
                                ) AS date_group,
                                @prev_date := a.attendance_date,
                                @prev_employee_id := e.id
                            FROM ".CONST_COMPANY_DB.".attendance a
                            INNER JOIN ".CONST_COMPANY_DB.".employee e ON e.id = a.employee_id
                            WHERE 
                                a.employee_id IN ($emr_array)
                                AND e.status = 1 
                                AND a.status = 1 
                                AND (
                                    (a.clock_in IS NULL OR a.clock_in = '') 
                                    AND (a.clock_out IS NULL OR a.clock_out = '') 
                                )
                                AND a.attendance_date not in ($totaloff) 
                                AND (a.attendance_status != 'day_off' OR a.attendance_status IS NULL)
                                AND a.attendance_date BETWEEN '$from_date' AND '$to_date'
                        ) AS grouped_dates
                        LEFT JOIN (
                            SELECT e.id,
                                IF((lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'),lad.from_date,lad.first_second_half_date) as leave_from_date ,
                                IF((lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'),lad.to_date,lad.first_second_half_date) as leave_to_date
                            FROM ".CONST_MASTER_DB.".leave_application la
                            INNER JOIN ".CONST_MASTER_DB.".leave_application_data lad ON la.id = lad.leave_application_id
                            INNER JOIN ".CONST_COMPANY_DB.".employee e ON e.id = la.employee_id
                            WHERE IF(
                                (lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'),
                                (lad.from_date BETWEEN '$from_date' AND '$to_date' OR lad.to_date BETWEEN '$from_date' AND '$to_date'),
                                lad.first_second_half_date BETWEEN '$from_date' AND '$to_date'
                            ) AND lad.status = 1 AND la.status = 1 AND e.id IN ($emr_array)
                        ) AS leave_dates
                        ON grouped_dates.attendance_date BETWEEN leave_dates.leave_from_date AND leave_dates.leave_to_date AND grouped_dates.id = leave_dates.id
                        WHERE leave_dates.id IS NULL
                        GROUP BY emp_name, working_hours_policy_id, date_group
                        ORDER BY emp_name, working_hours_policy_id, from_date
        
                                ";

        $attendance = DB::select(DB::raw($AttendanceReportQuery));

        return $attendance;

    }

    public static function halfDayAbsent($emr_array,$from_date,$to_date)
    {
        $totaloffDayOfMonth = static::totaloffDayOfMonth($from_date,$to_date);
        $totalHoliday = static::totalHoliday($from_date,$to_date);
        $totaloff =   array_merge($totaloffDayOfMonth , $totalHoliday) ;       
        $totaloff = $totaloff ? "'" . implode ( "', '", $totaloff ) . "'": 0 ;

        $AttendanceReportQuery = "
                                SELECT 
                                attendance_dates.id,
                                attendance_dates.emp_name,
                                attendance_dates.emp_id,
                                attendance_dates.department_id,
                                attendance_dates.working_hours_policy_id,
                                attendance_dates.type,
                                attendance_dates.from_date AS from_date,
                                attendance_dates.to_date AS to_date,
                                '0.5'  AS total_days
                                FROM 
                                (
                                    SELECT
                                            e.id,
                                            e.emp_name,
                                            e.emp_id,
                                            e.department_id,
                                            a.working_hours_policy_id,
                                            IF(
                                                SUBTIME(whp.end_working_hours_time, SEC_TO_TIME(whp.early_going_grace_time * 60)) > SUBTIME(a.clock_out, SEC_TO_TIME(0 * 60)),
                                                'Second_half',    
                                                'first_half' 
                                                    ) 'type',
                                            a.attendance_date AS from_date,
                                            a.attendance_date AS to_date
                                        FROM ".CONST_COMPANY_DB.".attendance a
                                        INNER JOIN ".CONST_COMPANY_DB.".employee e ON e.id = a.employee_id
                                        INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp ON whp.id = a.working_hours_policy_id
                                        WHERE 
                                            a.employee_id IN ($emr_array)
                                            AND a.attendance_date not in ($totaloff)
                                            AND e.status = 1
                                            AND a.status = 1
                                            AND IF(
                                                SUBTIME(whp.end_working_hours_time, SEC_TO_TIME(whp.early_going_grace_time * 60)) > SUBTIME(a.clock_out, SEC_TO_TIME(0 * 60)),
                                                SUBTIME(whp.end_working_hours_time, SEC_TO_TIME(whp.early_going_grace_time * 60)) >  SUBTIME(a.clock_out, SEC_TO_TIME(0 * 60)),    
                                                ADDTIME(whp.start_working_hours_time, SEC_TO_TIME(whp.half_day_time * 60)) <  SUBTIME(a.clock_in, SEC_TO_TIME(0 * 60)) 
                                            )
                                            AND (a.clock_in IS not NULL and a.clock_in != '') 
                                            AND (a.clock_out IS not NULL and a.clock_out != '') 
                                            AND (a.attendance_status != 'day_off' OR a.attendance_status IS NULL)
                                            AND a.attendance_date BETWEEN '$from_date' AND '$to_date'
                                ) AS attendance_dates
                                    LEFT JOIN 
                                    (
                                        SELECT e.id,
                                            IF((lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'), lad.from_date, lad.first_second_half_date) as leave_from_date,
                                            IF((lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'), lad.to_date, lad.first_second_half_date) as leave_to_date
                                        FROM ".CONST_MASTER_DB.".leave_application la
                                        INNER JOIN ".CONST_MASTER_DB.".leave_application_data lad ON la.id = lad.leave_application_id
                                        INNER JOIN ".CONST_COMPANY_DB.".employee e ON e.id = la.employee_id
                                        WHERE IF(
                                            (lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'),
                                            (lad.from_date BETWEEN '$from_date' AND '$to_date' OR lad.to_date BETWEEN '$from_date' AND '$to_date'),
                                            lad.first_second_half_date BETWEEN '$from_date' AND '$to_date'
                                        ) AND lad.status = 1 AND la.status = 1 AND e.id IN ($emr_array)
                                    ) AS leave_dates

                                    ON attendance_dates.id = leave_dates.id AND  leave_dates.leave_from_date = attendance_dates.from_date AND attendance_dates.id = leave_dates.id
                                    WHERE leave_dates.id IS NULL
                                ";

        $attendance = DB::select(DB::raw($AttendanceReportQuery));

        return $attendance;

        
    }

    public static function halfDayAbsentFirstHalf($emr_array,$from_date,$to_date)
    {
        $totaloffDayOfMonth = static::totaloffDayOfMonth($from_date,$to_date);
        $totalHoliday = static::totalHoliday($from_date,$to_date);
        $totaloff =   array_merge($totaloffDayOfMonth , $totalHoliday) ;       
        $totaloff = $totaloff ? "'" . implode ( "', '", $totaloff ) . "'": 0 ;

        $AttendanceReportQuery = "
                                SELECT 
                                attendance_dates.id,
                                attendance_dates.emp_name,
                                attendance_dates.emp_id,
                                attendance_dates.leaves_policy_id,
                                attendance_dates.department_id,
                                attendance_dates.working_hours_policy_id,
                                attendance_dates.type,
                                attendance_dates.month,
                                attendance_dates.year,
                                attendance_dates.from_date AS from_date,
                                attendance_dates.to_date AS to_date,
                                '0.5'  AS total_days
                                FROM 
                                (
                                    SELECT
                                            e.id,
                                            e.emp_name,
                                            e.leaves_policy_id,
                                            e.emp_id,
                                            e.department_id,
                                            a.working_hours_policy_id,
                                            'first_half' type,
                                            a.month,
                                            a.year,
                                            a.attendance_date AS from_date,
                                            a.attendance_date AS to_date
                                        FROM ".CONST_COMPANY_DB.".attendance a
                                        INNER JOIN ".CONST_COMPANY_DB.".employee e ON e.id = a.employee_id
                                        INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp ON whp.id = a.working_hours_policy_id
                                        WHERE 
                                            a.employee_id IN ($emr_array)
                                            AND a.attendance_date not in ($totaloff)
                                            AND e.status = 1
                                            AND a.status = 1
                                            AND   
                                                ADDTIME(whp.start_working_hours_time, SEC_TO_TIME(whp.half_day_time * 60)) <  SUBTIME(a.clock_in, SEC_TO_TIME(0 * 60)) 
                                          
                                            AND (a.clock_in IS not NULL and a.clock_in != '') 
                                            AND (a.clock_out IS not NULL and a.clock_out != '') 
                                            AND (a.attendance_status != 'day_off' OR a.attendance_status IS NULL)
                                            AND a.attendance_date BETWEEN '$from_date' AND '$to_date' and e.employment_status_id = 4
                                ) AS attendance_dates
                                    LEFT JOIN 
                                    (
                                        SELECT e.id,
                                            IF((lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'), lad.from_date, lad.first_second_half_date) as leave_from_date,
                                            IF((lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'), lad.to_date, lad.first_second_half_date) as leave_to_date
                                        FROM ".CONST_MASTER_DB.".leave_application la
                                        INNER JOIN ".CONST_MASTER_DB.".leave_application_data lad ON la.id = lad.leave_application_id
                                        INNER JOIN ".CONST_COMPANY_DB.".employee e ON e.id = la.employee_id
                                        WHERE IF(
                                            (lad.first_second_half_date = '00/00/0000' OR lad.first_second_half_date = '0000-00-00'),
                                            (lad.from_date BETWEEN '$from_date' AND '$to_date' OR lad.to_date BETWEEN '$from_date' AND '$to_date'),
                                            lad.first_second_half_date BETWEEN '$from_date' AND '$to_date'
                                        ) AND lad.status = 1 AND la.status = 1 AND e.id IN ($emr_array)
                                    ) AS leave_dates

                                    ON attendance_dates.id = leave_dates.id AND  leave_dates.leave_from_date = attendance_dates.from_date
                                    WHERE leave_dates.id IS NULL
                                ";

        $attendance = DB::select(DB::raw($AttendanceReportQuery));

        return $attendance;

        
    }

    public static function autoMonthlyAttendance(){
        $lastDayOfMonth = date('Y-m-t');

        // You can format the dates if needed
        $firstDayOfPreviousMonth = date('Y-m-01', strtotime('first day of last month'));
        $lastDayOfNextMonth = date('Y-m-t', strtotime('last day of next month'));
    }

}
?>    