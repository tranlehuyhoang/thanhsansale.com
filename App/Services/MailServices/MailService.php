<?php
namespace App\Services\MailServices;

use App\Core\Config;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailService implements IMailService
{

    private $mail;
    private $config;
    public function __construct()
    {
        $this->config = Config::MailConfig();
        $this->mail = new PHPMailer();
        //Server settings
        // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
        $this->mail->isSMTP(); // Send using SMTP
        $this->mail->Host = $this->config['Host']; // Set the SMTP server to send through
        $this->mail->SMTPAuth = true; // Enable SMTP authentication
        $this->mail->Username = $this->config['Username']; // SMTP username
        $this->mail->Password = $this->config['Password'];
        $this->mail->SMTPSecure = $this->config['SMTPSecure'] == 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = $this->config['Port'];
        $this->mail->CharSet = 'UTF-8';
    }
    /**
     *
     * @param mixed $mail
     *  $To;
     *  $From;
     *  $Subject;
     *  $Body;
     *  $Attachments;
     * @return mixed array
     */

    public function SendMail($data)
    {
        try {
            $fromEmail = $this->config['Username'];
            $fromName = $this->config['FromName'];
            $this->mail->setFrom($fromEmail, $fromName);

            //Recipients
            foreach ($data->Recipients as $item) {
                $this->mail->addAddress($item); // Add a recipient
            }

            // Content
            $this->mail->isHTML(true); // Set email format to HTML
            $this->mail->Subject = $data->Subject;
            $this->mail->Body = $data->Body;
            if (count($data->Attachments) > 0) {
                foreach ($data->Attachments as $item) {
                    $this->mail->addAttachment($item); // Add attachments
                }
            }

           $result = $this->mail->send();
            return $res = [
                'Success' => true,
                'Data' => $result,
                'Message' => 'Message has been sent'
            ];

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->mail->ErrorInfo;
            return $res = [
                'Success' => false,
                'Message' => $this->mail->ErrorInfo
            ];

        }
    }
}