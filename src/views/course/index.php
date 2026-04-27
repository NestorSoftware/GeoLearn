<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoLearn — Mis cursos</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="dashboard">

        <nav class="navbar">
            <span class="navbar-brand">GeoLearn — Profesor</span>
            <div class="navbar-user">
                <span>Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                <a href="/course/crear">Nuevo curso</a>
                <a href="/auth/logout">Cerrar sesión</a>
            </div>
        </nav>

        <div class="dashboard-content">
            <h2>Mis cursos</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (empty($cursos)): ?>
                <div class="card" style="text-align:center; padding: 40px;">
                    <p style="color:#999; margin-bottom:20px;">No tienes cursos creados todavía.</p>
                    <a href="/course/crear" class="btn btn-primary" style="width:auto; padding: 12px 32px;">
                        Crear mi primer curso
                    </a>
                </div>
            <?php else: ?>
                <div class="cards-grid">
                    <?php foreach ($cursos as $c): ?>
                        <div class="card">
                            <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px;">
                                <span class="badge badge-<?= strtolower($c['categoria']) ?>">
                                    <?= htmlspecialchars($c['categoria']) ?>
                                </span>
                                <span class="badge <?= $c['publicado'] ? 'badge-publicado' : 'badge-borrador' ?>">
                                    <?= $c['publicado'] ? 'Publicado' : 'Borrador' ?>
                                </span>
                            </div>
                            <h3 style="font-size:1.1rem; margin-bottom:8px;">
                                <?= htmlspecialchars($c['titulo']) ?>
                            </h3>
                            <p style="color:#666; font-size:0.85rem; margin-bottom:16px;">
                                <?= htmlspecialchars($c['descripcion'] ?? 'Sin descripción') ?>
                            </p>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                <a href="/course/verCurso?id=<?= $c['id'] ?>" class="btn btn-primary" style="width:auto; padding:8px 16px; font-size:0.85rem;">Ver</a>
                                <a href="/course/editar?id=<?= $c['id'] ?>" class="btn btn-success" style="width:auto; padding:8px 16px; font-size:0.85rem;">Editar</a>
                                <a href="/course/togglePublicado?id=<?= $c['id'] ?>" class="btn <?= $c['publicado'] ? 'btn-danger' : 'btn-success' ?>" style="width:auto; padding:8px 16px; font-size:0.85rem;">
                                    <?= $c['publicado'] ? 'Despublicar' : 'Publicar' ?>
                                </a>
                                <a href="/course/eliminar?id=<?= $c['id'] ?>" class="btn btn-danger" style="width:auto; padding:8px 16px; font-size:0.85rem;" onclick="return confirm('¿Eliminar este curso?')">Eliminar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>