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

}
   