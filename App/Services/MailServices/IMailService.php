<?php 
namespace App\Services\MailServices;

interface IMailService {
    public function SendMail($mail);
}