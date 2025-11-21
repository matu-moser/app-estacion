<?php
session_start();

// Cargar variables
require_once '.env.php';

// Página solicitada (por defecto login)
$page = $_GET['page'] ?? 'login';

/*
--------------------------------------------------------
 EXCEPCIONES PARA RUTAS ESPECIALES
--------------------------------------------------------
*/
if ($page === 'register') {
    require_once 'controllers/RegisterController.php';
    $controller = new RegisterController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->save();
    } else {
        $controller->index();
    }
    exit;
}

if ($page === 'login') {
    require_once 'controllers/LoginController.php';
    $controller = new LoginController();
    $controller->index();
    exit;
}

if ($page === 'recovery') {
    require_once 'controllers/RecoveryController.php';
    $controller = new RecoveryController();
    $controller->index();
    exit;
}

if ($page === 'reset') {
    require_once 'controllers/ResetController.php';
    $controller = new ResetController();
    $controller->index();
    exit;
}

if ($page == "blocked") {
    require_once "controllers/BlockedController.php";
    $controller = new BlockedController();
    $controller->index($_GET['token']);
    exit;
}

if ($page === 'administrator') {
    require_once 'controllers/AdministratorController.php';
    $controller = new AdministratorController();
    $controller->index();
    exit;
}

/*
--------------------------------------------------------
  ROUTER AUTOMÁTICO (FUNCIONA PARA TODAS LAS ESTACIONES)
--------------------------------------------------------
*/
$controllerName = ucfirst($page) . "Controller";
$controllerFile = "controllers/" . $controllerName . ".php";

if (file_exists($controllerFile)) {
    include $controllerFile;

    $controller = new $controllerName();
    if (method_exists($controller, 'index')) {
        $controller->index();
    } else {
        echo "Error: método index() no existe en $controllerName";
    }
} else {
    include "404.php";
}
?>
