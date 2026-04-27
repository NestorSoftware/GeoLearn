<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoLearn — Añadir pregunta</title>
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
            <h2>Añadir pregunta — <?= htmlspecialchars($curso['titulo']) ?></h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card" style="max-width:660px;">
                <form action="/course/procesarPregunta" method="POST">
                    <input type="hidden" name="curso_id" value="<?= $curso['id'] ?>">

                    <div class="form-group">
                        <label for="enunciado">Enunciado de la pregunta</label>
                        <textarea id="enunciado" name="enunciado" rows="3"
                            placeholder="Escribe aquí la pregunta..."
                            style="width:100%; padding:12px 16px; border:2px solid #dde3ea; border-radius:8px; font-size:0.95rem; font-family:inherit; resize:vertical;"
                            required><?= htmlspecialchars($_POST['enunciado'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="opcion_a">Opción A</label>
                        <input type="text" id="opcion_a" name="opcion_a"
                            placeholder="Opción A"
                            value="<?= htmlspecialchars($_POST['opcion_a'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="opcion_b">Opción B</label>
                        <input type="text" id="opcion_b" name="opcion_b"
                            placeholder="Opción B"
                            value="<?= htmlspecialchars($_POST['opcion_b'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="opcion_c">Opción C</label>
                        <input type="text" id="opcion_c" name="opcion_c"
                            placeholder="Opción C"
                            value="<?= htmlspecialchars($_POST['opcion_c'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="opcion_d">Opción D</label>
                        <input type="text" id="opcion_d" name="opcion_d"
                            placeholder="Opción D"
                            value="<?= htmlspecialchars($_POST['opcion_d'] ?? '') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="respuesta_correcta">Respuesta correcta</label>
                        <select id="respuesta_correcta" name="respuesta_correcta"
                            style="width:100%; padding:12px 16px; border:2px solid #dde3ea; border-radius:8px; font-size:0.95rem; background:white;">
                            <option value="a" <?= ($_POST['respuesta_correcta'] ?? '') === 'a' ? 'selected' : '' ?>>A</option>
                            <option value="b" <?= ($_POST['respuesta_correcta'] ?? '') === 'b' ? 'selected' : '' ?>>B</option>
                            <option value="c" <?= ($_POST['respuesta_correcta'] ?? '') === 'c' ? 'selected' : '' ?>>C</option>
                            <option value="d" <?= ($_POST['respuesta_correcta'] ?? '') === 'd' ? 'selected' : '' ?>>D</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dificultad">Dificultad</label>
                        <select id="dificultad" name="dificultad"
                            style="width:100%; padding:12px 16px; border:2px solid #dde3ea; border-radius:8px; font-size:0.95rem; background:white;">
                            <option value="facil"   <?= ($_POST['dificultad'] ?? '') === 'facil'   ? 'selected' : '' ?>>Fácil</option>
                            <option value="media"   <?= ($_POST['dificultad'] ?? 'media') === 'media' ? 'selected' : '' ?>>Media</option>
                            <option value="dificil" <?= ($_POST['dificultad'] ?? '') === 'dificil' ? 'selected' : '' ?>>Difícil</option>
                        </select>
                    </div>

                    <div style="display:flex; gap:12px;">
                        <button type="submit" class="btn btn-primary">Añadir pregunta</button>
                        <a href="/course/verCurso?id=<?= $curso['id'] ?>" class="btn btn-danger">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>