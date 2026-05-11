// ================================================
// GeoLearn - Lógica del juego (trivial)
// ================================================

const TIEMPO_POR_PREGUNTA = 20; // segundos por pregunta

let preguntas      = [];
let indiceActual   = 0;
let respuestas     = {};
let tiempoRestante = TIEMPO_POR_PREGUNTA;
let intervalo      = null;

// Elementos del DOM
const contenedorPregunta = document.getElementById('pregunta-container');
const indicadorNumero    = document.getElementById('numero-pregunta');
const indicadorTotal     = document.getElementById('total-preguntas');
const barraProgreso      = document.getElementById('barra-progreso');
const temporizador       = document.getElementById('temporizador');
const btnSiguiente       = document.getElementById('btn-siguiente');
const contenedorResultado = document.getElementById('resultado-container');
const contenedorJuego    = document.getElementById('juego-container');

// Arrancar el juego cargando las preguntas via fetch
async function iniciarJuego() {
    try {
        const response = await fetch('/game/getPreguntasApi');
        const data     = await response.json();

        if (data.error) {
            mostrarError(data.error);
            return;
        }

        preguntas = data;
        indicadorTotal.textContent = preguntas.length;
        mostrarPregunta(0);
    } catch (error) {
        mostrarError('Error al cargar las preguntas.');
    }
}

// Mostrar la pregunta actual
function mostrarPregunta(indice) {
    const p = preguntas[indice];
    indiceActual = indice;

    // Actualizar indicador
    indicadorNumero.textContent = indice + 1;

    // Actualizar barra de progreso
    const porcentaje = ((indice) / preguntas.length) * 100;
    barraProgreso.style.width = porcentaje + '%';

    // Renderizar pregunta y opciones
    contenedorPregunta.innerHTML = `
        <div class="pregunta-texto">${escaparHTML(p.enunciado)}</div>
        <div class="opciones-grid">
            ${['a','b','c','d'].map(letra => `
                <button 
                    class="opcion-btn" 
                    data-letra="${letra}"
                    onclick="seleccionarRespuesta('${p.id}', '${letra}', this)">
                    <span class="opcion-letra">${letra.toUpperCase()}</span>
                    <span class="opcion-texto">${escaparHTML(p['opcion_' + letra])}</span>
                </button>
            `).join('')}
        </div>
    `;

    // Restaurar respuesta si ya habia contestado esta pregunta
    if (respuestas[p.id]) {
        const btnSeleccionado = contenedorPregunta.querySelector(`[data-letra="${respuestas[p.id]}"]`);
        if (btnSeleccionado) btnSeleccionado.classList.add('seleccionada');
    }

    // Reiniciar temporizador
    reiniciarTemporizador();

    // Mostrar u ocultar boton siguiente
    btnSiguiente.textContent = (indice === preguntas.length - 1) ? 'Finalizar' : 'Siguiente';
}

// Seleccionar una respuesta
function seleccionarRespuesta(preguntaId, letra, btn) {
    // Guardar respuesta
    respuestas[preguntaId] = letra;

    // Marcar visualmente
    const botones = contenedorPregunta.querySelectorAll('.opcion-btn');
    botones.forEach(b => b.classList.remove('seleccionada'));
    btn.classList.add('seleccionada');
}

// Siguiente pregunta o finalizar
function siguientePregunta() {
    if (indiceActual < preguntas.length - 1) {
        mostrarPregunta(indiceActual + 1);
    } else {
        finalizarJuego();
    }
}

// Enviar respuestas al servidor y mostrar resultado
async function finalizarJuego() {
    detenerTemporizador();
    btnSiguiente.disabled = true;
    btnSiguiente.textContent = 'Enviando...';

    try {
        const response = await fetch('/game/enviarRespuestas', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ respuestas })
        });

        const data = await response.json();

        if (data.error) {
            mostrarError(data.error);
            return;
        }

        mostrarResultado(data);
    } catch (error) {
        mostrarError('Error al enviar las respuestas.');
    }
}

// Mostrar pantalla de resultado
function mostrarResultado(data) {
    contenedorJuego.style.display    = 'none';
    contenedorResultado.style.display = 'block';

    const porcentaje = data.puntuacion;
    const emoji      = porcentaje >= 80 ? '🏆' : porcentaje >= 50 ? '👍' : '💪';

    contenedorResultado.innerHTML = `
        <div class="resultado-card">
            <div class="resultado-emoji">${emoji}</div>
            <h2 class="resultado-titulo">Partida completada</h2>
            <div class="resultado-puntuacion">${porcentaje}<span>pts</span></div>
            <p class="resultado-detalle">${data.correctas} de ${data.total} respuestas correctas</p>

            <div class="resultado-tabla">
                ${data.resultados.map((r, i) => `
                    <div class="resultado-fila ${r.es_correcta ? 'correcta' : 'incorrecta'}">
                        <span class="resultado-num">${i + 1}</span>
                        <span class="resultado-enunciado">${escaparHTML(r.enunciado)}</span>
                        <span class="resultado-icono">${r.es_correcta ? '✓' : '✗'}</span>
                    </div>
                `).join('')}
            </div>

            <div class="resultado-acciones">
                <a href="/student/index" class="btn btn-primary" style="width:auto; padding:12px 32px;">
                    Volver al panel
                </a>
            </div>
        </div>
    `;
}

// Temporizador
function reiniciarTemporizador() {
    detenerTemporizador();
    tiempoRestante = TIEMPO_POR_PREGUNTA;
    actualizarTemporizador();

    intervalo = setInterval(() => {
        tiempoRestante--;
        actualizarTemporizador();

        if (tiempoRestante <= 0) {
            detenerTemporizador();
            // Tiempo agotado — pasar a siguiente sin respuesta
            setTimeout(() => siguientePregunta(), 500);
        }
    }, 1000);
}

function detenerTemporizador() {
    if (intervalo) {
        clearInterval(intervalo);
        intervalo = null;
    }
}

function actualizarTemporizador() {
    temporizador.textContent = tiempoRestante;
    temporizador.style.color = tiempoRestante <= 5 ? '#e53935' : 'var(--color-primary)';
}

// Escapar HTML para evitar XSS
function escaparHTML(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

// Mostrar error
function mostrarError(mensaje) {
    contenedorPregunta.innerHTML = `
        <div class="alert alert-error">${mensaje}</div>
        <a href="/student/index" class="btn btn-primary" style="width:auto;">Volver al panel</a>
    `;
}

// Arrancar cuando carga la pagina
document.addEventListener('DOMContentLoaded', iniciarJuego);