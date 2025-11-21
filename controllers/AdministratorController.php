<?php
require_once 'config/db.php';
require_once 'models/Usuario.php';

class AdministratorController {
    private $usuarioModel;

    public function __construct() {
        global $conn;
        $this->usuarioModel = new Usuario($conn);
    }

    public function index() {
        // Sólo admin autorizado
        if ($_SESSION['email'] !== 'admin-estacion') {
            header("Location: index.php?page=login");
            exit();
        }

        // Contar usuarios registrados
        $result = $this->usuarioModel->contarUsuarios();
        $usuariosRegistrados = $result['usuarios'] ?? 0;
        $clientes = $result['clientes'] ?? 0;

        include __DIR__ . '/../views/administrator.php';
    }
}
