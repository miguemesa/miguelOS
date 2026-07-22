# ADR-004 — Renderizado de Components

    **Estado:** Aceptado

    ## Contexto

    Los fragmentos visuales reutilizables no deben depender de `require` dispersos por las Views.

    ## Decisión

    Introducir `App\View\Component::render()` para cargar componentes con datos y devolver HTML.

    ## Alternativas consideradas

    - `require` directo.
- Copiar fragmentos.
- Incorporar un motor de plantillas externo.

    ## Consecuencias

    - Interfaz uniforme para componentes.
- Menos duplicación.
- Infraestructura propia pequeña.
- Debe evitarse que evolucione hacia un framework innecesario.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
