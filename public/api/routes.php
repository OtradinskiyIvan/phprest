<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../include/controllers/UserController.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($uri, '/');
$prefix = 'api/v1';

if (strpos($path, $prefix) !== 0) {
    sendJson(404, ['status' => 'error', 'message' => 'Endpoint not found']);
}

$resourcePath = trim(substr($path, strlen($prefix)), '/');
$segments = $resourcePath === '' ? [] : explode('/', $resourcePath);

$rawInput = file_get_contents('php://input');
$input = [];
if ($rawInput !== '') {
    $decoded = json_decode($rawInput, true);
    if ($decoded === null && trim($rawInput) !== '') {
        sendJson(400, ['status' => 'error', 'message' => 'Invalid JSON body']);
    }
    $input = $decoded ?? [];
}

switch ($segments[0] ?? null) {
    case 'register':
        if ($method !== 'POST') {
            methodNotAllowed(['POST']);
        }
        UserController::register($input);
        break;

    case 'login':
        if ($method !== 'POST') {
            methodNotAllowed(['POST']);
        }
        UserController::login($input);
        break;

    case 'users':
        $id = isset($segments[1]) ? intval($segments[1]) : null;
        if ($id === null) {
            if ($method !== 'GET') {
                methodNotAllowed(['GET']);
            }
            UserController::index();
            break;
        }

        switch ($method) {
            case 'GET':
                UserController::show($id);
                break;
            case 'PUT':
            case 'PATCH':
                UserController::update($id, $input);
                break;
            case 'DELETE':
                UserController::destroy($id);
                break;
            default:
                methodNotAllowed(['GET', 'PUT', 'PATCH', 'DELETE']);
        }
        break;

    default:
        sendJson(404, ['status' => 'error', 'message' => 'Endpoint not found']);
}

function sendJson(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function methodNotAllowed(array $methods): void
{
    header('Allow: ' . implode(', ', $methods));
    sendJson(405, ['status' => 'error', 'message' => 'Method not allowed']);
}
