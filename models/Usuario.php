<?php
class Usuario {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExiste($email) {
        $stmt = $this->conn->prepare("SELECT id FROM usuariosAppEstacion WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function registrar($email, $hashed_password, $token, $token_action) {
        $stmt = $this->conn->prepare("
            INSERT INTO usuariosAppEstacion (email, contrasena, token, token_action, activo, bloqueado, recupero)
            VALUES (?, ?, ?, ?, 0, 0, 0)
        ");
        $stmt->bind_param("ssss", $email, $hashed_password, $token, $token_action);
        return $stmt->execute();
    }

    public function obtenerPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuariosAppEstacion WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function verificarPassword($password, $hash) {
        return password_verify($password, $hash);
    }


public function iniciarRecuperacion($email, $token_action) {
    $stmt = $this->conn->prepare("
        UPDATE usuariosAppEstacion 
        SET recupero = 1, recover_date = NOW(), token_action = ?
        WHERE email = ?
    ");
    $stmt->bind_param("ss", $token_action, $email);
    return $stmt->execute();
}

public function obtenerPorTokenAction($token_action) {
    $stmt = $this->conn->prepare("SELECT * FROM usuariosAppEstacion WHERE token_action = ?");
    $stmt->bind_param("s", $token_action);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function restablecerPassword($id, $hashed_password) {
    $stmt = $this->conn->prepare("
        UPDATE usuariosAppEstacion 
        SET contrasena = ?, token_action = NULL, bloqueado = 0, recupero = 0 
        WHERE id = ?
    ");
    $stmt->bind_param("si", $hashed_password, $id);
    return $stmt->execute();
}

public function limpiarTokenYDesactivar($id) {
    $sql = "UPDATE usuariosAppEstacion 
            SET token_action = NULL, recupero = 0, bloqueado = 0 
            WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$id]);
}


    public function obtenerPorToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM usuariosAppEstacion WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function bloquearCuenta($id) {
        $stmt = $this->conn->prepare("
            UPDATE usuariosAppEstacion 
            SET bloqueado = 1 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function limpiarToken($id) {
        $stmt = $this->conn->prepare("
            UPDATE usuariosAppEstacion 
            SET token = NULL 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function bloquearCuentaPorToken($token) {
    $stmt = $this->conn->prepare("
        UPDATE usuariosAppEstacion
        SET bloqueado = 1
        WHERE token = ?
    ");
    $stmt->bind_param("s", $token);
    return $stmt->execute();
}

}
?>
