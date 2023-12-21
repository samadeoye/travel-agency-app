<?php
namespace AbcTravels\SendMail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use SendGrid;
use SendGrid\Mail\Mail;

class SendMail
{
    static $isSent = false;
    
    public static function sendMail($arParams)
    {
        global $arSiteSettings;

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try
        {
            //Server settings
            /*
            $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = "ssl";//PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = SMTP_PORT;
            */
            
            $mail->isMail();

            $mailTo = $arParams['mailTo'];
            $toName = $arParams['toName'];
            $mailFrom = $arParams['mailFrom'];
            $fromName = $arParams['fromName'];
            $subject =  (array_key_exists('subject', $arParams)) ? $arParams['subject'] : 'Mail From '.$arSiteSettings['name'];
            $body =  (array_key_exists('body', $arParams)) ? $arParams['body'] : '';
            $bodyHtml =  (array_key_exists('bodyHtml', $arParams)) ? $arParams['bodyHtml'] : '';
            $addReplyTo = (array_key_exists('addReplyTo', $arParams)) ? $arParams['addReplyTo'] : true;
            $isHtml = (array_key_exists('isHtml', $arParams)) ? $arParams['isHtml'] : false;
            $arCC = (array_key_exists('arCC', $arParams)) ? $arParams['arCC'] : [];
            $arBCC = (array_key_exists('arBCC', $arParams)) ? $arParams['arBCC'] : [];
            $arAttachments = (array_key_exists('arAttachments', $arParams)) ? $arParams['arAttachments'] : [];
            
            //Recipients
            //$mail->setFrom($mailFrom, $fromName);
            $mail->setFrom($arSiteSettings['email'], $arSiteSettings['name'], 0);
            $mail->addAddress($mailTo, $toName);
            if ($addReplyTo)
            {
                $mail->addReplyTo($mailFrom, $fromName);
            }
            if ($arSiteSettings['booking_email'] != '')
            {
                $arCC = [$arSiteSettings['booking_email']];
            }
            if (count($arCC) > 0)
            {
                foreach($arCC as $ccEmail)
                {
                    $mail->addCC($ccEmail);
                }
            }
            if (count($arBCC) > 0)
            {
                foreach($arBCC as $bccEmail)
                {
                    $mail->addBCC($bccEmail);
                }
            }

            //Attachments
            if (count($arAttachments) > 0)
            {
                foreach($arAttachments as $attachment)
                {
                    $mail->addAttachment($attachment);//full path e.g /var/tmp/file.tar.gz
                }
            }

            //Content
            if ($isHtml)
            {
                $mail->isHTML(true);
                if ($bodyHtml == '' && $body != '')
                {
                    $mail->AltBody = $body;
                    $bodyHtml = $body;
                }
                else
                {
                    $mail->AltBody = $body;
                }
                $body = $bodyHtml;
            }
            else
            {
                $mail->AltBody = $body;
            }
            $mail->Subject = $subject;
            $mail->Body = $body;

            if ($mail->send())
            {
                self::$isSent = true;
            }
            else
            {
                self::$isSent = false;
                throw new Exception('Message could not be sent');
            }
        }
        catch (Exception $e)
        {
            self::$isSent = false;
            throw new Exception('Message could not be sent');
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
        }
    }

    public static function sendDefaultMail($arParams)
    {
        global $arSiteSettings;

        try
        {
            $mailTo = $arParams['mailTo'];
            $toName = $arParams['toName'];
            $mailFrom = $arParams['mailFrom'];
            $fromName = $arParams['fromName'];
            $subject =  $arParams['subject'];
            $body = $arParams['body'];
            $arCC = (array_key_exists('arCC', $arParams)) ? $arParams['arCC'] : [];

            $siteEmail = $arSiteSettings['email'];
            $emailHeaders = "From: $siteEmail" . "\r\n" . "Reply-To: $mailFrom" . "\r\n";
            if ($arSiteSettings['booking_email'] != '')
            {
                $arCC = [$arSiteSettings['booking_email']];
            }
            if (count($arCC) > 0)
            {
                foreach($arCC as $ccEmail)
                {
                    $emailHeaders .= "Cc: $ccEmail\r\n";
                }
            }
            if (mail($mailTo, $subject, $body, $emailHeaders))
            {
                self::$isSent = true;
            }
            else
            {
                throw new Exception('An error occured. Please try again.');
            }
        }
        catch(Exception $e)
        {
            self::$isSent = false;
            //echo $e->getMessage();exit;
            throw new Exception('An error occured');
        }
    }

    public static function sendCustomMail($arParams)
    {
        global $arSiteSettings;

        $mailTo = $arParams['mailTo'];
        $toName = $arParams['toName'];
        $mailFrom = $arParams['mailFrom'];
        $fromName = $arParams['fromName'];
        $subject =  $arParams['subject'];
        $body = $arParams['body'];

        $arCC = [];
        if ($arSiteSettings['booking_email'] != '')
        {
            $mailTo = $arSiteSettings['booking_email'];
            $toName = 'Booking Admin';
            $arCC = [$arSiteSettings['email']];
        }
        
        $email = new Mail(); 
        $email->setFrom($mailFrom, $fromName);
        $email->setSubject($subject);
        $email->addTo($mailTo, $toName);
        if (count($arCC) > 0)
        {
            foreach($arCC as $ccEmail)
            {
                $email->addCc($ccEmail);
            }
        }
        $email->addContent("text/plain", $body);
        $email->addContent("text/html", $body);
        $sendgrid = new SendGrid(DEF_SENDGRID_API_KEY);
        try
        {
            $response = $sendgrid->send($email);
            /*
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            exit;
            */
            if(substr($response->statusCode(), 0, 1) == 2)
            {
                self::$isSent = true;
            }
        }
        catch (Exception $e)
        {
            //echo 'Caught exception: '. $e->getMessage() ."\n";
            throw new Exception('An error occured.');
        }
    }
}