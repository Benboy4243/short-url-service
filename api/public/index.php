<?php

declare(strict_types=1);

header('Content-Type: application/json');

$request = $_SERVER['REQUEST_URI'];

// Endpoint POST /shorten
if ($request === '/shorten' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $url = $data['url'] ?? '';

    if (!empty($url)) {
        // Ici, on stockerait l'URL dans la DB
        // Exemple (à décommenter plus tard) :
        /*
        $pdo = new PDO('mysql:host=localhost;dbname=short_url', 'root', '');
        $stmt = $pdo->prepare("INSERT INTO short_urls (original_url) VALUES (?)");
        $stmt->execute([$url]);
        */

        echo json_encode(['message' => "OK, bien reçu : $url"]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'URL vide']);
    }
} else {

    echo json_encode([
        'status' => 'ok',
        'message' => 'Short URL API is running'
    ]);
}
