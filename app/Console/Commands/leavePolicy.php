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
use App\Models\LeavesPolicy;

class leavePolicy extends Command
{
    protected $signature = 'notify:leavePolicy';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //1795,1256,2451,1813,1677,665,2661,2598
        //situation one is leave policy between 01-01-2022 to 31-12-2022
        //situation two is leave policy between 01-07-2022 to 30-06-2023

        $leave_policy = LeavesPolicy::where([['status', '=', 1]])->get();
        foreach($leave_policy as $key => $val):
            $policy_date_from = $val->policy_date_from;
            $policy_date_till = $val->policy_date_till;

            $explode_policy_date_from = explode('-', $policy_date_from);
            $explode_policy_date_till = explode('-', $policy_date_till);

            if($explode_policy_date_from[1] == '1' && $explode_policy_date_till[1] == '12'):
                $current_year = date('Y')-1;
                $date1 = $current_year.'-'.$explode_policy_date_from[1].'-01';
                $date2 = $current_year.'-'.$explode_policy_date_till[1].'-31';

                DB::table('leave_application AS la')->join('leave_application_data AS lad','la.id','=', 'lad.leave_application_id')
                    ->where([['la.status','=', 1]])->whereBetween('lad.from_date',[$date1,$date2])->update(['la.status' => 3, 'lad.status' => 3]);

            elseif($explode_policy_date_from[1] == '7' && $explode_policy_date_till[1] == '6'):
                $date1 = ($explode_policy_date_from[0]-1).'-'.$explode_policy_date_from[1].'-01';
                $date2 = ($explode_policy_date_till[0]-1).'-'.$explode_policy_date_till[1].'-31';

                DB::table('leave_application AS la')->join('leave_application_data AS lad','la.id','=', 'lad.leave_application_id')
                    ->where([['la.status','=', 1]])->whereBetween('lad.from_date',[$date1,$date2])->update(['la.status' => 3, 'lad.status' => 3]);
            endif;
        endforeach;
    }
}
