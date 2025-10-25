<?php
namespace App\Helpers;
use DB;
use Carbon\Carbon;
use Config;
use Input;
use App\Helpers\CommonHelper;
use App\Models\Attendance;
use App\Models\EmployeeBankData;
use App\Models\Employee_projects;
use App\Models\Employee;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\LeaveType;
use App\Models\Allowance;
use App\Models\TransferEmployeeProject;
use Illuminate\Support\Facades\Auth;

class apiLeaveHelper{

    public static function totalAppliedAndApprovedLeave($param1,$param2,$param3,$param4,$param5){
        
        $explode=explode('-',$param3);
        CommonHelper::companyDatabaseConnection('12');

        $leavePolicyId = Employee::where(['status'=> '1','id'=> $param1])->value('leaves_policy_id');
       
        CommonHelper::reconnectMasterDatabase();

       $leaveName = LeaveType::where(['status'=> 1 , 'id' => $param2])->value('leave_type_name');

        $AllLeaveCount = LeavesData::where(['status' => 1 , 'leaves_policy_id' => $leavePolicyId ])
            ->select(DB::raw('SUM(no_of_leaves) as totalLeave'))
            ->value('totalLeave');

        $empAllApprovedLeaveCount = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where(['leave_application.status' => '1', 'leave_application.employee_id' => $param1, 'leave_application.approval_status'  => 2, 'leave_application.approval_status_lm'  => 2])
            ->select(DB::raw('SUM(leave_application_data.no_of_days) as totalLeave'))
            ->value('totalLeave');    
        
        $SpecificLeaveCount = LeavesData::where(['status' => 1 , 'leaves_policy_id' => $leavePolicyId ,'leave_type_id' => $param2 ])
                ->value('no_of_leaves');    

        $empSpecificApprovedLeaveCount = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where(['leave_application.status' => '1', 'leave_application.employee_id' => $param1, 'leave_application.approval_status'  => 2, 'leave_application.approval_status_lm'  => 2 , 'leave_application.leave_type' => $param2])
            ->select(DB::raw('SUM(leave_application_data.no_of_days) as totalLeave'))
            ->value('totalLeave'); 
        
        $date=date('d');
        $check_month=date('m');
        
        
        // $maxAllowedLeaves=CommonHelper::MaxLeavesAllowedInCurrentMonth($explode[1]);
        
        // $getUsedCurrentYearLeaves= DB::select('select sum(no_of_days) as no_of_days from leave_application_data
        // INNER JOIN leave_application on leave_application.id = leave_application_data.leave_application_id
        // WHERE leave_application_data.employee_id = '.$param1.' AND (YEAR(leave_application_data.from_date) = '.$explode[0].' OR YEAR(leave_application_data.first_second_half_date) = '.$explode[0].' )
        // AND leave_application.status = 1 AND leave_application.approval_status != 3 AND leave_application.approval_status_lm != 3 AND
        // leave_application.leave_type != 1 ');
        
        // if($check_month < $explode[1] && $explode[0]== date('Y')){
        //     $diffrence=$explode[1]-$check_month;
        //     $maxAllowedLeaves=($maxAllowedLeaves-(1.5*$diffrence));
        //     //$maxAllowedLeaves=($maxAllowedLeaves-1.5);
        // }elseif($check_month == $explode[1] && $date <= 3){
        //     $maxAllowedLeaves=($maxAllowedLeaves-1.5);
        // }
        

        $leaveAlreadyExists = DB::table('leave_application')
        ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
        ->select('leave_application.employee_id','leave_application_data.no_of_days as totalLeave')
        ->whereRaw("leave_application.status = '1' and leave_application.employee_id = '$param1' and '$param3' between leave_application_data.from_date and leave_application_data.to_date")
        ->orWhereRaw("leave_application.status = '1' and leave_application.employee_id = '$param1' and '$param4' between leave_application_data.from_date and leave_application_data.to_date")
        ->exists();

        if(!empty($leavePolicyId))
        {

            if($AllLeaveCount > $empAllApprovedLeaveCount)
            {
                if($SpecificLeaveCount > $empSpecificApprovedLeaveCount)
                {
                    // if($param2 != 1 && $param5 > ($maxAllowedLeaves-$getUsedCurrentYearLeaves[0]->no_of_days)){
                    //     return ['message' => 'You cannot take more than '.($maxAllowedLeaves-$getUsedCurrentYearLeaves[0]->no_of_days).' in this month !'];
                    // }
                    if(floor($SpecificLeaveCount - $empSpecificApprovedLeaveCount) > $param5 )
                    {
                        if(empty($leaveAlreadyExists))
                        {
                            return ['message' => 'success','data'=>$leavePolicyId];
                        }
                        else
                        {
                            return ['message' => 'Leave already applied on between this '.$param3 .' - ' . $param4];
                        }
                    }
                    else
                    {
                        return ['message' => 'You are applying leaves more than your limit'];
                    }
                }
                else
                {
                
                    return ['message' => 'All '.$leaveName.' leaves already used'];
                    
                }
            }
            else
            {
                return ['message' => 'All leaves already used'];
            }
            
        }
        else
        {
            return ['message' => 'Policy is not exists'];
        }

    }
    
