<?php
$host = 'mattprofe.com.ar';
$user = '10093';
$pass = 'buey.arce.guante';
$db = '10093';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
