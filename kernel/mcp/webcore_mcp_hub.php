<?php
declare(strict_types=1);

/**
 * WebCore CMS — Agency MCP Gateway / CLI Stdio Hub
 * 
 * Локальный CLI-мост (stdio) для ИИ-агентов (OpenCode, Claude Desktop, nanobot).
 * Позволяет управлять всеми сайтами агентства через единый MCP-сервер,
 * предотвращая раздувание системного промпта десятками одинаковых инструментов.
 * 
 * Запуск в конфигурации агента:
 * "webcore-hub": {
 *   "command": "php",
 *   "args": ["/path/to/company/projects/webcore-cms/kernel/mcp/webcore_mcp_hub.php"]
 * }
 */

// Конфигурация сайтов клиентов агентства (может подгружаться из внешнего json/env)
$config = [
    'sites' => [
        'psk' => [
            'name'     => 'ПСК Стройкомплекс',
            'endpoint' => getenv('WEBCORE_ENDPOINT_PSK') ?: 'https://psk-nsk.ru/kernel/mcp/',
            'token'    => getenv('WEBCORE_TOKEN_PSK') ?: ''
        ],
        'ulybino' => [
            'name'     => 'Улыбино',
            'endpoint' => getenv('WEBCORE_ENDPOINT_ULYBINO') ?: 'https://ulibino-opt.ru/kernel/mcp/',
            'token'    => getenv('WEBCORE_TOKEN_ULYBINO') ?: ''
        ],
        'vega' => [
            'name'     => 'ВЕГА',
            'endpoint' => getenv('WEBCORE_ENDPOINT_VEGA') ?: 'https://vega-agro.ru/kernel/mcp/',
            'token'    => getenv('WEBCORE_TOKEN_VEGA') ?: ''
        ]
    ]
];

function sendJsonRpc(array $payload): void {
    $out = json_encode($payload, JSON_UNESCAPED_UNICODE);
    fwrite(STDOUT, $out . "\n");
    fflush(STDOUT);
}

function callRemoteMcp(string $url, string $token, array $request): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($request, JSON_UNESCAPED_UNICODE),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true
    ]);

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($curlErr !== '') {
        return [
            'jsonrpc' => '2.0',
            'error'   => ['code' => -32001, 'message' => "cURL error to {$url}: {$curlErr}"],
            'id'      => $request['id'] ?? null
        ];
    }

    $decoded = json_decode((string)$raw, true);
    if (!is_array($decoded)) {
        return [
            'jsonrpc' => '2.0',
            'error'   => ['code' => -32002, 'message' => "HTTP {$httpCode}: Invalid response from {$url}"],
            'id'      => $request['id'] ?? null
        ];
    }

    return $decoded;
}

