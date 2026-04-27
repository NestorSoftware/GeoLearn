<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoLearn — Editar curso</title>
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
            <h2>Editar curso</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card" style="max-width:600px;">
                <form action="/course/procesarEditar" method="POST">
                    <input type="hidden" name="id" value="<?= $curso['id'] ?>">

                    <div class="form-group">
                        <label for="titulo">Título del curso</label>
                        <input type="text" id="titulo" name="titulo"
                            value="<?= htmlspecialchars($_POST['titulo'] ?? $curso['titulo']) ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4"
                            style="width:100%; padding:12px 16px; border:2px solid #dde3ea; border-radius:8px; font-size:0.95rem; font-family:inherit; resize:vertical;"
                            ><?= htmlspecialchars($_POST['descripcion'] ?? $curso['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <select id="categoria" name="categoria"
                            style="width:100%; padding:12px 16px; border:2px solid #dde3ea; border-radius:8px; font-size:0.95rem; background:white;">
                            <option value="Historia"  <?= ($curso['categoria'] === 'Historia')  ? 'selected' : '' ?>>Historia</option>
                            <option value="Geografia" <?= ($curso['categoria'] === 'Geografia') ? 'selected' : '' ?>>Geografía</option>
                            <option value="Mixto"     <?= ($curso['categoria'] === 'Mixto')     ? 'selected' : '' ?>>Mixto</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:12px;">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <a href="/course/verCurso?id=<?= $curso['id'] ?>" class="btn btn-danger">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>