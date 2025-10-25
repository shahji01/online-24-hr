<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\RefreshCacheHelper;
use App\Models\EmployeeFuelData;
use App\Models\EmployeeGsspDocuments;
use App\Models\EmployeeTransfer;
use App\Models\Payslip;
use Illuminate\Http\Request;
use App\Models\AdvanceSalary;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\ApprovalSystem;
use App\Models\EmployeeHrAudit;
use App\Models\TransferLetter;
use App\Models\PromotionLetter;
use App\Models\projectTransferLetter;
use App\Models\TransferEmployeeProject;
use DB;
use Auth;
use Config;
use Redirect;
use Session;
use Input;
use File;
use Hash;
use \Cache;

class DeleteCompanyHRRecordsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
       

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteRowCompanyHRRecords()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        DB::update('update '.$tableName.' set status = ? where id = ?',['2',$recordId]);

        CommonHelper::LogGenerator($tableName,$recordId,'delete','row is deleted','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','Successfully Delete');
    }
     public function deleteLoanTopUp()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        DB::update('update '.$tableName.' set status = ? where id = ?',['2',$recordId]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully delete.');
    }

    public function deleteRowCompanyHRRecordsProjectTransfer(){

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
                $recordId = Input::get('recordId');
                $tableName = Input::get('tableName');
                $emr_no = Input::get('emr_no');
                $updateDetails=array(
                    'status' => 2,
                    'username' => Auth::user()->name
                );
                DB::table($tableName)
                    ->where('id', $recordId)
                    ->update($updateDetails);
                $TransferEmployeeProject = TransferEmployeeProject::where([['emr_no','=',$emr_no],['status','=',1]])->first();
                   if(count($TransferEmployeeProject) == 0){
                        $data['active'] = 1;
                        Employee::where('emr_no','=',$emr_no)->update($data);
                   }
                CommonHelper::reconnectMasterDatabase();
                Session::flash('dataDelete','successfully delete.');

    }

    public function repostOneTableRecords()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        DB::update('update '.$tableName.' set status = ? where id = ?',['1',$recordId]);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully Repost.');
    }

    public function approveOneTableRecords()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        $column = Input::get('column');
        $updateDetails=array(
            $column => 2,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', $recordId)
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully Approved.');
    }

//    public function rejectAdvanceSalaryWithPaySlip()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
//        $recordId = Input::get('recordId');
//        $tableName =Input::get('tableName');
//        $approval_status = Input::get('approval_status');
//
//        $getAdvanceSalary = AdvanceSalary::select('deduction_month','deduction_year')->find($recordId)->toArray();
//        if($getAdvanceSalary['deduction_month'][0] != '0' && $getAdvanceSalary['deduction_month'][0] != '1' ):
//            $month = "0".$getAdvanceSalary['deduction_month'];
//        else:
//            $month = $getAdvanceSalary['deduction_month'];
//        endif;
//        Payslip::where([['month','=',$month],['year','=',$getAdvanceSalary['deduction_year']]])->delete();
//        DB::update('update '.$tableName.' set `approval_status` = ? where id = ?',[$approval_status,$recordId]);
//
//        CommonHelper::reconnectMasterDatabase();
//        Session::flash('dataDelete','successfully Approved.');
//    }

