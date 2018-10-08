<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/21
 * Time: 9:41
 */

namespace app\common\library;

use app\common\Constant;
use PHPMailer\PHPMailer;

class Email implements Constant
{
    public static function send($address,$subject,$msg,$addAttachments = []){
        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = config('email.smtp_debug');
        //Set the hostname of the mail server
        $mail->Host = config('email.host');
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = config('email.port');
        //Whether to use SMTP authentication
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication
        $mail->Username = config('email.username');
        //Password to use for SMTP authentication
        $mail->Password = config('email.password');
        //Set who the message is to be sent from
        $mail->setFrom(config('email.username'));
        //Set who the message is to be sent to
        $mail->addAddress($address);
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML($msg);
        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        foreach ($addAttachments as $v){
            $mail->addAttachment($v);
        }
        //send the message, check for errors
        if (!$mail->send()) {
            throw new \Exception($mail->ErrorInfo,self::EMAIL_SEND_FAIL);
        }
        return true;
    }
}