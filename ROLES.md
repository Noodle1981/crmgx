# Sistema de Roles - CRM

## Roles Implementados

El sistema tiene dos roles claramente separados:

### 👨‍💼 Administrador (`is_admin = 1`)

**Acceso:**
- Panel de Administración: `/admin/dashboard`
- Gestión de Usuarios
- Métricas de Rendimiento
- Configuración del Sistema
- Mantenimiento
- Logs del Sistema

**Restricciones:**
- NO puede acceder al CRM de usuario (rutas `/dashboard`, `/leads`, `/clients`, `/deals`, etc.)
- Si intenta acceder al CRM, recibirá un error 403

**Inicio de sesión:**
- Redirige automáticamente a: `/admin/dashboard`

---

### 👤 Usuario CRM (`is_admin = 0`)

**Acceso:**
- Dashboard personal: `/dashboard`
- Gestión de Leads
- Gestión de Clientes
- Gestión de Oportunidades (Deals)
- Actividades y Tareas
- Calendario
- Reportes personales
- Secuencias de email
- Configuración personal

**Restricciones:**
- NO puede acceder al panel de administración (rutas `/admin/*`)
- Si intenta acceder a rutas admin, recibirá un error 403

**Inicio de sesión:**
- Redirige automáticamente a: `/dashboard`

---

## Middlewares

### `admin`
- **Archivo:** `app/Http/Middleware/AdminMiddleware.php`
- **Uso:** Protege todas las rutas `/admin/*`
- **Validación:** Verifica que `is_admin = 1`

### `user`
- **Archivo:** `app/Http/Middleware/UserMiddleware.php`
- **Uso:** Protege todas las rutas del CRM
- **Validación:** Verifica que `is_admin = 0`

---

## Rutas Protegidas

```php
// Rutas de Admin (requieren middleware 'admin')
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Todas las rutas administrativas
});

// Rutas de Usuario CRM (requieren middleware 'user')
Route::middleware(['auth', 'user'])->group(function () {
    // Todas las rutas del CRM
});
```

---

## Navegación

### Admin
- **Archivo:** `resources/views/layouts/admin-navigation.blade.php`
- **Menú:** Panel Principal, Usuarios, Rendimiento, Sistema (Email, Mantenimiento, Logs)
- **Sin acceso a:** CRM Usuario (botón removido)

### Usuario
- **Archivo:** `resources/views/layouts/navigation.blade.php`
- **Menú:** Dashboard, Leads, Clientes, Oportunidades, Calendario, etc.
- **Sin acceso a:** Panel de Administración

---

## Autenticación

**Login Controller:** `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

```php
// Redirige según el rol
if (Auth::user()->isAdmin()) {
    return redirect()->route('admin.dashboard');
}
return redirect()->route('dashboard');
```

---

## Página de Error 403

**Archivo:** `resources/views/errors/403.blade.php`

Muestra un mensaje de acceso denegado y un botón para regresar al área correcta según el rol del usuario.

---

## Comandos de Limpieza

Para aplicar cambios después de modificar middlewares o rutas:

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Creación de Usuarios

### Admin
```php
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true,
]);
```

### Usuario CRM
```php
User::create([
    'name' => 'Sales User',
    'email' => 'sales@example.com',
    'password' => bcrypt('password'),
    'is_admin' => false,
]);
```

---

## Verificación

### Verificar rol en código:
```php
// Verificar si es admin
if (auth()->user()->isAdmin()) {
    // Código para admin
}

// Verificar capacidades
if (auth()->user()->canManageUsers()) {
    // Solo admin puede hacer esto
}
```

### En Blade:
```blade
@if(auth()->user()->isAdmin())
    <!-- Contenido solo para admin -->
@else
    <!-- Contenido solo para usuarios -->
@endif
```
