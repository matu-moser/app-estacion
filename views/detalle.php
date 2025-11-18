<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de estación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            text-align: center;
        }
        .titulo {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .ubicacion {
            font-size: 1.2em;
            color: #555;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="titulo"><?= htmlspecialchars($estacion['apodo']) ?></div>
    <div class="ubicacion">📍 <?= htmlspecialchars($estacion['ubicacion']) ?></div>
    <a href="index.php?page=panel">← Volver al panel</a>
</div>

</body>
</html>

