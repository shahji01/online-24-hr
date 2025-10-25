<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Input;
use Redirect;
use Response;
use DB;
use Config;
use PDF;
use Mail;

use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\Payroll;
use App\Models\EmailQueue;
use App\Models\Employee;


class sendPayslips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:payslips';

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

       DB::Table('regions')->where('id',53)->delete();

    //   $email_queue=DB::Table('email_queue')->where('status',1)->get();
    //     foreach ($email_queue as $value){

    //         $cnic = Employee::where([['emp_id', '=', '043']])->select('emp_cnic', 'professional_email');

    //         Mail::send('test', ['emp_name' => 'Muzamil', 'month' =>'09', 'year' => 2020], function ($message) use ($value, $cnic) {

    //             CommonHelper::companyDatabaseConnection('12');
    //             $payslip_data = DB::Table('payslip')->where([['month', '=', $value->month], ['year', '=', $value->year], ['emp_id', '=', $value->emp_id]]);
    //              CommonHelper::reconnectMasterDatabase();
    //             $name ="test";
    //             $address = 'epay@hr-innovative.com';
    //             $subject = 'Payslip for the month of ';
    //             $pdf = PDF::loadView('pdf', compact('payslip_data'));
    //             $pdf->setEncryption(str_replace(["-", "–"], '', $cnic->value('cnic')));
    //             //$message->to('firebaseapplications10@gmail.com', 'Test');
    //             $message->to('khizarshafi05gmail.com', 'Test');
    //             $message->subject('This is test email');
    //             $message->from($address, $name);
    //             $message->cc($address, $name);
    //             $message->bcc($address, $name);
    //             $message->replyTo($address, $name);
    //             $message->subject($subject);
    //             // $message->attachData($pdf->output(), "Payslip_" . $value->month . "_$value->year .pdf");
    //             $message->attachData($pdf->output(), "Payslip_" . $value->month . "_$value->year .pdf");

    //         });
    //         echo 1;

    // }

    }

}