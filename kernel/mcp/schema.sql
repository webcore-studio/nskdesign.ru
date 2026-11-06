-- ============================================================================
-- SQL-миграция для WebCore CMS: Таблица аудита и точек отката ИИ (MCP)
-- ============================================================================

CREATE TABLE IF NOT EXISTS `sys_ai_audit` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `module` VARCHAR(64) NOT NULL COMMENT 'Модуль ядра: pages, pages_seo, blocks, catalog',
    `table_name` VARCHAR(64) NOT NULL COMMENT 'Таблица БД или sys_block_var для переменных блоков',
    `pk_field` VARCHAR(64) NOT NULL DEFAULT 'id' COMMENT 'Имя первичного ключа',
    `entity_id` INT UNSIGNED NOT NULL COMMENT 'ID записи или ID блока',
    `action` VARCHAR(64) NOT NULL COMMENT 'Действие: ai_update_content, ai_update_block_var, ai_update_meta',
    `author` VARCHAR(64) NOT NULL DEFAULT 'agent' COMMENT 'Автор изменения: agent или user',
    `note` TEXT NULL COMMENT 'Причина правки / обоснование от ИИ-агента',
    `prev_state` LONGTEXT NOT NULL COMMENT 'JSON со старыми значениями изменённых полей (diff)',
    `new_state` LONGTEXT NOT NULL COMMENT 'JSON с новыми значениями',
    `is_rolled_back` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Флаг выполненного отката (1 = откат выполнен)',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_module_entity` (`module`, `entity_id`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Журнал аудита действий ИИ и точки быстрого отката';
