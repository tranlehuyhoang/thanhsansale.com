<?php

namespace App\Services\MailServices;

class MailQuery
{
    public $From;
    public $Recipients;
    public $Subject;
    public $Body;
    public $Attachments;

    public function __construct(
        $from = null,
        $recipients,
        $subject,
        $body,
        $attachments
    ) {
        $this->Recipients = $recipients;
        $this->From = $from;
        $this->Subject = $subject;
        $this->Body = $body;
        $this->Attachments = $attachments;
    }
}