//    public function approveAdvanceSalaryWithPaySlip()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
//        $recordId = Input::get('recordId');
//        DB::update('update advance_salary set approval_status = ? where id = ?',['2',$recordId]);
//        $employeeData = Employee::select('id','emp_sub_department_id', 'emp_salary')->find(Input::get('emp_id'))->toArray();
//
//          $totalAllowance = DB::table('allowance')
//            ->select(DB::raw('SUM(allowance_amount) as allowance_amount'))
//            ->first();
//
//        $totalDeduction = DB::table('deduction')
//            ->select(DB::raw('SUM(deduction_amount) as deduction_amount'))
//            ->first();
//
//       $netAmount = ($employeeData['emp_salary']+$totalAllowance->allowance_amount)-$totalDeduction->deduction_amount;
//        $getAdvanceSalary = AdvanceSalary::select('deduction_month','deduction_year')->find($recordId)->toArray();
//
//        if($getAdvanceSalary['deduction_month'][0] != '0' && $getAdvanceSalary['deduction_month'][0] != '1' ):
//            $date = "0".$getAdvanceSalary['deduction_month'];
//        else:
//            $date = $getAdvanceSalary['deduction_month'];
//        endif;
//
//        $str = DB::selectOne("select max(convert(substr(`ps_no`,4,length(substr(`ps_no`,4))-4),signed integer)) reg from `payslip` where substr(`ps_no`,-4,2) = ".date('m')." and substr(`ps_no`,-2,2) = ".date('y')."")->reg;
//        $ps_no = 'psc'.($str+1).date('my');
//
//       $data['ps_no']                   = $ps_no;
//       $data['emp_id']                  = $employeeData['id'];
//       $data['emp_sub_department_id']   = $employeeData['emp_sub_department_id'];
//       $data['month']                   = $date;
//       $data['year']                    = $getAdvanceSalary['deduction_year'];
//       $data['basic_salary']            = $employeeData['emp_salary'];
//       $data['total_allowance']         = $totalAllowance->allowance_amount;
//       $data['total_deduction']         = $totalDeduction->deduction_amount;
//       $data['net_salary']              = $netAmount;
//       $data['salary_status']           = 1;
//       $data['status']                  = 1;
//       $data['username']                = Auth::user()->name;
//       $data['date']     		        = date("Y-m-d");
//       $data['time']     		        = date("H:i:s");
//
//       DB::table('payslip')->insert($data);
//       CommonHelper::reconnectMasterDatabase();
//
//    }


    public function deleteLeavesDataPolicyRows()
    {
        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table('leaves_policy')
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);

        DB::table('leaves_data')
            ->where('leaves_policy_id', Input::get('recordId'))
            ->update($updateDetails);

        Session::flash('dataDelete','Successfully deleted.');
    }

    public function repostMasterTableRecords()
    {
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');

        DB::update('update '.$tableName.' set status = ? where id = ?',['1',$recordId]);
        Session::flash('dataInsert','Successfully updated.');
    }

    /* Loan Request functions Start Here */

//    public function approveLoanRequest()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
//        $recordId = Input::get('recordId');
//        DB::update('update loan_request set approval_status = ? where id = ?',['2',Input::get('recordId')]);
//        CommonHelper::reconnectMasterDatabase();
//        Session::flash('dataEdit','successfully Approved.');
//    }

//    public function rejectLoanRequest()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
//        $recordId = Input::get('recordId');
//        DB::update('update loan_request set approval_status = ? where id = ?',['3',Input::get('recordId')]);
//        CommonHelper::reconnectMasterDatabase();
//        Session::flash('dataDelete','successfully Rejected.');
//
//    }
//    public function deleteLoanRequest()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
//        $recordId = Input::get('recordId');
//        DB::update('update loan_request set status = ? where id = ?',['2',Input::get('recordId')]);
//        CommonHelper::reconnectMasterDatabase();
//        Session::flash('dataDelete','successfully Deleted.');
//    }
    /* Loan Request functions  End */

    /* Delete Employee Bonus Start*/

    public function deleteEmployeeBonus()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));;
        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table('bonus_issue')
            ->where('id', Input::get('id'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
    }

    public function deleteIncomeTax()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $updateDetails['status']=2;
        DB::table('income_tax')
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
    }

    /* Delete Employee Bonus End*/

    public function deleteLeaveApplicationDetail()
    {
        // $updateDetails=array(
        //     'status' => 2,
        //     'username' => Auth::user()->name
        // );
        // DB::table('leave_application')
        //     ->where('id', Input::get('recordId'))
        //     ->update($updateDetails);
        
          DB::table('leave_application')
            ->where('id', Input::get('recordId'))->delete();

        // DB::table('leave_application_data')
        //     ->where('leave_application_id', Input::get('recordId'))
        //     ->update($updateDetails);
         DB::table('leave_application_data')
            ->where('leave_application_id', Input::get('recordId'))
            ->delete();
        Session::flash('dataDelete','Successfully deleted.');
    }

    public function deleteEmployeesBonus()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));;
        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table('bonus_issue')
            ->where('id', Input::get('id'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','Successfully deleted.');

    }

    public function deleteEmployeeAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date = Input::get('month_year');

        $month = strtotime($date);
        $month = date('M', $month);

        $year = strtotime($date);
        $year = date('Y', $year);

        Attendance::where([['emr_no','=',Input::get('emr_no')],['month','=',$month],['year','=',$year]])->delete();

        CommonHelper::reconnectMasterDatabase();
        echo "<div class='text-center' style='color:green;'>Successfully Deleted ".$month.'-'.$year." Month Attendance !</div>";
    }

