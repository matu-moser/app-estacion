<?php

require 'config/db.php';
require 'models/Usuario.php';
require __DIR__ . '/../Mailer/src/PHPMailer.php';
require __DIR__ . '/../Mailer/src/SMTP.php';
require __DIR__ . '/../Mailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LoginController {

    public function index() {

        // Si ya está logueado → ir a panel
        if (isset($_SESSION['idUsuario'])) {
            header("Location: index.php?page=panel");
            exit();
        }

        $error = '';
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $chipid = $_POST['chipid'] ?? 'Z';

        $usuarioModel = new Usuario($GLOBALS['conn']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($email) || empty($password)) {
                $error = "Debe completar todos los campos.";
            } else {

                $user = $usuarioModel->obtenerPorEmail($email);

                if (!$user) {
                    $this->redirectError("Credenciales no válidas.");
                }
                elseif ($user['activo'] == 0) {
                    $this->redirectError("Su usuario aún no se ha validado, revise su casilla de correo.");
                }
                elseif ($user['bloqueado'] == 1 || $user['recupero'] == 1) {
                    $this->redirectError("Su usuario está bloqueado, revise su casilla de correo.");
                }
                elseif (!$usuarioModel->verificarPassword($password, $user['contrasena'])) {
                    $this->enviarCorreoIntentoFallido($user['email'], $user['token']);
                    $this->redirectError("Credenciales no válidas.");
                }
                else {
                    // LOGIN OK
                    $_SESSION['idUsuario'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['chipid'] = $chipid;

                    $this->enviarCorreoLogin($user['email'], $user['token']);

					header("Location: index.php?page=panel");
                    exit();
                }
            }
        }

        // Mostrar formulario
        include 'views/login.php';
    }

    private function redirectError($msg) {
        header("Location: index.php?page=login&error=" . urlencode($msg));
        exit();
    }

    // -------------------------
    // FUNCIONES DE EMAIL
    // -------------------------
    private function getUserInfo() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP desconocida';
        $so = php_uname('s') . " " . php_uname('r');
        $nav = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        return [$ip, $so, $nav];
    }

    private function enviarCorreoLogin($email, $token) {
    [$ip, $so, $nav] = $this->getUserInfo();
    $url = "https://mattprofe.com.ar/alumno/10093/app-estacion/index.php?page=blocked&token=$token";

    $body = "
        <h3>Inicio de sesión en App Estación</h3>
        <p>Se ha iniciado sesión correctamente.</p>
        <p><b>IP:</b> $ip<br><b>Sistema:</b> $so<br><b>Navegador:</b> $nav</p>
        <p>
            <a href='$url' style='background:#d9534f;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;display:inline-block;'>
                No fui yo, bloquear cuenta
            </a>
        </p>
    ";

    $this->enviarCorreo($email, 'Inicio de sesión exitoso', $body);
}


private function enviarCorreoIntentoFallido($email, $token) {
    [$ip, $so, $nav] = $this->getUserInfo();
    $url = "https://mattprofe.com.ar/alumno/10093/app-estacion/index.php?page=blocked&token=$token";

    $body = "
        <h3>Intento fallido de inicio de sesión</h3>
        <p>Se intentó acceder a tu cuenta con una contraseña incorrecta.</p>
        <p><b>IP:</b> $ip<br><b>Sistema:</b> $so<br><b>Navegador:</b> $nav</p>
        <p>
            <a href='$url' style='background:#d9534f;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;display:inline-block;'>
                No fui yo, bloquear cuenta
            </a>
        </p>
    ";

    $this->enviarCorreo($email, 'Intento de acceso fallido', $body);
}


    private function enviarCorreo($email, $subject, $body) {
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

            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();

        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }
}