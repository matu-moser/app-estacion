<?php

require_once 'config/db.php';
require_once 'models/Usuario.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once __DIR__ . '/../Mailer/src/PHPMailer.php';
require_once __DIR__ . '/../Mailer/src/SMTP.php';
require_once __DIR__ . '/../Mailer/src/Exception.php';


class RecoveryController {

    public function index() {

        // Si ya está logueado → redirigir a panel
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header("Location: index.php?page=panel");
            exit();
        }

        $error = "";
        $success = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? '');

            if ($email === "") {
                $error = "Debes ingresar un email.";
            } else {

                global $conn;
                $usuario = new Usuario($conn);

                // ¿Existe el email?
                $user = $usuario->emailExiste($email);

                if ($user) {

                    // generar token
                    $token = bin2hex(random_bytes(16));

                    // actualizar base de datos
                    $usuario->iniciarRecuperacion($email, $token);

                    // enviar email
                    $this->sendRecoveryEmail($email, $token);

                    $success = "Se inició el proceso. Revisa tu correo.";

                } else {
                    $error = "El email no se encuentra registrado. ";
                    $error .= "<a href='index.php?page=register'>Registrarse</a>";
                }
            }
        }

        require "views/recovery.php";
    }


    private function sendRecoveryEmail($email, $token) {

        $link = "https://mattprofe.com.ar/alumno/10093/app-estacion/index.php?page=reset&token_action=$token";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'mosermatias95@gmail.com';
        $mail->Password = 'qllq merk oaaw vwlu';

        $mail->setFrom('mosermatias95@gmail.com', 'App Estación');
        $mail->addAddress($email);
        $mail->isHTML(true);

        $mail->Subject = 'Proceso de recuperación iniciado';

        $mail->Body = "
            <p>Haz clic para restablecer tu contraseña:</p>
            <a href='$link'>$link</a>
        ";

        $mail->send();
    }
}
