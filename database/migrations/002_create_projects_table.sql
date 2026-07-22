CREATE TABLE IF NOT EXISTS projects (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                                        name VARCHAR(180) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT NULL,

    status ENUM(
                   'idea',
                   'active',
                   'paused',
                   'completed',
                   'archived'
               ) NOT NULL DEFAULT 'idea',

    priority TINYINT UNSIGNED NOT NULL DEFAULT 3,

    start_date DATE NULL,
    due_date DATE NULL,
    completed_at DATETIME NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
    DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_projects_status (status),
    INDEX idx_projects_priority (priority),
    INDEX idx_projects_due_date (due_date)
    ) ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_unicode_ci;