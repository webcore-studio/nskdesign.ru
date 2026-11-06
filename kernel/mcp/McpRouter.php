<?php
declare(strict_types=1);

/**
 * WebCore CMS — MCP Router & Tool Handler
 * 
 * Маршрутизатор инструментов и адаптер к процедурным функциям ядра (kernel/API.php).
 * Расположение в кодовой базе: /kernel/mcp/McpRouter.php
 */
class McpRouter
{
    /**
     * Декларация инструментов для ИИ-агента (tools/list)
     */
    public function getToolsList(): array
    {
        return [
            [
                'name'        => 'get_page',
                'description' => 'Получить контент и SEO-метаданные страницы или раздела по ID или относительному URL (из content_docs или content_folders)',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'page_id'   => ['type' => 'integer', 'description' => 'ID страницы в таблице content_docs'],
                        'folder_id' => ['type' => 'integer', 'description' => 'ID раздела в таблице content_folders'],
                        'url_path'  => ['type' => 'string', 'description' => 'Относительный URL страницы или раздела (например, /about или /contacts)']
                    ]
                ]
            ],
            [
                'name'        => 'create_page',
                'description' => 'Создать новую страницу в CMS WebCore (таблица content_docs)',
                'inputSchema' => [
                    'type'       => 'object',
                    'required'   => ['title', 'content', 'url'],
                    'properties' => [
                        'title'            => ['type' => 'string', 'description' => 'Заголовок страницы (H1 / displayname)'],
                        'content'          => ['type' => 'string', 'description' => 'HTML-контент страницы'],
                        'url'              => ['type' => 'string', 'description' => 'URL страницы (например /new-service)'],
                        'description'      => ['type' => 'string', 'description' => 'Краткое описание / лид'],
                        'meta_title'       => ['type' => 'string', 'description' => 'SEO Title'],
                        'meta_description' => ['type' => 'string', 'description' => 'SEO Description'],
                        'meta_keywords'    => ['type' => 'string', 'description' => 'SEO Keywords'],
                        'is_draft'         => ['type' => 'boolean', 'description' => 'true = черновик (active=0), false = опубликовано (active=1)']
                    ]
                ]
            ],
            [
                'name'        => 'update_page_content',
                'description' => 'Обновить текст/HTML страницы или раздела с созданием точки отката в аудите и сбросом кэша',
                'inputSchema' => [
                    'type'       => 'object',
                    'required'   => ['content'],
                    'properties' => [
                        'page_id'   => ['type' => 'integer', 'description' => 'ID страницы в content_docs'],
                        'folder_id' => ['type' => 'integer', 'description' => 'ID раздела в content_folders'],
                        'url_path'  => ['type' => 'string', 'description' => 'Относительный URL (например /contacts или /about)'],
                        'content'   => ['type' => 'string', 'description' => 'Новый HTML-контент страницы'],
                        'title'     => ['type' => 'string', 'description' => 'Новый заголовок страницы (displayname) — опционально'],
                        'reason'    => ['type' => 'string', 'description' => 'Обоснование правки для журнала аудита']
                    ]
                ]
            ],
            [
                'name'        => 'update_meta_tags',
                'description' => 'Обновить SEO-теги страницы (meta_title, meta_description, meta_keywords)',
                'inputSchema' => [
                    'type'       => 'object',
                    'required'   => ['page_id'],
                    'properties' => [
                        'page_id'     => ['type' => 'integer', 'description' => 'ID страницы'],
                        'title'       => ['type' => 'string', 'description' => 'META Title'],
                        'description' => ['type' => 'string', 'description' => 'META Description'],
                        'keywords'    => ['type' => 'string', 'description' => 'META Keywords'],
                        'reason'      => ['type' => 'string', 'description' => 'Причина изменения']
                    ]
                ]
            ],
            [
                'name'        => 'get_block_vars',
                'description' => 'Прочитать настройки/переменные сквозного блока WebCore CMS через sysBlockGetVar',
                'inputSchema' => [
                    'type'       => 'object',
                    'required'   => ['block_id'],
                    'properties' => [
                        'block_id' => ['type' => 'integer', 'description' => 'ID блока в таблице blocks'],
                        'varname'  => ['type' => 'string', 'description' => 'Имя конкретной переменной (если не указано, вернет весь массив настроек блока)']
                    ]
                ]
            ],
            [
                'name'        => 'update_block_var',
                'description' => 'Обновить переменную блока через sysBlockSetVar (автоматическая сериализация и сброс кэша блоков)',
                'inputSchema' => [
                    'type'       => 'object',
                    'required'   => ['block_id', 'varname', 'varvalue'],
                    'properties' => [
                        'block_id' => ['type' => 'integer', 'description' => 'ID блока'],
                        'varname'  => ['type' => 'string', 'description' => 'Ключ переменной в настройках блока (например: phone, email, text_header)'],
                        'varvalue' => ['type' => 'string', 'description' => 'Новое значение переменной'],
                        'reason'   => ['type' => 'string', 'description' => 'Причина изменения для аудита']
                    ]
                ]
            ],
            [
                'name'        => 'rollback_change',
                'description' => 'Мгновенно откатить изменение по номеру записи аудита (audit_id)',
                'inputSchema' => [
                    'type'       => 'object',
                    'required'   => ['audit_id'],
                    'properties' => [
                        'audit_id' => ['type' => 'integer', 'description' => 'ID записи в таблице sys_ai_audit']
                    ]
                ]
            ]
        ];
    }

    /**
     * Вызов конкретного инструмента (tools/call)
     */
    public function callTool(string $name, array $args): array
    {
        switch ($name) {
            case 'get_page':
                return $this->handleGetPage($args);
            case 'create_page':
                return $this->handleCreatePage($args);
            case 'update_page_content':
                return $this->handleUpdatePageContent($args);
            case 'update_meta_tags':
                return $this->handleUpdateMetaTags($args);
            case 'get_block_vars':
                return $this->handleGetBlockVars($args);
            case 'update_block_var':
                return $this->handleUpdateBlockVar($args);
            case 'rollback_change':
                return $this->handleRollback($args);
            default:
                throw new \InvalidArgumentException("Неизвестный инструмент: {$name}");
        }
    }

    // =========================================================================
    // 1. РАБОТА СО СТРАНИЦАМИ И РАЗДЕЛАМИ (content_docs & content_folders)
    // =========================================================================

    private function handleGetPage(array $args): array
    {
        $foldersTable = $this->resolveTable('content_folders');
        $docsTable    = $this->resolveTable('content_docs');

        $cleanUrl = ltrim(trim((string)($args['url_path'] ?? '')), '/');
        $cleanUrl = rtrim($cleanUrl, '/');
        $cleanUrl = preg_replace('/\.html$/i', '', $cleanUrl);

        // 1. Поиск по разделам content_folders (основные разделы: contacts, about, privacy...)
        if (!empty($args['folder_id'])) {
            $folderId = (int)$args['folder_id'];
            $page     = $this->dbGetRow($foldersTable, "`id` = {$folderId}");
            if ($page) {
                $page['__entity_type'] = 'folder';
                return $this->formatTextResponse(json_encode($page, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }
        } elseif ($cleanUrl !== '') {
            $page = $this->dbGetRow($foldersTable, "`name` = '" . $this->escape($cleanUrl) . "'");
            if ($page) {
                $page['__entity_type'] = 'folder';
                return $this->formatTextResponse(json_encode($page, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }
        }

        // 2. Поиск по документам content_docs
        if (!empty($args['page_id'])) {
            $pageId = (int)$args['page_id'];
            $page   = $this->dbGetRow($docsTable, "`id` = {$pageId}");
        } elseif (!empty($args['url_path'])) {
            $url  = trim((string)$args['url_path']);
            $page = $this->dbGetRow($docsTable, "`url` = '" . $this->escape($url) . "' OR `url` = '/" . $this->escape($cleanUrl) . "'");
        } else {
            throw new \InvalidArgumentException('Укажите page_id, folder_id или url_path.');
        }

        if (!$page) {
            throw new \RuntimeException('Страница или раздел не найдены в базе данных.');
        }

        $page['__entity_type'] = 'doc';
        return $this->formatTextResponse(json_encode($page, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function handleCreatePage(array $args): array
    {
        $docsTable = $this->resolveTable('content_docs');
        $title     = trim((string)($args['title'] ?? ''));
        $content   = (string)($args['content'] ?? '');
        $url       = trim((string)($args['url'] ?? ''));

        if ($title === '' || $url === '') {
            throw new \InvalidArgumentException('title и url обязательны.');
        }

        $active = empty($args['is_draft']) ? 1 : 0;

        $insertData = [
            'displayname'      => $title,
            'name'             => $title,
            'content'          => $content,
            'description'      => (string)($args['description'] ?? ''),
            'url'              => $url,
            'active'           => $active,
            'meta_title'       => (string)($args['meta_title'] ?? $title),
            'meta_description' => (string)($args['meta_description'] ?? ''),
            'meta_keywords'    => (string)($args['meta_keywords'] ?? ''),
            'pub_datetime'     => date('Y-m-d H:i:s')
        ];

        $pageId = $this->dbInsertRow($docsTable, $insertData);

        // Фиксация в аудите
        $auditId = $this->recordAudit(
            module:    'content_docs',
            tableName: $docsTable,
            pkField:   'id',
            entityId:  $pageId,
            action:    'ai_create_page',
            note:      'Создание новой страницы через ИИ-агента',
            prevState: ['__created' => 1],
            newState:  $insertData
        );

        $this->clearCmsCache('content_docs');

        return $this->formatTextResponse(
            "Страница '{$title}' (#{$pageId}) успешно создана (URL: {$url}, статус: " . ($active ? 'опубликована' : 'черновик') . "). Точка отката: #{$auditId}."
        );
    }

    private function handleUpdatePageContent(array $args): array
    {
        $foldersTable = $this->resolveTable('content_folders');
        $docsTable    = $this->resolveTable('content_docs');

        $newContent = (string)($args['content'] ?? '');
        $newTitle   = isset($args['title']) ? trim((string)$args['title']) : null;
        $reason     = (string)($args['reason'] ?? 'AI content update');

        $targetTable = null;
        $targetId    = 0;
        $moduleName  = 'content';
        $current     = null;

        $cleanUrl = ltrim(trim((string)($args['url_path'] ?? '')), '/');
        $cleanUrl = rtrim($cleanUrl, '/');
        $cleanUrl = preg_replace('/\.html$/i', '', $cleanUrl);

        // 1. Проверяем раздел content_folders
        if (!empty($args['folder_id'])) {
            $targetId = (int)$args['folder_id'];
            $current  = $this->dbGetRow($foldersTable, "`id` = {$targetId}");
            if ($current) {
                $targetTable = $foldersTable;
                $moduleName  = 'content_folders';
            }
        } elseif ($cleanUrl !== '') {
            $current = $this->dbGetRow($foldersTable, "`name` = '" . $this->escape($cleanUrl) . "'");
            if ($current) {
                $targetTable = $foldersTable;
                $targetId    = (int)$current['id'];
                $moduleName  = 'content_folders';
            }
        }

        // 2. Если не раздел, проверяем документ content_docs
        if (!$targetTable) {
            if (!empty($args['page_id'])) {
                $targetId = (int)$args['page_id'];
                $current  = $this->dbGetRow($docsTable, "`id` = {$targetId}");
            } elseif (!empty($args['url_path'])) {
                $url     = trim((string)$args['url_path']);
                $current = $this->dbGetRow($docsTable, "`url` = '" . $this->escape($url) . "' OR `url` = '/" . $this->escape($cleanUrl) . "'");
                if ($current) {
                    $targetId = (int)$current['id'];
                }
            }

            if ($current) {
                $targetTable = $docsTable;
                $moduleName  = 'content_docs';
            }
        }

        if (!$targetTable || !$current) {
            throw new \RuntimeException('Страница или раздел для обновления не найдены в БД.');
        }

        // Формируем точечный diff изменяемых полей
        $prevState  = ['content' => $current['content'] ?? ''];
        $updateData = ['content' => $newContent];

        if ($newTitle !== null) {
            $prevState['displayname']  = $current['displayname'] ?? '';
            $updateData['displayname'] = $newTitle;
        }

        // Записываем аудит до изменения
        $auditId = $this->recordAudit(
            module:    $moduleName,
            tableName: $targetTable,
            pkField:   'id',
            entityId:  $targetId,
            action:    'ai_update_content',
            note:      $reason,
            prevState: $prevState,
            newState:  $updateData
        );

        // Обновляем строку таблицы через нативную функцию ядра
        $this->dbUpdateRow($targetTable, $updateData, "WHERE id='{$targetId}'");

        // Сбрасываем кэш
        $this->clearCmsCache('content');
        $this->clearCmsCache('content_folders');
        $this->clearCmsCache('content_docs');

        return $this->formatTextResponse(
            "Контент страницы/раздела #{$targetId} ({$moduleName}) успешно обновлен. Точка отката (audit_id): #{$auditId}."
        );
    }

    private function handleUpdateMetaTags(array $args): array
    {
        $docsTable = $this->resolveTable('content_docs');
        $pageId    = (int)($args['page_id'] ?? 0);
        $reason    = (string)($args['reason'] ?? 'AI SEO meta update');
        $current   = $this->dbGetRow($docsTable, "`id` = {$pageId}");

        if (!$current) {
            throw new \RuntimeException("Страница #{$pageId} не найдена в БД.");
        }

        $prevState  = [];
        $updateData = [];

        $fieldMap = [
            'title'       => 'meta_title',
            'description' => 'meta_description',
            'keywords'    => 'meta_keywords'
        ];

        foreach ($fieldMap as $argKey => $dbColumn) {
            if (isset($args[$argKey])) {
                $prevState[$dbColumn]  = $current[$dbColumn] ?? '';
                $updateData[$dbColumn] = trim((string)$args[$argKey]);
            }
        }

        if (empty($updateData)) {
            throw new \InvalidArgumentException('Не передано ни одного мета-тега для обновления.');
        }

        $auditId = $this->recordAudit(
            module:    'content_docs_seo',
            tableName: $docsTable,
            pkField:   'id',
            entityId:  $pageId,
            action:    'ai_update_meta',
            note:      $reason,
            prevState: $prevState,
            newState:  $updateData
        );

        $this->dbUpdateRow($docsTable, $updateData, "WHERE id='{$pageId}'");
        $this->clearCmsCache('content_docs');

        return $this->formatTextResponse(
            "SEO-метатеги страницы #{$pageId} обновлены. Точка отката (audit_id): #{$auditId}."
        );
    }

    // =========================================================================
    // 2. РАБОТА С БЛОКАМИ ЯДРА (sysBlockGetVar / sysBlockSetVar)
    // =========================================================================

    private function handleGetBlockVars(array $args): array
    {
        $blockId = (int)($args['block_id'] ?? 0);
        $varname = isset($args['varname']) && $args['varname'] !== '' ? (string)$args['varname'] : null;

        if (!function_exists('sysBlockGetVar')) {
            throw new \RuntimeException('Функция sysBlockGetVar не найдена в kernel/API.php.');
        }

        $data = sysBlockGetVar($blockId, $varname);

        return $this->formatTextResponse(json_encode([
            'block_id' => $blockId,
            'varname'  => $varname,
            'data'     => $data
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function handleUpdateBlockVar(array $args): array
    {
        $blockId  = (int)($args['block_id'] ?? 0);
        $varname  = trim((string)($args['varname'] ?? ''));
        $varvalue = (string)($args['varvalue'] ?? '');
        $reason   = (string)($args['reason'] ?? 'AI block variable update');

        if ($blockId <= 0 || $varname === '') {
            throw new \InvalidArgumentException('Параметры block_id и varname обязательны.');
        }

        if (!function_exists('sysBlockGetVar') || !function_exists('sysBlockSetVar')) {
            throw new \RuntimeException('Функции sysBlockGetVar / sysBlockSetVar не найдены в kernel/API.php.');
        }

        // Читаем текущее значение переменной блока для сохранения diff в аудит
        $oldValue = sysBlockGetVar($blockId, $varname);

        $auditId = $this->recordAudit(
            module:    'blocks',
            tableName: 'sys_block_var', // Маркер отката через sysBlockSetVar
            pkField:   'block_id',
            entityId:  $blockId,
            action:    'ai_update_block_var',
            note:      $reason,
            prevState: [$varname => $oldValue],
            newState:  [$varname => $varvalue]
        );

        // Нативная функция ядра sysBlockSetVar сериализует данные, вызывает sysDbUpdate и очищает sysVarDelCached('blocks_list', 'dbblockslist')
        sysBlockSetVar($blockId, $varname, $varvalue);

        return $this->formatTextResponse(
            "Переменная '{$varname}' в блоке #{$blockId} успешно обновлена (кэш сброшен). Точка отката (audit_id): #{$auditId}."
        );
    }

    // =========================================================================
    // 3. АУДИТ И МГНОВЕННЫЙ ОТКАТ (ROLLBACK)
    // =========================================================================

    private function handleRollback(array $args): array
    {
        $auditTable = $this->resolveTable('ai_audit');
        $auditId    = (int)($args['audit_id'] ?? 0);
        $record     = $this->dbGetRow($auditTable, "`id` = {$auditId}");

        if (!$record) {
            throw new \RuntimeException("Запись аудита #{$auditId} не найдена.");
        }

        if ((int)$record['is_rolled_back'] === 1) {
            throw new \RuntimeException("Запись аудита #{$auditId} уже была откачена ранее.");
        }

        $prevState = json_decode((string)$record['prev_state'], true);
        if (!is_array($prevState) || empty($prevState)) {
            throw new \RuntimeException("Не удалось декодировать prev_state для записи #{$auditId}.");
        }

        $entityId  = (int)$record['entity_id'];
        $tableName = (string)$record['table_name'];
        $pkField   = (string)$record['pk_field'];

        // Откат создания новой страницы (удаление созданной записи)
        if (isset($prevState['__created'])) {
            $this->dbDeleteRow($tableName, "WHERE `{$pkField}` = {$entityId}");
            $this->clearCmsCache('content_docs');
        } elseif ($tableName === 'sys_block_var') {
            // Откат переменной блока
            foreach ($prevState as $varName => $oldVal) {
                sysBlockSetVar($entityId, (string)$varName, $oldVal);
            }
        } else {
            // Откат полей обычной таблицы БД
            $this->dbUpdateRow($tableName, $prevState, "WHERE `{$pkField}` = {$entityId}");
            $this->clearCmsCache((string)$record['module']);
        }

        // Помечаем запись в журнале аудита как откаченную
        $this->dbUpdateRow($auditTable, ['is_rolled_back' => 1], "WHERE `id` = {$auditId}");

        return $this->formatTextResponse(
            "Откат по записи аудита #{$auditId} (модуль: {$record['module']}, ID: #{$entityId}) успешно выполнен."
        );
    }

    private function recordAudit(
        string $module,
        string $tableName,
        string $pkField,
        int $entityId,
        string $action,
        string $note,
        array $prevState,
        array $newState
    ): int {
        $auditTable = $this->resolveTable('ai_audit');
        $data = [
            'module'         => $module,
            'table_name'     => $tableName,
            'pk_field'       => $pkField,
            'entity_id'      => $entityId,
            'action'         => $action,
            'author'         => 'agent',
            'note'           => $note,
            'prev_state'     => json_encode($prevState, JSON_UNESCAPED_UNICODE),
            'new_state'      => json_encode($newState, JSON_UNESCAPED_UNICODE),
            'is_rolled_back' => 0,
            'created_at'     => date('Y-m-d H:i:s')
        ];

        return $this->dbInsertRow($auditTable, $data);
    }

    // =========================================================================
    // 4. АДАПТЕРЫ К СИСТЕМНЫМ ФУНКЦИЯМ KERNEL/API.PHP
    // =========================================================================

    private function resolveTable(string $name): string
    {
        global $sysConfig;
        $prefix = $sysConfig['DB.TablePrefix'] ?? 'sys';

        // Если таблица уже содержит системный префикс
        if (strpos($name, $prefix . '_') === 0) {
            return $name;
        }

        if (function_exists('sysDBGetTable')) {
            return sysDBGetTable($name);
        }

        return $prefix . '_' . $name;
    }

    private function dbUpdateRow(string $table, array $data, string $where): void
    {
        if (function_exists('sysDBUpdate')) {
            sysDBUpdate($table, $data, $where);
            return;
        }
        throw new \RuntimeException('Системная функция sysDBUpdate не найдена в kernel/API.php.');
    }

    private function dbInsertRow(string $table, array $data): int
    {
        if (function_exists('sysDBInsert')) {
            return (int)sysDBInsert($table, $data);
        }
        throw new \RuntimeException('Системная функция sysDBInsert не найдена в kernel/API.php.');
    }

    private function dbDeleteRow(string $table, string $where): void
    {
        if (function_exists('sysDBDelete')) {
            sysDBDelete($table, $where);
            return;
        }
        global $dbconn;
        mysqli_query($dbconn, "DELETE FROM `{$table}` {$where}");
    }

    private function dbGetRow(string $table, string $where): ?array
    {
        global $dbconn;
        $cleanWhere = preg_replace('/^\s*WHERE\s+/i', '', trim($where));
        $sql = "SELECT * FROM `{$table}` WHERE {$cleanWhere} LIMIT 1";
        $res = mysqli_query($dbconn, $sql);
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            return $row;
        }
        return null;
    }

    private function clearCmsCache(string $cacheKey = ''): void
    {
        if (function_exists('sysVarDelCached')) {
            if ($cacheKey !== '') {
                sysVarDelCached($cacheKey);
            }
            sysVarDelCached('blocks_list', 'dbblockslist');
        }
    }

    private function escape(string $val): string
    {
        global $dbconn;
        if ($dbconn instanceof \mysqli) {
            return mysqli_real_escape_string($dbconn, $val);
        }
        return addslashes($val);
    }

    private function formatTextResponse(string $text): array
    {
        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => $text
                ]
            ]
        ];
    }
}
