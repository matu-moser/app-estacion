<?php

class LandingController
{
    public function index()
    {
        // Carga directamente la vista landing
        include __DIR__ . '/../views/landing.php';
    }
}

$controller = new LandingController();
$controller->index();

