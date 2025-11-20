<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Usuario.php';

class BlockedController {

    public function index($token) {

        if (!$token) {
            echo "Token inválido.";
            return;
        }

        // Usar la conexión que viene desde db.php
        global $conn;

        // Instanciar el modelo
        $usuario = new Usuario($conn);

        // Ejecutar el bloqueo
        $resultado = $usuario->bloquearCuentaPorToken($token);

        // Cargar vista
        include __DIR__ . '/../views/blocked.php';
    }
}
