<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, 
initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Registrarse</h1>

    <?php if (isset($error) && $error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success) && $success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="index.php?page=register" method="POST">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" 
required><br><br>

        <label for="confirm_password">Repetir Contraseña:</label><br>
        <input type="password" id="confirm_password" 
name="confirm_password" required><br><br>

        <button type="submit">Registrarse</button>
    </form>

    <br>
    <a href="index.php?page=login">¿Ya tienes cuenta? Iniciar sesión</a>
</body>
</html>