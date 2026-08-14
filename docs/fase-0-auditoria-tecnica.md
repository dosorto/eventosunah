# Fase 0 - Auditoria Tecnica

## 1. Objetivo

Documentar el estado real del sistema antes de iniciar la modernizacion a `Laravel + Livewire + Tailwind puro`, definir el baseline tecnico, identificar riesgos de upgrade y establecer una primera matriz de roles/permisos para la siguiente fase.

## 2. Estado Actual Del Proyecto

### Stack detectado

- PHP CLI: `8.4.4`
- Laravel: `11.9`
- Jetstream: `5.1`
- Livewire: `3.x`
- Sanctum: `4.x`
- Tailwind CSS: `3.4.0`
- Vite: `5.x`
- UI externa: `Flowbite`
- Seguridad por permisos: `spatie/laravel-permission 6.x`

### Conclusiones rapidas

- El proyecto ya esta en una base moderna de Laravel, pero no en la ultima.
- La interfaz no esta en `Tailwind puro`: mezcla Tailwind, CSS global manual y `Flowbite`.
- El backend ya tiene `roles` y `permissions`, pero la autorizacion no esta aplicada de forma transversal.
- La aplicacion mezcla modulos `Livewire`, vistas Blade tradicionales y componentes visuales heredados de Jetstream.

## 3. Inventario Funcional

### Modulos administrativos detectados

- Dashboard
- Usuarios
- Roles
- Personas
- Nacionalidades
- Modalidades
- Tipos de perfil
- Localidades
- Eventos
- Conferencistas
- Conferencias
- Inscripciones a conferencias
- Asistencias generales
- Asistencias por conferencia
- Diplomas
- Reporte por evento

### Modulos/autoflujos de participante

- Registro de usuario
- Registro de persona vinculada al usuario
- Vista publica de eventos
- Vista publica de detalle de evento
- Vista de conferencias por evento
- Inscripcion a conferencia
- Historial de conferencias
- Vista de diploma
- Validacion publica de diploma por UUID

### Componentes Livewire encontrados

- `Asistencias`
- `AsistenciasConferencias`
- `Conferencias`
- `ConferenciasInscritas`
- `Conferencistas`
- `Dashboards`
- `Diplomas`
- `Eventos`
- `EventosVistas`
- `HistorialConferencias`
- `Localidades`
- `Modalidades`
- `Nacionalidades`
- `Personas`
- `ReporteEventos`
- `Roles`
- `Tipoperfiles`
- `Usuarios`
- `ValidarDiploma`
- `VistaConferencias`
- `VistaDiplomas`

## 4. Arquitectura Detectada

### Rutas

- La zona autenticada usa `auth:sanctum`, sesion Jetstream y `verified`.
- No se detecto proteccion por `permission`, `role`, `can`, `Gate` o `Policy` en las rutas principales.
- Hay mezcla de rutas a componentes Livewire y rutas a controladores Blade clasicos.

### UI

- `resources/js/app.js` importa `Flowbite` y contiene logica DOM manual para sidebar/tema.
- `resources/css/app.css` contiene una capa larga de estilos globales no utilitarios.
- Existen layouts heredados de Jetstream y componentes Blade personalizados.
- El objetivo final no debe ser solo "actualizar Tailwind", sino retirar la dependencia estructural de `Flowbite` y reducir CSS global a lo minimo.

### Autenticacion

- Jetstream/Fortify estan activos.
- Hay registro personalizado en `RegistrarUsarioController`.
- El alta de persona ocurre en un segundo paso manual despues de crear usuario.
- Se detecta asignacion de rol por ID fijo (`attach(2)`), lo cual no es estable ni mantenible.

### Seguridad y autorizacion

- `User` ya usa `HasRoles`.
- `spatie/laravel-permission` esta configurado en modo no-teams.
- Existen seeders de permisos y roles, pero el modelo actual de permisos es muy grueso y de tipo "pantalla", no de accion.

## 5. Modelo De Datos

### Entidades de negocio

