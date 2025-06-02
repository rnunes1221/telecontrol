<?php
require_once 'core/HttpException.php';

class AuthMiddleware {
    public static function verify() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new HttpException("Token não informado", 401);
        }

        $jwt = substr($authHeader, 7);
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new HttpException("Token inválido", 401);
        }

        [$header, $payload, $signature] = $parts;
        $validSignature = base64_encode(hash_hmac('sha256', "$header.$payload", 'telecontrol-teste', true));
        if (!hash_equals($validSignature, $signature)) {
            throw new HttpException("Assinatura inválida", 401);
        }

        $data = json_decode(base64_decode($payload), true);
        if ($data['exp'] < time()) {
            throw new HttpException("Token expirado", 401);
        }

        return $data;
    }
}
