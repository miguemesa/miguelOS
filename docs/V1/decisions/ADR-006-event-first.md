# ADR-006 — Arquitectura orientada a Events

    **Estado:** Aceptado

    ## Contexto

    Guardar únicamente el estado actual elimina la historia de las decisiones y cambios.

    ## Decisión

    Toda modificación significativa del dominio genera un Event.

    ## Alternativas consideradas

    - Registrar sólo `created_at` y `updated_at`.
- Logs informales.
- Reconstrucción a partir de copias de seguridad.

    ## Consecuencias

    - Auditoría y trazabilidad.
- Futuras métricas y automatizaciones.
- Mayor disciplina en Services.
- Debe evitarse registrar ruido o cambios inexistentes.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
