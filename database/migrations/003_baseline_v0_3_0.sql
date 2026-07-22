-- MiguelOS database baseline v0.3.0
--
-- Prerequisites:
--   001_create_migrations_table.sql
--   002_create_projects_table.sql
--
-- This baseline captures the database structures that originally evolved
-- during early development. It intentionally excludes the migrations and
-- projects tables because they are created by migrations 001 and 002.

CREATE TABLE IF NOT EXISTS commitments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    project_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    responsible_name VARCHAR(150) NULL,
    contact VARCHAR(255) NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'waiting',
    outcome VARCHAR(30) NULL,
    started_at DATETIME NULL,
    next_follow_up_at DATETIME NULL,
    last_follow_up_at DATETIME NULL,
    resolved_at DATETIME NULL,
    cancelled_at DATETIME NULL,
    notes TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_commitments_project (project_id),
    INDEX idx_commitments_status (status),
    INDEX idx_commitments_follow_up (next_follow_up_at),

    CONSTRAINT fk_commitments_project
        FOREIGN KEY (project_id)
        REFERENCES projects(id)
        ON DELETE SET NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS tasks (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    project_id BIGINT UNSIGNED NULL,
    generated_by_commitment_id BIGINT UNSIGNED NULL,
    title VARCHAR(220) NOT NULL,
    description TEXT NULL,
    status ENUM(
        'inbox',
        'next',
        'in_progress',
        'waiting',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'inbox',
    priority TINYINT UNSIGNED NOT NULL DEFAULT 3,
    estimated_minutes SMALLINT UNSIGNED NULL,
    start_date DATE NULL,
    due_date DATE NULL,
    completed_at DATETIME NULL,
    position INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_tasks_project_id (project_id),
    INDEX idx_tasks_status (status),
    INDEX idx_tasks_priority (priority),
    INDEX idx_tasks_due_date (due_date),
    INDEX idx_tasks_position (position),
    INDEX idx_tasks_generated_commitment (generated_by_commitment_id),

    CONSTRAINT fk_tasks_project
        FOREIGN KEY (project_id)
        REFERENCES projects(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_tasks_generated_commitment
        FOREIGN KEY (generated_by_commitment_id)
        REFERENCES commitments(id)
        ON DELETE SET NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS milestones (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    project_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'planned',
    scheduled_at DATETIME NULL,
    reached_at DATETIME NULL,
    cancelled_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_milestones_project (project_id),
    INDEX idx_milestones_status (status),
    INDEX idx_milestones_scheduled (scheduled_at),

    CONSTRAINT fk_milestones_project
        FOREIGN KEY (project_id)
        REFERENCES projects(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS entity_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    entity_type VARCHAR(30) NOT NULL,
    entity_id BIGINT UNSIGNED NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    from_value VARCHAR(255) NULL,
    to_value VARCHAR(255) NULL,
    note TEXT NULL,
    metadata JSON NULL,
    occurred_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_entity_events_entity (
        entity_type,
        entity_id,
        occurred_at
    ),
    INDEX idx_entity_events_event_type (event_type),
    INDEX idx_entity_events_occurred_at (occurred_at)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS task_dependencies (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    task_id BIGINT UNSIGNED NOT NULL,
    depends_on_task_id BIGINT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_task_dependency (task_id, depends_on_task_id),
    INDEX idx_task_dependencies_task (task_id),
    INDEX idx_task_dependencies_blocker (depends_on_task_id),

    CONSTRAINT fk_task_dependencies_task
        FOREIGN KEY (task_id)
        REFERENCES tasks(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_task_dependencies_blocker
        FOREIGN KEY (depends_on_task_id)
        REFERENCES tasks(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS commitment_task_blocks (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    task_id BIGINT UNSIGNED NOT NULL,
    commitment_id BIGINT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_commitment_task_block (task_id, commitment_id),
    INDEX idx_commitment_blocks_task (task_id),
    INDEX idx_commitment_blocks_commitment (commitment_id),

    CONSTRAINT fk_commitment_blocks_task
        FOREIGN KEY (task_id)
        REFERENCES tasks(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_commitment_blocks_commitment
        FOREIGN KEY (commitment_id)
        REFERENCES commitments(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;
