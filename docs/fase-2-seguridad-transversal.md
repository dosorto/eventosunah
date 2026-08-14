# Fase 2: Seguridad transversal

## Objetivo

Establecer una base consistente de autorizacion para el sistema sobre Laravel 13, Livewire 4 y `spatie/laravel-permission`, evitando permisos legacy acoplados a nombres antiguos.

## Cambios aplicados

- Se definio un catalogo nuevo de permisos con formato orientado a dominio y accion:
  - `dashboard.view`
  - `catalog.*.manage`
  - `people.manage`
  - `speakers.manage`
  - `events.manage`
  - `conferences.manage`
  - `attendances.manage`
  - `diplomas.manage`
  - `reports.events.view`
  - `security.roles.manage`
  - `security.users.manage`
  - `participant.*`
- Se redefinieron los roles base:
  - `super-admin`
  - `admin-eventos`
  - `gestor-contenido`
  - `participante`
- Se activo `Gate::before()` para que `super-admin` tenga acceso total.
- Se protegieron las rutas autenticadas con middleware `permission:*`.
- Se eliminó la asignacion fija por ID de rol y se sustituyo por `assignRole('participante')`.
- Se alineo el menu lateral para que solo renderice opciones permitidas por el nuevo mapa de permisos.

## Resultado esperado

- La seguridad deja de depender de nombres `admin-*` heredados.
- Navegacion y rutas quedan alineadas con el mismo esquema de permisos.
- El usuario administrador semilla queda preparado como `super-admin`.

## Pendientes para fases siguientes

- Autorizar acciones internas dentro de componentes Livewire criticos, no solo el acceso por ruta.
- Incorporar gestion visual de roles/permisos en los modulos de administracion.
- Cubrir los permisos con pruebas feature por perfil.
