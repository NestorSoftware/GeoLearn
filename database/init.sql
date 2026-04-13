-- ================================================
-- GeoLearn - Script de inicialización de la BD
-- PostgreSQL 16
-- ================================================

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id           SERIAL PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    email        VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol          VARCHAR(20) NOT NULL DEFAULT 'alumno'
                 CHECK (rol IN ('alumno', 'profesor', 'admin')),
    activo       BOOLEAN NOT NULL DEFAULT true,
    creado_en    TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Tabla de cursos
CREATE TABLE IF NOT EXISTS cursos (
    id           SERIAL PRIMARY KEY,
    titulo       VARCHAR(200) NOT NULL,
    descripcion  TEXT,
    categoria    VARCHAR(50) NOT NULL
                 CHECK (categoria IN ('Historia', 'Geografia', 'Mixto')),
    profesor_id  INTEGER REFERENCES usuarios(id),
    publicado    BOOLEAN NOT NULL DEFAULT false,
    creado_en    TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Tabla de lecciones
CREATE TABLE IF NOT EXISTS lecciones (
    id             SERIAL PRIMARY KEY,
    curso_id       INTEGER NOT NULL REFERENCES cursos(id) ON DELETE CASCADE,
    titulo         VARCHAR(200) NOT NULL,
    contenido      TEXT,
    orden          INTEGER NOT NULL DEFAULT 1,
    multimedia_url VARCHAR(500)
);

-- Tabla de preguntas
CREATE TABLE IF NOT EXISTS preguntas (
    id                 SERIAL PRIMARY KEY,
    curso_id           INTEGER NOT NULL REFERENCES cursos(id) ON DELETE CASCADE,
    enunciado          TEXT NOT NULL,
    opcion_a           VARCHAR(300) NOT NULL,
    opcion_b           VARCHAR(300) NOT NULL,
    opcion_c           VARCHAR(300) NOT NULL,
    opcion_d           VARCHAR(300) NOT NULL,
    respuesta_correcta CHAR(1) NOT NULL CHECK (respuesta_correcta IN ('a','b','c','d')),
    dificultad         VARCHAR(10) NOT NULL DEFAULT 'media'
                       CHECK (dificultad IN ('facil','media','dificil'))
);

-- Tabla de partidas
CREATE TABLE IF NOT EXISTS partidas (
    id               SERIAL PRIMARY KEY,
    usuario_id       INTEGER NOT NULL REFERENCES usuarios(id),
    curso_id         INTEGER NOT NULL REFERENCES cursos(id),
    puntuacion       INTEGER NOT NULL DEFAULT 0,
    total_preguntas  INTEGER NOT NULL,
    correctas        INTEGER NOT NULL DEFAULT 0,
    completada       BOOLEAN NOT NULL DEFAULT false,
    jugada_en        TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Tabla de matrículas
CREATE TABLE IF NOT EXISTS matriculas (
    id             SERIAL PRIMARY KEY,
    usuario_id     INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    curso_id       INTEGER NOT NULL REFERENCES cursos(id) ON DELETE CASCADE,
    matriculado_en TIMESTAMPTZ NOT NULL DEFAULT now(),
    progreso_pct   INTEGER NOT NULL DEFAULT 0
                   CHECK (progreso_pct BETWEEN 0 AND 100)
);

-- Usuario administrador por defecto (contraseña: admin1234)
INSERT INTO usuarios (nombre, email, password_hash, rol)
VALUES (
    'Administrador',
    'admin@geolearn.com',
    '$2y$10$l4XlPWOk9PJ6rg7ve23l3uImOZJM.Lvdk51OKytoPOhntE1mquasu',
    'admin'
) ON CONFLICT (email) DO NOTHING;