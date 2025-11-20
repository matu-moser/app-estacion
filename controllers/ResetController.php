<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once 'config/db.php';
require_once 'models/Usuario.php';
require_once __DIR__ . '/../Mailer/src/PHPMailer.php';
require_once __DIR__ . '/../Mailer/src/SMTP.php';
require_once __DIR__ . '/../Mailer/src/Exception.php';

class ResetController
{
    private $usuarioModel;
    private $error = '';
    private $success = '';

    public function __construct()
    {
        global $conn;
        $this->usuarioModel = new Usuario($conn);
    }

    public function index()
    {

        // Si ya está logueado → mandarlo al panel
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header("Location: index.php?page=panel");
            exit();
        }

        // Token
        $token_action = $_GET['token_action'] ?? '';

        if (!$token_action) {
            die("Token no válido.");
        }

        $user = $this->usuarioModel->obtenerPorTokenAction($token_action);

        if (!$user) {
            die("El token no corresponde a un usuario.");
        }

        // =============================
        // PROCESAR FORMULARIO
        // =============================
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm) {
                $this->error = "Las contraseñas no coinciden.";
            } else {

                $hashed = password_hash($password, PASSWORD_DEFAULT);

                if ($this->usuarioModel->restablecerPassword($user['id'], $hashed)) {

                    // Limpiar token / desbloqueos
                    $this->usuarioModel->limpiarTokenYDesactivar($user['id']);

                    // Enviar correo de seguridad
                    $this->sendSecurityEmail($user['email'], $user['token']);

                    // Redirigir
                    header("Location: index.php?page=login");
                    exit();
                } else {
                    $this->error = "No se pudo restablecer la contraseña.";
                }
            }
        }

        // =============================
        // CARGAR LA VISTA
        // =============================
        $error = $this->error;
        include 'views/reset.php';
    }

    // ========================================================
    // ✉️ FUNCIÓN PARA ENVIAR EMAIL DE SEGURIDAD
    // ========================================================
    private function sendSecurityEmail($email, $token)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP desconocida';
        $so = php_uname('s') . " " . php_uname('r');
        $nav = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';

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

        $mail->Subject = 'Tu contraseña fue restablecida';

        $mail->Body = "
            <h3>Se ha restablecido tu contraseña</h3>
            <p><b>IP:</b> $ip<br><b>Sistema:</b> $so<br><b>Navegador:</b> $nav</p>
            <p>
                <a href='https://mattprofe.com.ar/alumno/10093/app-estacion/index.php?page=reset&token_action=$token'>
                   style='background:#d9534f;color:white;padding:10px 15px;border-radius:5px;text-decoration:none;'>
                   No fui yo, bloquear cuenta
                </a>
            </p>
        ";

        return $mail->send();
    }
}
