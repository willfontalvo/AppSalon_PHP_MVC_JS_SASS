<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        //crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'appSalon.com');
        $mail->Subject = 'confirma tu cuenta';

        //set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] ."/confirmar-cuenta?token=" . $this->token . "'> confirmar cuenta </a> </p>";
        $contenido .= "<p> si tu no solicitaste esta cuenta puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->send();

    }

    public function EnviarInstrucciones(){
         //crear el objeto de email
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = $_ENV['EMAIL_HOST'];
         $mail->SMTPAuth = true;
         $mail->Port = $_ENV['EMAIL_PORT'];
         $mail->Username = $_ENV['EMAIL_USER'];
         $mail->Password = $_ENV['EMAIL_PASS'];
 
         $mail->setFrom('cuentas@appsalon.com');
         $mail->addAddress('cuentas@appsalon.com', 'appSalon.com');
         $mail->Subject = 'restablece tu password';
 
         //set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';
 
         $contenido = "<html>";
         $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo</p>";
         $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] ."/recuperar?token=" . $this->token . "'> restablecer password </a> </p>";
         $contenido .= "<p> si tu no solicitaste esta cuenta puedes ignorar el mensaje </p>";
         $contenido .= "</html>";
 
         $mail->Body = $contenido;
         $mail->send();
    }
}