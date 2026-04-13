<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoLearn — Iniciar sesión</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>GeoLearn</h1>
                <p>Plataforma educativa de Historia y Geografía</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($exito)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($exito) ?>
                </div>
            <?php endif; ?>

            <form action="/auth/procesarLogin" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="tu@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                    >
                </div>
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </form>

            <p class="auth-footer">
                ¿No tienes cuenta? 
                <a href="/auth/registro">Regístrate aquí