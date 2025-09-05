<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'vendor/autoload.php';

$secret_key = "VOTRE_CLE_SECRETE";

function generate_jwt($payload, $secret_key)
{
    $issuedAt = time();
    $expire = $issuedAt + (60 * 60); // 1 hour

    $token = array_merge($payload, [
        "iat" => $issuedAt,
        "exp" => $expire
    ]);

    return JWT::encode($token, $secret_key, 'HS256');
}

function validate_jwt($jwt, $secret_key)
{
    try {
        return JWT::decode($jwt, new Key($secret_key, 'HS256'));
    } catch (Exception $e) {
        return null;
    }
}