    public static function policyLeaves($param1)
    {

        CommonHelper::companyDatabaseConnection('12');

        $leavePolicyId = Employee::where(['status'=> '1','id'=> $param1])->value('leaves_policy_id');
        
        CommonHelper::reconnectMasterDatabase();

        $AllLeaves = DB::table('leaves_data')
        ->join('leave_type', 'leaves_data.leave_type_id', '=', 'leave_type.id')
        ->where(['leave_type.status' => '1','leaves_data.status' => '1', 'leaves_data.leaves_policy_id' => $leavePolicyId])
        ->select('leave_type.leave_type_name','leaves_data.no_of_leaves','leaves_data.leave_type_id','leaves_data.leaves_policy_id')->get();


        return  $AllLeaves;
    }

    public static function empAppliedLeaves($param1,$param2,$param3,$param4)
    {
        $leaveTypeName = DB::table('leaves_data')
        ->join('leave_type', 'leaves_data.leave_type_id', '=', 'leave_type.id')
        ->where(['leave_type.status' => '1','leaves_data.status' => '1', 'leaves_data.leaves_policy_id' => $param4, 'leaves_data.leave_type_id' => $param2])
        ->select('leave_type.leave_type_name')->value('leave_type.leave_type_name');

        $empAllApproved = DB::table('leave_application')
            ->join('leave_application_data AS lad', 'leave_application.id', '=', 'lad.leave_application_id')
            ->where(['leave_application.status' => '1', 'leave_application.employee_id' => $param1, 'leave_application.leave_type' => $param2, 'leave_application.approval_status'  => 2, 'leave_application.approval_status_lm'  => 2])
            ->select(DB::raw('IFNULL(SUM(lad.no_of_days),0) as totalLeave'))
            ->value('totalLeave');    
        $remainingLeave = (int)$param3 - (int)$empAllApproved ;

        return  ['leave_name' => $leaveTypeName, 'no_of_leaves' => $param3, 'used_leaves' => $empAllApproved, 'remaining_leaves' => $remainingLeave   ];
    }
    
