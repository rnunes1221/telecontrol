<?php

require_once 'repositories/ClientRepository.php';
require_once 'core/Validator.php';
require_once 'core/HttpException.php';
require_once 'utils/CpfValidator.php';
class ClientService {
    private $repository;
    private $cpfValidator;
    public function __construct() {
        $this->repository = new ClientRepository();
        $this->cpfValidator = new CpfValidator();
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
            'name' => 'required|string',
            'cpf' => 'required|string',
            'address' => 'string'
        ]);

        if (!$this->cpfValidator->isValidCpf(isset($data['consumer_cpf']) ? $data['consumer_cpf'] : $data['cpf'])) {
            throw new HttpException("Invalid CPF", 422);
        }

        if ($this->repository->findByCpf(isset($data['consumer_cpf']) ? $data['consumer_cpf'] : $data['cpf'])) {
            throw new HttpException("CPF already registered", 409);
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
            throw new HttpException("Client not found", 404);
        }

        Validator::validate($data, [
            'name' => 'required|string',
            'cpf' => 'required|string',
            'address' => 'required|string'
        ]);
        return $this->repository->update($id, $data);
    }

    public function delete($id) {
        if (!$id) {
            throw new HttpException("ID is required", 400);
        }

        $product = $this->repository->find($id);
        if (!$product) {
            throw new HttpException("Client not found", 404);
        }

        $this->repository->delete($id);
        return $this->repository->delete($id);
    }

    /*
    private function isValidCpf($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
    */
}

?>