<?php

require_once 'repositories/ProductRepository.php';
require_once 'core/Validator.php';
require_once 'core/HttpException.php';
require_once __DIR__ . '/../enums/ProductStatus.php';

class ProductService {
    private $repository;

    public function __construct() {
        $this->repository = new ProductRepository();
    }

    public function getAll() {
        return $this->repository->all();
    }

    public function findById($id) {
        if (!$id || !is_numeric($id)) {
            throw new HttpException("Invalid ID", 400);
        }
        return $this->repository->find($id);
    }

    public function create($data) {
        Validator::validate($data, [
            'description' => 'required|string',
            'status' => 'required|string',
            'warranty_time' => 'required|numeric'
        ]);
       if  (!ProductStatus::isValid($data['status'])) {
            throw new HttpException("Invalid status. Allowed statuses are: " . implode(', ', ProductStatus::values()), 422);
        }
        return $this->repository->save($data);
    }

    public function update($id, $data) {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new HttpException("Invalid ID", 400);
        }

        if (!$id) {
            throw new HttpException("ID is required", 400);
        }

        $product = $this->repository->find($id);
        if (!$product) {
            throw new HttpException("Product not found", 404);
        }

        if  (!ProductStatus::isValid($data['status'])) {
            throw new HttpException("Invalid status. Allowed statuses are: " . implode(', ', ProductStatus::values()), 422);
        }
        
        Validator::validate($data, [
            'description' => 'required|string',
            'status' => 'required|string',
            'warranty_time' => 'required|numeric'
        ]);


        return $this->repository->update($id, $data);
    }

    public function delete($id) {
        if (!$id) {
            throw new HttpException("ID is required", 400);
        }

        $product = $this->repository->find($id);
        if (!$product) {
            throw new HttpException("Product not found", 404);
        }

        $this->repository->delete($id);
        return $this->repository->delete($id);
    }
}

?>