    public static function empLeavesList($param1)
    {

        $empAllLeave = DB::table('online24_hr_company.employee as e')
            ->join('leave_application as la', 'la.employee_id', '=', 'e.id')
            ->join('leave_application_data as lad', 'la.id', '=', 'lad.leave_application_id')
            ->join('leave_type as lt', 'lt.id', '=', 'la.leave_type')
            ->where(['e.status' => '1','la.status' => '1','lad.status' => '1','lt.status' => '1', 'e.id' => $param1])
            ->select(
                DB::raw('
                e.emp_id,e.emp_name,
                lt.leave_type_name,
                if(lad.first_second_half_date = "0000-00-00" , lad.from_date ,lad.first_second_half_date) from_date ,
                if(lad.first_second_half_date = "0000-00-00" , lad.to_date ,"") to_date ,
                if(la.leave_day_type = 1 , "Full day leave", "Half day leave") as day_type,
                CASE
                    WHEN la.approval_status = "1" THEN "Pending"
                    WHEN la.approval_status = "2" THEN "Approved"
                    ELSE "Rejected"
                END AS hr_approval_status,
                CASE
                    WHEN la.approval_status_lm = "1" THEN "Pending"
                    WHEN la.approval_status_lm = "2" THEN "Approved"
                    ELSE "Rejected"
                END AS lm_approval_status
                ')
                )
            ->get();
         
        return  $empAllLeave ; 

    }

    public static function AddAllowanceOfFuelByLocation($location_id,$designation_id,$employee_id,$date,$username)
    {
        // $location_id = 1;
        $designation_wise_location_fuel_amount =  DB::table('online24_hr_master.designation_wise_locations as l')
                                    ->select('l.fuel_amount')
                                        ->where([ 
                                         ['l.status',1],
                                         ['l.location_id',$location_id],
                                         ['l.designation_id',$designation_id],
                                        ])
                                 ->whereNotIn('l.location_id' , [1,8])       
                                 ->value('l.fuel_amount');
                                 
        $location_fuel_amount =  DB::table('online24_hr_master.locations as l')
                                ->select('l.fuel_amount')
                                    ->where([ 
                                         ['l.status',1],
                                         ['l.id',$location_id],
                                        ])
                                 ->whereNotIn('l.id' , [1,8])       
                                 ->value('l.fuel_amount');
                                
        if($designation_wise_location_fuel_amount > 0)
        {

            $data['employee_id'] = $employee_id;
            $data['allowance_type_id'] = 8;
            $data['allowance_amount'] = $designation_wise_location_fuel_amount;
            $data['remarks'] = 'Auto fuel amount of location Designation Wise';
            $month_year = explode('-', $date);
            $data['once'] = 1;
            $data['month'] = $month_year[1];
            $data['year'] = $month_year[0];
            $data['username'] = $username;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $AllowanceLastId = Allowance::insertGetId($data);
            CommonHelper::LogGenerator('Allowance',$AllowanceLastId,'insert','row is inserted' ,'hr',$employee_id);
        }                         
        elseif($location_fuel_amount  > 0)
        {

            $data['employee_id'] = $employee_id;
            $data['allowance_type_id'] = 8;
            $data['allowance_amount'] = $location_fuel_amount;
            $data['remarks'] = 'Auto fuel amount of location';
            $month_year = explode('-', $date);
            $data['once'] = 1;
            $data['month'] = $month_year[1];
            $data['year'] = $month_year[0];
            $data['username'] = $username;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            $AllowanceLastId = Allowance::insertGetId($data);
            CommonHelper::LogGenerator('Allowance',$AllowanceLastId,'insert','row is inserted' ,'hr',$employee_id);
        }                            
    }

    public static function checkAttendanceLocation($employee_id, $company_id, $latitude, $longitude)
    {
        $earthRadius = 6371000;
        // convert from degrees to radians
        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = 0;
        $lonTo = 0;
        $result = '';
        $location_id = '';
        CommonHelper::companyDatabaseConnection($company_id);
        $employee_locations = DB::table('employee_multiple_locations')->where([['employee_id', '=', $employee_id],['status', '=', 1]])->select('location_id');
        if($employee_locations->count() > 0):
            $emp_all_location = DB::table('employee_multiple_locations')->where([['employee_id', '=', $employee_id],['status', '=', 1]])
            ->select('location_id')->value('location_id');
            if($emp_all_location == '0'):

                CommonHelper::reconnectMasterDatabase();
                $locations_detail = DB::table('locations')->where([['status', '=', 1]])->get();
                foreach($locations_detail as $key => $val):
                    $location_id = $val->id;
                    $latTo = deg2rad($val->latitude);
                    $lonTo = deg2rad($val->longitude);

                    $latDelta = $latTo - $latFrom;
                    $lonDelta = $lonTo - $lonFrom;
                    // $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom)  cos($latTo)  pow(sin($lonDelta / 2), 2)));
                    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

                    $meters = round($angle * $earthRadius);
                    if($meters < 100):
                        $result = 'true';
                        return compact('result','location_id');
                    else:
                        $result = 'false';
                    endif;
                endforeach;

            else:
                foreach($employee_locations->get() as $key => $val):
                    $location_id = $val->location_id;
                    CommonHelper::reconnectMasterDatabase();
                    $locations_detail = DB::table('locations')->where([['id', '=', $location_id],['status', '=', 1]])->first();

                    //saved location
                    $latTo = deg2rad($locations_detail->latitude);
                    $lonTo = deg2rad($locations_detail->longitude);

                    $latDelta = $latTo - $latFrom;
                    $lonDelta = $lonTo - $lonFrom;
                    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                    $meters = round($angle * $earthRadius);
                    if($meters < 100):
                        $result = 'true';
                        return compact('result','location_id');
                    else:
                        $result = 'false';
                    endif;
                endforeach;
            endif;
        else:
            $result = 'location not found';
        endif;

        CommonHelper::reconnectMasterDatabase();
        return compact('result');
    }



    public static function checkEmployeeLocation($employee_id, $company_id, $latitude, $longitude,$location_id)
    {
        $earthRadius = 6371000;
        // convert from degrees to radians
        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = 0;
        $lonTo = 0;
        $result = '';
        
        CommonHelper::reconnectMasterDatabase();
        $locations_detail = DB::table('locations')->where([['id', '=', $location_id],['status', '=', 1]])->first();

        //saved location
        $latTo = deg2rad($locations_detail->latitude);
        $lonTo = deg2rad($locations_detail->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $meters = round($angle * $earthRadius);
        if($meters < 100):
            $result = 'true';
            return compact('result','location_id');
        else:
            $result = 'false';
        endif;
            
       
        return compact('result');
    }


}

?>