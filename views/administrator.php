<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
        .top { display: flex; justify-content: space-between; align-items: center; }
        button { padding: 10px 20px; margin: 5px 0; cursor: pointer; }
        .counters { margin-top: 20px; }
        .map-button { margin-top: 15px; }
    </style>
</head>
<body>

<div class="top">
    <h1>Panel Administrador</h1>
    <a href="logout.php"><button>Cerrar sesión</button></a>
</div>

<!-- Botón Mapa debajo del Cerrar sesión -->
<div class="map-button">
    <a href="index.php?page=map"><button>Mapa de clientes</button></a>
</div>

<div class="counters">
    <p>Usuarios registrados: <?= $usuariosRegistrados ?></p>
    <p>Cantidad de clientes: <?= $clientes ?></p>
</div>

</body>
</html>
