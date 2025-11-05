# 🚀 CRM HSE 4.0 & Deals - Grupo Xamanen

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)

**Sistema de Gestión de Relaciones con el Cliente (CRM) profesional**  
Desarrollado para EP Consultora & Grupo Xamanen

[Características](#-características-principales) • [Instalación](#-instalación) • [Roles](#-sistema-de-roles) • [Documentación](#-documentación)

</div>

---

## 📋 Descripción

CRM HSE 4.0 & Deals es una aplicación web robusta de gestión de relaciones con el cliente, construida con Laravel 11. Diseñada específicamente para equipos de ventas y consultoras especializadas en Higiene y Seguridad, permite gestionar leads, clientes, oportunidades de venta, actividades y automatización de seguimientos de manera eficiente.

El sistema implementa un **sistema de roles completo** que separa las funciones administrativas de las operativas, asegurando que cada usuario tenga acceso solo a las herramientas necesarias para su rol.


## ✨ Características Principales

### 👨‍💼 Panel de Administración
- **Dashboard Administrativo:** Vista global de estadísticas del sistema
- **Gestión de Usuarios:** CRUD completo con control de roles (Admin/User)
- **Métricas de Rendimiento:** 
  - Análisis de conversión de leads
  - Rendimiento por vendedor
  - Métricas de ventas con gráficos
  - Análisis de actividades
  - Exportación de datos a CSV
- **Configuración del Sistema:**
  - Configuración de email SMTP
  - Mantenimiento del sistema
  - Limpieza de caché y optimización
  - Gestión de respaldos de base de datos
- **Logs del Sistema:** Registro completo de actividades

### 👤 CRM de Usuario
- **Dashboard Personalizado:** 
  - Métricas individuales en tiempo real
  - Gráficos de pipeline personal
  - Actividades recientes
  - Tareas pendientes

- **Gestión de Leads:**
  - CRUD completo de prospectos
  - Estados: Nuevo → Contactado → Calificado → Convertido
  - Conversión automática a Cliente + Contacto + Deal
  - Sistema de recordatorios
  - Actualización rápida de estados

- **Gestión de Clientes:**
  - Información completa de empresas
  - Múltiples contactos por cliente
  - Gestión de establecimientos
  - Historial de actividades
  - Registro de interacciones (llamadas, reuniones, emails)

- **Pipeline de Ventas (Deals):**
  - Vista Kanban drag & drop
  - Etapas personalizables
  - Marcado de ganadas/perdidas
  - Valor total del pipeline
  - Probabilidad de cierre

- **Automatización con Secuencias:**
  - Creación de secuencias de seguimiento
  - Pasos configurables (email/tarea/espera)
  - Inscripción de contactos
  - Seguimiento de progreso
  - Notificaciones automáticas

- **Calendario Integrado:**
  - Vista mensual/semanal/diaria
  - Gestión de tareas y eventos
  - Recordatorios
  - Integración con FullCalendar

- **Reportes de Ventas:**
  - Análisis de rendimiento personal
  - Filtros por período
  - Métricas de conversión
  - Exportación de datos


## 🚀 Stack Tecnológico

### Backend
- **PHP** 8.2+
- **Laravel** 11.x
- **Base de Datos:** MySQL / PostgreSQL / SQLite compatible
- **Laravel Sanctum** - Autenticación API
- **Pest PHP** - Testing framework

### Frontend
- **Vite** - Build tool moderno
- **Tailwind CSS** 3.x - Framework CSS utility-first
- **Alpine.js** 3.x - Framework JavaScript ligero
- **FullCalendar** - Calendario interactivo
- **ApexCharts** - Gráficos y visualizaciones
- **Sortable.js** - Drag & drop para Kanban
- **Font Awesome** 6.x - Iconografía

### Herramientas de Desarrollo
- **Composer** - Gestión de dependencias PHP
- **NPM** - Gestión de dependencias JavaScript
- **Laravel Pint** - Estilo de código PHP
- **PostCSS** - Procesamiento CSS

---

## 🛠️ Instalación

### Requisitos Previos
```bash
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM o Yarn
- MySQL / PostgreSQL / SQLite
```

### Pasos de Instalación

#### 1. Clonar el repositorio
```bash
git clone https://github.com/Noodle1981/crmgx.git
cd crmcx
```

#### 2. Instalar dependencias de PHP
```bash
composer install
```

#### 3. Instalar dependencias de Node.js
```bash
npm install
```

#### 4. Configurar el entorno
```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

#### 5. Generar clave de aplicación
```bash
php artisan key:generate
```

#### 6. Configurar base de datos
Edita el archivo `.env` con tus credenciales:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crmcx
DB_USERNAME=root
DB_PASSWORD=
```

#### 7. Ejecutar migraciones y seeders
```bash
php artisan migrate --seed
```

Este comando creará:
- Tablas de la base de datos
- Etapas del pipeline (deal_stages)
- Usuario administrador por defecto
- Datos de prueba (opcional)

#### 8. Crear enlace simbólico para storage
```bash
php artisan storage:link
```

---

## ▶️ Ejecución de la Aplicación

### Desarrollo

#### Opción 1: Comando único (Recomendado)
```bash
composer run dev
```

Este comando ejecuta simultáneamente:
- Servidor Laravel (`php artisan serve`)
- Cola de trabajos (`php artisan queue:work`)
- Logger Pail (`php artisan pail`)
- Servidor Vite para assets (`npm run dev`)

#### Opción 2: Comandos separados
En diferentes terminales:

```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Compilación de assets
npm run dev

# Terminal 3: Cola de trabajos (para emails y notificaciones)
php artisan queue:work

# Terminal 4: Logs en tiempo real
php artisan pail
```

### Producción

```bash
# Compilar assets para producción
npm run build

# Optimizar aplicación
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar cola en background
php artisan queue:work --daemon
```

**Acceso a la aplicación:**  
`http://127.0.0.1:8000`

---

## 👥 Sistema de Roles

El sistema implementa **dos roles completamente separados** para asegurar la seguridad y claridad de funciones:

### 🛡️ Administrador (`is_admin = 1`)

**Acceso Exclusivo:**
- `/admin/dashboard` - Panel administrativo
- `/admin/users` - Gestión de usuarios
- `/admin/performance` - Métricas del equipo
- `/admin/settings/email` - Configuración de email
- `/admin/maintenance` - Mantenimiento del sistema
- `/admin/system-logs` - Logs de actividad

**Restricciones:**
- ❌ NO puede acceder al CRM operativo
- ❌ NO puede gestionar leads/clientes/deals
- Redirigido automáticamente a `/admin/dashboard` al iniciar sesión

**Credenciales por defecto:**
```
Email: admin@example.com
Password: password
```

### 💼 Usuario CRM (`is_admin = 0`)

**Acceso Exclusivo:**
- `/dashboard` - Dashboard personal
- `/leads` - Gestión de leads
- `/clients` - Gestión de clientes
- `/deals` - Pipeline de ventas
- `/calendar` - Calendario y tareas
- `/sequences` - Automatización
- `/reports` - Reportes personales

**Restricciones:**
- ❌ NO puede acceder al panel administrativo
- ❌ NO puede gestionar otros usuarios
- Redirigido automáticamente a `/dashboard` al iniciar sesión

**Credenciales de prueba:**
```
Email: user@example.com
Password: password
```

### 🔐 Middlewares

```php
// routes/web.php

// Rutas Admin (protegidas con middleware 'admin')
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Solo usuarios con is_admin = 1
});

// Rutas CRM (protegidas con middleware 'user')
Route::middleware(['auth', 'user'])->group(function () {
    // Solo usuarios con is_admin = 0
});
```

**Documentación completa:** Ver [ROLES.md](ROLES.md)

---


## 📁 Estructura del Proyecto

```
crmcx/
├── app/
│   ├── Console/
│   │   └── Commands/          # Comandos Artisan personalizados
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controladores del panel admin
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── PerformanceController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   └── MaintenanceController.php
│   │   │   ├── Auth/          # Autenticación
│   │   │   ├── ClientController.php
│   │   │   ├── LeadController.php
│   │   │   ├── DealController.php
│   │   │   ├── ContactController.php
│   │   │   ├── SequenceController.php
│   │   │   ├── TaskController.php
│   │   │   └── ...
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php     # Protege rutas admin
│   │   │   └── UserMiddleware.php      # Protege rutas CRM
│   │   └── Requests/          # Form Requests de validación
│   │       ├── UpdateUserRequest.php
│   │       ├── StoreClientRequest.php
│   │       └── ...
│   ├── Models/
│   │   ├── User.php
│   │   ├── Client.php
│   │   ├── Lead.php
│   │   ├── Deal.php
│   │   ├── Contact.php
│   │   ├── Activity.php
│   │   ├── Task.php
│   │   ├── Sequence.php
│   │   └── ...
│   ├── Notifications/         # Notificaciones del sistema
│   ├── Traits/
│   │   └── HasAdminCapabilities.php
│   └── Mail/
│       └── SequenceEmail.php
├── database/
│   ├── factories/             # Factories para testing
│   ├── migrations/            # Migraciones de BD
│   └── seeders/               # Seeders de datos iniciales
├── resources/
│   ├── css/
│   │   └── app.css            # Estilos con Tailwind
│   ├── js/
│   │   ├── app.js             # JavaScript principal
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/             # Vistas del panel admin
│       │   ├── dashboard.blade.php
│       │   ├── users/
│       │   ├── performance.blade.php
│       │   ├── maintenance.blade.php
│       │   └── logs.blade.php
│       ├── layouts/
│       │   ├── app.blade.php          # Layout CRM usuario
│       │   ├── admin.blade.php        # Layout admin
│       │   ├── navigation.blade.php   # Nav usuario
│       │   └── admin-navigation.blade.php  # Nav admin
│       ├── clients/           # Vistas de clientes
│       ├── leads/             # Vistas de leads
│       ├── deals/             # Vistas de deals
│       ├── calendar/          # Vista del calendario
│       ├── sequences/         # Vistas de secuencias
│       └── ...
├── routes/
│   ├── web.php               # Rutas web principales
│   ├── api.php               # Rutas API (futuro)
│   ├── auth.php              # Rutas de autenticación
│   └── console.php           # Comandos de consola
├── tests/                    # Tests con Pest PHP
├── public/
│   ├── img/                  # Imágenes públicas
│   └── build/                # Assets compilados (generado)
├── storage/                  # Archivos generados
├── .env.example              # Variables de entorno ejemplo
├── composer.json             # Dependencias PHP
├── package.json              # Dependencias JavaScript
├── tailwind.config.js        # Configuración Tailwind
├── vite.config.js            # Configuración Vite
├── README.md                 # Este archivo
└── ROLES.md                  # Documentación de roles
```

---

## 🎨 Características Técnicas

### Validación con Form Requests
```php
// app/Http/Requests/StoreClientRequest.php
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:clients,email',
        'phone' => 'required|string',
        'address' => 'nullable|string',
    ];
}
```

### Observers para Actividades
```php
// app/Observers/DealObserver.php
public function created(Deal $deal): void
{
    Activity::create([
        'type' => 'deal_created',
        'description' => "Deal '{$deal->title}' creado",
        'user_id' => auth()->id(),
    ]);
}
```

### Traits Reutilizables
```php
// app/Traits/HasAdminCapabilities.php
trait HasAdminCapabilities
{
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
    
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }
}
```

### Componentes Blade Reutilizables
```blade
{{-- resources/views/components/stat-card.blade.php --}}
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
        </div>
        <div class="p-3 bg-blue-100 rounded-full">
            <i class="fas {{ $icon }} text-blue-600"></i>
        </div>
    </div>
</div>
```

---

## 🔧 Configuración

### Email SMTP
Configura el envío de emails desde el panel admin o editando `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

**⚠️ Importante para Producción:**
- **NO uses Gmail** para envíos masivos
- Usa servicios profesionales: **SendGrid**, **Postmark**, **Amazon SES**, **Mailgun**
- Configura SPF, DKIM y DMARC para tu dominio

### Cola de Trabajos (Queue)
Para producción, configura un driver persistente:

```env
QUEUE_CONNECTION=database  # o redis
```

Ejecuta el worker:
```bash
php artisan queue:work --daemon
```

### Caché
Para mejor rendimiento en producción:

```env
CACHE_DRIVER=redis  # o memcached
SESSION_DRIVER=redis
```

---

## 🧪 Testing

El proyecto usa **Pest PHP** para testing:

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar con coverage
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter=UserTest
```

Estructura de tests:
```
tests/
├── Feature/           # Tests de integración
│   ├── Auth/
│   ├── Admin/
│   ├── ClientTest.php
│   ├── LeadTest.php
│   └── DealTest.php
├── Unit/              # Tests unitarios
└── Pest.php           # Configuración Pest
```

---

## 🚀 Despliegue en Producción

### Preparación

1. **Optimizar aplicación:**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

2. **Compilar assets:**
```bash
npm run build
```

3. **Configurar permisos:**
```bash
chmod -R 755 storage bootstrap/cache
```

4. **Variables de entorno:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
```

### Servidor Web

#### Apache (.htaccess)
El archivo `.htaccess` está incluido en `public/`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name tudominio.com;
    root /var/www/crmcx/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Mantenimiento

```bash
# Entrar en modo mantenimiento
php artisan down --secret="token-secreto"

# Salir de mantenimiento
php artisan up

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📊 Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema (Admin/CRM) |
| `clients` | Empresas clientes |
| `contacts` | Personas de contacto en clientes |
| `establishments` | Sucursales/establecimientos |
| `leads` | Prospectos sin convertir |
| `deals` | Oportunidades de venta |
| `deal_stages` | Etapas del pipeline |
| `activities` | Registro de actividades |
| `tasks` | Tareas y recordatorios |
| `sequences` | Secuencias de automatización |
| `sequence_steps` | Pasos de las secuencias |
| `sequence_enrollments` | Inscripciones en secuencias |
| `settings` | Configuraciones del sistema |

### Migraciones

```bash
# Crear nueva migración
php artisan make:migration create_table_name

# Ejecutar migraciones
php artisan migrate

# Rollback última migración
php artisan migrate:rollback

# Rollback todas y re-ejecutar
php artisan migrate:fresh --seed
```

---

## 🔒 Seguridad

### Implementaciones de Seguridad

✅ **CSRF Protection:** Tokens en todos los formularios  
✅ **XSS Protection:** Escape automático en Blade  
✅ **SQL Injection:** Eloquent ORM con prepared statements  
✅ **Autenticación:** Laravel Breeze con bcrypt  
✅ **Middleware de Roles:** Separación Admin/User  
✅ **Rate Limiting:** Limitación de requests  
✅ **HTTPS:** Forzado en producción  

### Mejores Prácticas

```env
# .env en producción
APP_DEBUG=false
APP_ENV=production

# Cambiar credenciales por defecto
DB_PASSWORD=contraseña-segura-aquí

# Usar HTTPS
FORCE_HTTPS=true
```

---

## 🤝 Contribución

Este es un proyecto privado para EP Consultora & Grupo Xamanen. Si eres parte del equipo de desarrollo:

1. Crea un branch para tu feature: `git checkout -b feature/nueva-funcionalidad`
2. Commit tus cambios: `git commit -m 'feat: añadir nueva funcionalidad'`
3. Push al branch: `git push origin feature/nueva-funcionalidad`
4. Crea un Pull Request

### Convenciones de Código

- Sigue **PSR-12** para PHP
- Usa **Laravel Pint**: `./vendor/bin/pint`
- Comenta código complejo
- Escribe tests para nuevas features

---

## 📚 Documentación Adicional

- [ROLES.md](ROLES.md) - Sistema de roles detallado
- [PROCEDIMIENTO.md](PROCEDIMIENTO.md) - Procedimientos operativos
- [Laravel Documentation](https://laravel.com/docs/11.x) - Docs oficiales de Laravel
- [Tailwind CSS](https://tailwindcss.com/docs) - Docs de Tailwind
- [Alpine.js](https://alpinejs.dev/start-here) - Docs de Alpine.js

---

## 🐛 Resolución de Problemas

### Error: "Class not found"
```bash
composer dump-autoload
php artisan clear-compiled
```

### Error: "Mix manifest not found"
```bash
npm install
npm run build
```

### Error de permisos en storage/
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### La cola no procesa trabajos
```bash
php artisan queue:restart
php artisan queue:work --tries=3
```

---

## 📝 Changelog

### Versión 1.0.0 (2025-01-15)
- ✅ Implementación completa del CRM base
- ✅ Sistema de roles Admin/User separados
- ✅ Panel administrativo completo
- ✅ Gestión de leads, clientes y deals
- ✅ Sistema de secuencias
- ✅ Calendario integrado
- ✅ Reportes y métricas
- ✅ Exportación de datos

---

## 📞 Soporte

**Desarrollado para:**  
EP Consultora & Grupo Xamanen

**Repositorio:**  
[github.com/Noodle1981/crmgx](https://github.com/Noodle1981/crmgx)

**Versión:** 1.0.0  
**Última actualización:** Noviembre 2025

---

<div align="center">

**⚡ Construido con Laravel & Tailwind CSS**

</div>