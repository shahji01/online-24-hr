<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Input;
use Redirect;
use Response;
use DB;
use Config;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Mail;
use DateTime;
use Auth;
use Hash;

use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class monthlyAttendanceEmptyRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:monthlyAttendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $firstDayOfMonth = Carbon::now()->startOfMonth();

        // Get the last day of the current month
        $lastDayOfMonth = Carbon::now()->endOfMonth();
        
        // You can format the dates if needed
        $firstDayFormatted = $firstDayOfMonth->format('Y-m-d'); // Example format
        $lastDayFormatted = $lastDayOfMonth->format('Y-m-d'); // Example format
        
                // INSERT INTO attendance (employee_id, attendance_id, location_id, attendance_date, day, month, year, date, time, username)
                $sql = "
                 INSERT INTO attendance (employee_id, attendance_id, location_id, attendance_date, day, month, year, date, time, username)
        SELECT
            e.id AS employee_id,
            e.attendance_id,
            eml.location_id,
            DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS monthdate,
            DATE_FORMAT(DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY, '%W') AS day_name,
            MONTH(DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS month,
            YEAR(DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS year,
            CURDATE() AS `date`,
            CURTIME() AS `time`,
            'auto_date' AS username
        FROM (
            SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
        ) AS a
        CROSS JOIN (
            SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
        ) AS b
        CROSS JOIN (
            SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
        ) AS c
        CROSS JOIN  employee e
        INNER JOIN  employee_multiple_locations eml ON e.id = eml.employee_id
        WHERE DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY <= '$lastDayFormatted'
        -- AND eml.location_id = 7
        AND eml.status = 1 AND (
            (e.attendance_id IS NOT NULL AND e.attendance_id NOT IN ('', 'EXMPT', 'EXEMPT', '0'))
        );
        ";

        CommonHelper::companyDatabaseConnection(12);
         $username = DB::select(DB::raw($sql));
        CommonHelper::reconnectMasterDatabase();
    
    }
}
