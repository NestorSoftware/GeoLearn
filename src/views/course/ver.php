<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoLearn — <?= htmlspecialchars($curso['titulo']) ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="dashboard">

        <nav class="navbar">
            <span class="navbar-brand">GeoLearn — Profesor</span>
            <div class="navbar-user">
                <span>Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                <a href="/course/index">Mis cursos</a>
                <a href="/auth/logout">Cerrar sesión</a>
            </div>
        </nav>

        <div class="dashboard-content">

            <!-- Cabecera del curso -->
            <div class="card" style="margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:16px;">
                    <div>
                        <div style="display:flex; gap:8px; margin-bottom:10px;">
                            <span class="badge badge-<?= strtolower($curso['categoria']) ?>">
                                <?= htmlspecialchars($curso['categoria']) ?>
                            </span>
                            <span class="badge <?= $curso['publicado'] ? 'badge-publicado' : 'badge-borrador' ?>">
                                <?= $curso['publicado'] ? 'Publicado' : 'Borrador' ?>
                            </span>
                        </div>
                        <h2 style="font-size:1.5rem; margin-bottom:8px;">
                            <?= htmlspecialchars($curso['titulo']) ?>
                        </h2>
                        <p style="color:#666;">
                            <?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?>
                        </p>
                    </div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <a href="/course/editar?id=<?= $curso['id'] ?>" class="btn btn-success" style="width:auto; padding:10px 20px;">Editar</a>
                        <a href="/course/togglePublicado?id=<?= $curso['id'] ?>" class="btn <?= $curso['publicado'] ? 'btn-danger' : 'btn-success' ?>" style="width:auto; padding:10px 20px;">
                            <?= $curso['publicado'] ? 'Despublicar' : 'Publicar' ?>
                        </a>
                        <a href="/course/eliminar?id=<?= $curso['id'] ?>" class="btn btn-danger" style="width:auto; padding:10px 20px;" onclick="return confirm('¿Eliminar este curso y todas sus preguntas?')">Eliminar</a>
                    </div>
                </div>
            </div>

            <!-- Preguntas -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 style="font-size:1.2rem;">
                    Preguntas (<?= count($preguntas) ?>)
                </h3>
                <a href="/course/addPregunta?curso_id=<?= $curso['id'] ?>" class="btn btn-primary" style="width:auto; padding:10px 20px;">
                    + Añadir pregunta
                </a>
            </div>

            <?php if (empty($preguntas)): ?>
                <div class="card" style="text-align:center; padding:32px; color:#999;">
                    No hay preguntas todavía. ¡Añade la primera!
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Enunciado</th>
                                <th>Respuesta correcta</th>
                                <th>Dificultad</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preguntas as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($p['enunciado']) ?></td>
                                    <td style="text-transform:uppercase; font-weight:700; color:#2E75B6;">
                                        <?= htmlspecialchars($p['respuesta_correcta']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($p['dificultad']) ?></td>
                                    <td>
                                        <a href="/course/eliminarPregunta?id=<?= $p['id'] ?>&curso_id=<?= $curso['id'] ?>"
                                           class="btn btn-danger"
                                           style="width:auto; padding:6px 12px; font-size:0.8rem;"
                                           onclick="return confirm('¿Eliminar esta pregunta?')">
                                           Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>