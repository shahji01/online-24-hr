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
use App\Models\LoanRequest;
use App\Models\Payroll;
use App\Models\EmailQueue;
use App\Models\Employee;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// require 'vendor/autoload.php';



class cronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

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
        $email_queue = DB::Table('email_queue')->where([['status', '=', 1]])->orderBy('employee_id');
        CommonHelper::reconnectMasterDatabase();

        if ($email_queue->count() > 0):

            foreach ($email_queue->get() as $key => $value):
                
                if($value->type== "salary"){ 
                CommonHelper::companyDatabaseConnection('12');
                $cnic = Employee::where([['id', '=', $value->employee_id]])->select('cnic', 'official_email');


                CommonHelper::reconnectMasterDatabase();

                if($cnic->value('official_email') == '' || $cnic->value('official_email') == '-' || $cnic->value('official_email') == null):

                else:

                    CommonHelper::companyDatabaseConnection('12');
                    $emp_name = Employee::select('emp_name')->where([['id', '=', $value->employee_id]])->value('emp_name');
                    CommonHelper::reconnectMasterDatabase();

                    
                        $m='12';
                        CommonHelper::companyDatabaseConnection('12');


                        //$payslip_data = DB::Table('payslip')->where([['month', '=', $value->month], ['year', '=', $value->year], ['emp_id', '=', $value->emp_id]])->first();

                        $payslip_data = DB::table('payslip')
                            ->where([['payslip.month','=',$value->month],['payslip.year','=',$value->year],['payslip.employee_id','=',$value->employee_id]])
                            ->join('employee', 'employee.id', '=', 'payslip.employee_id')
                            ->select('payslip.*','employee.emp_father_name','employee.department_id', 'employee.emp_name', 'employee.cnic','employee.joining_date','employee.official_email', 'employee.designation_id')
                            ->get()->toArray();


                        $pay_month=$value->year."-".$value->month."-"."31";

                        $provident_fund=DB::select(DB::raw("SELECT sum(amount) as amount FROM pf where status=1 and employee_id='".$value->employee_id."' and month= '00'  " ));
                        $pf=DB::select(DB::raw("SELECT (sum(pf_employee_fund)+sum(pf_company_fund)+sum(pf_arrears_deffered_fund)) as amount FROM pf where status=1 and employee_id='".$value->employee_id."' and month_year <= '".$pay_month."'  " ));

                        CommonHelper::reconnectMasterDatabase();
                        $leaves_policy = DB::table('leaves_policy')
                            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
                            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                            ->select('leaves_policy.*', 'leaves_data.*')
                            ->where([['leaves_policy.id', '=', 21]])
                            ->get();

                        $payslip_data =$payslip_data[0];

                        $monthNum  = $value->month;
                        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                        $monthName = $dateObj->format('F');


                        $pdf = PDF::loadView('Hr.pdf', compact('payslip_data','leaves_policy','provident_fund','m','pf'));
                        $pdf->setEncryption(str_replace(["-", "–"], '', $cnic->value('cnic')));

                        $mail = new PHPMailer(true);
                            try {
                        //Server settings
                        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = "smrhr.com";                    //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Username = "unisons@smrhr.com";
                        $mail->Password = '}uE9A]1eD_Qc';                       //SMTP password
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                                             //   $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom("unisons@smrhr.com", 'E-Payslip');
                        //$mail->addAddress('khizer@innovative-net.com', 'Payslip');     //Add a recipient
                              //Name is optional
							          //   $mail->addAddress('muhammad.saad.aslam@vis.com.pk', 'Test');

                        $mail->addAddress($cnic->value('official_email'), 'Payslip');
                        $mail->addReplyTo("unisons@smrhr.com", 'Information');
                    
                    
                        //Attachments
                      
                      //$mail->addAttachment($pdf->output(), "Payslip_" . $value->month . "_$value->year .pdf", $encoding = 'base64', $type = 'application/pdf');    //Optional name
                        $mail->addStringAttachment($pdf->Output("Payslip","Payslip_" . $monthName . "_$value->year .pdf"), "Payslip_" . $monthName . "_$value->year .pdf", $encoding = 'base64', $type = 'application/pdf');
                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Payslip for the month of '.$monthName." ".$value->year;
                        $mail->Body    = 'Enter Your CNIC to view your Payslip';
                        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                       // echo("saad")

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }

                    

                endif;
                CommonHelper::companyDatabaseConnection('12');
                DB::table('email_queue')->where([['employee_id', '=', $value->employee_id]])->delete();
                CommonHelper::reconnectMasterDatabase();
                }
                else{
                    
                    CommonHelper::companyDatabaseConnection('12');
                    $cnic = Employee::where([['id', '=', $value->employee_id]])->select('cnic', 'official_email');


                CommonHelper::reconnectMasterDatabase();

                if($cnic->value('official_email') == '' || $cnic->value('official_email') == '-' || $cnic->value('official_email') == null):

                else:

                    CommonHelper::companyDatabaseConnection('12');
                    $emp_name = Employee::select('emp_name')->where([['id', '=', $value->employee_id]])->value('emp_name');
                    CommonHelper::reconnectMasterDatabase();

                    //Mail::send('Hr.test', ['emp_name' => $emp_name, 'month' => $value->month , 'year' => $value->year], function ($message) use ($value, $cnic) {
                        $m='12';
                        CommonHelper::companyDatabaseConnection('12');


                        //$payslip_data = DB::Table('payslip')->where([['month', '=', $value->month], ['year', '=', $value->year], ['emp_id', '=', $value->emp_id]])->first();

                        $payslip_data = DB::table('bonus_issue')
                            ->where([['bonus_issue.bonus_month','=',$value->month],['bonus_issue.bonus_year','=',$value->year],['bonus_issue.employee_id','=',$value->employee_id],['bonus_issue.bonus_pay','=',2]])
                            ->join('employee', 'employee.id', '=', 'bonus_issue.employee_id')
                            ->select('bonus_issue.*','employee.emp_father_name','employee.department_id','employee.emp_id', 'employee.emp_name', 'employee.cnic','employee.joining_date','employee.official_email', 'employee.designation_id')
                            ->get()->toArray();

                        
                        $pay_month=$value->year."-".$value->month."-"."31";

                        $payslip_data =$payslip_data[0];
                        
                        CommonHelper::reconnectMasterDatabase();
                        
                        $monthNum  = $value->month;
                        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                        $monthName = $dateObj->format('F');


                        $pdf = PDF::loadView('Hr.bonusPdf', compact('payslip_data','m'));
                        $pdf->setEncryption(str_replace(["-", "–"], '', $cnic->value('cnic')));

                        
                        $mail = new PHPMailer(true);
                            try {
                        //Server settings
                        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = "smrhr.com";                    //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Username = "unisons@smrhr.com";
                        $mail->Password = '}uE9A]1eD_Qc';                       //SMTP password
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    
                        //Recipients
                        $mail->setFrom("unisons@smrhr.com", 'E-Payslip');
                        //$mail->addAddress('khizer@innovative-net.com', 'Payslip');     //Add a recipient
                              //Name is optional
                        $mail->addAddress($cnic->value('official_email'), 'Payslip');
                        $mail->addReplyTo("unisons@smrhr.com", 'Information');
                    
                    
                        //Attachments
                      
                      //$mail->addAttachment($pdf->output(), "Payslip_" . $value->month . "_$value->year .pdf", $encoding = 'base64', $type = 'application/pdf');    //Optional name
                        $mail->addStringAttachment($pdf->Output("Payslip_bonus_" . $monthName . "_$value->year .pdf"), "Payslip_bonus_" . $monthName . "_$value->year .pdf", $encoding = 'base64', $type = 'application/pdf');
                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Bonus payslip for the year ended 30th june '.$value->year;
                        $mail->Body    = 'Enter Your CNIC to view your Bonus Payslip';
                        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                    
                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                        

                    //});

                endif;
///echo("saad");
                CommonHelper::companyDatabaseConnection('12');
                DB::table('email_queue')->where([['employee_id', '=', $value->employee_id]])->delete();
                CommonHelper::reconnectMasterDatabase();
                    
                }

            endforeach;

        else:



        endif;
        
    }
}
