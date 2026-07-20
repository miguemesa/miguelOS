# Commit 0002: núcleo HTTP

## Instalación

1. Copia el contenido del ZIP sobre la raíz del proyecto.
2. Verifica que `composer.json` tenga el autoload PSR-4 para `App\\`.
3. Ejecuta `composer dump-autoload`.
4. Añade al `.env` las variables de `.env.example`.
5. Sube el proyecto mediante PhpStorm.

## Pruebas

- `/` debe mostrar MiguelOS 0.0.2.
- `/salud` debe devolver JSON.
- `/ruta-inexistente` debe devolver 404.

Durante las pruebas usa `APP_DEBUG=true`. Después cambia a `false`.
