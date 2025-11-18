<?php
class DetalleController {
    public function index() {
        // Obtener chipid desde la URL
        $chipid = $_GET['chipid'] ?? null;

        if (!$chipid) {
            die("No se especificó ninguna estación.");
        }

        // Traer todas las estaciones desde la API
        $apiUrl = "https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations";
        $json = file_get_contents($apiUrl);
        $data = json_decode($json, true);

        // Filtrar la estación con el chipid
        $estacion = null;
        foreach ($data as $e) {
            if ($e['chipid'] == $chipid) {
                $estacion = $e;
                break;
            }
        }

        if (!$estacion) {
            die("Estación no encontrada.");
        }

        // Cargar la vista
        require_once "views/detalle.php";
    }
}

