# Estilo de código de MiguelOS

## PHP

### Reglas base

- `declare(strict_types=1);`
- PSR-4.
- Una clase por archivo.
- Clases `final` salvo necesidad real de herencia.
- Tipos explícitos en parámetros y retornos.
- Propiedades tipadas.
- SQL parametrizado.
- Métodos pequeños y con una responsabilidad.

### Formato

```php
public function update(
    int $id,
    array $data
): ?Project {
    // ...
}
```

Se prioriza la legibilidad sobre la compactación.

### Dependencias

Se permite inyección con valores predeterminados:

```php
public function __construct(
    private readonly ProjectRepository $repository = new ProjectRepository(),
    private readonly EventService $eventService = new EventService()
) {
}
```

Esto mantiene testabilidad sin introducir prematuramente un contenedor de dependencias.

### Controllers

- No contienen SQL.
- No registran Events.
- No transforman formatos de persistencia.
- No aplican reglas de dominio.

### Services

- Validan.
- Coordinan.
- Registran Events.
- Mantienen consistencia del dominio.

### Repositories

- Ejecutan SQL.
- Transforman formatos de almacenamiento.
- Hidratan Models.
- No contienen reglas de negocio.

### Views

- Escapan toda salida dinámica con `htmlspecialchars`.
- No decodifican JSON.
- No consultan Repositories.
- Delegan traducción a Presenters.
- Delegan fragmentos repetibles a Components.

### Nombres

- Clases en singular.
- Métodos expresivos.
- Constantes de dominio en mayúsculas.
- Nombres internos en inglés.
- Textos visibles en español.

### Fechas

- Persistencia en formato estable.
- Presentación mediante Presenter.
- La View no formatea directamente fechas de dominio.

### Eventos

Toda modificación significativa genera un Event después de persistirse correctamente.

No deben registrarse eventos cuando no hubo cambio real.

### Comentarios

Los comentarios explican por qué, no repiten qué hace el código.
