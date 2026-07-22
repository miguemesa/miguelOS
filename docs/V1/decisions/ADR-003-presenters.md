# ADR-003 — Presenter Pattern

    **Estado:** Aceptado

    ## Contexto

    Los valores internos del dominio no siempre son adecuados para mostrarse directamente.

    ## Decisión

    Usar Presenters para traducir valores de dominio a títulos, fechas, badges e iconos humanos.

    ## Alternativas consideradas

    - Lógica de presentación dentro de Views.
- Métodos de interfaz dentro de Models.
- Textos humanos dentro de Services.

    ## Consecuencias

    - Las Views contienen menos decisiones.
- El dominio no depende del idioma o de Bootstrap.
- La presentación se modifica en un lugar central.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
