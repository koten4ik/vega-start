<?php

namespace Modules\ZSupport\App\Services\Mailer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $body;
    public $subject;
    public $fromAddress;
    public $fromName;

    public function __construct($data)
    {
        $this->subject = $data['subject'];
        $this->body = $data['body'];
        $this->fromAddress = $data['fromAddress'];
        $this->fromName = $data['fromName'];
    }

    public function build()
    {
    	//todo перенести папку с письмами в модуль site
        return $this->from($this->fromAddress, $this->fromName)->subject($this->subject)->view('emails.send');
    }
}
