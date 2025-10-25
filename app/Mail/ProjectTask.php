<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectTask extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The data array to be used in the email.
     *
     * @var array
     */
    public $array;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view($this->array['view'])
            ->from($this->array['from'], env('MAIL_FROM_NAME'))
            ->subject($this->array['subject'])
            ->with([
                'project_task_detail' => $this->array['project_task_detail'],
                'subject' => $this->array['subject']
            ]); // Pass data to the view
        if (isset($this->array['cc'])) {
            $email->cc($this->array['cc']);
        }

        if (isset($this->array['bcc'])) {
            $email->bcc($this->array['bcc']);
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}