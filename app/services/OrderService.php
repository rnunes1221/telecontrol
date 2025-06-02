<?php
require_once 'repositories/OrderRepository.php';
require_once 'repositories/ClientRepository.php';
require_once 'repositories/ProductRepository.php';
require_once 'repositories/LogRepository.php';
require_once 'core/HttpException.php';
require_once 'core/Validator.php';
require_once 'utils/CpfValidator.php';
class OrderService {
    private $orderRepo;
    private $clientRepo;
    private $logRepo;
    private $productRepo;
    private $cpfValidator;

    public function __construct() {
        $this->cpfValidator = new CpfValidator();
        $this->orderRepo = new OrderRepository();
        $this->clientRepo = new ClientRepository();
        $this->logRepo = new LogRepository();
        $this->productRepo = new ProductRepository();
        $this->cpfValidator = new CpfValidator();
    }

     public function getAll() {
        return $this->orderRepo->all();
    }

    public function findById($id) {
        if (!$id || !is_numeric($id)) {
            throw new HttpException("Invalid ID", 400);
        }
        return $this->orderRepo->find($id);
    }

    public function create($data) {
        Validator::validate($data, [
            'consumer_name' => 'required|string',
            'consumer_cpf' => 'required|string',
            'product_id' => 'required|int'
        ]);

        $product = $this->productRepo->find($data['product_id']);
        
        if (!$product) {
            throw new HttpException("Product not found", 422);
        }

        if (!$this->cpfValidator->isValidCpf($data['consumer_cpf'])) {
            throw new HttpException("Invalid CPF", 422);
        }

        $client = $this->clientRepo->findByCpf($data['consumer_cpf']);

        if (!$client) {
            $clientId = $this->clientRepo->save($data);
        } else {
            $clientId = $client['id'];
        }

        $order = [
            'consumer_name' => $data['consumer_name'],
            'consumer_cpf' => $data['consumer_cpf'],
            'product_id' => $data['product_id'],
        ];
        return $this->orderRepo->save($order);
    }

    public function update($id, $data) {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new HttpException("Invalid ID", 400);
        }

        if (!$id) {
            throw new HttpException("ID is required", 400);
        }

        $order = $this->orderRepo->find($id);
        if (!$order) {
            throw new HttpException("Order not found", 404);
        }
        
        Validator::validate($data, [
            'consumer_name' => 'required|string',
            'consumer_cpf' => 'required|string',
            'product_id' => 'required|numeric'
        ]);

        $product = $this->productRepo->find($data['product_id']);
        if (!$product) {
            throw new HttpException("Product don't exists", 404);
        }

        $this->logRepo->log("Ordem de serviço id:".$id." alterada:" . json_encode($data));
        return $this->orderRepo->update($id, $data);
    }

    public function delete($id) {
        if (!$id) {
            throw new HttpException("ID is required", 400);
        }

        $order = $this->orderRepo->find($id);
        if (!$order) {
            throw new HttpException("Order not found", 404);
        }

        $this->orderRepo->delete($id);
        return $this->orderRepo->delete($id);
    }


}
?>