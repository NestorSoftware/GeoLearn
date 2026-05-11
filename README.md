# GeoLearn 🌍

Plataforma educativa de Historia y Geografía desarrollada como Proyecto Final del Ciclo Formativo de Grado Superior en Desarrollo de Aplicaciones Web (DAW).

## ¿Qué es GeoLearn?

GeoLearn es una aplicación web donde los alumnos aprenden Historia y Geografía mediante cuestionarios tipo trivial. Los profesores crean los cursos y las preguntas, y los alumnos juegan y ven su progreso en tiempo real.

## Tecnologías

- PHP 8.2
- PostgreSQL 16
- Apache (mod_rewrite)
- Docker + Docker Compose
- HTML5 + CSS3 + JavaScript (fetch API)
- Git + GitHub

## Arquitectura

La aplicación sigue el patrón MVC (Modelo-Vista-Controlador) desarrollado desde cero en PHP puro, sin frameworks externos.

## Requisitos

- Docker Desktop instalado y en ejecución
- Git

## Instalación y arranque

1. Clona el repositorio:
git clone https://github.com/NestorSoftware/GeoLearn.git
cd GeoLearn

2. Levanta los contenedores:
docker-compose up --build

3. Abre el navegador en:
http://localhost:8080

La base de datos se inicializa automáticamente con las tablas y un usuario administrador por defecto.

## Credenciales por defecto

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | admin@geolearn.com | admin1234 |

Para crear un profesor o alumno usa el formulario de registro o insértalo directamente desde el panel de administración.

## Estructura del proyecto
GeoLearn/
├── Dockerfile
├── docker-compose.yml
├── src/
│   ├── index.php          ← Router principal
│   ├── .htaccess          ← Redirige todo a index.php
│   ├── config/
│   │   └── database.php   ← Conexión PDO (Singleton)
│   ├── controllers/       ← AuthController, CourseController, GameController...
│   ├── models/            ← Usuario, Curso, Pregunta, Partida
│   ├── views/             ← Vistas PHP por rol (auth, student, course, admin)
│   └── public/
│       ├── css/style.css  ← Estilos de la aplicación
│       └── js/game.js     ← Lógica del trivial con fetch()
├── database/
│   └── init.sql           ← Crea las tablas al arrancar PostgreSQL
└── docs/
└── GeoLearn_Nestor.docx

## Roles de usuario

- **Administrador** — gestiona usuarios, ve estadísticas globales de cursos y partidas
- **Profesor** — crea y publica cursos con preguntas de opción múltiple
- **Alumno** — juega al trivial, ve su progreso y mejor puntuación

## Seguridad

- Contraseñas cifradas con bcrypt (`password_hash`)
- Consultas SQL con sentencias preparadas (PDO) para prevenir inyección SQL
- Salida HTML sanitizada con `htmlspecialchars` para prevenir XSS
- Rutas protegidas con verificación de sesión y rol

## Autor

Néstor Pérez Santos — DAW 2.º — Curso 2025-2026
[LinkedIn](https://www.linkedin.com/in/néstor-pérez-web)
