<?php
require_once 'config/db.php';

header('Content-Type: application/json');

if (isset($_GET['list-clients-location'])) {
    // Seleccionamos ip, latitud, longitud y cantidad de accesos
    $sql = "SELECT ip, latitud, longitud, COUNT(*) as accesos 
            FROM tracker 
            GROUP BY ip, latitud, longitud";
    
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'ip' => $row['ip'],
                'latitud' => $row['latitud'],
                'longitud' => $row['longitud'],
                'accesos' => (int)$row['accesos']
            ];
        }
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
} else {
    echo json_encode(['error' => 'Parámetro inválido']);
}
?>
