<?php
declare(strict_types=1);

// Debug temporaire
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Base path
// $basePath = '/api/short-url';
// $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $request = str_replace($basePath, '', $requestUri);
// if ($request === '') $request = '/';

// $allowedOrigins = [
//     'http://localhost:5173',
//     'https://www.zeroaheros.ca'
// ];
// if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins, true)) {
//     header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
// }

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/src/Config/database.php';
require_once __DIR__ . '/src/Services/Database.php';
require_once __DIR__ . '/src/Services/ShortUrlService.php';
require_once __DIR__ . '/src/Controllers/ShortUrlController.php';

$dbService = new Database($pdo);
$shortService = new ShortUrlService($dbService);
$controller = new ShortUrlController($shortService);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && $request === '/shorten') {
    $controller->shorten();
} elseif ($method === 'GET' && preg_match('#^/([a-zA-Z0-9]{6})$#', $request, $matches)) {
    $controller->redirect($matches[1]);
} else {
    echo json_encode(['status' => 'ok', 'message' => 'API Short URL en ligne']);
}
