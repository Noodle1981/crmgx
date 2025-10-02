# CRMCX - Un CRM con Laravel

CRMCX es una aplicación web de Gestión de Relaciones con el Cliente (CRM) robusta y con todas las funciones, construida con el framework Laravel. Está diseñada para ayudar a equipos de ventas y negocios a gestionar leads, clientes, oportunidades de venta y tareas de manera eficiente. La aplicación cuenta con un panel de control intuitivo, un pipeline de ventas visual, y capacidades de automatización de marketing a través de secuencias.

## ✨ Características Principales

- **Gestión de Dashboard:** Panel de control centralizado con métricas clave y visualización del pipeline de ventas.
- **Gestión de Leads:**
  - CRUD completo para leads.
  - Conversión de leads a Clientes, Contactos y Oportunidades (Deals).
  - Actualización de estado de los leads.
- **Gestión de Clientes:**
  - CRUD completo para clientes.
  - Gestión de contactos asociados a cada cliente.
  - Registro de actividades (llamadas, reuniones, etc.) por cliente.
- **Gestión de Oportunidades (Deals):**
  - Pipeline de ventas estilo Kanban para arrastrar y soltar oportunidades entre etapas.
  - Marcar oportunidades como "ganadas" o "perdidas".
  - Creación de oportunidades asociadas a clientes.
- **Automatización con Secuencias:**
  - Creación de secuencias de seguimiento personalizadas (ej. emails y tareas).
  - Inscripción de contactos en secuencias para automatizar la comunicación.
- **Calendario y Gestión de Tareas:**
  - Un calendario integrado para visualizar tareas, eventos y plazos.
  - CRUD para tareas.
- **Reportes:**
  - Módulo de reportes de ventas para analizar el rendimiento.
- **Gestión de Perfil y Configuración:**
  - Los usuarios pueden gestionar su información de perfil.
  - Configuración de correo electrónico para la integración con las secuencias.

## 🚀 Stack Tecnológico

### Backend
- PHP 8.2
- Laravel 12
- Laravel Sanctum (Autenticación de API)
- Pest (Testing)

### Frontend
- Vite
- Tailwind CSS
- Alpine.js
- FullCalendar
- ApexCharts

### Base de Datos
- Compatible con MySQL, PostgreSQL, SQLite.

## 🛠️ Guía de Instalación

Sigue estos pasos para configurar el proyecto en tu entorno de desarrollo local.

1.  **Clonar el repositorio:**
    ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd crmcx
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Instalar dependencias de Node.js:**
    ```bash
    npm install
    ```

4.  **Configurar el entorno:**
    - Copia el archivo de ejemplo `.env.example` a `.env`.
    ```bash
    copy .env.example .env
    ```
    - Genera la clave de la aplicación.
    ```bash
    php artisan key:generate
    ```

5.  **Configurar la base de datos:**
    - Abre el archivo `.env` y configura los detalles de tu base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
    - Ejecuta las migraciones y los seeders para poblar la base de datos con datos iniciales (como las etapas del pipeline).
    ```bash
    php artisan migrate --seed
    ```

## ▶️ Ejecución de la Aplicación

Para iniciar la aplicación, puedes usar el script `dev` incluido en `composer.json`, que ejecuta simultáneamente el servidor de PHP, el listener de la cola, el logger de `pail` y el servidor de Vite para el frontend.

```bash
composer run dev
```

Una vez ejecutado, la aplicación estará disponible en `http://127.0.0.1:8000` o la URL que `artisan serve` indique.