<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }
        .container {
            width: 350px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #0066cc;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }
        button:hover {
            background: #004a99;
        }
        p.error { color: red; }
    </style>
</head>
<body>

<div class="container">

    <h2>Restablecer contraseña</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nueva contraseña:</label>
        <input type="password" name="password" required>

        <label>Repetir contraseña:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Restablecer</button>
    </form>

</div>

</body>
</html>