//    public function approvePayroll()
//    {
//        $month_year = explode('-',Input::get('month_year'));
//        $year  = $month_year[0];
//        $month  = $month_year[1];
//        $users_name = DB::selectOne("select name from `users` where acc_type ='client' and email='".Auth::user()->email."' ");
//        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
//       // Payslip::where([['month','=',Input::get('month')],['year','=',Input::get('year')]])->update(['approved_by','=>',$users_name->name]);
//        Payslip::where('month', $month)
//            ->where('year', $year)
//            ->update(['approved_by' => $users_name->name]);
//        CommonHelper::reconnectMasterDatabase();
//        echo "Successfully Approved !";
//
//    }

    public function approveAndRejectTableRecord()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        
        $tableName = Input::get('tableName');
        $recordId = Input::get('recordId');
        $statusTpye = Input::get('approval_status');
        $status_name;
        $updateDetails=array(
            'approval_status' => $statusTpye,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id',$recordId )
            ->update($updateDetails);

            if($statusTpye == 2):
                $status_name = 'Approved';
            elseif($statusTpye):
                $status_name = 'Rejected';    
            endif;
            CommonHelper::LogGenerator($tableName,$recordId,$status_name,'row status is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully update.');
    }

    public function deleteEmployeeDocument()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);

        CommonHelper::reconnectMasterDatabase();
    }

    public function approveAndRejectEmployeeLocationAndPromotion()
    {
        $employee_location = 'employee_location';
        $employee_promotion = 'employee_promotion';

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $employeeLocationDetail = EmployeeTransfer::select('promotion_id')->where([['id', '=', Input::get('recordId')], ['status','=', 1]])->first();
        $promotion_id = $employeeLocationDetail['promotion_id'];

        $updateDetails=array(
            'approval_status' => Input::get('approval_status'),
            'username' => Auth::user()->name
        );

        DB::table($employee_location)
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);


        if($promotion_id != 0):
            $updateDetails2=array(
                'approval_status' => Input::get('approval_status'),
                'username' => Auth::user()->name
            );

            DB::table($employee_promotion)
                ->where('id', $promotion_id)
                ->update($updateDetails2);
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully update.');
    }

    public function deleteEmployeeLocationAndPromotion()
    {
        $employee_location = 'employee_location';
        $employee_promotion = 'employee_promotion';

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $employeeLocationDetail = EmployeeTransfer::select('promotion_id')->where([['id', '=', Input::get('recordId')], ['status','=', 1]])->first();
        $promotion_id = $employeeLocationDetail['promotion_id'];

        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );

        DB::table($employee_location)
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);

        if($promotion_id != 0):
            $updateDetails2=array(
                'status' => 2,
                'username' => Auth::user()->name
            );

            DB::table($employee_promotion)
                ->where('id', Input::get('$promotion_id'))
                ->update($updateDetails2);
        endif;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfullysss Deleted.');
        return Redirect::to('hr/viewEmployeeTransferList?pageType=viewlist&&parentCode=21&&m='.Input::get('companyId').'');
    }

