# ADR-002 — Services como centro del dominio

    **Estado:** Aceptado

    ## Contexto

    Las reglas de negocio no deben dispersarse entre Controllers, Models y Repositories.

    ## Decisión

    Concentrar validación, coordinación y reglas de operación en Services.

    ## Alternativas consideradas

    - Active Record en Models.
- Reglas dentro de Controllers.
- Reglas dentro de Repositories.

    ## Consecuencias

    - Los Controllers permanecen pequeños.
- Los Repositories se limitan a persistencia.
- Las operaciones de dominio tienen un punto de entrada claro.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
