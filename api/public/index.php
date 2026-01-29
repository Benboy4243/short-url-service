<?php
declare(strict_types=1);

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

/**
 * Preflight (CORS)
 */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


require_once __DIR__ . '/../src/Config/database.php'; // fournit le $pdo
require_once __DIR__ . '/../src/Services/Database.php';
require_once __DIR__ . '/../src/Services/ShortUrlService.php';
require_once __DIR__ . '/../src/Controllers/ShortUrlController.php';

$dbService = new Database($pdo);
$shortService = new ShortUrlService($dbService);
$controller = new ShortUrlController($shortService);

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && $request === '/shorten') 
{
    $controller->shorten();
} 
elseif ($method === 'GET' && preg_match('#^/([a-zA-Z0-9]{6})$#', $request, $matches)) 
{
    $controller->redirect($matches[1]);
} 
else 
{
    echo json_encode(['status' => 'ok', 'message' => 'API Short URL en ligne']);
}