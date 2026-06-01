# Aerolíneas — Proyecto FDB

Aplicación web de gestión para una aerolínea desarrollada con Laravel. Incluye interfaces para usuarios y un panel administrativo para operaciones, gestión de aeronaves, vuelos, tripulación y reservas.

## Tecnologías principales

- Laravel (Blade + Eloquent)
- Tailwind CSS
- Vite
- Node.js / npm

## Requisitos

- PHP 8.3+ (recomendado 8.2/8.3)
- Composer
- Node.js y npm
- Servidor local (XAMPP, Laragon, Valet, etc.)

## Configuración y puesta en marcha (rápida)

1. Clona el repositorio:

    git clone <url-del-repo>
    cd Aerolineas-Proyecto_FDB

2. Instala dependencias de PHP y frontend:

    composer install
    npm install

3. Copia el entorno y genera la clave de la app:

    copy .env.example .env         (Windows)
    php artisan key:generate

4. Configura la base de datos en `.env` y ejecuta migraciones y seeders:

    php artisan migrate --seed

5. Ejecuta la app en desarrollo:

    php artisan serve
    npm run dev


## Comandos útiles

- Limpiar y recompilar vistas: `php artisan view:clear && php artisan view:cache`
- Cache de configuración y rutas: `php artisan config:cache && php artisan route:cache`
- Optimizar autoload (si carga lento): `composer dump-autoload`

- Ejecutar pruebas (si hubiera pruebas): `php artisan test` o `vendor/bin/phpunit`
- Crear symlink de almacenamiento (si es necesario): `php artisan storage:link`

## Frontend

- Desarrollo: `npm run dev`
- Build producción: `npm run build`

## Vistas y rutas relevantes

- Vistas públicas (clientes): `resources/views/client/`
- Panel administrativo: `resources/views/internal/`
- Rutas principales: revisa `routes/web.php` para nombres de rutas como `vuelos.lista`, `reservas.store` y los prefijos `admin.*`.

## Notas de desarrollo

- Las acciones de administración están protegidas mediante comprobaciones de rol en los controladores (p. ej. `abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403)`).
- Evita modificar modelos centrales sin coordinación; muchos controladores y vistas asumen relaciones y campos concretos.

## Problemas comunes y soluciones

- Si ves errores de permisos en `storage/` o `bootstrap/cache`, ajusta permisos para el usuario del servidor web.
- Si cambias vistas y no ves cambios, ejecuta `php artisan view:clear` y recarga Vite.

## Contribuir

1. Crea una rama por feature: `git checkout -b feat/mi-cambio`
2. Haz commits claros y atómicos
3. Abre un pull request describiendo el cambio y cómo probarlo

