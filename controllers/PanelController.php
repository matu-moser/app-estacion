<?php
class PanelController {

    public function index() {

        // Validación de sesión
        if (!isset($_SESSION['idUsuario'])) {
            header("Location: index.php?page=login");
            exit();
        }

        // Datos del usuario desde la sesión
        $email = $_SESSION['email'];
        $idUsuario = $_SESSION['idUsuario'];
        $chipid = $_SESSION['chipid'] ?? null;

        // Obtener lista de estaciones desde la API
        $apiUrl = "https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations";
        $json = file_get_contents($apiUrl);
        $data = json_decode($json, true);

        // Cargar la vista enviando los datos
        require_once "views/panel.php";
    }
}
