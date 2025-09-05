<?php
require 'jwt_utils.php';

header('Content-Type: application/json');

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $jwt = $matches[1];
    $decoded = validate_jwt($jwt, $secret_key);

    if ($decoded) {
        echo json_encode(['message' => 'Accès autorisé', 'user' => $decoded]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Token invalide']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Token manquant']);
}
