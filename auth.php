<?php
require 'db.php';
require 'jwt_utils.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

if ($method === 'POST' && $_GET['action'] === 'register') {
    $name = $input['name'];
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$name, $email, $password]);
        echo json_encode(['message' => 'Inscription réussie']);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(['error' => 'Email déjà utilisé']);
    }
}

if ($method === 'POST' && $_GET['action'] === 'login') {
    $email = $input['email'];
    $password = $input['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $token = generate_jwt(['user_id' => $user['id'], 'email' => $user['email']], $secret_key);
        echo json_encode(['token' => $token]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Identifiants invalides']);
    }
}
