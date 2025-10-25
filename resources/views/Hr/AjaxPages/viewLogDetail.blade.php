<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$array = array("insert"=>'created',"update"=>'edited',"delete"=>'deleted', "upload"=>'uploaded', 'update' => 'edited','Approve' => 'approved');
$tableArray = array(
        'advance_salary' => 'Advance Salary', 'allowance' => 'Allowance',
        'attendance' => 'Attendance', 'bonus_issue' => 'Bonus Issue',
        'deduction' => 'Deduction', 'drivers_allowance' => 'Drivers Allowance',
        'employee' => 'Employee', 'employee_card_request' => 'Employee Card Request',
        'employee_exit' => 'Employee Exit Clearance', 'employee_equipments' => 'Equipments',
        'employee_location' => 'Employee Transfers', 'employee_promotion' => 'Employee Promotions',
        'final_settlement' => 'Final Settlement', 'fuel' => 'Fuel Allowance','payroll_data' => 'Attendance Progress',
        'holidays' => 'Holidays', 'hr_contract_conclusion_letter' => 'Contract Conclusion Letter',
        'hr_mfm_south_increment_letter' => 'MFM South Increment Letter', 'hr_mfm_south_without_increment_letter' => 'MFM South Without Increment Letter',
        'hr_termination_format1_letter' => 'Termination Format 1 Letter', 'hr_termination_format2_letter' => 'Termination Format 2 Letter',
        'hr_transfer_letter' => 'Transfer Letter', 'hr_warning_letter' => 'Warning Letter',
        'leave_application' => 'Leave Application', 'loan_request' => 'Loan Request','gratuity' => 'Gratuity',
        'overtime' => 'Overtime', 'trainings' => 'Trainings','employee_hr_audit' => 'HR Audit', 'users' => 'Users',
        'payslip' => 'Payroll','app_users' => 'App Users', 'attendance_approval' => 'FM Attendance Approval',
        'hr_attendance_approval' => 'HR Attendance Approval', 'letter_files' => 'Letter Files','role' => 'Roles',  'rebate' => 'Rebate',
        'LoanRequest'=>'Loan Request',
        'pf' => 'Provident Fund',
        'pf_withdrawal' => 'Provident Fund Withdrawal',
        'Employee_Bank_Data' => 'Employee Bank Data',
        'Employee_Equipments' => 'Employee Equipments',
        'Employee_Other_Details' => 'Employee Other Details',
        'Menu_Privileges' => 'Menu Privileges',
        'IncomeTax' => 'Income Tax',
        'Payslip' => 'Payslip'
);

$m = Input::get('m');
?>

<style>
    b{ font-size: 16px; }
</style>


                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover">
                                            @if($logs->count() > 0)
                                                <tbody>
                                                <tr>
                                                    <td>S#</td>
                                                    <td>Table Name</td>
                                                    <td>Remark</td>
                                                    <td>Date</td>
                                                    
                                                </tr>    
                                                <?php $counter = 1; ?>
                                                @foreach($logs->get()->toArray() as $value)
                                                    <tr>
                                                        <td>{{ $counter++ }}</td>
                                                        <td>{{ $tableArray[$value['table_name']] }}</td>
                                                        <td>{{ $value['remark']}}</td>
                                                        <td>
                                                            
                                                        <span style="color: #f77962">
                                                                        {{ date('d F, Y (l)',strtotime($value['date']))  }} - {{ date('h:i:s a',strtotime($value['time']))  }} <?php //echo $value['time']) ?>
                                                                    </span>
                                                        </td>
                                                    </tr>
                                            
                                                @endforeach
                                                </tbody>
                                            @else
                                                <tr><td class="text-center" style="color: red; font-size: 16px">No Log found for module "{{ $sub_module }}" between dates "{{ HrHelper::date_format($from_date) }}" to "{{ HrHelper::date_format($till_date) }}"</td></tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        