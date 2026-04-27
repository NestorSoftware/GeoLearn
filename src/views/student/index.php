<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoLearn — Mi panel</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="dashboard">

        <nav class="navbar">
            <span class="navbar-brand">GeoLearn</span>
            <div class="navbar-user">
                <span>Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                <a href="/auth/logout">Cerrar sesión</a>
            </div>
        </nav>

        <div class="dashboard-content">
            <h2>Mi panel de aprendizaje</h2>

            <!-- Tarjetas de resumen -->
            <div class="cards-grid">
                <div class="card">
                    <h3>Cursos disponibles</h3>
                    <div class="card-value"><?= count($cursos) ?></div>
                </div>
                <div class="card">
                    <h3>Partidas jugadas</h3>
                    <div class="card-value">0</div>
                </div>
                <div class="card">
                    <h3>Mejor puntuación</h3>
                    <div class="card-value">—</div>
                </div>
            </div>

            <!-- Cursos disponibles -->
            <h3 style="font-size:1.2rem; margin-bottom:16px;">Cursos disponibles</h3>

            <?php if (empty($cursos)): ?>
                <div class="card" style="text-align:center; padding:32px; color:#999;">
                    No hay cursos publicados todavía.
                </div>
            <?php else: ?>
                <div class="cards-grid">
                    <?php foreach ($cursos as $c): ?>
                        <div class="card">
                            <div style="margin-bottom:10px;">
                                <span class="badge badge-<?= strtolower($c['categoria']) ?>">
                                    <?= htmlspecialchars($c['categoria']) ?>
                                </span>
                            </div>
                            <h3 style="font-size:1.1rem; margin-bottom:8px;">
                                <?= htmlspecialchars($c['titulo']) ?>
                            </h3>
                            <p style="color:#666; font-size:0.85rem; margin-bottom:16px;">
                                <?= htmlspecialchars($c['descripcion'] ?? 'Sin descripción') ?>
                            </p>
                            <p style="font-size:0.8rem; color:#999; margin-bottom:16px;">
                                Profesor: <?= htmlspecialchars($c['nombre_profesor'] ?? 'Desconocido') ?>
                            </p>
                            <a href="#" class="btn btn-primary" style="width:auto; padding:8px 20px; font-size:0.85rem;">
                                Jugar
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>