<?php
// Cargar variables de entorno
require_once 'env.php';

session_start();

$page = $_GET['page'] ?? 'login';

// Construimos el nombre de la clase y del archivo del controlador
$controllerName = ucfirst($page) . "Controller";
$controllerFile = "controllers/" . $controllerName . ".php";

if (file_exists($controllerFile)) {
    include $controllerFile;

    // Instanciamos el controlador y llamamos al método index()
    $controller = new $controllerName();
    if (method_exists($controller, 'index')) {
        $controller->index();
    } else {
        echo "Error: el método index() no existe en $controllerName";
    }

} else {
    include 'views/404.php';
}
?>

