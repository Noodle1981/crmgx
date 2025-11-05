# Guía de flujos de prueba (Rol Usuario CRM)

Esta guía define flujos de “cargas” y verificación funcional desde el rol Usuario CRM (is_admin = 0) para validar el sistema de punta a punta.

## Requisitos previos
- Un usuario no administrador creado (is_admin = 0) y con acceso a la app.
- Sesión iniciada como ese usuario. Al iniciar sesión debe redirigir al dashboard de usuario (no al panel admin).
- Asegúrate de que el usuario NO pueda acceder a rutas de admin (debe ver 403).

## Convenciones de la guía
- Resultado esperado: lo que debe ocurrir si todo funciona.
- Evidencia: qué tomar como prueba (captura, registro en pantalla, trazas si aplica).
- Tablas afectadas: referencia rápida del modelo de datos ejercitado.

---

## 1) Sesión y permisos
- Paso: Iniciar sesión con el usuario CRM.
  - Resultado esperado: Redirección al dashboard de usuario.
  - Evidencia: Captura del dashboard; URL no debe iniciar con /admin.
- Paso: Intentar acceder manualmente a /admin o subrutas.
  - Resultado esperado: Página 403 personalizada con CTA de volver al CRM.
  - Evidencia: Captura de la vista 403.
- Tablas afectadas: users, sessions.

---

## 2) Gestión de Leads
- Crear lead
  - Navegar: Leads > Nuevo lead.
  - Datos sugeridos: nombre “Juan Test”, empresa “ACME SA”, email “juan.test+1@example.com”, teléfono “+54 9 11 2345 6789”, fuente “Web”.
  - Resultado esperado: Lead creado en estado “nuevo”.
  - Evidencia: Aparece en listado con estado “nuevo”.
  - Tablas: leads.
- Validaciones clave
  - Email único (si se repite, debe mostrar error de duplicado).
  - Teléfono formato válido (si hay regla, debe mostrar error apropiado).
  - Resultado esperado: Mensajes de error accesibles y claros.
- Actualizar lead
  - Cambiar estado: “nuevo” → “contactado” → “calificado”.
  - Añadir notas.
  - Resultado esperado: Persisten cambios, fechas/score si aplica.
- Recordatorios de lead (si está habilitado)
  - Crear recordatorio: título “Llamar demo”, due_date hoy+2, prioridad medium.
  - Resultado esperado: Se registra y aparece en listados relacionados.
  - Tablas: lead_reminders.
- Cierre negativo (opcional)
  - Seleccionar razón de pérdida (si existe) y marcar “perdido”.
  - Resultado esperado: lead con lost_at y razón almacenada.
  - Tablas: leads, loss_reasons (referencia).
- Conversión a cliente (si la UI lo permite)
  - Acción: “Convertir a cliente”.
  - Resultado esperado: Se crea/relaciona un Client y opcionalmente un Deal inicial.
  - Evidencia: Ver cliente/negocio vinculado.
  - Tablas: leads, clients, deals (según implementación).

---

## 3) Clientes
- Crear cliente
  - Navegar: Clientes > Nuevo.
  - Datos: CUIT “20-12345678-9” (único), nombre “ACME Argentina”, email corporativo, teléfono general.
  - Resultado esperado: Cliente creado y asignado al usuario, activo = true.
  - Evidencia: Figura en listado; detail con notas vacías.
  - Tablas: clients.
- Validaciones clave
  - CUIT único obligatorio.
  - Email formato válido (si corresponde).
- Editar cliente
  - Actualizar website, actividad económica, notas.
  - Resultado esperado: Cambios persistidos.
- Baja/activo
  - Desactivar cliente (si existe toggle).
  - Resultado esperado: Indicador de “inactivo” en listado/detalle.

---

## 4) Establecimientos (Sedes)
- Crear establecimiento de un cliente
  - Navegar: Cliente > Establecimientos > Nuevo.
  - Datos: nombre “Planta Pilar”, dirección, localidad.
  - Resultado esperado: Alta exitosa vinculado al cliente.
  - Tablas: establishments.
- Reglas de borrado
  - Borrar cliente con sedes de prueba.
  - Resultado esperado: Borra en cascada los establecimientos.
  - Evidencia: No quedan sedes huérfanas.

---

## 5) Contactos
- Crear contacto
  - Navegar: Cliente > Contactos > Nuevo.
  - Datos: nombre “María Referente”, email “maria.referente+1@example.com”, teléfono “+54 351 555 5555”, cargo “Jefa H&S”.
  - Opcional: asociar a un establecimiento.
  - Resultado esperado: Alta exitosa vinculado a cliente (y sede si aplica).
  - Tablas: contacts.
- Validaciones clave
  - Teléfono válido (regla ValidPhoneNumber si está activa).
  - Email formato válido.
- Edición
  - Cambiar cargo y notas.
  - Resultado esperado: Cambios visibles en detalle.

---

## 6) Negocios (Deals)
- Crear deal
  - Navegar: Cliente > Negocios > Nuevo.
  - Datos: nombre “Implementación Servicio H&S”, valor 1.500.000, etapa “Propuesta Enviada”, fecha cierre estimada +30 días.
  - Resultado esperado: Deal en estado “open” y etapa seleccionada.
  - Tablas: deals, deal_stages.
- Cambiar etapa y estado
  - Mover a etapa siguiente; marcar “won” o “lost”.
  - Resultado esperado: Persisten cambios; si “lost”, verificar notas de pérdida si existen.

