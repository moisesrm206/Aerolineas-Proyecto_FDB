# Aerolíneas Proyecto FDB

Sistema web de gestión para una aerolínea desarrollado con Laravel. El proyecto está orientado a una landing page responsiva y a la administración de operaciones internas.

## Descripción

La aplicación está pensada para centralizar información y procesos de la aerolínea en cuatro módulos principales:

- Pasajeros y clientes
- Operaciones de vuelo
- Flotilla
- Personal

## Tecnologías

- Laravel
- Blade
- Tailwind CSS
- Vite
- Node.js y npm

## Requisitos

- PHP compatible con Laravel (Usando laragon ó xampp)
    -Versiones Compatibles: 8.2, 8.3
- Composer (Instalado por defecto con laragon, requiere descarga si se usa xammp)
- Node.js
- npm
- Servidor local como Laragon, XAMPP o similar

## Instalación

1. Clonar el repositorio.
2. Instalar dependencias de PHP con Composer.
3. Instalar dependencias de frontend con npm.
4. Copiar el archivo `.env.example` a `.env`. (si no existe duplica '.env.example') 
5. Generar la clave de la aplicación.
6. Configurar la base de datos en el archivo `.env`.

## Ejecución en local

- Levantar el servidor de Laravel con `php artisan serve` (Si no se tiene laragon, o para usar ngrok y poder visualizar en mobiles).
- Ejecutar Vite en modo desarrollo con `npm run dev`.
- Si necesitas generar assets para producción, usar `npm run build`.

## Base de datos

El nombre definitivo de la base de datos aún está pendiente, ya que será definido mas tarde del proyecto.

Cuando se confirme, se deberá actualizar el archivo `.env` con estos datos:

- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME` 
- `DB_PASSWORD`

## Estructura general

- `app/`: lógica principal de la aplicación
- `resources/views/`: vistas Blade
- `resources/css/`: estilos de la interfaz
- `resources/js/`: scripts del frontend
- `routes/`: rutas de la aplicación
- `database/`: migraciones, seeders y factories

## Notas

- La interfaz usa Tailwind CSS.
- El proyecto depende de Vite para compilar los assets del frontend.
- Antes de ejecutar la vista en producción, es necesario generar el build de frontend.

## Pendientes

- Definir el nombre de la base de datos.
- Crear los módulos funcionales de la plataforma.
- Conectar la landing page con las rutas reales del sistema.
- Cambiar el diseño de la landing page
