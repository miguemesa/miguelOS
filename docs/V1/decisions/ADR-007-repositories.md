# ADR-007 — Repository Pattern

    **Estado:** Aceptado

    ## Contexto

    La persistencia necesita permanecer aislada del dominio y de HTTP.

    ## Decisión

    Toda consulta y escritura SQL pasa por Repositories.

    ## Alternativas consideradas

    - SQL en Controllers.
- SQL en Services.
- Active Record.

    ## Consecuencias

    - Persistencia centralizada.
- Models independientes.
- Cambios de almacenamiento más localizados.
- Es necesario evitar Repositories con reglas de negocio.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
