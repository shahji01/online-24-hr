<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Input;
use PDF;
use Mail;
use Auth;

use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\EmployeePromotion;
use App\Models\IncrementLettersQueue;
use App\Models\Employee;
use App\Models\ApiEmployeeShifts;

class sendIncrementLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:incrementLetters';

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
        $increment_letters_queue = IncrementLettersQueue::where([['status', '=', 1]]);

        if ($increment_letters_queue->count() > 0):

            foreach ($increment_letters_queue->get() as $key => $value):

                CommonHelper::fyDatabaseConnection();
                $cnic = ApiEmployeeShifts::where([['emp_code', '=', $value->emp_code]])->select('cnic', 'emp_email');
                CommonHelper::reconnectMasterDatabase();
                IncrementLettersQueue::where([['month', '=', $value->month], ['year', '=', $value->year]])->delete();

                if($cnic->value('emp_email') == '' || $cnic->value('emp_email') == '-' || $cnic->value('emp_email') == null):

                else:

                    $emp_name = Employee::select('emp_name')->where([['emp_code', '=', $value->emp_code]])->value('emp_name');
                    Mail::send('letter', ['emp_name' => $emp_name, 'month' => $value->month, 'year' => $value->year], function ($message) use ($value, $cnic) {

                        $employeePromotionDetail = EmployeePromotion::where([['id','=',$value->promotion_id],['status','=',1]])->first();
                        $pdf = PDF::loadView('Hr.AjaxPages.viewIncrementLetter', compact('employeePromotionDetail'));
                        $pdf->setEncryption(str_replace(["-", "–"], '', $cnic->value('cnic')));
                        $address = 'eletters@tenpearls.com';
                        $subject = 'Increment / Promotion Letter';
                        $name = '10Pearls';
                        //$message->to('firebaseapplications10@gmail.com', 'Test');
                        //$message->to($cnic->value('emp_email'), 'Test');
                        $message->to('aliasghar.7552@gmail.com', 'Test');
                        //$message->subject('Payslip');
                        $message->from($address, $name);
                        $message->cc($address, $name);
                        $message->bcc($address, $name);
                        $message->replyTo($address, $name);
                        $message->subject($subject);
                        $message->attachData($pdf->output(), "Letter " . $value->month . "_".$value->year.".pdf");

                    });

                endif;

            endforeach;

        endif;
    }

}