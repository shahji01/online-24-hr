<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExit extends Model{
    protected $table = 'employee_exit';
    protected $fillable = ['emr_no','leaving_type', 'room_key', 'room_key_remarks', 'mobile_sim','mobile_sim_remarks','fuel_card','fuel_card_remarks',
        'mfm_employee_card','mfm_employee_card_remarks','client_access_card','client_access_card_remarks','medical_insurance_card','medical_insurance_card_remarks','eobi_card',
        'eobi_card_remarks', 'biometric_scan', 'biometric_scan_remarks', 'payroll_deduction','payroll_deduction_remarks','info_sent_to_client','info_sent_to_client_remarks',
        'client_exit_checklist', 'client_exit_checklist_remarks','exit_interview','exit_interview_remarks','laptop','laptop_remarks','desktop_computer', 'desktop_computer_remarks',
        'email_account_deactivated','email_account_deactivated_remarks','toolkit_ppe','toolkit_ppe_remarks','uniform','uniform_remarks', 'advance_loan',
        'advance_loan_remarks','extra_leaves','extra_leaves_remarks','final_settlement','final_settlement_remarks',
        'status', 'approval_status','supervisor_name', 'signed_by_supervisor', 'last_working_date', 'date', 'time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

