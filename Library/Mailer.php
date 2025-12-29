<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->CharSet = 'UTF-8';
        $this->configureSMTP();
    }

    private function configureSMTP() {
        try {
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.office365.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'sahmhym@hogarymoda.com.co';
            $this->mail->Password = 'blbhwzdpzpntvkjp';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;

            $this->mail->setFrom('sahmhym@hogarymoda.com.co', 'SAHM Sistema Administrativo Hogar y Moda');
        } catch (Exception $e) {
            throw new Exception("Error al configurar el SMTP: " . $e->getMessage());
        }
    }

    public function sendMail($recipients, $subject, $shortMessage) {
        try {
            $recipients = array_filter((array) $recipients);
            
            if (empty($recipients)) {
                $recipients = ['sahmhym@hogarymoda.com.co'];
            }

            foreach ((array) $recipients as $recipient) {
                $this->mail->addAddress($recipient);
            }

            $body = $this->generateEmailBody($shortMessage);

            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: " . $e->getMessage());
        }
    }

    private function generateEmailBody($message) {
        return '
            <div style="max-width: 600px; margin: 0 auto; background: #f9f9f9; border-radius: 12px; overflow: hidden;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); font-family: Arial, sans-serif; color: #333; text-align: center;
            padding: 30px; border: 2px solid #2e86de;">
    
            <!-- Encabezado con logos a los lados -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <img src="https://sahm.hogarymoda.com/Views/Default//img/logo-sahm.png" alt="Logo SAHM"
                    style="height: 50px; object-fit: contain; opacity: 0.9; transition: opacity 0.3s;"
                    onmouseover="this.style.opacity=\'1\'" onmouseout="this.style.opacity=\'0.9\'">

                <div style="flex-grow: 1; text-align: center; font-size: 16px; font-weight: bold;">
                    Sistema Administrativo Hogar y Moda
                </div>

                <img src="https://sahm.hogarymoda.com/Views/Default//img/logo-hym.png" alt="Logo HYM"
                    style="height: 50px; object-fit: contain; opacity: 0.9; transition: opacity 0.3s;"
                    onmouseover="this.style.opacity=\'1\'" onmouseout="this.style.opacity=\'0.9\'">
            </div>

            <!-- Mensaje -->
            <p style="font-size: 16px; line-height: 1.6;">' . nl2br(htmlspecialchars($message)) . '</p>
    
            <!-- Botón con efecto suave -->
            <a href="https://sahm.hogarymoda.com/" style="display: inline-block; margin-top: 15px; padding: 12px 20px;
                background: #2e86de; color: white; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: bold;
                transition: background 0.3s ease, transform 0.2s ease;"
                onmouseover="this.style.background=\'#1e5bbf\'; this.style.transform=\'scale(1.05)\'"
                onmouseout="this.style.background=\'#2e86de\'; this.style.transform=\'scale(1)\'">
                Ir a SAHM
            </a>
    
            <!-- Pie página -->
            <div style="margin-top: 25px; font-size: 12px; color: #666;">
                &copy; ' . '2023 Hogar y Moda' . ' Hogar y Moda. Todos los derechos reservados. <br>
                <a href="#" style="color: #2e86de; text-decoration: underline;">Política de privacidad</a> 
                <a href="#" style="color: #2e86de; text-decoration: underline;">Términos de servicio</a>
            </div>
        </div>';
    }
    
}
