<?php
require_once('phpmailer/class.phpmailer.php');

$mail = new PHPMailer();

$mail->IsSMTP();                       // telling the class to use SMTP

$mail->SMTPDebug = 0;                  
// 0 = no output, 1 = errors and messages, 2 = messages only.

$mail->SMTPAuth = true;                // enable SMTP authentication
$mail->SMTPSecure = "tls";              // sets the prefix to the servier
$mail->Host = "smtp.gmail.com";        // sets Gmail as the SMTP server
$mail->Port = 587;                     // set the SMTP port for the GMAIL

$mail->Username = "cchatelet33000@gmail.com";  // Gmail username
$mail->Password = "Cch@telet33000";      // Gmail password

$mail->CharSet = 'windows-1250';
$mail->SetFrom ('cchatelet33000@gmail.com', 'Example.com Information');
$mail->Subject = 'on test';
$mail->ContentType = 'text/plain';
$mail->IsHTML(false);

$mail->Body = "test 2"; 

// you may also use $mail->Body = file_get_contents('your_mail_template.html');

$mail->AddAddress ('quentin.viegas@gmail.com', 'Quentin VIEGAS');     
// you may also use this format $mail->AddAddress ($recipient);

if(!$mail->Send())
{
        $error_message = "Mailer Error: " . $mail->ErrorInfo;
} else 
{
        $error_message = "Successfully sent!";
}
echo $error_message;
// You may delete or alter these last lines reporting error messages, but beware, that if you delete the $mail->Send() part, the e-mail will not be sent, because that is the part of this code, that actually sends the e-mail.
?>
