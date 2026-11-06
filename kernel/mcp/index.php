<?php
declare(strict_types=1);

/**
 * WebCore CMS — Native Model Context Protocol (MCP) Endpoint
 * 
 * Точка входа для взаимодействия ИИ-агентов с ядром WebCore CMS.
 * Расположение в кодовой базе: /kernel/mcp/index.php
 */

// 1. Переходим в корень CMS и инициализируем ядро
chdir(dirname(__DIR__, 2));

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

if (file_exists('kernel/API.php')) {
    require_once 'kernel/API.php';
    if (function_exists('sysInit')) {
        sysInit();
    }
} elseif (file_exists(__DIR__ . '/../API.php')) {
    require_once __DIR__ . '/../API.php';
    if (function_exists('sysInit')) {
        sysInit();
    }
}

require_once __DIR__ . '/McpRouter.php';

header('Content-Type: application/json; charset=UTF-8');

/**
 * Надежное извлечение Bearer токена с поддержкой Apache / FastCGI / PHP-FPM / Nginx
 */
function getBearerToken(): string {
    $header = $_SERVER['HTTP_AUTHORIZATION'] 
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] 
        ?? $_SERVER['HTTP_X_AI_TOKEN']
        ?? '';

    if (!$header && function_exists('getallheaders')) {
        $headers = getallheaders();
        $header = $headers['Authorization'] 
            ?? $headers['authorization'] 
            ?? $headers['X-AI-Token'] 
            ?? $headers['x-ai-token'] 
            ?? '';
    }

    if (preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
        return $matches[1];
    }
    if ($header !== '') {
        return $header;
    }
    return (string)($_GET['token'] ?? '');
}

// 2. Проверка авторизации
$expectedToken = defined('WEBCORE_AI_TOKEN') ? (string)WEBCORE_AI_TOKEN : (string)getenv('WEBCORE_AI_TOKEN');
$providedToken = getBearerToken();

if ($expectedToken === '' || !hash_equals($expectedToken, $providedToken)) {
    http_response_code(401);
    echo json_encode([
        'jsonrpc' => '2.0',
        'error'   => ['code' => -32000, 'message' => 'Unauthorized: Invalid AI Bearer Token'],
        'id'      => null
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. Чтение и парсинг JSON-RPC запроса
$rawInput = file_get_contents('php://input');
$request  = json_decode((string)$rawInput, true);

if (!is_array($request) || ($request['jsonrpc'] ?? '') !== '2.0' || !isset($request['method'])) {
    http_response_code(400);
    echo json_encode([
        'jsonrpc' => '2.0',
        'error'   => ['code' => -32600, 'message' => 'Invalid JSON-RPC Request'],
        'id'      => $request['id'] ?? null
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 4. Обработка уведомлений клиента (например, notifications/initialized), где нет поля id
if (!array_key_exists('id', $request)) {
    http_response_code(202);
    exit;
}

$method = (string)$request['method'];
$params = (array)($request['params'] ?? []);
$id     = $request['id'];

$router = new McpRouter();

// 5. Маршрутизация методов протокола MCP
try {
    switch ($method) {
        case 'initialize':
            $response = [
                'protocolVersion' => '2024-11-05',
                'capabilities'    => [
                    'tools' => (object)[]
                ],
                'serverInfo'      => [
                    'name'    => 'WebCore-CMS-Native-MCP',
                    'version' => '1.0.0'
                ]
            ];
            break;

        case 'tools/list':
            $response = ['tools' => $router->getToolsList()];
            break;

        case 'tools/call':
            $toolName  = (string)($params['name'] ?? '');
            $arguments = (array)($params['arguments'] ?? []);
            $response  = $router->callTool($toolName, $arguments);
            break;

        case 'ping':
            $response = (object)[];
            break;

        default:
            http_response_code(404);
            echo json_encode([
                'jsonrpc' => '2.0',
                'error'   => ['code' => -32601, 'message' => "Method not found: {$method}"],
                'id'      => $id
            ], JSON_UNESCAPED_UNICODE);
            exit;
    }

    echo json_encode([
        'jsonrpc' => '2.0',
        'result'  => $response,
        'id'      => $id
    ], JSON_UNESCAPED_UNICODE);

} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'jsonrpc' => '2.0',
        'error'   => ['code' => -32603, 'message' => $e->getMessage()],
        'id'      => $id
    ], JSON_UNESCAPED_UNICODE);
}
