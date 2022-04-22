<?php
include('class.phpmailer.php');

class mail extends PHPMailer{
    public function __construct($to, $body){
        $config = parse_ini_file('./private/config.ini');

        $code = '';
        if (strpos($body, '2fa_') !== false) {
            $stockage = explode('_' ,$body);
            $body = $stockage[0];
            $code = $stockage[1];
        }

        $mail = new PHPMailer();
        $mail->IsSMTP();                       // telling the class to use SMTP
        
        $mail->SMTPDebug = 0;                  
        // 0 = no output, 1 = errors and messages, 2 = messages only.
        
        $mail->SMTPAuth = true;                // enable SMTP authentication
        $mail->SMTPSecure = "tls";              // sets the prefix to the servier
        $mail->Host = "smtp.gmail.com";        // sets Gmail as the SMTP server
        $mail->Port = 587;                     // set the SMTP port for the GMAIL
        
        $mail->Username = $config['APP_GMAIL_EMAIL'];  // Gmail username
        $mail->Password = $config['APP_GMAIL_PW'];      // Gmail password
        
        $mail->CharSet = 'windows-1250';
        $mail->SetFrom ($config['APP_GMAIL_EMAIL'], 'Clinique du Chatelet');

        switch($body){
            case 'ipHorsFrance':
                $content = "Activité suspecte détectée, une ip hors de France a utilisé vos informations de connexion, nous avons bloqué la connexion.";
                $mail->Subject = mb_encode_mimeheader("Alerte activite suspecte - connexion détectée hors de France.");
                break;

            case 'NavigateurDifferent':
                $content = "Activité suspecte détecté, vous vous êtes connectés depuis un navigateur qui est différent de votre précédente connexion.";
                $mail->Subject = mb_encode_mimeheader("Alerte activite suspecte - connexion depuis un navigateur différent.");
                break;
            case '2fa':
                $content = "Nouvelle connexion détectée, voici votre code d'authentification {$code}";
                $mail->Subject = mb_encode_mimeheader("Code de vérification.");
            break;

            default :
                $content = "Activité suspect détectée, une ip différente de votre dernière connexion a été détecté.";
                $mail->Subject = mb_encode_mimeheader("Alerte activité suspecte - une ip différente a été détectée.");
            break;
        }

        $mail->ContentType = 'text/plain';
        $mail->IsHTML(false);
        
        $mail->Body = utf8_decode($content); 

        $mail->AddAddress ($to);
        
        if(!$mail->Send())
        {
                $error_message = "Mailer Error: " . $mail->ErrorInfo;
                die();
        } 
    }
}