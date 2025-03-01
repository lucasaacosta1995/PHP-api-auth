<?php

namespace AuthSystem;

require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {
    private static $secretKey = "authsystemportafolio";
    private static $algorithm = "HS256";

    public static function generateToken($userId) {
        $expirationTime = time() + (60 * 60); // Expira en 1 hora
        $payload = [
            'iss' => "tu_sistema",
            'iat' => time(),
            'exp' => $expirationTime,
            'sub' => $userId
        ];
        return [
            'token' => JWT::encode($payload, self::$secretKey, self::$algorithm),
            'exp' => $expirationTime
        ];
    }

    public static function verifyToken($token) {
        try {
            return JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
        } catch (\Exception $e) {
            return false;
        }
    }
}

// Recibir el parámetro de consola
if ($argc < 2) {
    echo "Se debe proporcionar un userId como parámetro.\n";
    exit(1);
}

$userId = $argv[1]; // Primer argumento de la consola

// Generar el token
$result = Auth::generateToken($userId);
$token = $result['token'];
$expirationTime = $result['exp'];

echo "Token generado: " . $token . "\n";
echo "Fecha de caducidad: " . date('Y-m-d H:i:s', $expirationTime) . "\n";

// Verificar el token
$decoded = Auth::verifyToken($token);
if ($decoded) {
    echo "Token válido para el usuario: " . $decoded->sub . "\n";
} else {
    echo "Token inválido\n";
}
