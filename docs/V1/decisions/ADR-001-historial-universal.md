# ADR-001 — Historial Universal

    **Estado:** Aceptado

    ## Contexto

    Los cambios de las entidades necesitan trazabilidad. Un historial separado por entidad duplicaría tablas, consultas y componentes.

    ## Decisión

    Usar una tabla transversal `entity_events`, identificada por `entity_type` y `entity_id`.

    ## Alternativas consideradas

    - Una tabla de historial por entidad.
- Columnas de auditoría incrustadas en cada tabla.
- Logs de texto sin modelo de dominio.

    ## Consecuencias

    - Todas las entidades pueden compartir infraestructura.
- El Timeline puede reutilizarse.
- Debe cuidarse la integridad referencial a nivel de aplicación.
- Los Services deben registrar Events consistentemente.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
