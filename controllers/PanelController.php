<?php
class PanelController {
    public function index() {
        $apiUrl = "https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations";
        $json = file_get_contents($apiUrl); // obtiene los datos
        $data = json_decode($json, true); // convierte a array PHP

        require_once "views/panel.php";
    }
}

