<?php
require_once 'config/db.php';
require_once 'models/Usuario.php';

class MapController {
    private $usuarioModel;

    public function __construct() {
        global $conn;
        $this->usuarioModel = new Usuario($conn);
    }

    public function index() {
        // Solo admin
        if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin-estacion') {
            header("Location: index.php?page=panel");
            exit();
        }

        // Traer datos de clientes (ip, lat, lng, accesos)
        $clientes = $this->usuarioModel->obtenerClientesParaMapa();

        include __DIR__ . '/../views/map.php';
    }
}