---

## 7) Tareas y Calendario
- Crear tarea vinculada
  - Desde un Cliente/Contacto/Deal: “Nueva tarea”.
  - Datos: título “Enviar propuesta”, due_date +3 días, status “pendiente”.
  - Resultado esperado: Tarea creada y asociada (polimórfica). Aparece en Tareas y Calendario.
  - Tablas: tasks.
- Actualización de estado
  - Cambiar a “en_proceso” y luego “completado”.
  - Resultado esperado: Reflejo en listado y, si aplica, estilos en calendario.
- Calendario
  - Ver mes actual; filtrar por estado (si hay filtros) y validar colores/branding.
  - Resultado esperado: Eventos visibles en fechas correctas.

---

## 8) Actividades (si disponible en UI)
- Registrar actividad en Cliente/Deal/Contacto: tipo “call”, descripción breve.
  - Resultado esperado: Aparece en timeline/historial.
  - Tablas: activities.

---

## 9) Secuencias (si disponible para usuario)
- Inscribir contacto en una secuencia existente.
  - Resultado esperado: Alta en sequence_enrollments con estado “active”.
  - Avanzar paso manualmente o esperar fecha programada; completar paso.
  - Resultado esperado: Registro en sequence_step_completions y actualización de next_step_due_at/estado.
  - Tablas: sequences, sequence_steps, sequence_enrollments, sequence_step_completions, notifications.

---

## 10) Notificaciones
- Generar una acción que dispare notificación (p. ej., vencimiento de tarea o paso de secuencia si aplica).
- Abrir el centro de notificaciones (si existe en la UI) y marcarlas como leídas.
  - Resultado esperado: `read_at` completado.
  - Tablas: notifications.

---

## 11) Listados, filtros y paginación
- Validar listados de Leads, Clientes, Contactos, Deals, Tareas.
  - Usar filtros (estado, fecha, vendedor si aplica) y paginación.
  - Resultado esperado: Rendimiento aceptable y resultados correctos.

---

## Validaciones y mensajes de error esperados
- Emails únicos en leads y formato válido en contactos/clientes.
- CUIT único en clientes.
- Teléfono válido cuando sea requerido (regla ValidPhoneNumber).
- Fechas requeridas en tareas (due_date).
- Mensajes localizados en español y claros.

---

## Datos de prueba sugeridos
- Emails: `nombre.apellido+<n>@example.com` para evitar duplicados.
- Teléfonos AR: `+54 9 11 2345 6789`, `+54 351 555 5555`.
- CUITs falsos de ejemplo: `20-12345678-9`, `27-87654321-5`.
- Montos: usar 6 a 7 dígitos para validar formatos.

---

## Criterios de aceptación por flujo (resumen)
- Sesión/Permisos: dashboard correcto; 403 en admin.
- Leads: creación, validaciones, cambio de estado; recordatorios; pérdida/conversión opcional.
- Clientes: CUIT único; edición; activo/inactivo.
- Establecimientos: alta y borrado en cascada al eliminar cliente.
- Contactos: alta, validaciones, relación opcional a sede.
- Deals: alta, cambio de etapa, ganado/perdido.
- Tareas/Calendario: alta polimórfica, estados, visualización calendario.
- Actividades: registro visible en historial (si UI disponible).
- Secuencias: inscripción, avance/completado, notificaciones (si habilitado).
- Notificaciones: lectura correcta.

---

## Registro de incidencias
- Anotar: pasos, datos usados, resultado observado, resultado esperado, capturas.
- Si hay error, revisar `storage/logs/laravel.log` y adjuntar el mensaje.

---

## Anexo: tablas clave ejercitadas
- users, sessions
- leads, lead_reminders, lead_qualification_criteria, lead_criteria_checks, loss_reasons, lead_conversion_follow_ups
- clients
- establishments
- contacts
- deals, deal_stages
- tasks
- activities
- sequences, sequence_steps, sequence_enrollments, sequence_step_completions
- notifications






Qué hacer ahora
Iniciar sesión como Usuario CRM

Si ya tenías un usuario no admin, usá ese.
Si no, se creó uno: email demo.user+crm@example.com / password
Al entrar deberías ir al dashboard de usuario (no admin). Probar entrar a /admin y verificar 403.
Navegar y verificar los datos sembrados

Clientes: deberías ver “ACME Argentina” (CUIT 20-12345678-9).
Dentro del cliente: Establecimiento “Planta Pilar”.
Contactos: “María Referente” (maria.referente+demo@example.com).
Leads: “Juan Test” (juan.test+demo@example.com) en estado “nuevo”.
Deals: en el cliente, el negocio “Implementación Servicio H&S” en etapa “Propuesta Enviada”.
Tareas y Calendario: dos tareas ya creadas
“Enviar propuesta” asociada al Deal
“Llamar a cliente” asociada al Cliente
Verificá que aparezcan también en el calendario.
Ejecutar los flujos de QA

Seguí la guía en QA_USUARIO.md (está pensada justo para esto).
Recorre: Leads → Clientes/Establecimientos/Contactos → Deals → Tareas/Calendario.
Anotá incidencias y compará con los criterios de aceptación de la guía.
Si algo no cuadra

Revisá laravel.log y avisame el error exacto; lo corrijo enseguida.
El seeder es idempotente: podés volver a correrlo sin duplicar datos.