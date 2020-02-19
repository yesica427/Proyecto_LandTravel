<?php 

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

const gmail = 'noreply.landtravel@gmail.com';
const password = 'LandTravel12345';

final class Mailer 
{
    private $mail;
    
    public function __construct(string $toAddress, string $subject, string $body)
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output                                     // Send using SMTP
        $this->mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $this->mail->Username   = gmail;                     // SMTP username
        $this->mail->Password   = password;                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $this->mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $this->mail->setFrom(gmail, 'Land Travel');
        $this->mail->addAddress($toAddress);               // Name is optional

        // Content
        $this->mail->isHTML(true);                                  // Set email format to HTML
        $this->mail->Subject = $subject;
        $this->mail->Body    = $body;
        $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    }
    
    public function send()
    {
        if(!$this->mail->Send()) 
        {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }
    }

    public static function confirmationEmail(string $address, string $usuario, string $contraseña) 
    {
        $subject = 'Credenciales de usuario';
        $body = "
        <h1> Land Travel </h1>
        <p> Sus credenciales de usuario son: </p> <br>
        <p> Usuario: $usuario </p>
        <p> Contraseña: $contraseña </p>
        <p> Procure no perderlas. </p>";
        $mail = new self($address, $subject, $body);
        $mail->send();
    }
    public static function lostPasswordEmail(string $address, string $usuario, string $contraseña) 
    {
        $subject = 'Recuperar credenciales';
        $body = "
        <h1> Land Travel </h1>
        <p> Sus credenciales de usuario son: </p> <br>
        <p> Usuario: $usuario </p>
        <p> Contraseña: $contraseña </p>
        <p> Procure no perderlas. </p>";
        $mail = new self($address, $subject, $body);
        $mail->send();
    }
}

