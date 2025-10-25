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
use App\Models\Payroll;
use App\Models\Arrears;
use App\Models\EmailQueue;
use App\Models\Employee;
use App\Models\ApiEmployeeShifts;
use App\Models\ApiEmployeeData;
use App\Models\IncrementLettersQueue;
use App\Models\TaxCertificateQueue;
use App\Models\TaxDeduction;

class sendTaxCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:tax';

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
        $tex_certificate_queue = TaxCertificateQueue::where([['status', '=', 1]]);

        if ($tex_certificate_queue->count() > 0):

            foreach ($tex_certificate_queue->get() as $key => $value):

                CommonHelper::fyDatabaseConnection();
                $cnic = ApiEmployeeShifts::where([['emp_code', '=', $value->emp_code]])->select('cnic', 'emp_email');
                $api_data = ApiEmployeeData::where([['emp_code', '=', $value->emp_code]])->select('separation_date', 'employee_status');
                CommonHelper::reconnectMasterDatabase();
                // TaxCertificateQueue::where([['tax_year', '=', $value->tax_year]])->delete();

                if ($cnic->value('emp_email') == '' || $cnic->value('emp_email') == '-' || $cnic->value('emp_email') == null):

                else:

                    $emp_name = Employee::select('emp_name')->where([['emp_code', '=', $value->emp_code]])->value('emp_name');
                    Mail::send('taxCertificate', ['emp_name' => $emp_name, 'tax_year' => $value->tax_year, 'last_working_date' => $api_data->value('separation_date'), 'employee_status' => $api_data->value('employee_status')], function ($message) use ($value, $cnic) {
                        $emp_code = $value->emp_code;
                        $year = $value->tax_year;
                        CommonHelper::DatabaseConnectionForTax($year);
                        $tax_deduction = TaxDeduction::where([['emp_code', '=', $emp_code]])->orderBy('id', 'desc');
                        CommonHelper::reconnectMasterDatabase();

                        $pdf = PDF::loadView('Hr.viewTaxCertificate', compact('tax_deduction', 'emp_code', 'year'));
                        $pdf->setEncryption(str_replace(["-", "–"], '', $cnic->value('cnic')));

                        $address = 'eCertificate@tenpearls.com';
                        $subject = 'Tax Certificate';
                        $name = '10Pearls';
                        $message->to('aliasghar.7552@gmail.com', 'Letter');
                        // $message->to($cnic->value('emp_email'), 'Letter');
                        $message->subject('Letter');
                        $message->from($address, $name);
                        $message->cc($address, $name);
                        $message->bcc($address, $name);
                        $message->replyTo($address, $name);
                        $message->subject($subject);
                        $message->attachData($pdf->output(), "Tax Certificate-" . $emp_code. ".pdf");

                    });

                endif;

            endforeach;

        endif;
    }
}