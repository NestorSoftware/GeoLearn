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

        <!-- Barra de navegación -->
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
                    <div class="card-value">
                        <?= count($usuarios ?? []) ?>
                    </div>
                </div>
                <div class="card">
                    <h3>Cursos creados</h3>
                    <div class="card-value">0</div>
                </div>
                <div class="card">
                    <h3>Partidas jugadas</h3>
                    <div class="card-value">0</div>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="table-container">
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

        </div>
    </div>
</body>
</html>