//    public function approveMonthViseTableRecord()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
//        EmployeeFuelData::where('emr_no', Input::get('emr_no'))
//            ->where('fuel_month', Input::get('fuel_month'))
//            ->where('fuel_year', Input::get('fuel_year'))
//            ->update(['approval_status' => Input::get('approval_status')]);
//        CommonHelper::reconnectMasterDatabase();
//        Session::flash('dataEdit','successfully update.');
//    }


    public function deleteEmployeeGsspDocument()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        EmployeeGsspDocuments::where([['id','=',Input::get('recordId')]])->delete();
        CommonHelper::reconnectMasterDatabase();
    }

    public function deleteEmployeeSixthMonthAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_and_year = explode('-',Input::get('date'));
        EmployeeHrAudit::where([['emr_no','=',Input::get('emr_no')],['month','=',$month_and_year[0]],['year','=',$month_and_year[1]]])->delete();
        CommonHelper::reconnectMasterDatabase();
    }

    public function deleteEmployeeTwelfthMonthAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_and_year = explode('-',Input::get('date'));
        EmployeeHrAudit::where([['emr_no','=',Input::get('emr_no')],['month','=',$month_and_year[0]],['year','=',$month_and_year[1]]])->delete();
        CommonHelper::reconnectMasterDatabase();
    }


    public function printAndDeliverIdCard()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');

        $updateDetails=array(
            'card_status' => Input::get('card_status'),
            'username' => Auth::user()->name
        );

        DB::table($tableName)
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully update.');
    }

    public function deleteEmployeeCnicCopy()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'cnic_path' => null,
            'cnic_name' => null,
            'cnic_type' => null,
            'username' => Auth::user()->name
        );

        DB::table($tableName)
            ->where('id', Input::get('id'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully update.');
    }

    public function deleteEmployeeExperienceDocuments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'work_exp_path' => null,
            'work_exp_name' => null,
            'work_exp_type' => null,
            'username' => Auth::user()->name
        );

        DB::table($tableName)
            ->where('id', Input::get('id'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully update.');
    }

    public function approveAndRejectEmployeeExit()
    {	
		$tableName = Input::get('tableName');
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
		if($tableName == 'employee_exit' && Input::get('approval_status') == 2){
			$data['status'] = 3;
			 DB::table('employee')
            ->where('emp_id', Input::get('employee_emr_no'))
            ->update($data);
		}
        $tableName = Input::get('tableName');
        $updateEmpoyeeExit=array(
            'approval_status' => Input::get('approval_status'),
            'username' => Auth::user()->name
        );

        DB::table($tableName)
            ->where('id', Input::get('recordId'))
            ->update($updateEmpoyeeExit);


        $updateEmpoyee=array(
            'status' => Input::get('employee_status'),
            'username' => Auth::user()->name
        );

       
        CommonHelper::LogGenerator($tableName,Input::get('recordId'),'update','row status is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully update.');
    }

    public function deleteEmployeeEobiCopy()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'eobi_path' => null,
            'eobi_type' => null,
            'username' => Auth::user()->name
        );

        DB::table($tableName)
            ->where('id', Input::get('id'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully update.');
    }

    public function deleteEmployeeInsuranceCopy()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'insurance_path' => null,
            'insurance_type' => null,
            'username' => Auth::user()->name
        );

        DB::table($tableName)
            ->where('id', Input::get('id'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully update.');
    }

    public function deleteEmployeeEquipments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = explode('|',Input::get('recordId'));
        $tableName = 'employee_equipments';

        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('emp_id', $recordId[1])
            ->update($updateDetails);

        $log['table_name']         = 'employee_equipments';
        $log['activity_id']        = $recordId[0];
        $log['deleted_emr_no']     = $recordId[1];
        $log['activity']           = 'Delete';
        $log['module']             = 'hr';
        $log['username']           = Auth::user()->name;
        $log['date']               = date("Y-m-d");
        $log['time']               = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully delete.');
    }

    public function deleteEmployeeExitClearance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName = Input::get('tableName');
        $emr_no = Input::get('emr_no');
        $updateEmployeeExit=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', $recordId)
            ->update($updateEmployeeExit);

        $updateEmployee=array(
            'status' => 1,
            'username' => Auth::user()->name
        );
        DB::table('employee')
            ->where('emp_id', $emr_no)
            ->update($updateEmployee);

        CommonHelper::LogGenerator($tableName,$recordId,'delete','row is deleted','hr',Auth::user()->id);


        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully delete.');
    }

     public function deleteEmployee()
    {
        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        

        $recordId = Input::get('recordId');
        $tableName = Input::get('tableName');

        $updateDetails=array(
            'status' => 2,
            'can_login' => 'no',
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', $recordId)
            ->update($updateDetails);


        RefreshCacheHelper::EmployeeCache();
        
        CommonHelper::reconnectMasterDatabase();


        
        DB::table('users')->where([['emp_id','=',Input::get('emp_id')]])->update(array('status'=>'2'));
        DB::table('menu_privileges')->where([['emp_id','=',Input::get('emp_id')]])->delete();
     Session::flash('dataDelete','Successfully Deleted');
    }


    public function restoreEmployee()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'status' => 1,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', $recordId)
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','Successfully Activated.');
    }

    public function deleteTransferLetter(){
        $companyId = Input::get('companyId');
        $recordId = Input::get('recordId');
        CommonHelper::companyDatabaseConnection($companyId);
        TransferLetter::where('id','=',$recordId)->delete();
        CommonHelper::reconnectMasterDatabase();
    }

    public function deletePromotionLetter(){
        $companyId = Input::get('companyId');
        $recordId = Input::get('recordId');
        CommonHelper::companyDatabaseConnection($companyId);
        PromotionLetter::where('id','=',$recordId)->delete();
        CommonHelper::reconnectMasterDatabase();
    }

    public function deleteProjectLetter(){
        $companyId = Input::get('companyId');
        $recordId = Input::get('recordId');
        CommonHelper::companyDatabaseConnection($companyId);
        projectTransferLetter::where('id','=',$recordId)->delete();
        CommonHelper::reconnectMasterDatabase();
    }
	
	public function change_status(){
	
		$statusId = Input::get('status');
        $statusName;
        if($statusId == 1):
            $statusName = 'active';
            
            elseif($statusId == 2):
                $statusName = 'delete';
        
            endif;
        
		$data['status'] = $statusId;
		DB::table('working_hours_policy')->where([['id',Input::get('id')],['company_id',Input::get('m')]])->update($data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        CommonHelper::LogGenerator('working_hours_policy',Input::get('id'),$statusName,'row status is updated','hr',Auth::user()->id);
        CommonHelper::reconnectMasterDatabase();
		Session::flash('dataDelete','successfully Updated.');
	}

    public function deleteEmployeeRebate()
    {
        $recordId = Input::get('recordId');
        $tableName = Input::get('tableName');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        DB::update('update '.$tableName.' set status = ? where id = ?',['2',$recordId]);
        CommonHelper::LogGenerator($tableName,$recordId,'delete','row is deleted','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','Successfully Deleted.');
    }

    public function deleteRebateDocument()
    {
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $tableName = Input::get('tableName');
        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table($tableName)
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);
        CommonHelper::reconnectMasterDatabase();
    }

    public function approveAndRejectLeaveApplication2()
    {
        $data1['approval_status_lm'] = Input::get('approval_status_lm');
        $leave_application_id=DB::table('leave_application_data')->select('leave_application_id')->where('id', Input::get('recordId'))->value('leave_application_id');
        $status=Input::get('approval_status');
        if(Input::get('approval_status_lm') == 2){
            $data1['approved']=2;
        }else{
            $data1['approved']=1;
        }
        $update_approval = DB::table('leave_application')->where('id', Input::get('recordId'))->update($data1);

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $data['leave_application_id']=$leave_application_id;
        $data['leave_status'] = $status;
        $data['type']=1;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        $data['username']=Auth::user()->name;
        $data['status']=1;

        DB::table('leave_email_queue')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','Successfully Updated');

    }

    public function approveAndRejectLeaveApplication()
    {
        $data1['approval_status'] = Input::get('approval_status');
        $leave_application_id=DB::table('leave_application_data')->select('leave_application_id')->where('id', Input::get('recordId'))->value('leave_application_id');
        $status=Input::get('approval_status');
        if(Input::get('approval_status') == 2){
            $data1['approved']=2;
        }else{
            $data1['approved']=1;
        }
        $update_approval = DB::table('leave_application')->where('id', Input::get('recordId'))->update($data1);

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));
        $data['leave_application_id']=$leave_application_id;
        $data['leave_status']=$status;
        $data['type']=2;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        $data['username']=Auth::user()->name;
        $data['status']=1;

        DB::table('leave_email_queue')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','Successfully Updated');

    }
}

