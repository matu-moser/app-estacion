<?php
// Si viene un mensaje de error desde el process
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Iniciar sesión</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="index.php?page=login" method="POST">
        <label>Email</label>
        <input type="text" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <input type="hidden" name="chipid" value="<?php echo htmlspecialchars($chipid); ?>">

        <button type="submit">Acceder</button>

        <a href="index.php?page=recovery">¿Olvidaste tu contraseña?</a>
        <a href="index.php?page=register">¿No tienes una cuenta? Registrarse</a>
    </form>
</body>
</html>
