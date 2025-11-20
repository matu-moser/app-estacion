<form method="POST">
    <label>Ingresa tu email:</label>
    <input type="email" name="email" required>
    <button type="submit">Enviar enlace</button>
</form>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color:green;"><?= $success ?></p>
    <p>Revisa tu correo para continuar con el proceso.</p>
<?php endif; ?>
