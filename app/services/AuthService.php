<?php
require_once 'repositories/UserRepository.php';
require_once 'core/HttpException.php';

class AuthService {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function login($email, $password) {
        if (!$email || !$password) {
            throw new HttpException("Email e senha obrigatórios", 400);
        }

        $user = $this->userRepository->findByEmail($email);
        if (!$user || $user['password'] !== $password) {
            throw new HttpException("Credenciais inválidas", 401);
        }

        $payload = [
            'sub' => $user['id'],
            'email' => $user['email'],
            'iat' => time(),
            'exp' => time() + (60 * 60)
        ];

        return $this->generateJWT($payload);
    }

    private function generateJWT($payload) {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", 'telecontrol-teste', true);
        $signature = base64_encode($signature);
        return "$header.$payload.$signature";
    }
}
