<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use DB;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// require 'vendor/autoload.php';

class notifyLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:leave';

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
    
        
        CommonHelper::companyDatabaseConnection('12');
        $email_queue = DB::Table('leave_email_queue')->where([['status', '=', 1]])->orderBy('id');
        CommonHelper::reconnectMasterDatabase();
    
        if ($email_queue->count() > 0):
    
            foreach ($email_queue->get() as $key => $value):
                //smtp Leave
                if ($value->leave_application_id > 0) {
                    if (empty($value->leave_type_name)) {
                        $leave_application = DB::table('leave_application')->where('id', $value->leave_application_id)->first();
                        $approve_or_reject = '';
                        $person = '';
                        $value->type == 2 ? $person = 'HR' : $person = 'Line Manager';
                        $value->leave_status == 2 ? $approve_or_reject = 'Approved' : $approve_or_reject = 'Rejected';
                        CommonHelper::companyDatabaseConnection('12');
                        $reporting_manager = DB::table('employee')->select('reporting_manager_id')->where([['id', '=', $leave_application->employee_id]])->value('reporting_manager');
                        $emp_name = DB::table('employee')->select('emp_name')->where([['id', '=', $leave_application->employee_id]])->value('emp_name');

                        $emp_email = DB::table('employee')->select('official_email')->where([['id', '=', $leave_application->employee_id]])->value('official_email');
                        $messages = 'Hi, the Requested Leave Application of ' . $emp_name . ' has been ' . $approve_or_reject . ' by ' . $person . ' check your dashboard to confirm link below';
                        $emp_email == '' ? $emp_email = 'hr-notifications@smrsoftwares.com' : '';
                        //$emp_email='khizer@innovative-net.com';
    
                        $official_email = '';
                        if ($reporting_manager != '') {
                            $official_email = DB::table('employee')->select('official_email')->where([['id', '=', $reporting_manager]])->value('official_email');
                        } else {
                            $official_email = 'hr-notifications@smrsoftwares.com';
                        }
                        //$official_email='khizer@innovative-net.com';
                        $details = [
                            'subject' => 'Application For Leave Approval',
                            'title' => $emp_name,
                            'body' => $messages,
                            'link' => 'https://garibsons.smrsoftwares.com/dc/userDashboard?m=12',
                            'desc' => '',
                        ];
    
                        $mail = new PHPMailer(true);
                        try {
                            //Server settings
                            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                            $mail->isSMTP(); //Send using SMTP
                            $mail->Host = "mail.smrsoftwares.com"; //Set the SMTP server to send through
                            $mail->SMTPAuth = true; //Enable SMTP authentication
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                            $mail->Username = "hr-notifications@smrsoftwares.com";
                            $mail->Password = 'Pga+R8uKqTkc'; //SMTP password
                            $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            //   $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
                            //Recipients
                            $mail->setFrom("hr-notifications@smrsoftwares.com", 'E-Leave Application');
                            $mail->addAddress($emp_email, 'E-Leave Application'); //Add a recipient
                            $mail->AddCC($official_email);
    
                            
                            $mail->addReplyTo("hr-notifications@smrsoftwares.com", 'Information');
                            //Content
                            $mail->isHTML(true); //Set email format to HTML
                            $mail->Subject = 'Application For Applied Leave';
                            // $mail->Body    = $messages;
                            $mail->Body = view('Hr.mail', compact('details'))->render();
                            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                         //   echo'leave approve <pre>';
                         
                          $mail->send();
    
                        } catch (Exception $e) {
                           echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    } else {
    
                        $leave_application = DB::select(DB::raw("SELECT la.employee_id ,
                                IF(lad.leave_day_type = 1 ,CONCAT('Full day leave from ',lad.from_date,' till ',lad.to_date  ),'Half day leave') leave_day_type,
                                IF(lad.first_second_half = '' ,'',CONCAT('on ',lad.first_second_half_date ,' ', REPLACE(lad.first_second_half,'_',' '))) first_second_half
                                FROM smrhr_garibsons_master.leave_application la
                                INNER JOIN smrhr_garibsons_master.leave_application_data lad
                                ON la.id = lad.leave_application_id
                                WHERE la.id = $value->leave_application_id"));
    
                        $employee = DB::select(DB::raw("SELECT e.id,e.emp_name,e.official_email,d.department_name,d1.designation_name FROM smrhr_garibsons_company.employee e
                                INNER JOIN smrhr_garibsons_master.department d
                                ON e.department_id = d.id
                                INNER JOIN smrhr_garibsons_master.designation d1
                                ON d1.id = e.designation_id
                                WHERE e.id = " . $leave_application[0]->employee_id));
    
                        CommonHelper::companyDatabaseConnection('12');
                        $reporting_manager_id = DB::table('employee')->select('reporting_manager_id')->where([['id', '=', $leave_application[0]->employee_id]])->value('reporting_manager_id');
    
                        $employee[0]->official_email == '' ? 'hr-notifications@smrsoftwares.com' : '';
                        //$emp_email='khizer@innovative-net.com';
                        $toName = '';
    
                        $official_email = '';
                        if ($reporting_manager_id != '') {
                            $official_email = DB::table('employee')->select('official_email', 'emp_name')->where([['id', '=', $reporting_manager_id]])->first();
                            $toName = $official_email->emp_name;
                            $emp_email = $official_email->official_email;
                            
                        } else {
                            $emp_email = 'muhammad.rafay@innovative-net.com';
                            $toName = 'Hr';
                        }
    
                        $toName ='Hi ' . $toName;
                        $empid = 'Employee id : ' . $employee[0]->id ;
                        $Department = ' Department : ' . $employee[0]->department_name;  
                        $Designation = 'Designation : ' . $employee[0]->designation_name ;
    
                        $messages = $employee[0]->emp_name . ' has requested ' . $value->leave_type_name . ' ' . $leave_application[0]->leave_day_type . ' ' . $leave_application[0]->first_second_half . '.
    
                                    check your dashboard to confirm link below';
    
                        //$official_email='khizer@innovative-net.com';
                        $details = [
                            'subject' => 'Leave Request',
                            'title' => $employee[0]->emp_name,
                            'toName' => $toName,
                            'empid' => $empid,
                            'Department' => $Department,
                            'Designation' => $Designation,
                            'employee' => $employee,
                            'body' => $messages,
                            'link' => 'https://garibsons.smrsoftwares.com/dc/userDashboard?m=12',
                            'desc' => '',
                        ];
    
                        
                                 $mail = new PHPMailer(true);
                        try {
                            //Server settings
                            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                            $mail->isSMTP(); //Send using SMTP
                            $mail->Host = "mail.smrsoftwares.com"; //Set the SMTP server to send through
                            $mail->SMTPAuth = true; //Enable SMTP authentication
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                            $mail->Username = "hr-notifications@smrsoftwares.com";
                            $mail->Password = 'Pga+R8uKqTkc'; //SMTP password
                            $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            //   $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
                            //Recipients
                            $mail->setFrom("hr-notifications@smrsoftwares.com", 'E-Leave Application');
                            $mail->addAddress($emp_email, 'E-Leave Application'); //Add a recipient
                          //  $mail->AddCC($official_email);
    
                            //Name is optional
    
                            //$mail->addAddress($cnic->value('official_email'), 'Payslip');
                            $mail->addReplyTo("hr-notifications@smrsoftwares.com", 'Information');
    
                            //Content
                            $mail->isHTML(true); //Set email format to HTML
                            $mail->Subject = 'Application For Applied Leave';
                            // $mail->Body    = $messages;
                            $mail->Body = view('Hr.mailAddleave', compact('details'))->render();
                            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                            // echo '<pre>';
                            // print_r($mail->Username);
                            // print_r($mail->Username);
                            // print_r($mail);
                          //  exit();
                          $mail->send();
    
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
    
                    }
                    //smtp leave end
                } else {
                    // CommonHelper::companyDatabaseConnection('12');
                    // $loan_request = DB::table('loan_request')->where('id', $value->loan_id)->first();
                    // $approve_or_reject = '';
                    // $person = '';
                    // $value->type == 2 ? $person = 'HR' : $person = 'Line Manager';
                    // $value->leave_status == 2 ? $approve_or_reject = 'Approved' : $approve_or_reject = 'Rejected';
    
                    // //$reporting_manager = DB::table('employee')->select('reporting_manager')->where([['id', '=', $leave_application->employee_id]])->value('reporting_manager');
                    // $emp_name = DB::table('employee')->select('emp_name')->where([['id', '=', $loan_request->id]])->value('emp_name');
                    // $emp_email = DB::table('employee')->select('official_email')->where([['id', '=', $loan_request->id]])->value('official_email');
                    // $messages = 'Hi, the Requested Loan Application of ' . $emp_name . ' has been ' . $approve_or_reject . ' by ' . $person . ' check your dashboard to confirm link below';
                    // $emp_email == '' ? 'hr-notifications@smrsoftwares.com' : '';
                    // //$emp_email='khizer@innovative-net.com';
    
                    // $details = [
                    //     'subject' => 'Application For Loan Approval',
                    //     'title' => $emp_name,
                    //     'body' => $messages,
                    //     'link' => 'http://unisons.smrhr.com/hr/viewLoanRequestList?m=12',
                    //     'desc' => '',
                    // ];
    
                    // $mail = new PHPMailer(true);
                    // try {
                    //     //Server settings
                    //     //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    //     $mail->isSMTP(); //Send using SMTP
                    //     $mail->Host = "smrhr.com"; //Set the SMTP server to send through
                    //     $mail->SMTPAuth = true; //Enable SMTP authentication
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                    //     $mail->Username = "hr-notifications@smrsoftwares.com";
                    //     $mail->Password = 'Pga+R8uKqTkc'; //SMTP password
                    //     $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    //     //   $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
                    //     //Recipients
                    //     $mail->setFrom("hr-notifications@smrsoftwares.com", 'E-Loan Application');
                    //     $mail->addAddress($emp_email, 'E-Loan Application'); //Add a recipient
                    //     //$mail->AddCC($official_email);
    
                    //     //Name is optional
    
                    //     //$mail->addAddress($cnic->value('official_email'), 'Payslip');
                    //     $mail->addReplyTo("hr-notifications@smrsoftwares.com", 'Information');
    
                    //     //Content
                    //     $mail->isHTML(true); //Set email format to HTML
                    //     $mail->Subject = 'Application For Applied Loan';
                    //     // $mail->Body    = $messages;
                    //     $mail->Body = view('Hr.mail', compact('details'))->render();
                    //     // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
                    //     $mail->send();
    
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
    
                }
                CommonHelper::companyDatabaseConnection('12');
               DB::table('leave_email_queue')->where([['id', '=', $value->id]])->delete();
                CommonHelper::reconnectMasterDatabase();
            endforeach;
    
        else:
    
        endif;
    
    }
}
