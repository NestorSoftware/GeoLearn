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
            <span class="navbar-brand">GeoLearn</span>
            <div class="navbar-user">
                <span>Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                <a href="/student/index">Salir del juego</a>
            </div>
        </nav>

        <!-- Cabecera del juego -->
        <div style="background:#fff; border-bottom:1px solid #dde3ea; padding:16px 32px;">
            <div style="max-width:800px; margin:0 auto; display:flex; align-items:center; gap:16px;">
                <div style="flex:1;">
                    <div style="font-size:0.85rem; color:#666; margin-bottom:6px;">
                        <?= htmlspecialchars($curso['titulo']) ?> —
                        Pregunta <span id="numero-pregunta">1</span> de
                        <span id="total-preguntas">?</span>
                    </div>
                    <div style="background:#eee; border-radius:4px; height:6px;">
                        <div id="barra-progreso"
                             style="background:var(--color-primary); height:6px; border-radius:4px; width:0%; transition:width 0.3s;">
                        </div>
                    </div>
                </div>
                <div style="text-align:center; min-width:60px;">
                    <div style="font-size:0.75rem; color:#666;">Tiempo</div>
                    <div id="temporizador"
                         style="font-size:1.8rem; font-weight:800; color:var(--color-primary); line-height:1;">
                        20
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor del juego -->
        <div id="juego-container" style="max-width:800px; margin:32px auto; padding:0 32px;">
            <div id="pregunta-container">
                <div style="text-align:center; padding:60px; color:#999;">
                    Cargando preguntas...
                </div>
            </div>

            <div style="margin-top:24px; text-align:right;">
                <button id="btn-siguiente"
                        onclick="siguientePregunta()"
                        class="btn btn-primary"
                        style="width:auto; padding:12px 32px;">
                    Siguiente
                </button>
            </div>
        </div>

        <!-- Contenedor del resultado (oculto hasta terminar) -->
        <div id="resultado-container"
             style="display:none; max-width:800px; margin:32px auto; padding:0 32px;">
        </div>

    </div>

    <script src="/public/js/game.js"></script>
</body>
</html>