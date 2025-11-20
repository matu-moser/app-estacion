<?php

require_once 'config/db.php';
require_once 'models/Usuario.php';
require_once __DIR__ . '/../Mailer/src/PHPMailer.php';
require_once __DIR__ . '/../Mailer/src/SMTP.php';
require_once __DIR__ . '/../Mailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegisterController {

    public function index() {
        $error = '';
        $success = '';

        require 'views/register.php';
    }

    public function save() {

        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header("Location: index.php?page=panel");
            exit();
        }

        global $conn; // Para usar la conexión creada arriba

        $usuarioModel = new Usuario($conn);
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "El formato de email no es válido.";
            } elseif ($password !== $confirm_password) {
                $error = "Las contraseñas no coinciden.";
            } elseif ($usuarioModel->emailExiste($email)) {
                $error = "Este email ya está registrado.";
            } else {

                $token = bin2hex(random_bytes(16));
                $token_action = bin2hex(random_bytes(16));
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                if ($usuarioModel->registrar($email, $hashed_password, $token, $token_action)) {

                    if ($this->sendActivationEmail($email, $token_action)) {
                        $success = "Te enviamos un correo para activar tu cuenta.";
                    } else {
                        $error = "No se pudo enviar el correo.";
                    }

                } else {
                    $error = "Error al registrar usuario.";
                }
            }
        }

        require 'views/register.php';
    }

    private function sendActivationEmail($email, $token_action) {

        $mail = new PHPMailer(true);

        try {

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
            $mail->Subject = 'Activa tu cuenta';

            $mail->Body = "
                <h2>¡Bienvenido!</h2>
                <p>Activa tu cuenta con este enlace:</p>
                <a href='http://mattprofe.com.ar/alumno/10093/app-estacion/controllers/ValidateController.php?token_action=$token_action'>
                    Activar cuenta
                </a>
            ";

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }
    }
}
