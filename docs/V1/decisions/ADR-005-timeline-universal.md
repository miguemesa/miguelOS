# ADR-005 — Timeline Universal

    **Estado:** Aceptado

    ## Contexto

    Project, Task, Practice y otras entidades necesitarán mostrar Events.

    ## Decisión

    El Timeline recibe exclusivamente `array<Event>` y no conoce la entidad propietaria.

    ## Alternativas consideradas

    - Un historial específico para cada entidad.
- Views con condiciones por tipo de entidad.

    ## Consecuencias

    - Reutilización inmediata.
- Menor duplicación visual.
- El EventPresenter debe resolver diferencias de presentación.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
