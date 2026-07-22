# ADR-008 — MiguelOS modela actividad humana

    **Estado:** Aceptado

    ## Contexto

    Un gestor centrado únicamente en tareas no representa adecuadamente proyectos artísticos, investigación, prácticas o compromisos.

    ## Decisión

    Modelar distintas formas de actividad humana como entidades propias relacionadas.

    ## Alternativas consideradas

    - Convertir todo en Task.
- Usar etiquetas para simular conceptos.
- Adoptar un modelo genérico sin semántica.

    ## Consecuencias

    - El sistema refleja mejor la vida real.
- Las entidades conservan significado.
- La arquitectura debe evitar una proliferación innecesaria de tipos.

    ## Notas de evolución

    Este ADR debe revisarse si la decisión deja de ajustarse al crecimiento real de MiguelOS.