- `User`
- `Persona`
- `Nacionalidad`
- `Tipoperfil`
- `Localidad`
- `Modalidad`
- `Evento`
- `Conferencia`
- `Conferencista`
- `Suscripcion`
- `Asistencia`
- `Diploma`
- `DiplomaGenerado`
- `Firma`

### Observaciones estructurales

- El proyecto usa nombres de tablas y columnas con mezcla de convenciones:
  - pluralizaciones irregulares como `nacionalidads`, `modalidads`, `localidads`, `tipoperfils`, `suscripcions`
  - columnas como `IdEvento`, `IdPersona`, `IdTipoPerfil`, mezcladas con `idmodalidad`, `idlocalidad`
- Hay una clase `BaseModel` que intenta auditar `created_by`, `updated_by`, `deleted_by`, pero su `boot()` usa `save()` dentro de eventos `updating/deleting`, lo que es riesgoso y puede producir efectos secundarios.
- No todos los modelos heredan de `BaseModel`, por lo que la auditoria no es consistente.

## 6. Riesgos Tecnicos Prioritarios

### Riesgo A. Tooling de Composer

- El entorno usa `PHP 8.4.4`.
- El `composer` local mostrado por el sistema es `2.1.9` y genera deprecations fuertes en PHP 8.4.
- Antes del upgrade de framework hay que normalizar el tooling para evitar actualizaciones no reproducibles.

### Riesgo B. Dependencia visual no controlada

- `Flowbite` esta mezclado con scripts DOM manuales y estilos globales.
- Migrar a `Tailwind 4` sin desmontar antes esa capa puede romper layouts, modales y navegacion.

### Riesgo C. Seguridad incompleta

- Aunque existen roles/permisos, las rutas no estan segmentadas por autorizacion.
- La navegacion y los modulos administrativos pueden quedar accesibles a usuarios autenticados sin restricciones finas.

### Riesgo D. Registro de usuarios fragil

- El flujo de alta de usuario/persona es mixto y manual.
- Se detecto uso de nombres de campos inconsistentes en el controlador de registro.
- Se asigna un rol por ID fijo, lo cual puede fallar entre entornos.

### Riesgo E. Calidad de dominio y consistencia

- Hay relaciones, nombres y convenciones inconsistentes.
- Existen modelos con relaciones potencialmente incorrectas o ambiguas.
- Hay componentes Livewire con mucha logica de infraestructura dentro del componente.

### Riesgo F. Pruebas insuficientes

- Solo se detectan pruebas base de Jetstream/Fortify.
- No hay evidencia de pruebas funcionales del dominio principal: eventos, conferencias, inscripciones, asistencias, diplomas, seguridad.

## 7. Hallazgos Funcionales Que Deben Corregirse En La Migracion

### Seguridad

- No hay middleware por permiso en la mayoria de rutas administrativas.
- No hay policies por recurso.
- No hay una matriz clara de quien puede ver, crear, editar, eliminar o ejecutar acciones sensibles.

### UI y arquitectura

- El dashboard aun se renderiza desde controlador Blade, no desde Livewire.
- Convivencia de layouts Blade viejos con componentes Livewire nuevos.
- El sistema de tema/sidebar depende de selectores DOM fragiles.

### Dominio

- La generacion y validacion de diplomas requiere una revision dedicada.
- Las asistencias por conferencia mezclan marcado de asistencia con generacion de diploma.
- El registro de participante y su vinculacion con persona necesita endurecimiento.

## 8. Baseline Objetivo Recomendado

### Objetivo tecnico

- Laravel `13`
- Livewire `4`, si la compatibilidad real del ecosistema lo permite al momento del upgrade
- Tailwind CSS `4`
- Vite actualizado segun compatibilidad del framework
- UI sin `Flowbite`
- Layout administrativo unificado en `Livewire + Blade + Tailwind puro`

### Estrategia recomendada

- No hacer upgrade mas refactor funcional en un solo paso.
- Separar la modernizacion en dos capas:
  - Fase 1: plataforma, assets, shell visual y seguridad base
  - Fase 2 en adelante: migracion modulo por modulo

## 9. Matriz Inicial De Roles

### Roles propuestos

- `super-admin`
  - control total del sistema
- `admin-eventos`
  - administracion operativa de catalogos, eventos, conferencias, asistencias y diplomas
