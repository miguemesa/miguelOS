# Roadmap de MiguelOS

**Estado:** Vivo  
**Propósito:** Mantener visible la dirección del sistema sin convertir el futuro en una lista ceremonial de promesas incumplidas.

---

## Estado actual

### Núcleo

- Enrutamiento.
- Controllers.
- Services.
- Repositories.
- Models.
- Views.
- Component renderer.
- Presenters.
- Migraciones web.
- Configuración de entorno.

### Proyectos

- Creación.
- Consulta.
- Edición.
- Eliminación.
- Slugs únicos.
- Prioridad.
- Estado.
- Fechas.

### Historial Universal

- Tabla `entity_events`.
- `Event` Model.
- `EventRepository`.
- `EventService`.
- `EventPresenter`.
- Timeline reutilizable.
- Registro de creación.
- Registro de cambios de estado.
- Registro de cambios de prioridad.
- Registro de actualizaciones generales.

---

## Próximos sprints

### Sprint 3.3 — Timeline Universal

- Fechas humanas.
- Diferencias visuales de valores.
- Metadata inteligente.
- Badges consistentes.
- Componente visual definitivo.
- Estados vacíos reutilizables.
- Cierre documental de arquitectura.

### Sprint 3.4 — Infraestructura visual

- `empty-state`.
- `property-list`.
- `field-diff`.
- `badge`.
- `card`.
- Convenciones visuales compartidas.

### Sprint 4 — Tareas

- Modelo Task.
- Repositorio.
- Service.
- CRUD.
- Relación con Project.
- Historial heredado.
- Prioridades y estados.
- Dependencias.

### Sprint 5 — Compromisos y agenda

- Commitment.
- Fechas y duración.
- Repetición.
- Integración con agenda.
- Eventos asociados.

### Sprint 6 — Prácticas

- Practice.
- Frecuencia.
- Sesiones.
- Historial.
- Continuidad y revisión.

### Sprint 7 — Producción

- Production.
- Entregables.
- Recursos.
- Fechas clave.
- Relación con proyectos.

### Sprint 8 — Planeación personal

- Agenda semanal.
- Estimaciones.
- Dependencias.
- Capacidad semanal.
- Bloques de concentración.
- Revisión semanal.

### Sprint 9 — Métricas y reflexión

- Actividad por entidad.
- Cambios históricos.
- Tiempo estimado contra real.
- Proyectos detenidos.
- Tendencias de carga.
- Resúmenes semanales.

---

## Principios de priorización

Una funcionalidad sube de prioridad cuando:

1. reduce fricción cotidiana;
2. puede reutilizarse;
3. mejora trazabilidad;
4. simplifica futuras entidades;
5. evita duplicación;
6. responde a una necesidad real de uso.

Una funcionalidad baja de prioridad cuando:

- existe sólo por entusiasmo técnico;
- introduce complejidad sin uso inmediato;
- duplica una capacidad existente;
- requiere una arquitectura todavía inmadura.
