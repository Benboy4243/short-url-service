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
 * DB Connection Helper
 */
function getDB(): ?PDO {
    try {
        // Updated to use your singular database name 'short_url'
        $pdo = new PDO('mysql:host=localhost;dbname=short_url', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (Exception $e) {
        return null;
    }
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

// Add the /list route so the history works
if ($uri === '/list' && $method === 'GET') {
    handleList();
    exit;
}

/**
 * Fallback
 */
http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'message' => 'Short URL API is running',
    'db' => (getDB() !== null) ? 'connected' : 'disconnected'
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

    // 1. Generate the unique slug
    $slug = substr(md5($url . microtime()), 0, 6);
    $shortenedUrl = "http://localhost:8000/u/$slug";

    // 2. Database Action
    $pdo = getDB();
    if (!$pdo) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        // Match column names 'slug' and 'original_url' from your schema.sql
        $stmt = $pdo->prepare("INSERT INTO short_urls (original_url, slug) VALUES (?, ?)");
        $stmt->execute([$url, $slug]);

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'short_url' => $shortenedUrl, // singular to match frontend
            'data' => [
                'original_url' => $url,
                'slug' => $slug
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Storage error: ' . $e->getMessage()]);
    }
}

function handleList(): void
{
    $pdo = getDB();
    if (!$pdo) {
        echo json_encode(['urls' => []]);
        return;
    }

    $stmt = $pdo->query("SELECT id, original_url, CONCAT('http://localhost:8000/u/', slug) as short_url, created_at FROM short_urls ORDER BY created_at DESC LIMIT 50");
    $urls = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['urls' => $urls]);
}