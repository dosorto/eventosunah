# Fase 1 - Base Tecnica Actualizada

## 1. Resultado

La base tecnica del proyecto fue actualizada y estabilizada sobre el siguiente stack:

- Laravel `13.17.0`
- Livewire `4.3.3`
- Jetstream `5.5.3`
- Sanctum `4.3.2`
- Tailwind CSS `4.3.1`
- Vite `7.3.6`
- Laravel Vite Plugin `2.1.0`

## 2. Cambios Realizados

### Backend

- Upgrade de dependencias principales en `composer.json` y `composer.lock`.
- Eliminacion de `laravel/tinker` del baseline para destrabar compatibilidad con Laravel 13.
- Actualizacion del flujo base de Fortify en `CreateNewUser` para devolver el `User` correctamente.
- Ajuste de autoload para namespaces heredados que no cumplian PSR-4.
- Correccion del namespace de `Tipoperfiles`.

### Frontend

- Eliminacion de `Flowbite`.
- Migracion de assets a `Tailwind CSS 4`.
- Integracion nueva de Tailwind con `@tailwindcss/vite`.
- Limpieza de `postcss.config.js`.
- Reescritura del `app.css` base para usar Tailwind 4 y reducir CSS global.
- Reescritura del `app.js` base para tema visual sin dependencias de terceros.

### Layout base

- Actualizacion de `layouts/base.blade.php`.
- Reemplazo del shell administrativo dependiente de `Flowbite` por una navegacion en `Tailwind + Alpine/Livewire`.
- Actualizacion de `nav-link` para un estilo base consistente.

### Verificacion y pruebas

- Compilacion de frontend completada con `npm run build`.
- `php artisan about` y `php artisan route:list` ejecutados correctamente.
- Suite `php artisan test` estable:
  - `25` pruebas pasando
  - `7` pruebas omitidas por features desactivadas de Jetstream/Fortify

## 3. Hallazgos Importantes

### Tooling local

- El `composer` que ejecuta el shell todavia emite muchas deprecations por binarios viejos del sistema, aunque Laravel reporta `Composer 2.5.5` en la aplicacion.
- Esto no bloqueo la actualizacion, pero conviene normalizar el binario CLI mas adelante.

### Node.js

- El build funciona, pero el entorno actual usa `Node 22.3.0`.
- `Vite 7` y `laravel-vite-plugin 2` piden oficialmente `Node 20.19+` o `22.12+`.
- No impidio compilar, pero sigue siendo un riesgo operativo del entorno local.

### Seguridad

- En esta fase no se hizo la migracion completa de roles/permisos.
- Se mantuvieron los permisos existentes para no mezclar upgrade tecnico con refactor de seguridad.

## 4. Deuda Tecnica Que Sigue Abierta

- Convenciones inconsistentes de tablas y columnas del dominio.
- Registro personalizado de persona/participante aun fuera del flujo estandar moderno.
- Vistas heredadas de autenticacion y registro con mucho CSS inline.
- Controladores `app/Http/Auth` heredados que hoy no forman parte del flujo principal de Fortify.

## 5. Siguiente Paso

La siguiente fase recomendada es la `Fase 2: seguridad transversal`, con este orden:

1. redefinir roles y permisos nuevos
2. crear seeder limpio de roles/permisos
3. aplicar middleware por permiso en rutas
4. proteger navegacion y modulos
5. dejar listo el modulo `Roles y Usuarios` como primer modulo funcional a revisar
