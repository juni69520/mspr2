<?php
include('class.phpmailer.php');

class mail extends PHPMailer{
    public function __construct($to, $body){
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
        
        $mail->Body = utf8_decode($this->body($body)); 

        $mail->AddAddress ($to);
        
        if(!$mail->Send())
        {
                $error_message = "Mailer Error: " . $mail->ErrorInfo;
                die();
        } 
    }

    private function body($body){
        if (strpos($body, '2fa_') !== false) {
           $stockage = explode('_' ,$body);
           $body = $stockage[0];
           $code = $stockage[1];
        }

        $content = null;
        switch($body){
            case 'ipHorsFrance':
                $content = "Activité suspecte détecté, une ip hors de France a utilisé vos informations de connexion, nous avons bloqué la connexion.";
            break;

            case 'NavigateurDifferent':
                $content = "Activité suspecte détecté, vous vous êtes connectés depuis un navigateur qui diffère de votre précédente connexion.";
            break;
            
            case '2fa':
                $content = "Nouvelle détection connecté, voici votre code d'authentification {$code}";
            break;

            default :
                $content = "Activité suspect détectée, une ip différente de votre dernière connexion a été détecté.";
            break;
        }
        return $content;
    }
}