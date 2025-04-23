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
         \Log::debug('TestMail@build data:', $this->data);

        $email = $this->data['email'] ?? 'no-reply@aroundie.sakura.ne.jp';
        $subject = $this->data['subject'] ?? '（件名なし）';

        return $this->from($email)
                ->subject($subject)
                ->text('emails.plain-test') // ← プレーンテキストビュー
                ->with(['data' => $this->data]);
    }
}
