<?php
require_once 'services/AuthService.php';
require_once 'core/HttpException.php';

class AuthController {
    private $service;

    public function __construct() {
        $this->service = new AuthService();
        header('Content-Type: application/json');
    }

    public function login() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $token = $this->service->login($data['email'] ?? '', $data['password'] ?? '');
            echo json_encode(['token' => $token]);
        } catch (HttpException $e) {
            http_response_code($e->getCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
