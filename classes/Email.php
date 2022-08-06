<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarInstracionConfirmar()
    {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cdc89f31a40ce7';
        $mail->Password = 'e7248609b9c569';

        $mail->setFrom('UpTask@gmail.com', 'UpTask.com');
        $mail->addAddress('UpTask@uptask.com', 'UpTask.com');

        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu Cuenta';

        $contenido = '<html>';
        $contenido .= '<p>Hola <strong>' . $this->nombre . '</strong> has solicitado una cuenta en <strong>UpTask.com</strong>, Da click en el siguiente enlace para confirmar: </p>';
        $contenido .= '<a href="' . $_ENV['SERVER_HOST'] . 'confirmar?token=' . $this->token .'">Confirma tu Cuenta.</a>';
        $contenido .= '<p>Si tu no has solicitado esta cuenta puedes ingnorar este mensaje.</p>';
        $contenido .= '</html>';
        $mail->Body = $contenido;

        $mail->send();
    }

    public function enviarInstrucionRecuperar()
    {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cdc89f31a40ce7';
        $mail->Password = 'e7248609b9c569';

        $mail->setFrom('UpTask@gmail.com', 'UpTask.com');
        $mail->addAddress('UpTask@uptask.com', 'UpTask.com');

        $mail->isHTML(true);
        $mail->Subject = 'Reestablecer Password';

        $contenido = '<html>';
        $contenido .= '<p>Hola <strong>' . $this->nombre . '</strong> has solicitado reestablecer tu password de tu cuenta de <strong>UpTask.com</strong>, Da click en el siguiente enlace para reestablecer tu password: </p>';
        $contenido .= '<a href="' . $_ENV['SERVER_HOST'] . 'reestablecer?token=' . $this->token .'">Reestablece tu Password.</a>';
        $contenido .= '<p>Si tu no has solicitado recuperar tu password puedes ingnorar este mensaje.</p>';
        $contenido .= '</html>';
        $mail->Body = $contenido;

        $mail->send();
    }
}
