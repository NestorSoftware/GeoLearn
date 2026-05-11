<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoLearn — Panel de administración</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="dashboard">

        <nav class="navbar">
            <span class="navbar-brand">GeoLearn — Admin</span>
            <div class="navbar-user">
                <span>Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                <a href="/auth/logout">Cerrar sesión</a>
            </div>
        </nav>

        <div class="dashboard-content">
            <h2>Panel de administración</h2>

            <!-- Tarjetas de resumen -->
            <div class="cards-grid">
                <div class="card">
                    <h3>Usuarios registrados</h3>
                    <div class="card-value"><?= count($usuarios) ?></div>
                </div>
                <div class="card">
                    <h3>Cursos creados</h3>
                    <div class="card-value"><?= count($cursos) ?></div>
                </div>
                <div class="card">
                    <h3>Partidas jugadas</h3>
                    <div class="card-value"><?= count($partidas) ?></div>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <h3 style="font-size:1.2rem; margin-bottom:16px;">Usuarios</h3>
            <div class="table-container" style="margin-bottom:32px;">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registrado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#999; padding:32px;">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['rol']) ?></td>
                                    <td><?= $u['activo'] ? 'Activo' : 'Inactivo' ?></td>
                                    <td><?= date('d/m/Y', strtotime($u['creado_en'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tabla de cursos -->
            <h3 style="font-size:1.2rem; margin-bottom:16px;">Cursos</h3>
            <div class="table-container" style="margin-bottom:32px;">
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Profesor</th>
                            <th>Estado</th>
                            <th>Creado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cursos)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#999; padding:32px;">
                                    No hay cursos creados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cursos as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['titulo']) ?></td>
                                    <td><?= htmlspecialchars($c['categoria']) ?></td>
                                    <td><?= htmlspecialchars($c['nombre_profesor'] ?? 'Sin profesor') ?></td>
                                    <td><?= $c['publicado'] ? 'Publicado' : 'Borrador' ?></td>
                                    <td><?= date('d/m/Y', strtotime($c['creado_en'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tabla de partidas -->
            <h3 style="font-size:1.2rem; margin-bottom:16px;">Últimas partidas</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Curso</th>
                            <th>Puntuación</th>
                            <th>Correctas</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($partidas)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#999; padding:32px;">
                                    No hay partidas jugadas todavía.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($partidas as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['nombre_usuario']) ?></td>
                                    <td><?= htmlspecialchars($p['titulo_curso']) ?></td>
                                    <td><?= $p['puntuacion'] ?> pts</td>
                                    <td><?= $p['correctas'] ?>/<?= $p['total_preguntas'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($p['jugada_en'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>