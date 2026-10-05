<?php

namespace App\Repositories;

use App\Models\pago;

class PagoRepository
{
    public function getAll()
    {
        return pago::with('agenda.cliente')->get();
    }

    public function keep(array $datos)
    {
        return pago::create($datos);
    }

    public function findById(int $id)
    {
        return pago::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $pago = pago::findOrFail($id);
        $pago->update($datos);
        return $pago;
    }

    public function delete(int $id)
    {
        return pago::destroy($id);
    }
}