- `gestor-contenido`
  - gestion de eventos, conferencias y conferencistas, sin acceso total a usuarios/roles
- `participante`
  - acceso a inscripciones, historial y diplomas propios

### Comentarios

- El rol `root` actual debe migrarse a `super-admin`.
- El rol `Participante` actual puede conservarse conceptualmente, pero debe renombrarse o mapearse de forma consistente.
- No se recomienda seguir administrando permisos con nombres acoplados a pantalla tipo `admin-evento`.

## 10. Matriz Inicial De Permisos

### Seguridad y sistema

- `security.roles.view`
- `security.roles.create`
- `security.roles.update`
- `security.roles.delete`
- `security.permissions.view`
- `security.users.view`
- `security.users.create`
- `security.users.update`
- `security.users.delete`

### Catalogos

- `catalog.modalities.view`
- `catalog.modalities.create`
- `catalog.modalities.update`
- `catalog.modalities.delete`
- `catalog.nationalities.view`
- `catalog.nationalities.create`
- `catalog.nationalities.update`
- `catalog.nationalities.delete`
- `catalog.profile-types.view`
- `catalog.profile-types.create`
- `catalog.profile-types.update`
- `catalog.profile-types.delete`
- `catalog.locations.view`
- `catalog.locations.create`
- `catalog.locations.update`
- `catalog.locations.delete`

### Personas y conferencistas

- `people.view`
- `people.create`
- `people.update`
- `people.delete`
- `speakers.view`
- `speakers.create`
- `speakers.update`
- `speakers.delete`

### Eventos y conferencias

- `events.view`
- `events.create`
- `events.update`
- `events.delete`
- `conferences.view`
- `conferences.create`
- `conferences.update`
- `conferences.delete`

### Inscripciones y asistencias

- `registrations.view`
- `registrations.create`
- `registrations.delete`
- `attendances.view`
- `attendances.mark`
- `attendances.delete`

### Diplomas y reportes

- `diplomas.view`
- `diplomas.create`
- `diplomas.update`
- `diplomas.delete`
- `diplomas.generate`
- `diplomas.validate`
- `reports.events.view`

### Participante

- `participant.portal.access`
- `participant.registrations.manage`
- `participant.history.view`
- `participant.diplomas.view`

## 11. Mapeo Inicial Rol -> Permisos

### `super-admin`

- Todos los permisos.

### `admin-eventos`

- Todos los permisos operativos excepto administracion de roles/permisos.

### `gestor-contenido`

- Catalogos de apoyo
- Eventos
- Conferencias
- Conferencistas
- Reportes de eventos
- Sin gestion de usuarios/roles

### `participante`

- `participant.portal.access`
- `participant.registrations.manage`
- `participant.history.view`
- `participant.diplomas.view`
- `diplomas.validate`

## 12. Alcance De La Siguiente Fase

La Fase 1 debe ejecutar estas tareas, en este orden:

1. Normalizar tooling de trabajo y estrategia de upgrade.
2. Subir framework y dependencias frontend al baseline aprobado.
3. Retirar `Flowbite` y reconstruir shell visual base.
4. Unificar layout administrativo.
5. Preparar middleware por permisos y seeder nuevo de roles/permisos.
6. Dejar listo el primer modulo de migracion: `Roles y Usuarios`.

## 13. Checklist De Cierre De Fase 0

- [x] Inventario tecnico y funcional levantado
- [x] Estado actual del stack confirmado
- [x] Riesgos principales identificados
- [x] Baseline objetivo recomendado
- [x] Propuesta inicial de roles
- [x] Propuesta inicial de permisos
- [x] Orden de trabajo para la siguiente fase

## 14. Decisiones Pendientes Para Validacion

- Confirmar si el baseline final sera `Laravel 13 + Livewire 4 + Tailwind 4`.
- Confirmar si Jetstream se conserva solo para auth temporalmente o si se desmonta tambien su UI de perfil.
- Confirmar el nombre final de roles de negocio.
- Confirmar si la nomenclatura actual de tablas se mantiene por compatibilidad o si se normaliza gradualmente.
