<?php

namespace App\Services;

use App\Repositories\clienteRepository;

class clienteService {
    private clienteRepository $clienteRepository;

    public function __construct(clienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function listarTodo(){
        return $this->clienteRepository->listarTodo();
    }
     public function guardar(array $datos){
        return $this->clienteRepository->guardar($datos);
}

public function eliminar(int $id){
    $this->clienteRepository->eliminar($id);
    }

    public function buscarPorId(int $id){
        return $this->clienteRepository->buscarPorId($id);
    }

    
}
   