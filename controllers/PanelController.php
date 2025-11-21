<?php
require 'config/db.php';
require 'models/Usuario.php';

class PanelController {

    private $usuarioModel;

    public function __construct() {
        global $conn;
        $this->usuarioModel = new Usuario($conn);
    }

    public function index() {

        // Datos de sesión si existe
        $email = $_SESSION['email'] ?? 'visitante';
        $idUsuario = $_SESSION['idUsuario'] ?? null;

        // Capturar info del cliente
        $ipCliente = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        $sistema = php_uname('s') . " " . php_uname('r');

        // Obtener info de geolocalización
        $geoJson = @file_get_contents("http://ipwho.is/$ipCliente");
        $geo = json_decode($geoJson, true);

        $latitud = $geo['latitude'] ?? '0';
        $longitud = $geo['longitude'] ?? '0';
        $pais = $geo['country'] ?? 'Desconocido';

        // Generar token único
        $token = bin2hex(random_bytes(16));

        // Guardar info en tracker (aunque sea visitante)
        $this->usuarioModel->guardarTracker(
            $token,
            $ipCliente,
            $latitud,
            $longitud,
            $pais,
            $navegador,
            $sistema
        );

        // Obtener estaciones desde la API
        $apiUrl = "https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations";
        $json = @file_get_contents($apiUrl);
        $data = $json ? json_decode($json, true) : [];

        // Cargar vista (tu view actual)
        require_once "views/panel.php";
    }
}