// Главный цикл чтения Stdio (MCP protocol)
while ($line = fgets(STDIN)) {
    $line = trim($line);
    if ($line === '') {
        continue;
    }

    $req = json_decode($line, true);
    if (!is_array($req) || !isset($req['method'])) {
        continue;
    }

    // Если это уведомление (без id)
    if (!array_key_exists('id', $req)) {
        continue;
    }

    $method = $req['method'];
    $id     = $req['id'];
    $params = $req['params'] ?? [];

    switch ($method) {
        case 'initialize':
            sendJsonRpc([
                'jsonrpc' => '2.0',
                'id'      => $id,
                'result'  => [
                    'protocolVersion' => '2024-11-05',
                    'capabilities'    => ['tools' => (object)[]],
                    'serverInfo'      => [
                        'name'    => 'WebCore-Agency-Hub',
                        'version' => '1.0.0'
                    ]
                ]
            ]);
            break;

        case 'tools/list':
            // Базовый набор инструментов хаба с обязательным указанием site_alias
            sendJsonRpc([
                'jsonrpc' => '2.0',
                'id'      => $id,
                'result'  => [
                    'tools' => [
                        [
                            'name'        => 'webcore_get_page',
                            'description' => 'Получить контент страницы сайта клиента на WebCore CMS',
                            'inputSchema' => [
                                'type'       => 'object',
                                'required'   => ['site_alias'],
                                'properties' => [
                                    'site_alias' => ['type' => 'string', 'description' => 'Алиас сайта: psk, ulybino, vega'],
                                    'page_id'    => ['type' => 'integer'],
                                    'url_path'   => ['type' => 'string']
                                ]
                            ]
                        ],
                        [
                            'name'        => 'webcore_update_page_content',
                            'description' => 'Обновить HTML-текст страницы на сайте клиента',
                            'inputSchema' => [
                                'type'       => 'object',
                                'required'   => ['site_alias', 'page_id', 'content'],
                                'properties' => [
                                    'site_alias' => ['type' => 'string'],
                                    'page_id'    => ['type' => 'integer'],
                                    'content'    => ['type' => 'string'],
                                    'title'      => ['type' => 'string'],
                                    'reason'     => ['type' => 'string']
                                ]
                            ]
                        ],
                        [
                            'name'        => 'webcore_update_meta_tags',
                            'description' => 'Обновить SEO-метатеги страницы на сайте клиента',
                            'inputSchema' => [
                                'type'       => 'object',
                                'required'   => ['site_alias', 'page_id'],
                                'properties' => [
                                    'site_alias'  => ['type' => 'string'],
                                    'page_id'     => ['type' => 'integer'],
                                    'title'       => ['type' => 'string'],
                                    'description' => ['type' => 'string'],
                                    'keywords'    => ['type' => 'string'],
                                    'reason'      => ['type' => 'string']
                                ]
                            ]
                        ],
                        [
                            'name'        => 'webcore_get_block_vars',
                            'description' => 'Прочитать настройки сквозного блока WebCore CMS на сайте клиента',
                            'inputSchema' => [
                                'type'       => 'object',
                                'required'   => ['site_alias', 'block_id'],
                                'properties' => [
                                    'site_alias' => ['type' => 'string'],
                                    'block_id'   => ['type' => 'integer'],
                                    'varname'    => ['type' => 'string']
                                ]
                            ]
                        ],
                        [
                            'name'        => 'webcore_update_block_var',
                            'description' => 'Обновить переменную блока (телефон, email, баннер) на сайте клиента',
                            'inputSchema' => [
                                'type'       => 'object',
                                'required'   => ['site_alias', 'block_id', 'varname', 'varvalue'],
                                'properties' => [
                                    'site_alias' => ['type' => 'string'],
                                    'block_id'   => ['type' => 'integer'],
                                    'varname'    => ['type' => 'string'],
                                    'varvalue'   => ['type' => 'string'],
                                    'reason'     => ['type' => 'string']
                                ]
                            ]
                        ],
                        [
                            'name'        => 'webcore_rollback_change',
                            'description' => 'Откатить изменение по audit_id на сайте клиента',
                            'inputSchema' => [
                                'type'       => 'object',
                                'required'   => ['site_alias', 'audit_id'],
                                'properties' => [
                                    'site_alias' => ['type' => 'string'],
                                    'audit_id'   => ['type' => 'integer']
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
            break;

        case 'tools/call':
            $toolName = (string)($params['name'] ?? '');
            $args     = (array)($params['arguments'] ?? []);
            $site     = (string)($args['site_alias'] ?? '');

            if (!isset($config['sites'][$site])) {
                sendJsonRpc([
                    'jsonrpc' => '2.0',
                    'id'      => $id,
                    'result'  => [
                        'isError' => true,
                        'content' => [['type' => 'text', 'text' => "Неизвестный сайт: '{$site}'. Доступны: " . implode(', ', array_keys($config['sites']))]]
                    ]
                ]);
                break;
            }

            $siteCfg = $config['sites'][$site];
            unset($args['site_alias']);

            // Преобразование префиксного имени инструмента в нативное имя для WebCore
            $remoteToolName = preg_replace('/^webcore_/', '', $toolName);

            $remoteReq = [
                'jsonrpc' => '2.0',
                'id'      => 1,
                'method'  => 'tools/call',
                'params'  => [
                    'name'      => $remoteToolName,
                    'arguments' => $args
                ]
            ];

            $remoteRes = callRemoteMcp($siteCfg['endpoint'], $siteCfg['token'], $remoteReq);

            if (isset($remoteRes['error'])) {
                sendJsonRpc([
                    'jsonrpc' => '2.0',
                    'id'      => $id,
                    'result'  => [
                        'isError' => true,
                        'content' => [['type' => 'text', 'text' => "Ошибка сайта {$site}: " . json_encode($remoteRes['error'], JSON_UNESCAPED_UNICODE)]]
                    ]
                ]);
            } else {
                sendJsonRpc([
                    'jsonrpc' => '2.0',
                    'id'      => $id,
                    'result'  => $remoteRes['result'] ?? ['content' => [['type' => 'text', 'text' => 'Пустой ответ от CMS']]]
                ]);
            }
            break;

        case 'ping':
            sendJsonRpc(['jsonrpc' => '2.0', 'id' => $id, 'result' => (object)[]]);
            break;

        default:
            sendJsonRpc([
                'jsonrpc' => '2.0',
                'id'      => $id,
                'error'   => ['code' => -32601, 'message' => "Method not found: {$method}"]
            ]);
            break;
    }
}
