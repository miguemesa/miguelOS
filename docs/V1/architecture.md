# Arquitectura de MiguelOS v1.0

> La arquitectura debe reducir la cantidad de decisiones que el programador necesita tomar al implementar una nueva funcionalidad.

**Estado:** Activo  
**Naturaleza:** Documento vivo

---

## 1. Visión

MiguelOS no es únicamente un gestor de tareas.

Es un sistema operativo personal diseñado para organizar proyectos, investigación, producción artística, práctica, compromisos, conocimiento y otras formas significativas de actividad humana.

La meta no es acumular funcionalidades. La meta es que cada funcionalidad encuentre un lugar natural dentro de una arquitectura simple, coherente y sostenible.

---

## 2. Principios generales

MiguelOS prioriza:

- claridad;
- simplicidad;
- mantenibilidad;
- trazabilidad;
- crecimiento incremental;
- consistencia entre módulos;
- reducción de deuda técnica.

> [!IMPORTANT]
> Toda abstracción debe justificar su existencia mediante una reducción real de complejidad o duplicación.

---

## 3. Filosofía del dominio

MiguelOS modela conceptos de la vida real.

Ejemplos:

- Proyecto
- Práctica
- Producción
- Compromiso
- Hito
- Tarea
- Evento

Las entidades no existen únicamente porque una tabla las necesite. El dominio precede a la persistencia.

---

## 4. Arquitectura por capas

```text
HTTP
  ↓
Router
  ↓
Controller
  ↓
Service
  ↓
Repository
  ↓
Database
```

El flujo de presentación continúa así:

```text
Repository
  ↓
Model
  ↓
Presenter
  ↓
Component
  ↓
View
  ↓
HTML
```

---

## 5. Responsabilidades

### 5.1 Controller

Coordina la petición HTTP.

Puede:

- recibir datos de entrada;
- invocar Services;
- devolver Views o respuestas;
- gestionar redirecciones.

Nunca debe:

- ejecutar SQL;
- contener reglas de negocio;
- interpretar JSON de persistencia;
- conocer detalles de PDO;
- registrar eventos directamente.

### 5.2 Service

Contiene las reglas del dominio.

Puede:

- validar operaciones;
- coordinar Repositories;
- crear y modificar entidades;
- registrar Events;
- aplicar reglas de consistencia.

Nunca debe:

- generar HTML;
- depender de Bootstrap;
- construir fragmentos de vista.

### 5.3 Repository

Es responsable de la persistencia.

Puede:

- ejecutar SQL parametrizado;
- mapear filas;
- hidratar Models;
- transformar formatos propios de almacenamiento.

Nunca debe:

- aplicar reglas de negocio;
- generar texto de interfaz;
- decidir cómo se representa una entidad visualmente.

### 5.4 Model

Representa una entidad del dominio.

Los Models:

- no conocen la base de datos;
- no conocen las Views;
- contienen propiedades coherentes con el dominio;
- viajan entre Services, Repositories y Presenters.

### 5.5 Presenter

Traduce información del dominio a lenguaje humano.

Ejemplos:

```php
EventPresenter::title($event);
EventPresenter::icon($event);
EventPresenter::badgeClass($event);
EventPresenter::date($event);
```

Las Views no deben traducir directamente valores internos como `status_changed` o `priority_changed`.

### 5.6 Component

Renderiza HTML reutilizable.

Ejemplo:

```php
Component::render(
    'timeline',
    [
        'events' => $history,
    ]
);
```

Los Components:

- reciben datos preparados;
- no consultan la base de datos;
- no aplican reglas de dominio;
- pueden usar Presenters.

### 5.7 View

Compone la interfaz.

Una View debe:

- organizar Components;
- mostrar información ya preparada;
- contener la menor lógica posible.

---

## 6. Flujo de una petición

```text
Petición HTTP
      ↓
Router
      ↓
Controller
      ↓
Service
      ↓
Repository
      ↓
Database
      ↓
Repository
      ↓
Model
      ↓
Presenter
      ↓
Component
      ↓
View
      ↓
HTML
```

Cada capa tiene una responsabilidad definida y conoce únicamente lo necesario.

---

## 7. Historial Universal

Toda modificación significativa del dominio genera un Event.

```text
Entidad
  ↓
Service de la entidad
  ↓
EventService
  ↓
EventRepository
  ↓
entity_events
```

El historial no pertenece a Project, Task o Practice. Es una capacidad transversal.

Esto permite:

- auditoría;
- trazabilidad;
- líneas de tiempo;
- análisis histórico;
- futuras automatizaciones;
- estadísticas de actividad.

> [!IMPORTANT]
> Guardar una entidad y registrar su Event forman parte de la misma operación conceptual.

---

## 8. Reglas de diseño

1. Una responsabilidad por clase.
2. El dominio manda.
3. Los Controllers coordinan, no deciden.
4. Los Services contienen las reglas.
5. Los Repositories conocen la persistencia.
6. Las Views nunca conocen SQL, PDO o JSON de almacenamiento.
7. Los Presenters traducen el dominio.
8. Los Components renderizan.
9. Todo cambio relevante deja evidencia.
10. La reutilización precede a la duplicación.
11. Una abstracción nace cuando existe una necesidad real.
12. El camino correcto debe ser también el más fácil de seguir.

---

## 9. Convenciones estructurales

```text
app/
    Core/
    Domain/
    Http/
        Controllers/
    Models/
    Repositories/
    Services/
    View/

resources/
    views/
        components/

docs/
    architecture.md
    roadmap.md
    coding-style.md
    decisions/
    diagrams/
```

En el futuro podrá existir:

```text
app/Support/
```

para utilidades reutilizables que no pertenecen al dominio, la persistencia o la presentación.

---

## 10. Qué no es MiguelOS

MiguelOS no intenta:

- reproducir Laravel;
- construir un ORM completo;
- crear un framework generalista;
- añadir patrones sólo por prestigio técnico;
- ocultar código sencillo detrás de abstracciones innecesarias.

Cada nueva pieza debe resolver un problema real.

---

## 11. Prueba de una buena arquitectura

Antes de implementar una funcionalidad debe ser posible responder:

- ¿En qué capa pertenece?
- ¿Quién es responsable?
- ¿Qué clase debe cambiar?
- ¿Qué otras capas no deberían enterarse?
- ¿La solución puede reutilizarse?
- ¿Debe generar un Event?

Cuando estas respuestas no son claras, debe revisarse el diseño antes de añadir código.

---

## 12. Evolución del documento

Este documento cambia cuando una decisión modifica:

- la responsabilidad de una capa;
- el flujo de datos;
- las reglas de extensión;
- las convenciones del sistema;
- la filosofía del dominio.

Las decisiones específicas se documentan en ADR.
