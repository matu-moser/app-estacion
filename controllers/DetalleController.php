<?php

class DetalleController {

    public function index() {

         $apiUrl = "https://mattprofe.com.ar/proyectos/app-estacion/datos.php";
        // validar chipid
        if (!isset($_GET['chipid']) || empty($_GET['chipid'])) {
            echo "ChipID inválido";
            return;
        }

        $chipid = $_GET['chipid'];

        // carga la vista detalle.php
        require_once "views/detalle.php";
    }
}

