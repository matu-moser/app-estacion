<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de estaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .lista-estaciones {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            padding: 15px;
            width: 220px;
            text-align: center;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .titulo {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        .ubicacion {
            font-size: 0.9em;
            color: #555;
            margin-bottom: 10px;
        }
        .visitas {
            font-size: 0.85em;
            margin-bottom: 10px;
        }
        .btn-estacion {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-estacion:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<h1>Bienvenido a tu panel</h1>

    <p>Estás logueado como: <strong><?= htmlspecialchars($email) ?></strong></p>
    <p>ID de usuario: <strong><?= htmlspecialchars($idUsuario) ?></strong></p>

    <a href="logout.php">Cerrar sesión de <?= htmlspecialchars($idUsuario) ?></a>

<!-- Contenedor donde se mostrarán las estaciones -->
<div class="lista-estaciones">
<?php

// Si no hay sesión activa → volver al login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php?page=login");
    exit();
}

// Datos del usuario logueado
$email = $_SESSION['email'] ?? '';
$idUsuario = $_SESSION['idUsuario'] ?? '';

foreach ($data as $est): ?>
    <div class="card">
        <div class="titulo"><?= htmlspecialchars($est['apodo']) ?></div>
        <div class="ubicacion">📍 <?= 
htmlspecialchars($est['ubicacion']) ?></div>
        <div class="visitas">Visitas: <?= 
htmlspecialchars($est['visitas']) ?></div>
        <a class="btn-estacion" href="index.php?page=detalle&chipid=<?= 
urlencode($est['chipid']) ?>">Ver detalle/<?= 
urlencode($est['chipid']) ?></a>
    </div>
<?php endforeach; ?>
</div>

<!-- Template para clonar -->
<template id="tpl-estacion">
    <div class="card">
        <div class="titulo"></div>
        <div class="ubicacion"></div>
        <div class="visitas"></div>
        <button class="btn-estacion">Ver detalle</button>
    </div>
</template>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const API_URL = 
"https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations";
    const lista = document.getElementById("lista-estaciones");
    const template = document.getElementById("tpl-estacion");

    try {
        const res = await fetch(API_URL);
        const data = await res.json();

        lista.innerHTML = ""; // limpiar

        data.forEach(est => {
            const clone = template.content.cloneNode(true);

            clone.querySelector(".titulo").textContent = est.apodo;
            clone.querySelector(".ubicacion").textContent = "📍 " + 
est.ubicacion;
            clone.querySelector(".visitas").textContent = `Visitas: 
${est.visitas}`;

            
clone.querySelector(".btn-estacion").addEventListener("click", () => {
                window.location.href = `detalle/${est.chipid}`;
            });

            lista.appendChild(clone);
        });

    } catch (error) {
        console.error("Error al conectar a la API:", error);
        lista.innerHTML = `<p style="color:red;">Error al cargar las 
estaciones</p>`;
    }
});
</script>

</body>
</html>

