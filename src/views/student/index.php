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

        <!-- Barra de navegación -->
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
                    <h3>Cursos matriculado</h3>
                    <div class="card-value">0</div>
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

            <!-- Tabla de cursos disponibles -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Categoría</th>
                            <th>Progreso</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align:center; color:#999; padding:32px;">
                                No hay cursos disponibles todavía.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>