<?php
session_start();
session_unset();  // limpia variables
session_destroy(); // destruye sesión
header("Location: index.php?page=login");
exit();
