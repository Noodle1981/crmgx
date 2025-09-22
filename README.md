# CRMGX - Sistema de CRM con Laravel

CRMGX es un sistema de Gestión de Relaciones con el Cliente (CRM) robusto y moderno, desarrollado con el framework Laravel. Está diseñado para ayudar a equipos de ventas a gestionar prospectos, oportunidades de venta, clientes y sus actividades relacionadas de una manera eficiente.

## Características Principales

- **Gestión de Entidades:** CRUD completo para Clientes, Contactos, Prospectos (Leads) y Oportunidades de Venta (Deals).
- **Pipeline de Ventas:** Interfaz visual para arrastrar y soltar oportunidades a través de las diferentes etapas del embudo de ventas.
- **Secuencias de Comunicación:** Creación de secuencias de pasos para estandarizar el contacto con prospectos y clientes.
- **Calendario de Actividades:** Una vista de calendario, impulsada por FullCalendar, que muestra las tareas pendientes y las fechas de cierre de oportunidades, permitiendo una visión clara de la carga de trabajo.
- **Configuración de Correo por UI:** Permite a los administradores configurar los detalles del servidor de correo (SMTP) directamente desde la interfaz de usuario, sin necesidad de modificar archivos `.env`.
- **Reportes de Ventas:** Sección de informes para analizar el rendimiento de las ventas.
- **API RESTful:** API que utiliza Laravel Sanctum para la autenticación, permitiendo la integración con otras aplicaciones.

## Stack Tecnológico

- **Backend:** Laravel 11, PHP 8.2
- **Frontend:** Vite, Tailwind CSS, Alpine.js, FullCalendar.io
- **Base de Datos:** Preparado para MySQL, PostgreSQL. Usa SQLite para desarrollo.
- **Autenticación:** Laravel Breeze (Blade)

## Guía de Instalación

Sigue estos pasos para tener una copia local del proyecto funcionando.

1.  **Clonar el repositorio**
    ```bash
    git clone <URL-DEL-REPOSITORIO>
    cd crmcx
    ```

2.  **Instalar dependencias**
    ```bash
    composer install
    npm install
    ```

3.  **Configurar el entorno**
    Copia el archivo `.env.example` y genera la clave de la aplicación.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Configurar la base de datos**
    Abre el archivo `.env` y configura los parámetros de conexión a tu base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

5.  **Ejecutar migraciones y seeders**
    Esto creará la estructura de la base de datos y la llenará con datos de prueba.
    ```bash
    php artisan migrate --seed
    ```

6.  **Lanzar la aplicación**
    Este comando ejecutará el servidor de PHP y el compilador de Vite simultáneamente.
    ```bash
    npm run dev
    ```
    La aplicación estará disponible en `http://localhost:8000`.

## Pruebas

Para ejecutar el set de pruebas automatizadas, utiliza el siguiente comando:

```bash
php artisan test
```

## Estado de la API REST

La API REST del proyecto utiliza autenticación vía Sanctum. A continuación se detalla el estado actual de los endpoints.

- **Recursos con API CRUD Completa:** `/api/clients`, `/api/contacts`, `/api/leads`, `/api/deals`, `/api/tasks`.
- **Recursos con API Incompleta o Faltante:** `Activities`, `Sequences`, `Sequence Steps & Enrollments`.

## Roadmap de Futuras Funcionalidades

- **Envío de Correos Automáticos:** Integración completa en el módulo de Secuencias.
- **Calendario Interactivo:** Añadir funcionalidad de arrastrar y soltar para actualizar fechas.
- **Integración con Google Calendar:** Para sincronizar tareas y eventos del CRM con el calendario del usuario.
- **Notificaciones y Recordatorios:** Sistema de alertas para tareas y eventos próximos.
- **Roles y Permisos de Usuario:** Para definir diferentes niveles de acceso (Agente, Manager, Administrador).

## Guía de Despliegue (Deploy)

Para desplegar esta aplicación a un servidor de producción, sigue estos pasos generales:

1.  **Instalar dependencias de producción:**
    ```bash
    composer install --optimize-autoloader --no-dev
    ```

2.  **Construir los assets de frontend:**
    ```bash
    npm install
    npm run build
    ```

3.  **Configurar el `.env` de producción:**
    Asegúrate de que `APP_ENV=production` y `APP_DEBUG=false`.

4.  **Ejecutar migraciones en producción:**
    ```bash
    php artisan migrate --force
    ```

5.  **Optimizar la configuración:**
    Para mejorar el rendimiento, cachea la configuración y las rutas.
    ```bash
    php artisan config:cache
    php artisan route:cache
    ```

6.  **Configuración del Servidor Web (Nginx/Apache):**
    Asegúrate de que el "document root" de tu servidor web apunte al directorio `/public` del proyecto.
