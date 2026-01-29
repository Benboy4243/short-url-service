<?php
declare(strict_types=1);

/**
 * Headers
 */
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

/**
 * Récupération du chemin sans query string
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

/**
 * ROUTING
 */
if ($uri === '/shorten' && $method === 'POST') {
    handleShorten();
    exit;
}

/**
 * Fallback
 */
http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'message' => 'Short URL API is running'
]);
exit;

/**
 * =========================
 * Handlers
 * =========================
 */
function handleShorten(): void
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        return;
    }

    $url = trim($data['url'] ?? '');

    if ($url === '') {
        http_response_code(400);
        echo json_encode(['error' => 'URL vide']);
        return;
    }

    /**
     * Ici viendront PLUS TARD :
     * - validation URL (teammate)
     * - captcha (teammate)
     * - stockage DB
     */

    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'message' => 'URL bien reçue',
        'data' => [
            'original_url' => $url
        ]
    ]);
}
