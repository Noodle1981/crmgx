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



Opción 1: Usar una cuenta de Gmail
Sí, es técnicamente factible, pero no es recomendable para un entorno de producción por varias razones importantes:

Seguridad y Autenticación: Para que funcione, tendrías que habilitar la autenticación de dos factores (2FA) en tu cuenta de Google y luego generar una "Contraseña de Aplicación". Ya no puedes usar tu contraseña normal directamente. Google considera la conexión SMTP desde una aplicación externa como un método "menos seguro".
Límites de Envío: Gmail impone límites estrictos de envío (alrededor de 500 correos en un período de 24 horas para una cuenta estándar). Si usas las "Secuencias" del CRM para enviar correos a muchos contactos, alcanzarás este límite rápidamente y Google podría bloquear tu cuenta temporalmente.
Reputación y Entregabilidad: Enviar correos masivos o de negocio desde una dirección @gmail.com tiene más probabilidades de ser marcado como spam por los filtros de correo de tus destinatarios. Se ve menos profesional y daña la reputación de tu dominio.
¿Cuándo usar Gmail? Es una opción viable solo para pruebas iniciales o si el volumen de correos es extremadamente bajo (ej. solo notificaciones para ti mismo).

Opción 2: Usar un correo de tu propio dominio (La opción recomendada)
Esta es la práctica profesional y la solución robusta y escalable. Sin embargo, no se trata solo de usar el servidor de correo que te da tu hosting. La mejor manera de hacerlo es a través de un servicio de correo transaccional.

¿Qué es un servicio de correo transaccional?

Son plataformas especializadas en enviar correos desde aplicaciones como la tuya. Se encargan de que tus correos lleguen a la bandeja de entrada y no a la de spam.

Ejemplos de servicios populares:

SendGrid (muy popular, tiene un plan gratuito generoso)
Postmark (conocido por su excelente entregabilidad)
Amazon SES (potente y económico, pero más técnico de configurar)
Mailgun
Pasos para la configuración ideal:

Registras tu dominio (ej. miempresa.com).
Te das de alta en un servicio como SendGrid.
Verificas tu dominio con ellos (te pedirán añadir unos registros DNS como SPF y DKIM, lo cual es fundamental para la reputación de tu correo).
El servicio te proporcionará las credenciales SMTP (host, puerto, usuario y contraseña) que necesitas.
Esas son las credenciales que debes introducir en la pantalla de "Settings" de tu CRM.
Conclusión y Recomendación
Característica	Gmail	Servicio Transaccional (ej. SendGrid)
Ideal para	Pruebas, desarrollo, uso personal	Producción, cualquier uso profesional
Límites	Muy bajos (~500/día)	Muy altos (miles o millones)
Entregabilidad	Baja (riesgo de spam)	Muy Alta
Profesionalismo	Bajo	Alto
Configuración	Rápida pero con advertencias	Requiere verificar dominio (más seguro)
Respuesta corta: Para el correcto funcionamiento y la reputación de tu negocio, deberías usar un servicio de correo transaccional con un correo de tu propio dominio. Usa Gmail solo si estás haciendo pruebas internas.