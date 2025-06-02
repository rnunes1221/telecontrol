<?php
require_once 'core/AuthMiddleware.php';
require_once 'services/ProductService.php';
require_once 'core/HttpException.php';

class ProductController {
    private $service;

    public function __construct() {
        $this->service = new ProductService();
        header('Content-Type: application/json');
    }

    public function find($id) {
        try {
            AuthMiddleware::verify();
            $product = $this->service->findById($id);
            if (!$product) {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found']);
                return;
            }
            echo json_encode($product);
        } catch (HttpException $e) {
            http_response_code($e->getCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function index() {
        try {
            AuthMiddleware::verify();
            echo json_encode($this->service->getAll());
        } catch (HttpException $e) {
            http_response_code($e->getCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function store() {
        try {
            AuthMiddleware::verify();
            $data = json_decode(file_get_contents("php://input"), true);
            $this->service->create($data);
            http_response_code(201);
        } catch (HttpException $e) {
            http_response_code($e->getCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id) {
        try {
            AuthMiddleware::verify();
            $data = json_decode(file_get_contents("php://input"), true);
            $product = $this->service->update($id, $data);
            echo json_encode($product);
        } catch (HttpException $e) {
            http_response_code($e->getCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($id) {
        try {
            AuthMiddleware::verify();
            $this->service->delete($id ?? null);
            echo json_encode(['message' => 'Deleted']);
        } catch (HttpException $e) {
            http_response_code($e->getCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

?>