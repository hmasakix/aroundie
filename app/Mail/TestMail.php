<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
       
    }

    public function build()
    {
        $email = $this->data['email'] ?? 'no-reply@aroundie.sakura.ne.jp'; // ← フォールバック
        $subject = $this->data['subject'] ?? '（件名なし）';

        return $this->from($email)
                    ->subject($subject)
                    ->view('emails.contact-form')
                    ->with(['data' => $this->data]);
    }
}
