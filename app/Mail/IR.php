<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\IncidentReports;
use App\Models\UserBasic;

class IR extends Mailable
{
    use Queueable, SerializesModels;
    public $ir;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ir)
    {
        $this->ir = $ir;
        $this->toEmail = $ir->incident_tonotify;
        $this->incident_id = $ir->incident_id;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'))
        ->subject('Portal: Incident Reports')
        ->markdown('emails.ir')
        ->with([
            'user' => $this->ir,
            'toEmail' => $this->toEmail,
        ]);
    }
}
