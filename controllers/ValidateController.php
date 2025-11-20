<?php
require '../config/db.php';

if (isset($_GET['token_action'])) {
    $token_action = $_GET['token_action'];

    $stmt = $conn->prepare("SELECT id, activo FROM usuariosAppEstacion WHERE token_action = ?");
    $stmt->bind_param("s", $token_action);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['activo'] == 0) {
            $update = $conn->prepare("UPDATE usuariosAppEstacion SET activo = 1 WHERE token_action = ?");
            $update->bind_param("s", $token_action);
            $update->execute();
            echo "<h2>Cuenta activada exitosamente ✅</h2>";
        } else {
            echo "<h2>Tu cuenta ya está activada.</h2>";
        }
    } else {
        echo "<h2>Token inválido ❌</h2>";
    }
}
?>
