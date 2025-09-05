<?php
require 'db.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/api/users' && $method === 'GET') {
    getUsers($pdo);
} elseif ($uri === '/api/users' && $method === 'POST') {
    createUser($pdo);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
}

function getUsers($pdo)
{
    $stmt = $pdo->query('SELECT * FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

function createUser($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
    $stmt->execute([$data['name'], $data['email']]);
    echo json_encode(['message' => 'User created']);
}
